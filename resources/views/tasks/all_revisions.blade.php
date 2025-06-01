@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">📜 Historia wszystkich zmian</h1>
            <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left-square-fill"></i> Powrót do zadań
            </a>
        </div>

        @if ($revisions->count())
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Zadanie</th>
                        <th>Pole</th>
                        <th>Stara wartość</th>
                        <th>Nowa wartość</th>
                        <th>Zmienione przez</th>
                        <th>Data zmiany</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($revisions as $index => $revision)
                        <tr>
                            <td>{{ $loop->iteration + ($revisions->currentPage() - 1) * $revisions->perPage() }}</td>

                            <td>
                                <a href="{{ route('tasks.revisions', $revision->task_id) }}">
                                    {{ $revision->task->title ?? '—' }}
                                </a>
                            </td>

                            <td>
                        <span class="badge bg-info text-dark">
                            {{ ucfirst($revision->field_name) }}
                        </span>
                            </td>

                            <td class="text-danger">
                                {{ $revision->old_values !== null ? Str::limit($revision->old_values, 100) : '—' }}
                            </td>

                            <td class="text-success">
                                {{ $revision->new_values !== null ? Str::limit($revision->new_values, 100) : '—' }}
                            </td>

                            <td>
                                {{ $revision->user->name ?? 'Użytkownik usunięty' }}
                            </td>

                            <td>
                                {{ $revision->created_at->format('Y-m-d H:i') }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $revisions->links() }}
            </div>

        @else
            <div class="alert alert-warning">
                Brak zmian do wyświetlenia.
            </div>
        @endif

    </div>
@endsection
