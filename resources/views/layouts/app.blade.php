<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>{{ config('app.name', "YJ's Finance") }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Icons & Alpine -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100" x-data="{ sidebarOpen: false }">
    <!-- Alpine test -->
    <div x-init="console.log('Alpine OK')" class="hidden"></div>

    <div class="flex h-screen overflow-hidden">
        <!-- ░░░ Mobile header ░░░ -->
        <header class="fixed inset-x-0 top-0 z-40 flex items-center justify-between bg-white px-4 py-3 shadow md:hidden">
            <button id="sidebarToggle">
                <svg class="h-6 w-6 text-gray-700" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <span class="text-lg font-bold text-blue-700">YJ's Finance</span>
        </header>

        <!-- ░░░ Sidebar ░░░ -->
        <aside id="sidebar"
            class="fixed z-50 h-full w-64 -translate-x-full overflow-y-auto bg-white shadow-md transition-transform duration-200 
            ease-in-out md:relative md:block md:translate-x-0 pt-4 md:pt-6">
            <div class="hidden p-6 text-xl font-bold text-blue-700 md:block">YJ's Finance</div>

            <nav class="flex flex-col justify-between h-full px-4" x-data>

                @php
                    function activeRoute($route)
                    {
                        return request()->is($route . '*')
                            ? 'bg-blue-100 text-blue-700 font-semibold'
                            : 'text-gray-700';
                    }
                @endphp
                <div class="space-y-1">
                    <a href="/dashboard"
                        class="flex items-center gap-2 rounded p-2 {{ activeRoute('dashboard') }}">🏠<span>Dashboard</span></a>
                    <a href="/transaksi"
                        class="flex items-center gap-2 rounded p-2 {{ activeRoute('transaksi') }}">💰<span>Transaksi</span></a>
                    <a href="/akun" class="flex items-center gap-2 rounded p-2 {{ activeRoute('akun') }}">🏦<span>Bank
                            &
                            Wallet</span></a>
                    <a href="/kategori"
                        class="flex items-center gap-2 rounded p-2 {{ activeRoute('kategori') }}">🗂️<span>Kategori</span></a>
                    <a href="/target-dana"
                        class="flex items-center gap-2 rounded p-2 {{ activeRoute('target-dana') }}">🎯<span>Target
                            Dana</span></a>
                    <a href="/pengeluaran-rutin"
                        class="flex items-center gap-2 rounded p-2 {{ activeRoute('pengeluaran-rutin') }}">💸<span>Pengeluaran
                            Rutin</span></a>
                    <a href="/anggaran"
                        class="flex items-center gap-2 rounded p-2 {{ activeRoute('anggaran') }}">🏷️<span>Anggaran
                            Kategori</span></a>
                    <a href="/mutasi"
                        class="flex items-center gap-2 rounded p-2 {{ activeRoute('mutasi') }}">🔄<span>Mutasi
                            Rekening</span></a>
                    <a href="/transaksi-mutasi"
                        class="flex items-center gap-2 rounded p-2 {{ activeRoute('transaksi-mutasi') }}">🔀<span>Transaksi
                            Mutasi</span></a>

                    <!-- ░░░ Dropdown : Laporan ░░░ -->
                    <div x-data="{ open: {{ request()->is('laporan*') ? 'true' : 'false' }} }" class="space-y-1">
                        <button @click="open = !open"
                            class="flex w-full items-center justify-between rounded p-2 text-gray-700 hover:bg-gray-100"
                            :class="{ 'bg-blue-100 text-blue-700 font-semibold': open }">
                            <span class="flex items-center gap-2">📊<span>Laporan</span></span>
                            <svg class="h-4 w-4 transform transition-transform" :class="open ? 'rotate-90' : ''"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                        <div x-show="open" x-transition x-cloak class="space-y-1 pl-4">
                            <a href="/laporan/perbandingan-bulanan"
                                class="flex items-center gap-2 rounded p-2 {{ activeRoute('perbandingan-bulanan') }}">📈<span>Perbandingan
                                    Bulanan</span></a>
                            <a href="/laporan/perbandingan-member"
                                class="flex items-center gap-2 rounded p-2 {{ activeRoute('perbandingan-member') }}">👥<span>Perbandingan
                                    Member</span></a>
                            <a href="/laporan/tahunan"
                                class="flex items-center gap-2 rounded p-2 {{ activeRoute('tahunan') }}">🧾<span>Laporan
                                    Tahunan</span></a>
                        </div>
                    </div>

                    <a href="/anggota"
                        class="flex items-center gap-2 rounded p-2 {{ activeRoute('anggota') }}">🧑‍🤝‍🧑<span>Anggota</span></a>
                </div>
                <!-- ░░░ User & Logout ░░░ -->
                <div class="mb-4">
                    <div class="text-sm text-gray-600 mb-2">
                        👋 Halo, {{ auth()->user()->name }}
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-2 p-2 rounded text-gray-700 hover:bg-red-100 hover:text-red-700">
                            🚪 <span>Logout</span>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- ░░░ Main content ░░░ -->
        <main class="flex-1 overflow-y-auto mt-16 md:mt-0 md:ml-30 p-6">
            {{ $header ?? '' }}
            <div class="mt-6">{{ $slot }}</div>
        </main>
    </div>

    <!-- ░░░ Scripts ░░░ -->
    <script>
        lucide.createIcons();
    </script>

    <!-- Format input .rupiah -->
    <script>
        document.querySelectorAll('input.rupiah').forEach((el) => {
            el.addEventListener('input', (e) => {
                const value = e.target.value.replace(/[^\d]/g, '');
                e.target.value = new Intl.NumberFormat('id-ID').format(value);
            });
        });
    </script>

    <!-- Sidebar toggle -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const toggle = document.getElementById('sidebarToggle');

        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            sidebar.classList.toggle('translate-x-0');
        });

        document.addEventListener('click', (e) => {
            if (window.innerWidth < 768 && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('translate-x-0');
            }
        });
    </script>
</body>

</html>
