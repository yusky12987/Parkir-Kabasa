<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Parkir</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100 font-sans text-slate-800 flex flex-col min-h-screen">

    <!-- TOPBAR / HEADER UTAMA -->
    <header class="bg-slate-900 text-slate-100 px-6 py-3 flex justify-between items-center border-b border-slate-800">
        <div class="font-bold text-lg tracking-wide">E-PARKIR APP</div>
        <div class="flex items-center gap-4 text-sm">
            <span>
                User: <strong>{{ Auth::user()->nama_lengkap ?? 'Guest' }}</strong>
                <span
                    class="text-xs bg-slate-700 px-2 py-1 rounded uppercase ml-1">{{ Auth::user()->role ?? '-' }}</span>
            </span>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit"
                    class="border border-slate-500 hover:bg-slate-800 text-slate-200 text-xs px-3 py-1 rounded transition">
                    Logout
                </button>
            </form>
        </div>
    </header>

    <div class="flex flex-1">
        <!-- SIDEBAR DINAMIS -->
        <aside class="w-64 bg-slate-800 text-slate-300 flex-shrink-0 min-h-full">
            <nav class="text-sm border-t border-slate-700">
                @if (Auth::user()->role == 'admin')
                    <a href="{{ route('admin.dashboard') }}"
                        class="block px-4 py-3 border-b border-slate-700 hover:bg-slate-700 hover:text-white transition">•
                        Dashboard</a>
                    <a href="{{ route('admin.user.index') }}"
                        class="block px-4 py-3 border-b border-slate-700 hover:bg-slate-700 hover:text-white transition">•
                        Data User</a>
                    <a href="{{ route('admin.tarif.index') }}"
                        class="block px-4 py-3 border-b border-slate-700 hover:bg-slate-700 hover:text-white transition">•
                        Data Tarif Parkir</a>
                    <a href="{{ route('admin.area.index') }}"
                        class="block px-4 py-3 border-b border-slate-700 hover:bg-slate-700 hover:text-white transition">•
                        Data Area Parkir</a>
                    <a href="{{ route('admin.kendaraan.index') }}"
                        class="block px-4 py-3 border-b border-slate-700 hover:bg-slate-700 hover:text-white transition">•
                        Data Kendaraan</a>
                    <a href="{{ route('admin.log.index') }}"
                        class="block px-4 py-3 border-b border-slate-700 hover:bg-slate-700 hover:text-white transition">•
                        Log Aktivitas</a>
                @elseif(Auth::user()->role == 'petugas')
                    <a href="{{ route('petugas.dashboard') }}"
                        class="block px-4 py-3 border-b border-slate-700 hover:bg-slate-700 hover:text-white transition">•
                        Dashboard</a>
                    <a href="{{ route('petugas.transaksi.masuk') }}"
                        class="block px-4 py-3 border-b border-slate-700 hover:bg-slate-700 hover:text-white transition">•
                        Transaksi Masuk</a>
                    <a href="{{ route('petugas.transaksi.keluar') }}"
                        class="block px-4 py-3 border-b border-slate-700 hover:bg-slate-700 hover:text-white transition">•
                        Transaksi Keluar / Bayar</a>
                @elseif(Auth::user()->role == 'owner')
                    <a href="{{ route('owner.dashboard') }}"
                        class="block px-4 py-3 border-b border-slate-700 hover:bg-slate-700 hover:text-white transition">•
                        Dashboard</a>
                    <a href="{{ route('owner.laporan.index') }}"
                        class="block px-4 py-3 border-b border-slate-700 hover:bg-slate-700 hover:text-white transition">•
                        Laporan Transaksi</a>
                @endif
            </nav>
        </aside>

        <!-- CONTENT AREA -->
        <main class="flex-1 p-6">
            @if (session('success'))
                <div class="mb-4 bg-slate-200 border border-slate-400 text-slate-800 px-4 py-2 rounded text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- FOOTER -->
    <footer class="bg-slate-200 border-t border-slate-300 text-center py-2 text-xs text-slate-600">
        &copy; 2026 Aplikasi Parkir UKK RPL - All Rights Reserved.
    </footer>

</body>

</html>
