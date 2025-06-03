<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskShare;
use App\Models\TaskHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::query()->where('user_id', auth()->id());

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('due_date_from')) {
            $query->where('due_date', '>=', $request->due_date_from . ' 00:00:00');
        }

        if ($request->filled('due_date_to')) {
            $query->where('due_date', '<=', $request->due_date_to . ' 23:59:59');
        }

        $query->orderBy('due_date', 'asc');

        $tasks = $query->paginate(10);

        return view('tasks.index', [
            'tasks' => $tasks,
            'status' => $request->status,
            'priority' => $request->priority,
            'due_date_from' => $request->due_date_from,
            'due_date_to' => $request->due_date_to,
        ]);
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:to-do,in progress,done',
            'due_date' => 'required|date|after_or_equal:today',
        ]);

        $task = Auth::user()->tasks()->create($validated);

        return redirect()->route('tasks.show', $task)->with('success', 'Task created.');
    }

    public function show(Task $task)
    {
        $this->authorizeTaskOwner($task);

        return view('tasks.show', compact('task'));
    }

    public function showShared($token)
    {
        $taskShare = TaskShare::where('token', $token)
            ->where('expires_at', '>', Carbon::now())
            ->firstOrFail();

        $task = $taskShare->task;

        return view('tasks.show_shared', compact('task'));
    }

    public function edit(Task $task)
    {
        $this->authorizeTaskOwner($task);

        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorizeTaskOwner($task);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:to-do,in progress,done',
            'due_date' => 'required|date|after_or_equal:today',
        ]);

        $this->saveTaskHistory($task, $validated);

        $task->update($validated);

        return redirect()->route('tasks.show', $task)->with('success', 'Task updated.');
    }

    protected function saveTaskHistory(Task $task, array $newData)
    {
        $userId = auth()->id();
        $changedAt = now();

        foreach ($newData as $field => $newValue) {
            $oldValue = $task->$field;

            if ($oldValue != $newValue) {
                \DB::table('task_histories')->insert([
                    'task_id'    => $task->id,
                    'user_id'    => $userId,
                    'field'      => $field,
                    'old_value'  => $oldValue,
                    'new_value'  => $newValue,
                    'changed_at' => $changedAt,
                ]);
            }
        }
    }

    public function destroy(Task $task)
    {
        $this->authorizeTaskOwner($task);

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted.');
    }

    public function share(Task $task)
    {
        $this->authorizeTaskOwner($task);

        $token = Str::random(32);
        $expiresAt = Carbon::now()->addHours(24);

        $task->taskShares()->delete();

        $taskShare = $task->taskShares()->create([
            'token' => $token,
            'expires_at' => $expiresAt,
        ]);

        $publicLink = route('tasks.show_shared', $token);

        return view('tasks.share', compact('publicLink', 'expiresAt', 'task'));
    }

    public function allRevisions()
    {
        $user = Auth::user();

        $revisions = TaskHistory::whereHas('task', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->with(['task.user'])
            ->orderByDesc('changed_at')
            ->paginate(10);

        return view('tasks.all_history', compact('revisions'));
    }

    public function taskRevisions(Task $task)
    {
        $this->authorizeTaskOwner($task);

        // Tutaj zmieniamy get() na paginate(10)
        $revisions = $task->taskHistories()
            ->with('task.user')
            ->orderByDesc('changed_at')
            ->paginate(10);

        return view('tasks.history', compact('revisions', 'task'));
    }

    protected function authorizeTaskOwner(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'Unauthorized.');
        }
    }
}
