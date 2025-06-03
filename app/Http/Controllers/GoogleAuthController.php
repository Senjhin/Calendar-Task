<?php

namespace App\Http\Controllers;

use App\Models\GoogleEvent;
use App\Models\Task;
use Carbon\Carbon;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoogleAuthController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setClientId(config('services.google.client_id'));
        $this->client->setClientSecret(config('services.google.client_secret'));
        $this->client->setRedirectUri(route('google.callback'));
        $this->client->setAccessType('offline');
        $this->client->setPrompt('consent');
        $this->client->addScope(Google_Service_Calendar::CALENDAR);
    }

    // Przekierowanie do Google OAuth
    public function redirect()
    {
        $authUrl = $this->client->createAuthUrl();
        return redirect()->away($authUrl);
    }

    // Callback po autoryzacji
    public function callback(Request $request)
    {
        if ($request->has('code')) {
            $token = $this->client->fetchAccessTokenWithAuthCode($request->code);

            if (isset($token['error'])) {
                return redirect('/tasks')->withErrors('Google authentication failed.');
            }

            $this->client->setAccessToken($token);

            $user = Auth::user();

            // Zapis tokenów w DB
            $user->google_token = $token['access_token'];
            $user->google_refresh_token = $token['refresh_token'] ?? $user->google_refresh_token;
            $user->google_token_expires_at = Carbon::now()->addSeconds($token['expires_in']);
            $user->save();

            return redirect('/tasks')->with('success', 'Google account connected.');
        }

        return redirect('/tasks')->withErrors('Google authorization code not found.');
    }

    // Synchronizacja zadania do Google Calendar
    public function sync(Request $request, Task $task)
    {
        $user = Auth::user();

        // Sprawdź czy zadanie należy do użytkownika
        if ($task->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        // Ustaw token i odśwież jeśli trzeba
        $this->client->setAccessToken([
            'access_token' => $user->google_token,
            'refresh_token' => $user->google_refresh_token,
            'expires_in' => $user->google_token_expires_at ? $user->google_token_expires_at->diffInSeconds(now()) : 0,
            'created' => $user->google_token_expires_at ? $user->google_token_expires_at->subSeconds($user->google_token_expires_at->diffInSeconds(now()))->timestamp : time(),
        ]);

        if ($this->client->isAccessTokenExpired()) {
            if ($user->google_refresh_token) {
                $newToken = $this->client->fetchAccessTokenWithRefreshToken($user->google_refresh_token);

                $user->google_token = $newToken['access_token'];
                $user->google_token_expires_at = Carbon::now()->addSeconds($newToken['expires_in']);
                $user->save();

                $this->client->setAccessToken($newToken);
            } else {
                return redirect()->back()->withErrors('Google token expired and no refresh token available.');
            }
        }

        $service = new Google_Service_Calendar($this->client);

        // Sprawdź czy zadanie ma już przypisane wydarzenie w Google Calendar
        $googleEvent = $task->googleEvent;

        $event = new Google_Service_Calendar_Event([
            'summary' => $task->title,
            'description' => $task->description,
            'start' => [
                'dateTime' => $task->due_date->toAtomString(),
                'timeZone' => config('app.timezone'),
            ],
            'end' => [
                'dateTime' => $task->due_date->copy()->addHour()->toAtomString(), // 1h wydarzenie
                'timeZone' => config('app.timezone'),
            ],
        ]);

        if ($googleEvent) {
            // Aktualizuj istniejące wydarzenie
            $service->events->update('primary', $googleEvent->google_event_id, $event);
        } else {
            // Dodaj nowe wydarzenie
            $createdEvent = $service->events->insert('primary', $event);

            // Zapisz ID wydarzenia w bazie
            GoogleEvent::create([
                'task_id' => $task->id,
                'google_event_id' => $createdEvent->getId(),
            ]);
        }

        // Ustaw flagę google_synced na true i zapisz
        $task->google_synced = true;
        $task->save();

        return redirect()->route('tasks.show', $task)->with('success', 'Task synced to Google Calendar.');
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        // Usuwamy token Google z użytkownika (przykładowo)
        $user->google_token = null;
        $user->google_refresh_token = null;
        $user->google_token_expires_at = null;
        $user->save();

        return redirect()->route('tasks.index')->with('success', 'Wylogowano z Google.');
    }
}
