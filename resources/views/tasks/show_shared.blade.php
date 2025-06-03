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
            <a href="{{ route('tasks.index') }}" class="btn btn-secondary"> Powrót do listy zadań</a>
        </div>

        {{-- Tytuł --}}
        <div class="mb-4">
            <h2 class="fw-bold"> Udostępnione zadanie</h2>
            <p class="text-muted">Podgląd udostępnionego zadania.</p>
        </div>

        {{-- Informacje o zadaniu w karcie --}}
        <div class="card shadow-sm border-3 rounded-3">
            <div class="card-body">

                <h3 class="card-title mb-3">{{ $task->title }}</h3>

                <table class="table table-bordered mb-3">
                    <tbody>
                    <tr>
                        <th style="width: 180px;"> Status</th>
                        <td>{{ ucfirst(str_replace('-', ' ', $task->status)) }}</td>
                    </tr>
                    <tr>
                        <th> Priorytet</th>
                        <td>{{ ucfirst($task->priority) }}</td>
                    </tr>
                    <tr>
                        <th> Termin</th>
                        <td>{{ $task->due_date ? $task->due_date->format('Y-m-d') : '—' }}</td>
                    </tr>
                    <tr>
                        <th> Opis</th>
                        <td>{{ $task->description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th> Udostępnione w Google</th>
                        @if($task->googleEvent)
                            <td class="text-success fw-bold">
                                 Tak<br>
                                <small>Od: {{ $task->googleEvent->created_at->format('Y-m-d H:i') }}</small>
                            </td>
                        @else
                            <td class="text-danger fw-bold"> Nie</td>
                        @endif
                    </tr>
                    </tbody>
                </table>

                <a href="{{ route('tasks.index') }}" class="btn btn-secondary"> Powrót do listy zadań</a>

            </div>
        </div>

    </div>
@endsection
