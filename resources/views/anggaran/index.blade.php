<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Anggaran Kategori Bulanan</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto mt-6 space-y-6">

        {{-- Form Tambah / Update Anggaran --}}
        <div class="bg-white p-4 shadow rounded-xl">
            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('anggaran.store') }}" method="POST" class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @csrf
                <select name="type" required class="rounded border-gray-300">
                    <option value="">Jenis</option>
                    <option value="pemasukan">Pemasukan</option>
                    <option value="pengeluaran">Pengeluaran</option>
                </select>

                <input type="text" name="category" placeholder="Nama Kategori" required
                    class="rounded border-gray-300">

                <input type="text" name="amount" placeholder="Nominal Anggaran" required
                    class="rupiah rounded border-gray-300 text-right">

                <select name="month" required class="rounded border-gray-300">
                    @foreach (range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                        </option>
                    @endforeach
                </select>

                <select name="year" required class="rounded border-gray-300">
                    @foreach (range(date('Y'), date('Y') - 5) as $y)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>

                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 col-span-2 md:col-span-1">
                    Simpan Anggaran
                </button>
            </form>
        </div>

        {{-- List Anggaran --}}
        <div class="bg-white p-4 shadow rounded-xl">
            <h3 class="text-lg font-semibold mb-4">Daftar Anggaran ({{ DateTime::createFromFormat('!m', $month)->format('F') }} {{ $year }})</h3>

            @forelse ($budgets as $budget)
                @php
                    $usage = $usages->firstWhere('category', $budget->category)?->total ?? 0;
                    $percent = min(100, round($usage / $budget->amount * 100));
                @endphp

                <div class="mb-4">
                    <div class="flex justify-between text-sm font-medium">
                        <span>{{ ucfirst($budget->type) }}: {{ $budget->category }}</span>
                        <span class="{{ $usage > $budget->amount ? 'text-red-600' : 'text-gray-600' }}">
                            Rp{{ number_format($usage, 0, ',', '.') }} / Rp{{ number_format($budget->amount, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 h-3 rounded mt-1">
                        <div class="h-3 rounded bg-{{ $usage > $budget->amount ? 'red' : 'blue' }}-500"
                            style="width: {{ $percent }}%">
                        </div>
                    </div>
                    <form action="{{ route('anggaran.destroy', $budget->id) }}" method="POST"
                        onsubmit="return confirm('Hapus anggaran ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="text-xs text-red-600 mt-1 hover:underline">Hapus</button>
                    </form>
                </div>
            @empty
                <p class="text-gray-500 text-sm">Belum ada anggaran bulan ini.</p>
            @endforelse
        </div>
    </div>

    <script>
        document.querySelectorAll('input.rupiah').forEach(function (el) {
            el.addEventListener('input', function (e) {
                let value = e.target.value.replace(/[^\d]/g, '');
                e.target.value = new Intl.NumberFormat('id-ID').format(value);
            });
        });
    </script>
</x-app-layout>
