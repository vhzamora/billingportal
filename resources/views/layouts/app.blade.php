<!DOCTYPE html>
<html lang="es" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal de Facturación') - ISSABEL Billing</title>
    
    @php($hasViteManifest = file_exists(public_path('build/manifest.json')))
    @php($viteHot = file_exists(storage_path('framework/vite.hot')))
    @if($hasViteManifest || $viteHot)
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <!-- Tailwind CSS CDN (fallback) -->
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.10/dist/tailwind.min.css" rel="stylesheet">
        <!-- Tailwind Play CDN para cargar utilidades sin build -->
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    
    <!-- Estilos mínimos para clases personalizadas usadas en vistas (equivalentes a nuestras utilidades) -->
    @unless($hasViteManifest)
    <style>
      /* Fallback básico si Tailwind no cargara */
      html, body { height: 100%; }
      body { background-color: #f9fafb; color: #111827; font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji"; }
      .btn { padding: 0.5rem 1rem; border-radius: 0.375rem; font-weight: 500; transition: background-color .2s, color .2s; display:inline-flex; align-items:center; }
      .btn-primary { background-color: #2563eb; color: #fff; }
      .btn-primary:hover { background-color: #1d4ed8; }
      .btn-secondary { background-color: #e5e7eb; color: #111827; }
      .btn-secondary:hover { background-color: #d1d5db; }
      .btn-danger { background-color: #dc2626; color: #fff; }
      .btn-danger:hover { background-color: #b91c1c; }
      .card { background-color: #ffffff; border-radius: 0.5rem; box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05); border: 1px solid #e5e7eb; }
      .input { width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.5rem 0.75rem; }
      .input:focus { outline: 2px solid #3b82f6; outline-offset: 2px; border-color: #3b82f6; }
      /* Layout de respaldo */
      #sidebar { position: fixed; inset: 0 auto 0 0; width: 16rem; background: #1f2937; color: #e5e7eb; display: none; }
      @media (min-width: 1024px) { #sidebar { display: flex; flex-direction: column; } #main { padding-left: 16rem; } }
      #sidebar .brand { background:#111827; height:4rem; display:flex; align-items:center; padding: 0 1rem; }
      #sidebar nav { padding: 1rem 0.5rem; }
      #sidebar nav a { display:flex; align-items:center; gap:0.75rem; padding:0.5rem 0.5rem; border-radius:0.375rem; color:#d1d5db; text-decoration:none; }
      #sidebar nav a:hover { background:#374151; color:#fff; }
      #topbar { position: sticky; top:0; z-index:40; height:4rem; background:#fff; box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05); display:flex; align-items:center; }
    </style>
    @endunless
    
    @livewireStyles
</head>
<body class="h-full">
    <div class="min-h-full">
        <!-- Sidebar -->
        <div id="sidebar" class="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-64 lg:flex-col">
            <div class="flex min-h-0 flex-1 flex-col bg-gray-800">
                <div class="brand flex h-16 flex-shrink-0 items-center bg-gray-900 px-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 rounded-lg bg-blue-600 flex items-center justify-center">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-white">ISSABEL</p>
                            <p class="text-xs text-gray-300">Billing Portal</p>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-1 flex-col overflow-y-auto">
                    <nav class="flex-1 space-y-1 px-2 py-4">
                        <!-- Dashboard -->
                        <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="mr-3 h-6 w-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2H3a2 2 0 00-2 2z" />
                            </svg>
                            Dashboard
                        </a>
                        
                        <!-- Clientes -->
                        <a href="{{ route('admin.clientes.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.clientes.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="mr-3 h-6 w-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                            Clientes
                        </a>
                        
                        <!-- Tarifas -->
                        <a href="{{ route('admin.tarifas.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.tarifas.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="mr-3 h-6 w-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                            Tarifas
                        </a>
                        
                        <!-- Llamadas -->
                        <a href="{{ route('admin.llamadas.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.llamadas.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="mr-3 h-6 w-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            Llamadas
                        </a>
                        
                        <!-- Facturas -->
                        <a href="{{ route('admin.facturas.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.facturas.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="mr-3 h-6 w-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Facturas
                        </a>
                        
                        <!-- Destinos Desconocidos -->
                        <a href="{{ route('admin.destinos.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.destinos.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="mr-3 h-6 w-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Destinos Desconocidos
                        </a>
                    </nav>
                </div>
            </div>
        </div>
        
        <!-- Main content -->
        <div id="main" class="lg:pl-64">
            <!-- Top bar -->
            <div id="topbar" class="sticky top-0 z-40 flex h-16 flex-shrink-0 bg-white shadow">
                <button type="button" class="border-r border-gray-200 px-4 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 lg:hidden">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                </button>
                
                <div class="flex flex-1 justify-between px-4">
                    <div class="flex flex-1">
                        <div class="flex w-full md:ml-0">
                            <label for="search-field" class="sr-only">Buscar</label>
                            <div class="relative w-full text-gray-400 focus-within:text-gray-600">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <h1 class="block h-full w-full border-transparent py-2 pl-8 pr-3 text-gray-900 text-lg font-semibold">
                                    @yield('page-title', 'Portal de Facturación')
                                </h1>
                            </div>
                        </div>
                    </div>
                    <div class="ml-4 flex items-center md:ml-6">
                        <div class="text-sm text-gray-500">
                            Modo: <span class="font-medium text-blue-600">Administrador</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Page content -->
            <main class="py-6">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    
    @livewireScripts
</body>
</html> 