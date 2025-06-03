@extends('layouts.app')

@section('content')
    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0"> Historia zmian zadania</h1>
            <a href="{{ route('tasks.index', $task) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left-square-fill"></i> Powrót do zadania
            </a>
        </div>

        @if($revisions->count())
            <div class="table-responsive shadow-sm rounded">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-dark">
                    <tr>
                        <th>Pole</th>
                        <th>Stara wartość</th>
                        <th>Nowa wartość</th>
                        <th>Użytkownik (ID)</th>
                        <th>Data zmiany</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($revisions as $revision)
                        <tr>
                            <td>
                                <span class="badge bg-info text-dark text-capitalize">
                                    {{ str_replace('_', ' ', $revision->field) }}
                                </span>
                            </td>
                            <td class="text-danger">
                                {{ $revision->old_value ?? '—' }}
                            </td>
                            <td class="text-success">
                                {{ $revision->new_value ?? '—' }}
                            </td>
                            <td>
                                {{ $revision->user_id }}
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
                Brak historii zmian dla tego zadania.
            </div>
        @endif
    </div>
@endsection
