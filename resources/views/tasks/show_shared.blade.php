@extends('layouts.app')

@section('content')
    <div class="container">

        {{-- Nagłówek --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <a href="{{ url('/') }}" class="text-decoration-none text-dark d-flex align-items-center">
                    🗂️ <span class="ms-2">TaskPlanner</span>
                </a>
            </h1>
            <a href="{{ url('/') }}" class="btn btn-secondary">⬅️ Strona główna</a>
        </div>

        {{-- Informacje o zadaniu --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="card-title mb-3">{{ $task->title }}</h3>

                <p class="mb-2"><strong>Status:</strong> {{ ucfirst(str_replace('-', ' ', $task->status)) }}</p>
                <p class="mb-2"><strong>Priorytet:</strong> {{ ucfirst($task->priority) }}</p>
                <p class="mb-2"><strong>Termin:</strong> {{ $task->due_date ? $task->due_date->format('Y-m-d') : '—' }}</p>

                <hr>

                <p><strong>Opis:</strong></p>
                <p class="text-muted">{{ $task->description }}</p>

                <div class="mt-4">
                    <a href="{{ url('/') }}" class="btn btn-outline-primary">🏠 Powrót do strony głównej</a>
                </div>
            </div>
        </div>

    </div>
@endsection
