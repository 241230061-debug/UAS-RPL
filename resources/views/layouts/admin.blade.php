<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta charset="utf-8" />
    <title>@yield('title', 'Admin Panel') - Toko Buah Mas Ali</title>
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
    
    <div class="flex flex-col w-[260px] h-full items-start justify-between px-4 py-6 bg-slate-100 border-r border-slate-200 box-border shrink-0 hidden md:flex">
        <div class="w-full flex flex-col items-start">
            <div class="px-2 w-full box-border">
                <p class="m-0 font-bold text-brand-600 text-xl xl:text-2xl leading-tight">
                    Toko Buah Mas Ali 
                    <span class="text-xs font-semibold text-slate-500 block uppercase tracking-wider mt-1">Panel Admin</span>
                </p>
            </div>
            <div class="mt-8 w-full flex flex-col gap-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-200/70 rounded-xl text-slate-600 no-underline font-medium transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-brand-500 text-white font-semibold shadow-sm shadow-brand-500/20' : '' }}">
                    <span>Dashboard</span>
                </a>
                
                {{-- MENU DATA MASTER --}}
                <details class="group" {{ request()->routeIs('admin.buah.*') ? 'open' : '' }}>
                    <summary class="flex items-center justify-between gap-3 px-4 py-3 cursor-pointer list-none hover:bg-slate-200/70 rounded-xl text-slate-600 font-medium transition-all duration-200 {{ request()->routeIs('admin.buah.*') ? 'bg-brand-500 text-white font-semibold shadow-sm shadow-brand-500/20' : '' }}">
                        <span>Data Master</span>
                        <svg class="w-4 h-4 transition-transform duration-200 group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>
                    <div class="mt-1 ml-3 pl-3 border-l border-slate-200 flex flex-col gap-1">
                        <a href="{{ route('admin.buah.index') }}" class="block px-4 py-2 rounded-lg text-sm no-underline transition-all duration-200 {{ request()->routeIs('admin.buah.index') || request()->routeIs('admin.buah.edit') ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-slate-500 hover:bg-slate-200/70' }}">
                            Kelola Data Buah
                        </a>
                        <a href="{{ route('admin.buah.rusak.index') }}" class="block px-4 py-2 rounded-lg text-sm no-underline transition-all duration-200 {{ request()->routeIs('admin.buah.rusak.*') ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-slate-500 hover:bg-slate-200/70' }}">
                            Lapor Buah Rusak/Busuk
                        </a>
                    </div>
                </details>

                <a href="{{ route('admin.restok.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-200/70 rounded-xl text-slate-600 no-underline font-medium transition-all duration-200 {{ request()->routeIs('admin.restok.*') ? 'bg-brand-500 text-white font-semibold shadow-sm shadow-brand-500/20' : '' }}">
                    <span>Pembelian & Restok</span>
                </a>

                {{-- MENU GRUP LAPORAN (SUDAH DIPERBAIKI) --}}
                <details class="group" {{ request()->routeIs('admin.laporan.*') ? 'open' : '' }}>
                    <summary class="flex items-center justify-between gap-3 px-4 py-3 cursor-pointer list-none hover:bg-slate-200/70 rounded-xl text-slate-600 font-medium transition-all duration-200 {{ request()->routeIs('admin.laporan.*') ? 'bg-brand-500 text-white font-semibold shadow-sm shadow-brand-500/20' : '' }}">
                        <span>Laporan Toko</span>
                        <svg class="w-4 h-4 transition-transform duration-200 group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>
                    <div class="mt-1 ml-3 pl-3 border-l border-slate-200 flex flex-col gap-1">
                        <a href="{{ route('admin.laporan.index') }}" class="block px-4 py-2 rounded-lg text-sm no-underline transition-all duration-200 {{ request()->routeIs('admin.laporan.index') ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-slate-500 hover:bg-slate-200/70' }}">
                            Laporan Transaksi
                        </a>
                        <a href="{{ route('admin.laporan.buah.index') }}" class="block px-4 py-2 rounded-lg text-sm no-underline transition-all duration-200 {{ request()->routeIs('admin.laporan.buah.index') ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-slate-500 hover:bg-slate-200/70' }}">
                            Laporan Buah Rusak
                        </a>
                    </div>
                </details>

                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-200/70 rounded-xl text-slate-600 no-underline font-medium transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-brand-500 text-white font-semibold shadow-sm shadow-brand-500/20' : '' }}">
                    <span>Manajemen Pengguna</span>
                </a>
            </div>
        </div>
        
        <div class="w-full pt-4 border-t border-slate-200 box-border">
            <div class="flex items-center gap-3 px-2 mb-3">
                <div class="flex w-10 h-10 shrink-0 items-center justify-center bg-brand-500 rounded-xl text-white font-bold shadow-sm">
                    {{ strtoupper(substr(auth()->user()->name ?? 'Ad', 0, 2)) }}
                </div>
                <div class="flex flex-col overflow-hidden">
                    <div class="font-bold text-slate-900 text-sm leading-tight truncate">{{ auth()->user()->name ?? 'Admin' }}</div>
                    <div class="font-medium text-slate-500 text-xs leading-none mt-1">{{ ucfirst(auth()->user()->role ?? 'Admin') }}</div>
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

    <div class="flex flex-col flex-1 h-full bg-slate-50 overflow-hidden">
        
        <div class="flex h-16 items-center justify-between px-6 bg-white border-b border-slate-200 shrink-0 box-border">
            <div>
                <h1 class="m-0 font-bold text-slate-900 text-lg leading-tight">@yield('page_title', 'Admin Panel')</h1>
                <p class="text-slate-500 text-xs mt-0.5 hidden sm:block">@yield('page_description', 'Kelola aplikasi Anda')</p>
            </div>
            <span class="text-slate-500 text-xs font-semibold bg-slate-100 px-3 py-1.5 rounded-full">{{ now()->translatedFormat('l, d F Y') }}</span>
        </div>

        <div class="flex-1 overflow-y-auto p-6 box-border">
            @if(session('success'))
                <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 shadow-sm flex items-center gap-2">
                    <span class="font-semibold">Berhasil:</span> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800 shadow-sm flex items-center gap-2">
                    <span class="font-semibold">Error:</span> {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>
</body>
</html>