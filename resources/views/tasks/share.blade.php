@extends('layouts.app')

@section('content')
    <div class="container">

        {{-- Nagłówek --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <a href="{{ route('tasks.index') }}" class="text-decoration-none text-dark d-flex align-items-center">
                     <span class="ms-2">TaskPlanner</span>
                </a>
            </h1>
            <a href="{{ route('tasks.index') }}" class="btn btn-secondary">⬅ Powrót do listy zadań</a>
        </div>

        {{-- Info o współdzieleniu --}}
        <div class="mb-4">
            <h2 class="fw-bold"> Udostępnij zadanie</h2>
            <p class="text-muted">Poniższy link umożliwia dostęp do tego zadania innym osobom.</p>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">{{ $task->title }}</h5>
                <p class="card-text">{{ $task->description ?? '-' }}</p>
                <hr>
                <p>
                    <strong>Status:</strong> {{ ucfirst(str_replace('-', ' ', $task->status)) }} <br>
                    <strong>Priorytet:</strong> {{ ucfirst($task->priority) }} <br>
                    <strong>Termin:</strong> {{ $task->due_date ? $task->due_date->format('Y-m-d') : '—' }}
                </p>

                {{-- Informacja o udostępnieniu w Google Calendar --}}
                <div class="border rounded p-3 shadow-sm mt-4">
                    <h5 class="mb-3"> Udostępnione w Google Calendar</h5>

                    @if($task->googleEvent && $task->googleEvent->created_at)
                        <span class="badge bg-success" title="Zadanie udostępnione w Google Calendar od {{ $task->googleEvent->created_at->format('Y-m-d H:i') }}">
                        Tak (od {{ $task->googleEvent->created_at->format('Y-m-d H:i') }})
                    </span>
                    @else
                        <span class="badge bg-danger" title="Zadanie nie jest udostępnione w Google Calendar">
                        Nie
                    </span>
                    @endif
                </div>

                <div class="alert alert-info mt-4">
                    <label class="form-label fw-bold"> Link do udostępnienia:</label>
                    <div class="input-group">
                        <input type="text" class="form-control" readonly value="{{ $publicLink }}">
                        <button class="btn btn-outline-secondary" onclick="navigator.clipboard.writeText('{{ $publicLink }}')"> Kopiuj</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
