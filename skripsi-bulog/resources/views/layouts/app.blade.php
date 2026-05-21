<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIM Pasar') — SIM Pasar Jabar</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .nav-active { background: rgba(255,255,255,0.18); color: #fff; }
        .nav-link:hover { background: rgba(255,255,255,0.1); }
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-thumb { background: #93c5fd; border-radius: 3px; }
        .tbl-row:hover { background-color: #eff6ff; }
        .page-in { animation: fadeUp .3s ease both; }
        @keyframes fadeUp {
            from { opacity:0; transform:translateY(10px); }
            to   { opacity:1; transform:translateY(0); }
        }
        .flash { animation: slideDown .3s ease both; }
        @keyframes slideDown {
            from { opacity:0; transform:translateY(-8px); }
            to   { opacity:1; transform:translateY(0); }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-blue-50 min-h-screen flex">

{{-- ── Sidebar ───────────────────────────────────────────────────────────────── --}}
<aside class="w-60 shrink-0 min-h-screen flex flex-col shadow-xl"
       style="background:linear-gradient(180deg,#1e3a8a 0%,#1d4ed8 60%,#2563eb 100%)">

    {{-- Brand --}}
    <div class="px-5 py-5 border-b border-white/10 flex items-center gap-3">
        <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
            <img 
                src="{{ asset('image/logoputih.png') }}" 
                alt="Description" 
                class="w-5 h-5 object-contain"
            >
        </div>
        <div>
            <p class="text-white font-extrabold text-sm leading-tight">Monitoring SPHP di Pasar</p>
            <p class="text-blue-200 text-[10px] leading-tight">Jawa Barat</p>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-4 space-y-1">
        <p class="text-blue-300 text-[10px] font-semibold uppercase tracking-widest px-3 mb-3">Menu Utama</p>

        {{-- Manajemen Pasar --}}
        <a href="{{ route('pasars.index') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-blue-100 hover:text-white transition text-sm font-medium
                  {{ request()->routeIs('pasars.*') ? 'nav-active' : '' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016 2.993 2.993 0 0 0 2.25-1.016 3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
            </svg>
            <span>Manajemen Pasar</span>
        </a>

        {{-- Transaksi --}}
        <a href="{{ route('transaksis.index') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-blue-100 hover:text-white transition text-sm font-medium
                  {{ request()->routeIs('transaksis.*') ? 'nav-active' : '' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
            </svg>
            <span>Transaksi</span>
        </a>

        {{-- Peta Lokasi --}}
        <a href="{{ route('maps.index') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-blue-100 hover:text-white transition text-sm font-medium
                  {{ request()->routeIs('maps.*') ? 'nav-active' : '' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
            </svg>
            <span>Peta Lokasi</span>
            @if(request()->routeIs('maps.*'))
                <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
            @endif
        </a>
    </nav>
    

    {{-- Footer --}}
    <div class="px-5 py-4 border-t border-white/10">
        <p class="text-blue-300 text-[10px] text-center">© {{ date('Y') }} Dinas Perdagangan Jabar</p>
    </div>
</aside>

{{-- ── Main ─────────────────────────────────────────────────────────────────── --}}
<div class="flex-1 flex flex-col min-h-screen overflow-hidden">

    {{-- Top bar --}}
    <header class="bg-white border-b border-blue-100 px-6 py-3.5 flex items-center justify-between shadow-sm">
        <div class="flex flex-col">
            <h1 class="text-blue-800 font-bold text-base">@yield('page-title', 'Dashboard')</h1>
            <nav class="text-[10px] text-blue-400 font-medium flex items-center gap-1.5 mt-0.5">
                <a href="{{ route('pasars.index') }}" class="hover:text-blue-600 transition">Home</a>
                @hasSection('breadcrumb')
                    <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                    </svg>
                    <span class="text-blue-300">@yield('breadcrumb')</span>
                @endif
            </nav>
        </div>

        <div class="flex items-center gap-4">
            {{-- User Profile Info --}}
            <div class="hidden md:flex flex-col items-end mr-2">
                <p class="text-xs font-semibold text-slate-700">{{ Auth::user()->name ?? 'Administrator' }}</p>
                <p class="text-[10px] text-slate-400 capitalize">{{ Auth::user()->role ?? 'Petugas' }}</p>
            </div>

            {{-- TOMBOL LOGOUT BARU: Menggunakan link <a> agar sinkron dengan rute GET di web.php --}}
            <a href="{{ route('logout') }}" 
               class="flex items-center gap-2 px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg border border-red-100 transition duration-200 group">
                <span class="text-xs font-bold uppercase tracking-wider">Keluar</span>
                <svg class="w-4 h-4 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                </svg>
            </a>
        </div>
    </header>

    {{-- Flash messages --}}
    @if(session('success'))
    <div class="flash mx-6 mt-4 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-4 py-3 text-sm font-medium">
        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
        </svg>
        {{ session('success') }}
        <button onclick="this.parentElement.remove()" class="ml-auto text-emerald-400 hover:text-emerald-600 text-xl leading-none">&times;</button>
    </div>
    @endif

    @if(session('error'))
    <div class="flash mx-6 mt-4 flex items-center gap-3 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 text-sm font-medium">
        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
        </svg>
        {{ session('error') }}
        <button onclick="this.parentElement.remove()" class="ml-auto text-red-400 hover:text-red-600 text-xl leading-none">&times;</button>
    </div>
    @endif

    {{-- Page content --}}
    <main class="page-in flex-1 px-6 py-6 overflow-auto">
        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>