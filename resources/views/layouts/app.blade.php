<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>TaskPlanner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    @include('layouts.navbar')
    <div class="py-4">
        @if(session('success'))
            <div class="container">
                <div class="alert alert-success">{{ session('success') }}</div>
            </div>
        @endif
        @yield('content')
    </div>
</body>
</html>
