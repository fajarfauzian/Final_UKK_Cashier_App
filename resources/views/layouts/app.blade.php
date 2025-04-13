<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="icon" href="{{ asset('logo.png') }}" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</head>

<body class="bg-gray-50 font-sans" style="font-family: 'Montserrat', 'Inter', sans-serif;">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div id="sidebar" class="fixed inset-y-0 left-0 z-30 w-64 bg-white shadow transform -translate-x-full lg:translate-x-0 lg:relative transition-transform">
            <!-- Logo -->
            <div class="p-3 flex items-center border-b">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="w-8 h-8">
                <span class="ml-2 font-bold text-blue-600">Kasir App</span>
            </div>

            <!-- Menu -->
            <nav class="p-3 space-y-1">
                <a href="{{ route('dashboard') }}" 
                   class="{{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }} flex items-center px-3 py-2 rounded">
                    <i class="ri-home-line mr-3 {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="{{ route('products.index') }}" 
                   class="{{ request()->routeIs('products.index') ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }} flex items-center px-3 py-2 rounded">
                    <i class="ri-archive-line mr-3 {{ request()->routeIs('products.index') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                    <span>Produk</span>
                </a>
                
                <a href="{{ route('sales.index') }}" 
                   class="{{ request()->routeIs('sales.index') ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }} flex items-center px-3 py-2 rounded">
                    <i class="ri-shopping-cart-line mr-3 {{ request()->routeIs('sales.index') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                    <span>Pembelian</span>
                </a>
                
                @if (auth()->user()->role === 'admin')
                <a href="{{ route('users.index') }}" 
                   class="{{ request()->routeIs('users.index') ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }} flex items-center px-3 py-2 rounded">
                    <i class="ri-team-line mr-3 {{ request()->routeIs('users.index') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                    <span>User</span>
                </a>
                @endif
            </nav>
        </div>

        <!-- Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Mobile Header -->
            <div class="lg:hidden fixed top-0 left-0 right-0 z-20 bg-white shadow px-4 py-2 flex items-center justify-between">
                <button id="menuBtn" class="text-gray-700">
                    <i class="ri-menu-line text-xl"></i>
                </button>
                
                <div class="flex-1 text-center">
                    <span class="font-bold text-blue-600">Kasir App</span>
                </div>
                
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white">
                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                    </button>
                    <div x-show="open" @click.away="open = false" 
                         class="absolute right-0 mt-2 w-48 bg-white rounded shadow-lg py-1 z-30">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Logout</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Desktop Header -->
            <div class="hidden lg:flex items-center justify-between px-4 py-2 bg-white shadow">
                <div class="relative w-64">
                    <form action="{{ route('search') }}" method="GET">
                        <input type="text" name="query" placeholder="Search..." 
                               class="w-full pl-3 pr-10 py-2 rounded bg-gray-100 border-0 focus:ring-2 focus:ring-blue-500">
                        <button type="submit" class="absolute right-2 top-2 text-gray-400">
                            <i class="ri-search-line"></i>
                        </button>
                    </form>
                </div>
                
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center space-x-2">
                        <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white">
                            {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                        </div>
                        <span class="font-medium">{{ auth()->user()->name }}</span>
                    </button>
                    <div x-show="open" @click.away="open = false" 
                         class="absolute right-0 mt-2 w-48 bg-white rounded shadow py-2 z-50">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Logout</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex-1 overflow-y-auto">
                <div class="h-12 lg:h-0"></div>
                <div class="p-4">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    @yield('scripts')
</body>
</html>