<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Calculadora IMC')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { display:flex; min-height:100vh; background:#f8f9fa; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .sidebar { width:250px; background:linear-gradient(180deg,#0d6efd 0%,#0a58ca 100%); color:#fff; flex-shrink:0; box-shadow:2px 0 10px rgba(0,0,0,0.15); }
        .sidebar h4 { font-size:1.3rem; padding:20px; text-align:center; border-bottom:1px solid rgba(255,255,255,0.2); }
        .sidebar a { color:#fff; text-decoration:none; display:block; padding:12px 20px; transition:all 0.3s; font-size:0.95rem; }
        .sidebar a:hover { background:rgba(255,255,255,0.15); padding-left:25px; }
        .navbar-custom { background-color:#fff; box-shadow:0 2px 10px rgba(0,0,0,0.1); }
        .navbar-custom .navbar-brand { color:#0d6efd !important; font-weight:bold; }
        .content { flex-grow:1; padding:20px; animation:fadeIn 0.4s ease-in-out; }
        @keyframes fadeIn { from { opacity:0; transform:translateY(10px);} to { opacity:1; transform:translateY(0);} }
        .card-custom { border:none; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); transition:transform 0.2s; }
        .card-custom:hover { transform: translateY(-5px); }
    </style>
    @stack('head')
</head>
<body>
    {{-- Sidebar --}}
    <div class="sidebar">
        <h4>⚡ IMC App</h4>
        <a href="{{ route('home') }}">🏠 Inicio</a>
        <a href="{{ route('bmi.index') }}">🧮 Calculadora</a>
        <a href="{{ route('acerca') }}">ℹ️ Acerca de</a>
    </div>

    <div class="content">
        {{-- Navbar --}}
        <nav class="navbar navbar-expand-lg navbar-light navbar-custom mb-4">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('home') }}">Calculadora IMC</a>
                <div class="d-flex">
                    @auth
                        <a class="btn btn-outline-primary btn-sm me-2" href="{{ route('clients.edit', \App\Models\Client::where('user_id',auth()->id())->value('id') ?? 1) }}">Perfil</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-primary btn-sm">Salir</button>
                        </form>
                    @endauth
                    @guest
                        <a class="btn btn-primary btn-sm" href="{{ route('login') }}">Ingresar</a>
                    @endguest
                </div>
            </div>
        </nav>

        {{-- Flash messages --}}
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Ups:</strong> corrige los campos marcados.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Contenido dinámico --}}
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
