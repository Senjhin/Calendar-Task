<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskRevision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;

class TaskController extends Controller
{
    public function index(Request $request)
{
    $query = Task::where('user_id', auth()->id());

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('priority')) {
        $query->where('priority', $request->priority);
    }

    if ($request->filled('due_date_from')) {
        $query->whereDate('due_date', '>=', $request->due_date_from);
    }

    if ($request->filled('due_date_to')) {
        $query->whereDate('due_date', '<=', $request->due_date_to);
    }

    $tasks = $query
        ->orderBy('due_date', 'asc')
        ->orderByRaw("FIELD(priority, 'high', 'medium', 'low')")
        ->paginate(10); // <--- TUTAJ

    return view('tasks.index', compact('tasks'));
}

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', Rule::in(['low', 'medium', 'high'])],
            'status' => ['required', Rule::in(['to-do', 'in-progress', 'done'])],
            'due_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    $dueDate = Carbon::parse($value);
                    if (in_array($request->status, ['to-do', 'in-progress']) && $dueDate->isPast()) {
                        $fail('Due date must not be in the past for tasks that are not done.');
                    }
                },
            ],
        ]);

        $task = new Task($request->all());
        $task->user_id = auth()->id();
        $task->save();

        return redirect()->route('tasks.index')->with('success', 'Task added successfully.');
    }

    public function show(Task $task): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory
    {
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }

        return view('tasks.edit', compact('task'));
    }
    public function allRevisions(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory
    {
        $userId = auth()->id();

        // Pobierz wszystkie rewizje dla zadań tego użytkownika, posortowane malejąco i paginowane
        $revisions = TaskRevision::whereHas('task', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->with('task') // eager load task dla optymalizacji
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('tasks.all_revisions', compact('revisions'));
    }


    public function taskRevisions(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }

        $revisions = TaskRevision::where('task_id', $task->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('tasks.revisions', compact('revisions', 'task'));
    }


    public function update(Request $request, Task $task): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|string',
            'due_date' => 'nullable|date',
        ]);

        // Zachowaj stare wartości
        $oldValues = $task->only(array_keys($validated));

        // Aktualizuj zadanie
        $task->update($validated);

        // Nowe wartości po update
        $newValues = $task->only(array_keys($validated));

        // Zapisywanie rewizji
        foreach ($validated as $field => $newValue) {
            $oldValue = $oldValues[$field] ?? null;
            if ($oldValue != $newValue) {
                TaskRevision::create([
                    'task_id' => $task->id,
                    'user_id' => auth()->id(),
                    'field_name' => $field,
                    'old_values' => $oldValue ?? '',
                    'new_values' => $newValue ?? '',
                ]);
            }
        }

        return redirect()->route('tasks.index')->with('success', 'Zadanie zaktualizowane i rewizja zapisana.');
    }



    public function destroy(Task $task): \Illuminate\Http\RedirectResponse
    {
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }

    public function showShared($token)
    {
        $task = Task::where('public_token', $token)
            ->where('public_token_expires_at', '>', now())
            ->firstOrFail();

        return view('tasks.show_shared', compact('task'));
    }




    // Generowanie linku do udostępniania (token ważny 24h)
    public function share(Task $task): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory
    {
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if (!$task->public_token || Carbon::parse($task->public_token_expires_at)->isPast()) {
            $task->public_token = Str::random(32);
            $task->public_token_expires_at = Carbon::now()->addHours(24);
            $task->save();
        }

        $publicLink = route('tasks.show_shared', $task->public_token);

        return view('tasks.share', compact('task', 'publicLink'));
    }


    // Przykład synchronizacji z Google Calendar (implementację dostosuj)
    public function syncGoogleCalendar(Task $task): \Illuminate\Http\RedirectResponse
    {
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Przykład: wywołanie serwisu integracji z Google Calendar
            app('google-calendar')->addEventForTask($task);

            return redirect()->route('tasks.show', $task)->with('success', 'Task synced with Google Calendar.');
        } catch (\Exception $e) {
            return redirect()->route('tasks.show', $task)->with('error', 'Google Calendar sync failed: ' . $e->getMessage());
        }
    }
}
