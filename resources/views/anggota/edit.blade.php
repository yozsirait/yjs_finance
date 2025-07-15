<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Edit Anggota</h2>
    </x-slot>

    <form action="{{ route('anggota.update', ['anggota' => $anggota->id]) }}" method="POST"
        class="bg-white p-6 rounded-xl shadow max-w-xl mx-auto space-y-6">
        @csrf
        @method('PUT')

        <x-form-group label="Nama Anggota" name="name">
            <input type="text" name="name" class="w-full border-gray-300 rounded"
                value="{{ old('name', $anggota->name) }}" required>
        </x-form-group>

        <x-form-group label="PIN (opsional)" name="pin">
            <input type="text" name="pin" class="w-full border-gray-300 rounded"
                value="{{ old('pin', $anggota->pin) }}" maxlength="4" pattern="\d*">
            <small class="text-gray-500">Kosongkan jika tidak ingin mengubah PIN</small>
        </x-form-group>

        <div class="flex justify-between">
            <a href="{{ route('anggota.index') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</a>
            <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
        </div>
    </form>
</x-app-layout>
