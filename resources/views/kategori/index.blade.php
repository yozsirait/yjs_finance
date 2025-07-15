<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Kategori</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto mt-6 space-y-4">

        {{-- Notifikasi sukses --}}
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Form tambah kategori --}}
        <form action="{{ route('kategori.store') }}" method="POST"
            class="bg-white p-4 rounded shadow flex flex-wrap gap-3 items-center">
            @csrf
            <input type="text" name="name" placeholder="Nama Kategori" required
                class="flex-grow rounded border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-400">
            <select name="type" required
                class="w-40 rounded border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-400">
                <option value="" disabled selected>Jenis</option>
                <option value="pemasukan">Pemasukan</option>
                <option value="pengeluaran">Pengeluaran</option>
            </select>
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">
                Tambah
            </button>
        </form>

        {{-- Tabel kategori --}}
        <div class="bg-white p-4 rounded shadow overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left border-b border-gray-300">
                        <th class="py-2">Nama</th>
                        <th class="py-2">Jenis</th>
                        <th class="py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $cat)
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                            <td class="py-2">{{ $cat->name }}</td>
                            <td class="py-2">
                                <span
                                    class="inline-block px-2 py-1 text-xs font-semibold rounded
                                    {{ $cat->type === 'pemasukan' ? 'bg-green-500 text-green-900' : 'bg-red-500 text-red-900' }}">
                                    {{ ucfirst($cat->type) }}
                                </span>
                            </td>
                            <td class="py-2">
                                <form action="{{ route('kategori.destroy', $cat->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline text-xs font-medium">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-gray-500 text-center py-6">
                                Belum ada kategori.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
