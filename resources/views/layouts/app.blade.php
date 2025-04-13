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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</head>

<body class="bg-gray-50 font-sans" style="font-family: 'Montserrat', 'Inter', sans-serif;">
    @if (auth()->user()->role === 'admin')
        <!-- Admin Header -->
        <div class="fixed top-0 left-0 right-0 bg-white shadow-sm border-b border-gray-200 z-50 h-16 lg:left-64">
            <div class="flex items-center justify-between h-full px-4 sm:px-6">
                <!-- Left Side: Hamburger + Search -->
                <div class="flex items-center flex-1">
                    <!-- Hamburger Menu (Mobile) -->
                    <div x-data="{ open: false }" class="relative lg:hidden mr-2">
                        <button @click="open = !open" class="text-blue-600 p-1">
                            <i class="ri-menu-line text-2xl"></i>
                        </button>
                        <div x-show="open" @click.away="open = false"
                            class="absolute left-0 mt-2 w-56 bg-white rounded-lg shadow-lg py-2 z-60 border border-gray-200">
                            <a href="{{ route('dashboard') }}"
                                class="{{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-600' }} flex items-center px-4 py-3 transition duration-200">
                                <i class="ri-home-line mr-3 text-lg {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ route('products.index') }}"
                                class="{{ request()->routeIs('products.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-600' }} flex items-center px-4 py-3 transition duration-200">
                                <i class="ri-archive-line mr-3 text-lg {{ request()->routeIs('products.index') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                                <span>Produk</span>
                            </a>
                            <a href="{{ route('sales.index') }}"
                                class="{{ request()->routeIs('sales.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-600' }} flex items-center px-4 py-3 transition duration-200">
                                <i class="ri-shopping-cart-line mr-3 text-lg {{ request()->routeIs('sales.index') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                                <span>Pembelian</span>
                            </a>
                            <a href="{{ route('users.index') }}"
                                class="{{ request()->routeIs('users.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-600' }} flex items-center px-4 py-3 transition duration-200">
                                <i class="ri-team-line mr-3 text-lg {{ request()->routeIs('users.index') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                                <span>User</span>
                            </a>
                        </div>
                    </div>

                    <!-- Search -->
                    <div class="flex-1">
                        <form action="{{ route('search') }}" method="GET" class="relative">
                            <input type="text" name="query" placeholder="Cari..."
                                class="w-full pl-3 pr-8 py-1.5 text-sm rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 sm:pl-4 sm:py-2 sm:text-base">
                            <button type="submit" class="absolute right-3 top-1.5 text-gray-400 hover:text-blue-600 sm:right-4 sm:top-2.5">
                                <i class="ri-search-line"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- User Profile -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center space-x-2 px-3">
                        <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white sm:w-9 sm:h-9">
                            {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                        </div>
                        <span class="hidden md:inline-block text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                    </button>
                    <div x-show="open" @click.away="open = false"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-60 border border-gray-200">
                        <a href="{{ route('users.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">Users</a>
                        <div class="border-t border-gray-100 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="block w-full text-left px-4 py-2 text-sm text-blue-600 hover:bg-blue-50">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Sidebar (Desktop) -->
        <div class="hidden lg:block fixed top-0 left-0 h-full w-64 bg-white shadow-lg border-r border-gray-200 z-40">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center px-4 py-4 border-b border-gray-200">
                    <img src="{{ asset('logo.png') }}" alt="Logo" class="w-8 h-8">
                    <span class="ml-2 text-lg font-bold text-blue-600">Kasir App</span>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-2 py-4 space-y-1">
                    <a href="{{ route('dashboard') }}"
                        class="{{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-600' }} flex items-center px-4 py-2 rounded-lg transition duration-200 hover:scale-[1.02]">
                        <i class="ri-home-line mr-3 text-lg"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('products.index') }}"
                        class="{{ request()->routeIs('products.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-600' }} flex items-center px-4 py-2 rounded-lg transition duration-200 hover:scale-[1.02]">
                        <i class="ri-archive-line mr-3 text-lg"></i>
                        <span>Produk</span>
                    </a>
                    <a href="{{ route('sales.index') }}"
                        class="{{ request()->routeIs('sales.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-600' }} flex items-center px-4 py-2 rounded-lg transition duration-200 hover:scale-[1.02]">
                        <i class="ri-shopping-cart-line mr-3 text-lg"></i>
                        <span>Pembelian</span>
                    </a>
                    <a href="{{ route('users.index') }}"
                        class="{{ request()->routeIs('users.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-600' }} flex items-center px-4 py-2 rounded-lg transition duration-200 hover:scale-[1.02]">
                        <i class="ri-team-line mr-3 text-lg"></i>
                        <span>User</span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content for Admin -->
        <div class="pt-28 md:pt-20 lg:ml-64 lg:pt-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-5">
                @yield('content')
            </div>
        </div>

    @else
        <!-- Petugas Navbar -->
        <div class="fixed top-0 w-full shadow-sm bg-white border-b border-gray-200 z-50">
            <div class="flex items-center justify-between h-16 px-4">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <div class="flex items-center">
                        <img src="{{ asset('logo.png') }}" alt="Logo" class="w-8 h-8">
                        <span class="ml-2 font-bold text-green-600">Kasir App</span>
                    </div>
                </div>

                <!-- Navigation Menu - Dropdown -->
                <div class="hidden md:block ml-10">
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="flex items-center space-x-1 px-4 py-2 rounded-lg bg-green-50 hover:bg-green-100 text-green-600 transition duration-200 hover:scale-[1.02]">
                            <span class="font-medium">Kategori</span>
                            <i class="ri-arrow-down-s-line ml-1"></i>
                        </button>
                        <div x-show="open" @click.away="open = false"
                            class="absolute left-0 mt-2 w-56 bg-white rounded-lg shadow-lg py-2 z-50 border border-gray-200">
                            <a href="{{ route('dashboard') }}"
                                class="{{ request()->routeIs('dashboard') ? 'bg-green-50 text-green-600' : 'text-gray-700 hover:bg-green-50' }} flex items-center px-4 py-3 transition duration-200">
                                <i class="ri-home-line mr-3 {{ request()->routeIs('dashboard') ? 'text-green-600' : 'text-gray-500' }}"></i>
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ route('products.index') }}"
                                class="{{ request()->routeIs('products.index') ? 'bg-green-50 text-green-600' : 'text-gray-700 hover:bg-green-50' }} flex items-center px-4 py-3 transition duration-200">
                                <i class="ri-archive-line mr-3 {{ request()->routeIs('products.index') ? 'text-green-600' : 'text-gray-500' }}"></i>
                                <span>Produk</span>
                            </a>
                            <a href="{{ route('sales.index') }}"
                                class="{{ request()->routeIs('sales.index') ? 'bg-green-50 text-green-600' : 'text-gray-700 hover:bg-green-50' }} flex items-center px-4 py-3 transition duration-200">
                                <i class="ri-shopping-cart-line mr-3 {{ request()->routeIs('sales.index') ? 'text-green-600' : 'text-gray-500' }}"></i>
                                <span>Pembelian</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Search -->
                <div class="hidden md:block flex-1 mx-8">
                    <form action="{{ route('search') }}" method="GET" class="relative">
                        <input type="text" name="query" placeholder="Cari di Kasir App"
                            class="w-full pl-4 pr-10 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200">
                        <button type="submit" class="absolute right-4 top-2.5 text-gray-400 hover:text-green-600">
                            <i class="ri-search-line"></i>
                        </button>
                    </form>
                </div>

                <!-- Right side icons -->
                <div class="flex items-center space-x-6">
                    <a href="{{ route('sales.index') }}"
                        class="text-green-600 hover:text-green-800 transition duration-200">
                        <i class="ri-shopping-cart-line text-xl"></i>
                    </a>

                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center space-x-2">
                            <div class="w-9 h-9 rounded-full bg-green-600 flex items-center justify-center text-white">
                                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                            </div>
                            <span class="hidden md:inline-block text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                        </button>
                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50 border border-gray-200">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50">Profile</a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-sm text-green-600 hover:bg-green-50">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation for Petugas -->
        <div class="md:hidden fixed top-16 w-full bg-white border-b border-gray-200 z-40">
            <div class="px-4 py-2">
                <div class="flex items-center justify-between">
                    <!-- Mobile Menu Button -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="flex items-center p-2 rounded-lg text-green-600 hover:bg-green-50 transition duration-200">
                            <i class="ri-menu-line text-xl mr-1"></i>
                            <span class="font-medium">Kategori</span>
                        </button>
                        <div x-show="open" @click.away="open = false"
                            class="absolute left-0 mt-2 w-56 bg-white rounded-lg shadow-lg py-2 z-50 border border-gray-200">
                            <a href="{{ route('dashboard') }}"
                                class="{{ request()->routeIs('dashboard') ? 'bg-green-50 text-green-600' : 'text-gray-700 hover:bg-green-50' }} flex items-center px-4 py-3 transition duration-200">
                                <i class="ri-home-line mr-3 {{ request()->routeIs('dashboard') ? 'text-green-600' : 'text-gray-500' }}"></i>
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ route('products.index') }}"
                                class="{{ request()->routeIs('products.index') ? 'bg-green-50 text-green-600' : 'text-gray-700 hover:bg-green-50' }} flex items-center px-4 py-3 transition duration-200">
                                <i class="ri-archive-line mr-3 {{ request()->routeIs('products.index') ? 'text-green-600' : 'text-gray-500' }}"></i>
                                <span>Produk</span>
                            </a>
                            <a href="{{ route('sales.index') }}"
                                class="{{ request()->routeIs('sales.index') ? 'bg-green-50 text-blue-600' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-600' }} flex items-center px-4 py-3 transition duration-200">
                                <i class="ri-shopping-cart-line mr-3 text-lg {{ request()->routeIs('sales.index') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                                <span>Pembelian</span>
                            </a>
                        </div>
                    </div>

                    <!-- Mobile Search -->
                    <div class="flex-1 mx-2">
                        <form action="{{ route('search') }}" method="GET" class="relative">
                            <input type="text" name="query" placeholder="Cari..."
                                class="w-full pl-3 pr-8 py-1.5 text-sm rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200">
                            <button type="submit" class="absolute right-3 top-1.5 text-gray-400 hover:text-green-600">
                                <i class="ri-search-line"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Mobile Cart -->
                    <a href="{{ route('sales.index') }}"
                        class="flex items-center p-2 text-green-600 hover:text-green-800 transition duration-200">
                        <i class="ri-shopping-cart-line text-xl"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content for Petugas -->
        <div class="pt-28 md:pt-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @yield('content')
            </div>
        </div>
    @endif

    @yield('scripts')
</body>

</html>