@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>
                <a href="{{ route('tasks.index') }}" class="text-decoration-none text-dark d-flex align-items-center">
                     <span class="ms-2">TaskPlanner</span>
                </a>
            </h1>
            <div class="d-flex align-items-center">
                @php
                    $googleConnected = auth()->user()->google_token ?? false;
                @endphp

                @if($googleConnected)
                    <span class="badge bg-success me-3">Połączony z Google</span>
                    <form action="{{ route('google.logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">Rozłącz Google</button>
                    </form>
                @else
                    <a href="{{ route('google.connect') }}" class="btn btn-outline-primary btn-sm">Połącz z Google</a>
                @endif

                <a href="{{ route('tasks.create') }}" class="btn btn-primary ms-3">Dodaj nowe zadanie</a>
                <a href="{{ route('tasks.history') }}" class="btn btn-secondary ms-2">Historia wszystkich zadań</a>
            </div>
        </div>

        {{-- Filtrowanie --}}
        <form method="GET" action="{{ route('tasks.index') }}" class="mb-4">
            <div class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Wszystkie</option>
                        <option value="to-do" {{ request('status') == 'to-do' ? 'selected' : '' }}>To-do</option>
                        <option value="in progress" {{ request('status') == 'in progress' ? 'selected' : '' }}>In progress</option>
                        <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Done</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="priority" class="form-label">Priorytet</label>
                    <select name="priority" id="priority" class="form-select">
                        <option value="">Wszystkie</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="due_date_from" class="form-label">Termin od:</label>
                    <input type="datetime-local"
                           name="due_date_from"
                           id="due_date_from"
                           value="{{ request('due_date_from') ? \Carbon\Carbon::parse(request('due_date_from'))->format('Y-m-d\TH:i') : '' }}"
                           class="form-control">
                </div>

                <div class="col-md-3">
                    <label for="due_date_to" class="form-label">Termin do:</label>
                    <input type="datetime-local"
                           name="due_date_to"
                           id="due_date_to"
                           value="{{ request('due_date_to') ? \Carbon\Carbon::parse(request('due_date_to'))->format('Y-m-d\TH:i') : '' }}"
                           class="form-control">
                </div>

                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-outline-primary">Filtruj</button>
                </div>
            </div>
        </form>

        {{-- Powiadomienia --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($tasks->count())
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>Tytuł</th>
                    <th>Opis</th>
                    <th>Priorytet</th>
                    <th>Status</th>
                    <th>Termin</th>
                    <th>Akcje</th>
                </tr>
                </thead>
                <tbody>
                @foreach($tasks as $task)
                    <tr>
                        <td>
                            <a href="{{ route('tasks.show', $task) }}" class="text-decoration-none">
                                {{ $task->title }}
                            </a>
                        </td>
                        <td>{{ Str::limit($task->description, 50) }}</td>
                        <td>{{ ucfirst(translatePriority($task->priority)) }}</td>
                        <td>{{ ucfirst(str_replace('-', ' ', translateStatus($task->status))) }}</td>
                        <td>
                            @if($task->due_date)
                                {{ \Illuminate\Support\Carbon::parse($task->due_date)->format('Y-m-d H:i') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-warning">Edytuj</a>

                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Na pewno usunąć?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Usuń</button>
                            </form>

                            <a href="{{ route('tasks.share', $task) }}" class="btn btn-sm btn-info">Udostępnij Link</a>

                            @if($googleConnected)
                                @if($task->google_synced)
                                    <button type="button"
                                            class="btn btn-sm btn-secondary"
                                            title="Zadanie zostało już dodane do Google Calendar"
                                            disabled
                                            style="opacity: 0.5; cursor: not-allowed;">
                                        Udostępnione w Google
                                    </button>
                                @else
                                    <form action="{{ route('tasks.sync_google', $task) }}" method="POST" style="display:inline-block">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" title="Udostępnij w Google Calendar">Udostępnij w Google</button>
                                    </form>
                                @endif
                            @endif

                            @if($task->public_token && $task->public_token_expires_at > now())
                                <a href="{{ url('/tasks/public/'.$task->public_token) }}" target="_blank" class="btn btn-sm btn-secondary">Udostępnij link</a>
                            @endif

                            <a href="{{ route('tasks.history.task', $task->id) }}" class="btn btn-sm btn-primary" title="Historia tego zadania">Historia zadania</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                {{ $tasks->links() }}
            </div>
        @else
            <p>Brak zadań do wyświetlenia.</p>
        @endif
    </div>
@endsection
