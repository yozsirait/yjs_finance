<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Akses Terproteksi</h2>
    </x-slot>

    <div class="max-w-md mx-auto mt-10 bg-white p-6 rounded-xl shadow">
        @if ($errors->any())
            <div class="text-red-600 mb-4">{{ $errors->first('pin') }}</div>
        @endif

        <form method="POST" action="{{ route('pin.verify') }}">
            @csrf
            <label class="block mb-2 font-medium">Masukkan PIN:</label>
            <input type="password" name="pin" maxlength="4" pattern="\d*"
                   class="w-full border border-gray-300 rounded px-4 py-2 text-center text-lg tracking-widest"
                   required autofocus>
            <button type="submit"
                    class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full">
                Verifikasi
            </button>
        </form>
    </div>
</x-app-layout>
