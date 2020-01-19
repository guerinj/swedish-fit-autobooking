<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <div class="p-2">

        @auth
            <form method="post" action="{{ route('logout') }}" class="d-flex">
                @csrf
                <button class="ml-auto btn btn-sm btn-outline-primary">Déconnexion</button>
            </form>

        @endauth

    </div>

    <main class="pt-5">
        @yield('content')
    </main>
</div>

<script>
    window.Laravel = "{{ csrf_token() }}";
</script>
</body>
</html>
