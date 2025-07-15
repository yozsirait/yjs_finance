<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Verifikasi PIN</h2>
    </x-slot>

    <div class="max-w-md mx-auto mt-6 bg-white p-6 rounded-xl shadow">
        <form method="POST" action="{{ route('pin.submit') }}">
            @csrf
            <x-form-group label="Masukkan PIN Akses" name="pin">
                <input type="password" name="pin" class="w-full rounded border-gray-300" required>
            </x-form-group>

            @error('pin')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror

            <div class="mt-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Akses
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
