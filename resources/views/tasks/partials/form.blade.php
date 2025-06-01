<div class="form-group">
    <label for="title">Nazwa zadania</label>
    <input type="text" name="title" class="form-control" value="{{ old('title', optional($task)->title) }}" required>
</div>

<div class="form-group">
    <label for="description">Opis</label>
    <textarea name="description" class="form-control">{{ old('description', optional($task)->description) }}</textarea>
</div>

<div class="form-group">
    <label for="priority">Priorytet</label>
    <select name="priority" class="form-control">
        <option value="low" {{ old('priority', optional($task)->priority) == 'low' ? 'selected' : '' }}>Niski</option>
        <option value="medium" {{ old('priority', optional($task)->priority) == 'medium' ? 'selected' : '' }}>Średni</option>
        <option value="high" {{ old('priority', optional($task)->priority) == 'high' ? 'selected' : '' }}>Wysoki</option>
    </select>
</div>

<div class="form-group">
    <label for="status">Status</label>
    <select name="status" class="form-control">
        <option value="to-do" {{ old('status', optional($task)->status) == 'to-do' ? 'selected' : '' }}>Do zrobienia</option>
        <option value="in-progress" {{ old('status', optional($task)->status) == 'in-progress' ? 'selected' : '' }}>W trakcie</option>
        <option value="done" {{ old('status', optional($task)->status) == 'done' ? 'selected' : '' }}>Zrobione</option>
    </select>
</div>

<div class="form-group">
    <label for="due_date">Termin</label>
    <input type="date" name="due_date" class="form-control" value="{{ old('due_date', optional($task)->due_date) }}" required>
</div>
