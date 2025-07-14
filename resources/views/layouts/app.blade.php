<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ config('app.name', 'YJ\'s Finance') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-gray-100">

    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar --}}
        <aside class="bg-white shadow-md w-full md:w-64 h-screen fixed md:relative z-10">
            <div class="p-6 text-xl font-bold text-blue-700">
                YJ's Finance
            </div>

            <nav class="space-y-1 px-4" x-data>
                @php
                    function activeRoute($route) {
                        return request()->is($route . '*') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700';
                    }
                @endphp

                <a href="/dashboard" class="flex items-center gap-2 p-2 rounded {{ activeRoute('dashboard') }}">
                    🏠 <span>Dashboard</span>
                </a>

                <a href="/transaksi" class="flex items-center gap-2 p-2 rounded {{ activeRoute('transaksi') }}">
                    💰 <span>Transaksi</span>
                </a>

                <a href="/akun" class="flex items-center gap-2 p-2 rounded {{ activeRoute('akun') }}">
                    🏦 <span>Bank & Wallet</span>
                </a>

                <a href="/kategori" class="flex items-center gap-2 p-2 rounded {{ activeRoute('kategori') }}">
                    🗂️ <span>Kategori</span>
                </a>

                <a href="/target-dana" class="flex items-center gap-2 p-2 rounded {{ activeRoute('target-dana') }}">
                    🎯 <span>Target Dana</span>
                </a>

                <a href="/pengeluaran-rutin" class="flex items-center gap-2 p-2 rounded {{ activeRoute('pengeluaran-rutin') }}">
                    💸 <span>Pengeluaran Rutin</span>
                </a>

                <a href="/anggaran" class="flex items-center gap-2 p-2 rounded {{ activeRoute('anggaran') }}">
                    🏷️ <span>Anggaran Kategori</span>
                </a>

                <a href="/mutasi" class="flex items-center gap-2 p-2 rounded {{ activeRoute('mutasi') }}">
                    🔄 <span>Mutasi Rekening</span>
                </a>

                <a href="/transaksi-mutasi" class="flex items-center gap-2 p-2 rounded {{ activeRoute('transaksi-mutasi') }}">
                    🔀 <span>Transaksi Mutasi</span>
                </a>

                 <!-- Laporan + dropdown -->
                <div
                    x-data="{ open: {{ request()->is('laporan*') ? 'true' : 'false' }} }"
                    class="space-y-1"
                >
                    <!-- Toggle button -->
                    <button
                        @click="open = !open"
                        class="flex items-center justify-between w-full p-2 rounded text-gray-700 hover:bg-gray-100"
                        :class="{ 'bg-blue-100 text-blue-700 font-semibold': {{ request()->is('laporan*') ? 'true' : 'false' }} }"
                    >
                        <span class="flex items-center gap-2">
                            📊 <span>Laporan</span>
                        </span>
                        <!-- caret -->
                        <svg class="w-4 h-4 transform transition-transform"
                            :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>

                    <!-- Sub‑menu -->
                    <div x-show="open" x-collapse class="pl-4 space-y-1" x-cloak>
                        <a href="/laporan/perbandingan-bulanan"
                        class="flex items-center gap-2 p-2 rounded {{ activeRoute('perbandingan-bulanan') }}">
                            📈 <span>Perbandingan Bulanan</span>
                        </a>

                        <a href="/laporan/perbandingan-member"
                        class="flex items-center gap-2 p-2 rounded {{ activeRoute('perbandingan-member') }}">
                            👥 <span>Perbandingan Member</span>
                        </a>

                        <a href="/laporan/tahunan"
                        class="flex items-center gap-2 p-2 rounded {{ activeRoute('tahunan') }}">
                            🧾 <span>Laporan Tahunan</span>
                        </a>
                    </div>
                </div>
            </nav>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 p-6 overflow-y-auto">
            {{ $header ?? '' }}

            <div class="mt-6">
                {{ $slot }}
            </div>
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>   

    <script>
    // Format semua input angka dengan class .rupiah
        document.querySelectorAll('input.rupiah').forEach(function (el) {
            el.addEventListener('input', function (e) {
                let value = e.target.value.replace(/[^\d]/g, '');
                e.target.value = new Intl.NumberFormat('id-ID').format(value);
            });
        });
    </script>
</body>
</html>
