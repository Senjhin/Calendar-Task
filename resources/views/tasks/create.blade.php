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

    {{-- Tytuł formularza --}}
    <div class="mb-4">
        <h2 class="fw-bold">📝 Dodaj nowe zadanie</h2>
        <p class="text-muted">Wypełnij poniższy formularz, aby dodać nowe zadanie do listy.</p>
    </div>

    {{-- Formularz tworzenia zadania --}}
    <form action="{{ route('tasks.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">📌 Tytuł zadania</label>
            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title') }}" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">📝 Opis</label>
            <textarea name="description" id="description" rows="4"
                      class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="priority" class="form-label">🔥 Priorytet</label>
                <select name="priority" id="priority" class="form-select @error('priority') is-invalid @enderror" required>
                    <option value="">Wybierz...</option>
                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Niski</option>
                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Średni</option>
                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Wysoki</option>
                </select>
                @error('priority')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="status" class="form-label">📍 Status</label>
                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="">Wybierz...</option>
                    <option value="to-do" {{ old('status') == 'to-do' ? 'selected' : '' }}>Do zrobienia</option>
                    <option value="in-progress" {{ old('status') == 'in-progress' ? 'selected' : '' }}>W trakcie</option>
                    <option value="done" {{ old('status') == 'done' ? 'selected' : '' }}>Zrobione</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="due_date" class="form-label">📅 Termin</label>
                <input type="date" name="due_date" id="due_date"
                       class="form-control @error('due_date') is-invalid @enderror"
                       value="{{ old('due_date') }}">
                @error('due_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-success btn-lg">
                💾 Zapisz zadanie
            </button>
        </div>
    </form>
</div>
@endsection
