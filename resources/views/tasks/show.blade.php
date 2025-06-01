@extends('layouts.app')

@section('content')
    <div class="container">

        {{-- Nagłówek z tytułem i przyciskiem powrotu --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <a href="{{ route('tasks.index') }}" class="text-decoration-none text-dark d-flex align-items-center">
                    🗂️ <span class="ms-2">TaskPlanner</span>
                </a>
            </h1>
            <a href="{{ route('tasks.index') }}" class="btn btn-secondary">⬅️ Powrót do listy zadań</a>
        </div>

        {{-- Tytuł podglądu zadania --}}
        <div class="mb-4">
            <h2 class="fw-bold">🔍 Szczegóły zadania</h2>
            <p class="text-muted">Podgląd i zarządzanie wybranym zadaniem.</p>
        </div>

        {{-- Informacje o zadaniu w tabeli --}}
        <table class="table table-bordered">
            <tbody>
            <tr>
                <th scope="row" style="width: 180px;">📌 Tytuł</th>
                <td>{{ $task->title }}</td>
            </tr>
            <tr>
                <th>📍 Status</th>
                <td>{{ ucfirst(str_replace('-', ' ', $task->status)) }}</td>
            </tr>
            <tr>
                <th>🔥 Priorytet</th>
                <td>{{ ucfirst($task->priority) }}</td>
            </tr>
            <tr>
                <th>📅 Termin</th>
                <td>{{ $task->due_date ? $task->due_date->format('Y-m-d') : '-' }}</td>
            </tr>
            <tr>
                <th>📝 Opis</th>
                <td>{{ $task->description ?? '-' }}</td>
            </tr>
            </tbody>
        </table>

        {{-- Akcje --}}
        <div class="mb-4">
            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-warning">✏️ Edytuj</a>

            <form action="{{ route('tasks.sync_google', $task) }}" method="POST" class="d-inline-block ms-2">
                @csrf
                <button type="submit" class="btn btn-primary">📅 Dodaj do Google Calendar</button>
            </form>

            <a href="{{ route('tasks.index') }}" class="btn btn-secondary ms-2">⬅️ Powrót do listy</a>
        </div>
    </div>
@endsection
