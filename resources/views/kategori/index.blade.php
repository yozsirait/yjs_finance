<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Kategori</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto mt-6 space-y-4">
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-2 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- Form tambah kategori --}}
        <form action="{{ route('kategori.store') }}" method="POST" class="bg-white p-4 rounded shadow flex flex-wrap gap-2 items-center">
            @csrf
            <input type="text" name="name" placeholder="Nama Kategori" required class="rounded border-gray-300 px-3 py-2">
            <select name="type" required class="rounded border-gray-300 px-3 py-2">
                <option value="">Jenis</option>
                <option value="pemasukan">Pemasukan</option>
                <option value="pengeluaran">Pengeluaran</option>
            </select>
            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Tambah</button>
        </form>

        {{-- Tabel kategori --}}
        <div class="bg-white p-4 rounded shadow">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left border-b">
                        <th class="py-2">Nama</th>
                        <th class="py-2">Jenis</th>
                        <th class="py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $cat)
                        <tr class="border-b">
                            <td class="py-2">{{ $cat->name }}</td>
                            <td class="py-2">
                                <span class="px-2 py-1 text-white text-xs rounded {{ $cat->type == 'pemasukan' ? 'text-green-600 bg-green-500' : 'text-red-600 bg-red-500' }}">
                                    {{ ucfirst($cat->type) }}
                                </span>
                            </td>
                            <td class="py-2">
                                <form action="{{ route('kategori.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline text-xs">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @if ($categories->isEmpty())
                        <tr>
                            <td colspan="3" class="text-gray-500 text-sm py-4 text-center">Belum ada kategori.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
