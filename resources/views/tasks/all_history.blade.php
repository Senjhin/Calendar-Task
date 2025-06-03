@extends('layouts.app')

@section('content')
    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0"> Historia wszystkich zmian</h1>
            <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left-square-fill"></i> Powrót do zadań
            </a>
        </div>

        @if ($revisions->count())
            <div class="table-responsive shadow-sm rounded">
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
                                <a href="{{ route('tasks.history.task', $revision->task_id) }}">
                                    {{ optional($revision->task)->title ?? '—' }}
                                </a>
                            </td>
                            <td>
                            <span class="badge bg-info text-dark text-capitalize">
                                {{ str_replace('_', ' ', $revision->field) }}
                            </span>
                            </td>
                            <td class="text-danger">
                                {{ \Illuminate\Support\Str::limit($revision->old_value, 100) ?? '—' }}
                            </td>
                            <td class="text-success">
                                {{ \Illuminate\Support\Str::limit($revision->new_value, 100) ?? '—' }}
                            </td>
                            <td>
                                {{ optional($revision->task->user)->name ?? 'Użytkownik usunięty' }}
                            </td>
                            <td>
                                {{ \Illuminate\Support\Carbon::parse($revision->changed_at)->format('Y-m-d H:i') }}
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
            <div class="alert alert-warning text-center mt-4">
                Brak zmian do wyświetlenia.
            </div>
        @endif
    </div>
@endsection
