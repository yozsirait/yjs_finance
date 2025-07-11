<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Data Pengeluaran</h2>
            <a href="{{ route('pengeluaran.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">
                + Tambah
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto mt-6 bg-white rounded-xl shadow p-4">
        <table class="w-full table-auto text-sm">
            <thead>
                <tr class="text-gray-500 text-left border-b">
                    <th>Tanggal</th>
                    <th>Anggota</th>
                    <th>Kategori</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenditures as $expenditure)
                    <tr class="border-t">
                        <td>{{ $expenditure->date }}</td>
                        <td>{{ $expenditure->member->name }}</td>
                        <td>{{ $expenditure->category?->name ?? '-' }}</td>
                        <td>Rp {{ number_format($expenditure->amount, 0, ',', '.') }}</td>
                        <td>{{ $expenditure->description }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">Belum ada data pengeluaran</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
