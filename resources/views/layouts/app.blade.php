<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Gestor de Notas') }}</title>
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        
        @auth
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
            <div class="container">
                
                @if(auth()->user()->role == 'admin')
                    <a class="navbar-brand" href="{{ route('dashboard') }}">
                        GESTOR DE NOTAS CESJB (Admin)
                    </a>
                @else
                    <a class="navbar-brand" href="{{ route('docente.dashboard') }}">
                        GESTOR DE NOTAS CESJB (Docente)
                    </a>
                @endif
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
        
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">
                    </ul>
                    <ul class="navbar-nav ms-auto">
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ auth()->user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Cerrar Sesión') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="post" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                    </ul>
                </div>
            </div>
        </nav>
        @endauth
        @auth
        <div class="container-fluid mt-4">
            <div class="row">
                
                @if(auth()->user()->role == 'admin')
                <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                    <div class="position-sticky pt-3">
                        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                            <span>Menú principal</span>
                        </h6>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                    Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('docentes*') ? 'active' : '' }}" href="{{ route('docentes.index') }}">
                                    Gestión Docentes
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('alumnos*') ? 'active' : '' }}" href="{{ route('alumnos.index') }}">
                                    Gestión Alumnos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('materias*') ? 'active' : '' }}" href="{{ route('materias.index') }}">
                                    Gestión Materias
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('periodos*') ? 'active' : '' }}" href="{{ route('periodos.index') }}">
                                    Gestión Períodos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('cursos*') ? 'active' : '' }}" href="{{ route('cursos.index') }}">
                                    Gestión Cursos
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
                @endif
                <main class="{{ auth()->user()->role == 'admin' ? 'col-md-9 ms-sm-auto col-lg-10' : 'col-md-12' }} px-md-4">
                    @yield('content')
                </main>
                </div>
        </div>
        @else
        <main class="py-4">
            @yield('content')
        </main>
        @endauth
        </div>
</body>
</html>