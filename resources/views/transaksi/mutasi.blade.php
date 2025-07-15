<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Transaksi Mutasi</h2>
    </x-slot>

    <div class="max-w-6xl mx-auto mt-4 space-y-4">
        {{-- Filter --}}
        <form method="GET" class="flex flex-wrap gap-2 bg-white p-4 rounded shadow">
            <select name="month" class="rounded border-gray-300">
                <option value="" {{ $month === null ? 'selected' : '' }}>Semua Bulan</option>
                @foreach (range(1, 12) as $m)
                    <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                    </option>
                @endforeach
            </select>

            <select name="year" class="rounded border-gray-300">
                <option value="" {{ $year === null ? 'selected' : '' }}>Semua Tahun</option>
                @foreach ($availableYears as $y)
                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>

            <select name="member" class="rounded border-gray-300">
                <option value="">Semua Anggota</option>
                @foreach ($members as $m)
                    <option value="{{ $m->id }}" {{ $m->id == $member ? 'selected' : '' }}>
                        {{ $m->name }}
                    </option>
                @endforeach
            </select>

            <button class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                Filter
            </button>
        </form>

        {{-- Table --}}
        <div class="overflow-x-auto bg-white shadow rounded p-4">
            <table class="min-w-full text-sm table-auto">
                <thead>
                    <tr class="text-left text-gray-600 border-b">
                        <th class="px-2 py-2">Tanggal</th>
                        <th class="px-2 py-2">Akun</th>
                        <th class="px-2 py-2">Kategori</th>
                        <th class="px-2 py-2">Anggota</th>
                        <th class="px-2 py-2 text-right">Jumlah</th>
                        <th class="px-2 py-2">Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $trx)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-2 py-2">{{ \Carbon\Carbon::parse($trx->date)->format('d M Y') }}</td>
                            <td class="px-2 py-2">{{ $trx->account->name }}</td>
                            <td class="px-2 py-2">{{ $trx->category }}</td>
                            <td class="px-2 py-2">{{ $trx->member->name ?? '-' }}</td>
                            <td
                                class="px-2 py-2 text-right text-{{ $trx->type == 'pengeluaran' ? 'red' : 'green' }}-600">
                                Rp{{ number_format($trx->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-2 py-2">{{ $trx->description }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-500 py-4">Tidak ada transaksi mutasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
