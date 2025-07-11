<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Data Kategori</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto mt-6">
        @if (session('success'))
            <div class="mb-4 bg-green-100 text-green-800 px-4 py-2 rounded shadow">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Data Kategori</h2>
                <a href="{{ route('kategori.create') }}"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                    + Tambah
                </a>
            </div>


            <table class="w-full table-auto">
                <thead class="text-left text-sm text-gray-500">
                    <tr>
                        <th>Nama</th>
                        <th>Tipe</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="text-sm text-gray-700">
                    @forelse($categories as $cat)
                        <tr class="border-t">
                            <td class="py-2">{{ $cat->name }}</td>
                            <td class="py-2">{{ ucfirst($cat->type) }}</td>
                            <td class="py-2 text-right">
                                <form action="{{ route('kategori.destroy', $cat->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline text-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-4 text-center">Belum ada kategori</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>
</x-app-layout>
