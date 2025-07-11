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
            <table class="w-full table-auto">
                <thead class="text-left text-sm text-gray-500">
                    <tr>
                        <th>Nama</th>
                        <th>Tipe</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    @forelse($categories as $cat)
                        <tr class="border-t">
                            <td>{{ $cat->name }}</td>
                            <td>{{ ucfirst($cat->type) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="py-4 text-center">Belum ada kategori</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
