<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Daftar Anggota</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto mt-6 space-y-4">
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-2 rounded">{{ session('success') }}</div>
        @endif

        <a href="{{ route('anggota.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            + Tambah Anggota
        </a>

        <ul class="bg-white rounded-xl shadow divide-y">
            @foreach ($members as $member)
                <li class="p-4 flex justify-between items-center">
                    <span>{{ $member->name }}</span>
                    <div class="space-x-2">
                        {{-- Ganti form ini --}}
                        <a href="{{ route('anggota.edit', ['anggota' => $member->id]) }}"
                            class="text-blue-600 hover:underline">Edit</a>

                        <form action="{{ route('anggota.destroy', $member->id) }}" method="POST" class="inline"
                            onsubmit="return confirm('Hapus anggota ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>

    </div>
</x-app-layout>
