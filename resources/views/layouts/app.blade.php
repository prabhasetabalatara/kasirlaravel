<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="flex h-screen bg-gray-100">
            <!-- Sidebar -->
            <aside class="w-64 flex-shrink-0 bg-gray-800 text-white">
                <div class="p-4 text-2xl font-bold">
                    <a href="{{ route('dashboard') }}">KasirApp</a>
                </div>
                <nav class="mt-5">
                    <!-- Menu Utama -->
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 {{ request()->routeIs('dashboard') ? 'bg-gray-900' : '' }}">
                        <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('transactions.create') }}" class="flex items-center px-4 py-2 mt-2 text-gray-300 hover:bg-gray-700 {{ request()->routeIs('transactions.create') ? 'bg-gray-900' : '' }}">
                        <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Kasir
                    </a>
                    <a href="{{ route('transactions.index') }}" class="flex items-center px-4 py-2 mt-2 text-gray-300 hover:bg-gray-700 {{ request()->routeIs('transactions.index') ? 'bg-gray-900' : '' }}">
                        <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Riwayat Transaksi
                    </a>

                    <!-- Menu Admin -->
                    {{-- @if(Auth::user()->role->name == 'Admin') --}}
                    <div class="mt-4">
                        <p class="px-4 text-gray-500 uppercase tracking-wider text-xs">Manajemen</p>
                        <a href="{{ route('produk.index') }}" class="flex items-center px-4 py-2 mt-2 text-gray-300 hover:bg-gray-700 {{ request()->routeIs('produk.*') ? 'bg-gray-900' : '' }}">
                            <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            Produk
                        </a>
                        <a href="{{ route('kategori.index') }}" class="flex items-center px-4 py-2 mt-2 text-gray-300 hover:bg-gray-700 {{ request()->routeIs('kategori.*') ? 'bg-gray-900' : '' }}">
                           <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            Kategori
                        </a>
                        <a href="{{ route('users.index') }}" class="flex items-center px-4 py-2 mt-2 text-gray-300 hover:bg-gray-700 {{ request()->routeIs('users.*') ? 'bg-gray-900' : '' }}">
                           <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197"/></svg>
                            Pengguna
                        </a>
                    </div>
                    {{-- @endif --}}
                </nav>
            </aside>

            <!-- Main content -->
            <div class="flex-1 flex flex-col">
                <!-- Header -->
<header class="flex justify-between items-center py-1 px-1 bg-white border-b-4 border-gray-800">
    <!-- Placeholder untuk kesimbangan kiri -->
    <div></div>

    <!-- Profile Dropdown -->
    <div x-data="{ isOpen: false }" class="relative">
        <button @click="isOpen = !isOpen"
            class="flex items-center max-w-[200px] space-x-2 relative z-10 p-2 focus:outline-none overflow-hidden">
            
            <!-- Nama Pengguna (tampil penuh atau dipotong jika panjang) -->
            <span class="text-gray-700 text-sm font-medium truncate hidden md:inline-block">
                {{ Auth::user()->name }}
            </span>

            <!-- Inisial bulat -->
            <div class="h-8 w-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-bold flex-shrink-0">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
        </button>

        <!-- Dropdown Menu -->
        <div x-show="isOpen"
            @click.away="isOpen = false"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20"
            style="display: none;">
            
            <div class="px-4 py-2 text-xs text-gray-400">Manage Account</div>
            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit Profil</a>
            <div class="border-t border-gray-100"></div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    Log Out
                </button>
            </form>
        </div>
    </div>
</header>

                <!-- Page Heading -->
                @if (isset($header))
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto bg-gray-100">
                    <div class="py-12">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            {{ $slot }}
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
