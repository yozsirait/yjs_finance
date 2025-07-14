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
        <aside class="w-64 bg-white border-r shadow-sm p-4">
            <h1 class="text-xl font-bold mb-6">YJ's Finance</h1>
            <nav class="flex flex-col gap-3 text-gray-700">
                @php
                    function active($path) {
                        return request()->is($path) ? 'text-blue-600 font-semibold' : '';
                    }
                @endphp

                <a href="/dashboard" class="flex items-center gap-2 hover:text-blue-600 {{ active('dashboard') }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Dashboard
                </a>
                <a href="/kategori" class="flex items-center gap-2 hover:text-blue-600 {{ active('kategori') }}">
                    <i data-lucide="folder" class="w-5 h-5"></i> Kategori
                </a>
                <a href="/anggaran" class="flex items-center gap-2 hover:text-blue-600 {{ active('anggaran') }}">
                    <i data-lucide="wallet" class="w-5 h-5"></i> Anggaran Kategori
                </a>                
                <a href="/akun" class="flex items-center gap-2 hover:text-blue-600 {{ active('akun') }}">
                    <i data-lucide="credit-card" class="w-5 h-5"></i> Bank & Wallet
                </a>
                <a href="/mutasi" class="flex items-center gap-2 hover:text-blue-600 {{ active('mutasi') }}">
                    <i data-lucide="repeat" class="w-5 h-5"></i> Mutasi Rekening
                </a>
                <a href="/transaksi" class="flex items-center gap-2 hover:text-blue-600 {{ active('transaksi') }}">
                    <i data-lucide="list" class="w-5 h-5"></i> Transaksi
                </a>
                <a href="/target-dana" class="flex items-center gap-2 hover:text-blue-600 {{ active('target-dana') }}">
                    <i data-lucide="target" class="w-5 h-5"></i> Target Simpan Dana
                </a>
                <a href="/pengeluaran-rutin" class="flex items-center gap-2 hover:text-blue-600 {{ active('pengeluaran-rutin') }}">
                    <i data-lucide="banknote-arrow-down" class="w-5 h-5"></i> Pengeluaran Rutin
                </a>
                <a href="/laporan/perbandingan-bulanan" class="flex items-center gap-2 hover:text-blue-600 {{ active('laporan/bulanan') }}">
                    <i data-lucide="bar-chart-2" class="w-5 h-5"></i> Perbandingan Bulanan
                </a>
                <a href="/laporan/perbandingan-member" class="flex items-center gap-2 hover:text-blue-600 {{ active('laporan/member') }}">
                    <i data-lucide="users" class="w-5 h-5"></i> Perbandingan Member
                </a>

                <!--<a href="/report/bulanan" class="flex items-center gap-2 hover:text-blue-600 {{ active('report/bulanan') }}">
                    <i data-lucide="calendar" class="w-5 h-5"></i> Report Bulanan
                </a>
                <a href="/report/tahunan" class="flex items-center gap-2 hover:text-blue-600 {{ active('report/tahunan') }}">
                    <i data-lucide="calendar-range" class="w-5 h-5"></i> Report Tahunan
                </a>
                <a href="/grafik" class="flex items-center gap-2 hover:text-blue-600 {{ active('grafik') }}">
                    <i data-lucide="bar-chart-3" class="w-5 h-5"></i> Grafik
                </a>-->
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
