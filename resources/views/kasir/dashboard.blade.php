<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta charset="utf-8" />
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
                <a href="#" class="flex items-center gap-3 px-4 py-3 bg-[#0056b3] rounded-lg text-decoration-none transition-all duration-200 group">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-[#bbd0ff]">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>
                    <span class="text-[#bbd0ff] text-base font-medium">Transaksi</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 hover:bg-[#e4e6f2] rounded-lg text-decoration-none text-[#434751] transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-[#434751]">
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
            <div class="relative w-full max-w-md flex items-center">
                <input class="w-full bg-white rounded-lg border border-[#c2c6d4] py-2 pl-10 pr-4 text-gray-700 text-sm focus:outline-none focus:border-[#0056b3] shadow-sm box-border" placeholder="Search products or scan barcode..." type="text" />
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-[18px] h-[18px] text-gray-400 absolute left-3 pointer-events-none">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z" />
                </svg>
            </div>
            
            <div class="flex items-center gap-6 select-none">
                <button class="bg-transparent border-0 flex items-center gap-1.5 px-2 py-1 cursor-pointer rounded hover:bg-[#ededf6] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-[#424752]">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    <span class="text-[#424752] text-xs font-semibold">SYNC</span>
                </button>
                
                <div class="relative cursor-pointer p-1 rounded hover:bg-[#ededf6] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-[#424752]">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                    </svg>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-[#ba1a1a] rounded-full"></span>
                </div>
                
                <div class="w-px h-6 bg-[#c2c6d4]"></div>
                
                <div class="flex flex-col items-end text-right">
                    <span class="text-[#003f87] text-xs font-bold tracking-wider">KASIR: {{ strtoupper(auth()->user()->name ?? 'SITI AMINAH') }}</span>
                    <span class="text-[#424752] text-[10px] font-medium opacity-80 mt-0.5">SESSION: {{ date('H:i:s') }}</span>
                </div>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-6 flex flex-col gap-6 box-border">
            
            <div class="w-full flex items-center gap-3 overflow-x-auto pb-2 border-b border-transparent">
                <button class="px-5 py-2.5 bg-[#003f87] text-white font-bold text-sm rounded-xl border-0 shadow-sm cursor-pointer whitespace-nowrap">All Items</button>
                <button class="px-5 py-2.5 bg-[#e7e8f0] text-[#424752] font-bold text-sm rounded-xl border-0 hover:bg-[#dcdde6] transition-colors cursor-pointer whitespace-nowrap">Food & Bakery</button>
                <button class="px-5 py-2.5 bg-[#e7e8f0] text-[#424752] font-bold text-sm rounded-xl border-0 hover:bg-[#dcdde6] transition-colors cursor-pointer whitespace-nowrap">Drinks</button>
                <button class="px-5 py-2.5 bg-[#e7e8f0] text-[#424752] font-bold text-sm rounded-xl border-0 hover:bg-[#dcdde6] transition-colors cursor-pointer whitespace-nowrap">Snacks</button>
                <button class="px-5 py-2.5 bg-[#e7e8f0] text-[#424752] font-bold text-sm rounded-xl border-0 hover:bg-[#dcdde6] transition-colors cursor-pointer whitespace-nowrap">Household</button>
                <button class="px-5 py-2.5 bg-[#e7e8f0] text-[#424752] font-bold text-sm rounded-xl border-0 hover:bg-[#dcdde6] transition-colors cursor-pointer whitespace-nowrap">Personal Care</button>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                
                <div class="flex flex-col bg-white rounded-xl overflow-hidden border border-[#c2c6d4] shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="w-full h-32 bg-[#ededf6] overflow-hidden relative">
                        <img class="w-full h-full object-cover" src="{{ asset('img/image-1.png') }}" alt="Semangka" />
                    </div>
                    <div class="p-3 flex flex-col justify-between flex-1 gap-2">
                        <div>
                            <div class="font-bold text-[#191c21] text-base leading-snug">Semangka</div>
                            <div class="text-[#424752] text-sm font-semibold mt-0.5">Rp 15.000</div>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-[#f0f0f5]">
                            <span class="bg-[#93f7ba] text-[#00663c] text-[10px] font-bold px-1.5 py-0.5 rounded-sm">STOCK: 42</span>
                            <button class="bg-[#0056b3] border-0 p-1.5 rounded-lg text-white hover:bg-[#004694] cursor-pointer flex items-center justify-center transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col bg-white rounded-xl overflow-hidden border border-[#c2c6d4] shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="w-full h-32 bg-[#ededf6] overflow-hidden">
                        <img class="w-full h-full object-cover" src="{{ asset('img/image-2.png') }}" alt="Apel" />
                    </div>
                    <div class="p-3 flex flex-col justify-between flex-1 gap-2">
                        <div>
                            <div class="font-bold text-[#191c21] text-base leading-snug">Apel</div>
                            <div class="text-[#424752] text-sm font-semibold mt-0.5">Rp 35.000</div>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-[#f0f0f5]">
                            <span class="bg-[#93f7ba] text-[#00663c] text-[10px] font-bold px-1.5 py-0.5 rounded-sm">STOCK: 15</span>
                            <button class="bg-[#0056b3] border-0 p-1.5 rounded-lg text-white hover:bg-[#004694] cursor-pointer flex items-center justify-center transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col bg-white rounded-xl overflow-hidden border border-[#c2c6d4] shadow-sm hover:shadow-md transition-shadow duration-200 opacity-90">
                    <div class="w-full h-32 bg-[#ededf6] overflow-hidden">
                        <img class="w-full h-full object-cover" src="{{ asset('img/image-3.png') }}" alt="Alpukat" />
                    </div>
                    <div class="p-3 flex flex-col justify-between flex-1 gap-2">
                        <div>
                            <div class="font-bold text-[#191c21] text-base leading-snug">Alpukat</div>
                            <div class="text-[#424752] text-sm font-semibold mt-0.5">Rp 28.000</div>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-[#f0f0f5]">
                            <span class="bg-[#e7e8f0] text-[#424752] text-[10px] font-bold px-1.5 py-0.5 rounded-sm">STOCK: 120</span>
                            <button class="bg-[#0056b3] border-0 p-1.5 rounded-lg text-white hover:bg-[#004694] cursor-pointer flex items-center justify-center transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col bg-white rounded-xl overflow-hidden border border-[#c2c6d4] shadow-sm hover:shadow-md transition-shadow duration-200 opacity-90">
                    <div class="w-full h-32 bg-[#ededf6] overflow-hidden">
                        <img class="w-full h-full object-cover" src="{{ asset('img/image-4.png') }}" alt="Mangga" />
                    </div>
                    <div class="p-3 flex flex-col justify-between flex-1 gap-2">
                        <div>
                            <div class="font-bold text-[#191c21] text-base leading-snug">Mangga</div>
                            <div class="text-[#424752] text-sm font-semibold mt-0.5">Rp 30.000</div>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-[#f0f0f5]">
                            <span class="bg-[#e7e8f0] text-[#424752] text-[10px] font-bold px-1.5 py-0.5 rounded-sm">STOCK: 450</span>
                            <button class="bg-[#0056b3] border-0 p-1.5 rounded-lg text-white hover:bg-[#004694] cursor-pointer flex items-center justify-center transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col bg-white rounded-xl overflow-hidden border border-[#c2c6d4] shadow-sm hover:shadow-md transition-shadow duration-200 opacity-90">
                    <div class="w-full h-32 bg-[#ededf6] overflow-hidden">
                        <img class="w-full h-full object-cover" src="{{ asset('img/image-5.png') }}" alt="Jeruk" />
                    </div>
                    <div class="p-3 flex flex-col justify-between flex-1 gap-2">
                        <div>
                            <div class="font-bold text-[#191c21] text-base leading-snug">Jeruk</div>
                            <div class="text-[#424752] text-sm font-semibold mt-0.5">Rp 22.000</div>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-[#f0f0f5]">
                            <span class="bg-[#e7e8f0] text-[#424752] text-[10px] font-bold px-1.5 py-0.5 rounded-sm">STOCK: 28</span>
                            <button class="bg-[#0056b3] border-0 p-1.5 rounded-lg text-white hover:bg-[#004694] cursor-pointer flex items-center justify-center transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="flex flex-col w-[380px] h-full bg-[#f2f3fc] border-l border-[#c2c6d4] shrink-0 box-border justify-between">
        
        <div class="flex flex-col gap-3 p-4 bg-[#f9f9ff] border-b border-[#c2c6d4] box-border">
            <div class="flex items-center justify-between">
                <span class="font-semibold text-[#191c21] text-lg">Current Sale</span>
                <button class="bg-transparent border-0 flex items-center gap-1 cursor-pointer text-[#ba1a1a] hover:opacity-80 transition-opacity">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                    <span class="text-xs font-bold tracking-wider">CLEAR</span>
                </button>
            </div>
            
            <div class="flex items-center gap-2.5 px-3 py-2 bg-[#ededf6] rounded border border-[#c2c6d4]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-[#424752]"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                <span class="text-[#424752] text-sm font-medium">Walk-in Customer</span>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-4 flex flex-col gap-3 box-border">
            </div>

        <div class="p-4 bg-[#f9f9ff] border-t border-[#c2c6d4] flex flex-col gap-4 shadow-[0px_-4px_12px_rgba(0,0,0,0.04)] box-border w-full">
            <div class="flex items-center gap-3 w-full">
                <button class="flex-1 bg-white border border-[#c2c6d4] rounded-lg py-2.5 flex items-center justify-center gap-1.5 font-bold text-[#424752] text-sm hover:bg-gray-50 transition-colors cursor-pointer box-border">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581a2.25 2.25 0 0 0 3.181 0l4.318-4.318a2.25 2.25 0 0 0 0-3.181l-9.58-9.581A2.25 2.25 0 0 0 9.568 3Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" /></svg>
                    Promo
                </button>
                <button class="flex-1 bg-white border border-[#c2c6d4] rounded-lg py-2.5 flex items-center justify-center gap-1.5 font-bold text-[#424752] text-sm hover:bg-gray-50 transition-colors cursor-pointer box-border">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                    Draft
                </button>
            </div>
            
            <button class="w-full bg-[#003f87] border-0 text-white font-semibold text-lg py-4 rounded-xl flex items-center justify-center gap-3 shadow-md hover:bg-[#00316e] transition-all cursor-pointer box-border">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75m0 .75v.75m0 .75v.75m0 .75V15h16.5V8.25m-16.5 0h16.5M3.75 8.25v7.5m16.5-7.5V5.25c0-.754-.726-1.294-1.453-1.096A60.065 60.065 0 0 0 3.75 4.5Z" />
                </svg>
                <span>Bayar</span>
            </button>
        </div>

    </div>

</div>
</body>
</html>