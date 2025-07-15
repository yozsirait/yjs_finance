<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Tambah Anggota</h2>
    </x-slot>

    <form method="POST" action="{{ route('anggota.store') }}" class="max-w-xl mx-auto mt-6 bg-white p-6 rounded-xl shadow space-y-4">
        @csrf

        <x-form-group label="Nama Anggota" name="name">
            <input type="text" name="name" class="w-full border-gray-300 rounded" required>
        </x-form-group>

        <div class="flex justify-end">
            <a href="{{ route('anggota.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</a>
            <button type="submit" class="ml-2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
        </div>
    </form>
</x-app-layout>
