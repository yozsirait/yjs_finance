<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Data Transaksi</h2>
            <a href="{{ route('transaksi.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">
                + Tambah Transaksi
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto mt-6 space-y-6">

        <!-- Filter Form -->
        <form method="GET"
            class="bg-white p-6 rounded-xl shadow-md grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Bulan</label>
                <select name="month" class="w-full border-gray-300 rounded-md">
                    <option value="">Semua</option>
                    @foreach (range(1, 12) as $m)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Tahun</label>
                <select name="year" class="w-full border-gray-300 rounded-md">
                    <option value="">Semua</option>
                    @foreach ($availableYears as $year)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Tipe</label>
                <select name="type" class="w-full border-gray-300 rounded-md">
                    <option value="">Semua</option>
                    <option value="pemasukan" {{ request('type') == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                    <option value="pengeluaran" {{ request('type') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran
                    </option>
                </select>
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Akun</label>
                <select name="account_id" class="w-full border-gray-300 rounded-md">
                    <option value="">Semua</option>
                    @foreach (auth()->user()->accounts as $acc)
                        <option value="{{ $acc->id }}" {{ request('account_id') == $acc->id ? 'selected' : '' }}>
                            {{ $acc->name }} ({{ ucfirst($acc->type) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Anggota</label>
                <select name="member" class="w-full border-gray-300 rounded-md">
                    <option value="">Semua</option>
                    @foreach ($members as $member)
                        <option value="{{ $member->id }}" {{ request('member') == $member->id ? 'selected' : '' }}>
                            {{ $member->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="self-end">
                <button type="submit"
                    class="w-full bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800 text-sm">
                    Filter
                </button>
            </div>
        </form>

        <!-- Success Alert -->
        @if (session('success'))
            <div class="px-4 py-3 bg-green-100 text-green-800 rounded shadow">
                {{ session('success') }}
            </div>
        @endif

        <!-- Transactions Table -->
        <div class="bg-white shadow-md rounded-xl overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-2 text-left">Tanggal</th>
                        <th class="px-4 py-2 text-left">Anggota</th>
                        <th class="px-4 py-2 text-left">Tipe</th>
                        <th class="px-4 py-2 text-left">Akun</th>
                        <th class="px-4 py-2 text-left">Kategori</th>
                        <th class="px-4 py-2 text-right">Jumlah</th>
                        <th class="px-4 py-2 text-left">Keterangan</th>
                        <th class="px-4 py-2 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-gray-700">
                    @forelse ($transactions as $trx)
                        <tr>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($trx->date)->format('d M Y') }}</td>
                            <td class="px-4 py-2">{{ $trx->member->name ?? '-' }}</td>
                            <td class="px-4 py-2">
                                <span
                                    class="px-2 py-1 text-white text-xs rounded
                                {{ $trx->type === 'pemasukan' ? 'bg-green-500' : 'bg-red-500' }}">
                                    {{ ucfirst($trx->type) }}
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                {{ $trx->account?->name }}
                                <small class="block text-gray-500">({{ ucfirst($trx->account?->type) }})</small>
                            </td>
                            <td class="px-4 py-2">{{ $trx->category ?? '-' }}</td>
                            <td class="px-4 py-2 text-right font-semibold">
                                Rp{{ number_format($trx->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-2">{{ $trx->description }}</td>
                            <td class="px-4 py-2">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('transaksi.edit', $trx->id) }}"
                                        class="text-blue-600 hover:text-blue-800" title="Edit">
                                        ✏️
                                    </a>
                                    <a href="{{ route('transaksi.duplicate', $trx->id) }}"
                                        class="text-yellow-500 hover:text-yellow-600" title="Duplicate">
                                        📋
                                    </a>
                                    <form action="{{ route('transaksi.destroy', $trx->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin hapus transaksi ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800"
                                            title="Hapus">🗑️</button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-4 text-center text-gray-500">Tidak ada transaksi
                                ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</x-app-layout>
