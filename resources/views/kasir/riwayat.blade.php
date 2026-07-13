<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Riwayat Transaksi - Toko Buah Mas Ali</title>
    <link rel="stylesheet" href="{{ asset('css/globals.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            corePlugins: { preflight: false },
            theme: { extend: {} },
            plugins: [],
        }
    </script>
</head>
<body class="m-0 p-0 bg-[#f9f9ff] font-sans antialiased">
<div class="flex h-screen w-full items-start overflow-hidden relative">
    <div class="flex flex-col w-[260px] h-full items-start justify-between px-4 py-6 bg-[#f2f3fc] border-r border-[#c2c6d4] box-border shrink-0">
        <div class="w-full flex flex-col items-start">
            <div class="px-2 w-full box-border">
                <p class="m-0 font-bold text-[#003f87] text-xl xl:text-2xl leading-8">
                    Toko Buah Mas Ali <span class="text-sm font-medium text-[#424752] block opacity-70">Panel Kasir</span>
                </p>
            </div>
            <div class="mt-8 w-full flex flex-col gap-2">
                <a href="{{ route('kasir.dashboard') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-[#e4e6f2] rounded-lg text-decoration-none text-[#434751] transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-[#434751]">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>
                    <span class="text-base font-medium">Transaksi</span>
                </a>
                <a href="{{ route('kasir.riwayat') }}" class="flex items-center gap-3 px-4 py-3 bg-[#0056b3] rounded-lg text-white text-decoration-none transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-[#bbd0ff]">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 9h3.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span class="text-base font-medium">Riwayat Transaksi</span>
                </a>
            </div>
        </div>
        <div class="w-full pt-4 border-t border-[#c2c6d4] box-border">
            <div class="flex items-center gap-3 px-2">
                <div class="flex w-10 h-10 shrink-0 items-center justify-center bg-[#0056b3] rounded-xl text-[#bbd0ff] font-bold shadow-sm">
                    {{ strtoupper(substr(auth()->user()->name ?? 'Kasir', 0, 2)) }}
                </div>
                <div class="flex flex-col overflow-hidden">
                    <div class="font-bold text-[#191c21] text-sm leading-5 truncate">{{ auth()->user()->name ?? 'Siti Aminah' }}</div>
                    <div class="font-medium text-[#424752] text-xs leading-4 opacity-80">{{ ucfirst(auth()->user()->role ?? 'Cashier') }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="w-full text-left px-2 py-2 text-[#ba1a1a] text-sm font-semibold rounded-lg hover:bg-[#fdecec] border-0 bg-transparent cursor-pointer">
                    Keluar
                </button>
            </form>
        </div>
    </div>

    <div class="flex flex-col flex-1 h-full bg-[#f9f9ff] overflow-hidden">
        <div class="flex h-16 items-center justify-between px-6 bg-[#f9f9ff] border-b border-[#c2c6d4] shrink-0 box-border">
            <div>
                <span class="text-[#003f87] text-xl font-bold">Riwayat Transaksi</span>
                <p class="text-[#424752] text-sm opacity-80 mt-1">Daftar transaksi terakhir yang telah diproses oleh kasir.</p>
            </div>
            <a href="{{ route('kasir.dashboard') }}" class="inline-flex items-center gap-2 rounded-xl border border-[#c2c6d4] bg-white px-4 py-2 text-sm font-semibold text-[#424752] hover:bg-[#e4e6f2] transition-all duration-200">
                Kembali ke Transaksi
            </a>
        </div>

        <div class="flex-1 overflow-y-auto p-6 box-border">
            @if($transaksi->isEmpty())
                <div class="rounded-3xl bg-white border border-[#c2c6d4] p-10 text-center text-[#424752] shadow-sm">
                    Belum ada riwayat transaksi.
                </div>
            @else
                <div class="grid gap-4">
                    @foreach($transaksi as $trx)
                        <div class="rounded-3xl bg-white border border-[#c2c6d4] p-5 shadow-sm">
                            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                                <div>
                                    <div class="text-sm text-[#424752]">{{ $trx->created_at->format('d M Y H:i') }}</div>
                                    <div class="text-lg font-bold text-[#191c21]">{{ $trx->kode_transaksi }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-[#424752]">Metode: {{ ucfirst($trx->metode_pembayaran) }}</div>
                                    <div class="text-lg font-bold text-[#003f87]">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</div>
                                </div>
                            </div>

                            <div class="mt-4 rounded-2xl bg-[#f2f3fc] p-4">
                                @foreach($trx->items as $item)
                                    <div class="flex justify-between gap-3 text-sm text-[#424752] py-2 border-b border-[#d9dce7] last:border-0">
                                        <span>{{ $item->buah->nama_buah }} x{{ $item->qty }}</span>
                                        <span class="font-semibold text-[#191c21]">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $transaksi->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
</body>
</html>
