<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kasir') - Toko Buah Mas Ali</title>
    <link rel="stylesheet" href="{{ asset('css/globals.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0f4fa',
                            100: '#dbe5f5',
                            500: '#0056b3',
                            600: '#003f87',
                            700: '#002d66',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="m-0 p-0 bg-slate-50 font-sans antialiased text-slate-800">
<div class="flex h-screen w-full items-start overflow-hidden relative">
    
    <!-- SIDEBAR -->
    <div class="flex flex-col w-[260px] h-full items-start justify-between px-4 py-6 bg-slate-100 border-r border-slate-200 box-border shrink-0 hidden md:flex">
        <div class="w-full flex flex-col items-start">
            <div class="px-2 w-full box-border">
                <p class="m-0 font-bold text-brand-600 text-xl xl:text-2xl leading-tight">
                    Toko Buah Mas Ali 
                    <span class="text-xs font-semibold text-slate-500 block uppercase tracking-wider mt-1">Panel Kasir</span>
                </p>
            </div>
            <div class="mt-8 w-full flex flex-col gap-1">
                <a href="{{ route('kasir.dashboard') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-200/70 rounded-xl text-slate-600 no-underline font-medium transition-all duration-200 {{ request()->routeIs('kasir.dashboard') ? 'bg-brand-500 text-white font-semibold shadow-sm shadow-brand-500/20' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>
                    <span>Transaksi</span>
                </a>
                <a href="{{ route('kasir.riwayat') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-200/70 rounded-xl text-slate-600 no-underline font-medium transition-all duration-200 {{ request()->routeIs('kasir.riwayat') ? 'bg-brand-500 text-white font-semibold shadow-sm shadow-brand-500/20' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 9h3.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span>Riwayat Transaksi</span>
                </a>
            </div>
        </div>
        
        <!-- USER PROFILE & LOGOUT -->
        <div class="w-full pt-4 border-t border-slate-200 box-border">
            <div class="flex items-center gap-3 px-2 mb-3">
                <div class="flex w-10 h-10 shrink-0 items-center justify-center bg-brand-500 rounded-xl text-white font-bold shadow-sm">
                    {{ strtoupper(substr(auth()->user()->name ?? 'Kasir', 0, 2)) }}
                </div>
                <div class="flex flex-col overflow-hidden">
                    <div class="font-bold text-slate-900 text-sm leading-tight truncate">{{ auth()->user()->name ?? 'Kasir' }}</div>
                    <div class="font-medium text-slate-500 text-xs leading-none mt-1">{{ ucfirst(auth()->user()->role ?? 'Cashier') }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2.5 text-red-600 text-sm font-semibold rounded-xl hover:bg-red-50 border-0 bg-transparent cursor-pointer transition-colors duration-150">
                    Keluar
                </button>
            </form>
        </div>
    </div>

    <!-- MAIN CONTENT CONTAINER -->
    <div class="flex flex-col flex-1 h-full bg-slate-50 overflow-hidden">
        
        <!-- TOPBAR -->
        <div class="flex h-16 items-center justify-between px-6 bg-white border-b border-slate-200 shrink-0 box-border">
            <div>
                <h1 class="m-0 font-bold text-slate-900 text-lg leading-tight">@yield('page_title', 'Kasir')</h1>
                <p class="text-slate-500 text-xs mt-0.5 hidden sm:block">@yield('page_description', 'Kelola transaksi penjualan')</p>
            </div>
            <span class="text-slate-500 text-xs font-semibold bg-slate-100 px-3 py-1.5 rounded-full">{{ now()->translatedFormat('l, d F Y') }}</span>
        </div>

        <!-- CONTENT BODY -->
        <div class="flex-1 overflow-y-auto box-border">
            @yield('content')
        </div>
    </div>
</div>
</body>
</html>
