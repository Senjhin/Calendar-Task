<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Przypomnienie o zadaniu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0; padding: 20px;
        }
        .container {
            max-width: 600px;
            background-color: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin: auto;
        }
        h1 {
            font-size: 24px;
            color: #343a40;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        h1 span {
            margin-left: 8px;
        }
        p {
            font-size: 16px;
            color: #495057;
            line-height: 1.5;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 15px;
        }
        th, td {
            padding: 8px 12px;
            border: 1px solid #dee2e6;
        }
        th {
            background-color: #e9ecef;
            text-align: left;
            width: 150px;
        }
        a.button {
            display: inline-block;
            padding: 10px 18px;
            background-color: #0d6efd;
            color: white !important;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 15px;
        }
        a.button:hover {
            background-color: #0b5ed7;
        }
        .footer {
            font-size: 14px;
            color: #6c757d;
            margin-top: 30px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>⏰ <span>Przypomnienie o zadaniu</span></h1>

    <p>Cześć,</p>
    <p>To przypomnienie o zadaniu, którego termin zbliża się do końca:</p>

    <table>
        <tr>
            <th>Tytuł</th>
            <td>{{ $task->title }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ ucfirst(str_replace('-', ' ', $task->status)) }}</td>
        </tr>
        <tr>
            <th>Priorytet</th>
            <td>{{ ucfirst($task->priority) }}</td>
        </tr>
        <tr>
            <th>Termin</th>
            <td>{{ $task->due_date ? $task->due_date->format('Y-m-d') : '-' }}</td>
        </tr>
        <tr>
            <th>Opis</th>
            <td>{{ $task->description ?? '-' }}</td>
        </tr>
    </table>

    <p>Możesz zobaczyć szczegóły zadania i wprowadzić ewentualne zmiany.</p>
    <p><a href="{{ route('tasks.show', $task) }}" class="button">Zobacz szczegóły</a>></p>
    <p>Serdecznie pozdrawiamy,<br>TaskPlanner</p>

    <div class="footer">
        &copy; {{ date('Y') }} TaskPlanner — Twój osobisty planer zadań.
    </div>
</div>
</body>
</html>
