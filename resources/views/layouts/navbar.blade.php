<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="{{ route('tasks.index') }}">TaskPlanner (Interview Tech Task)</a>
        <div class="d-flex">
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-outline-secondary" type="submit">Wyloguj</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-primary">Zaloguj</a>
            @endauth
        </div>
    </div>
</nav>
