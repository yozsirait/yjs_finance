<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Target Simpan Dana</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6 mt-6">
        <a href="{{ route('target-dana.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">+ Tambah Target</a>

        @forelse ($targets as $target)
            <div class="p-4 bg-white shadow rounded-xl space-y-2">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-semibold">{{ $target->name }}</h3>

                        <a href="{{ route('target-dana.show', $target->id) }}"
                            class="text-sm text-blue-600 hover:underline">Detail</a>

                        <p class="text-sm text-gray-500">
                            Target: Rp{{ number_format($target->target_amount, 0, ',', '.') }} |
                            Tersimpan: Rp{{ number_format($target->saved_amount, 0, ',', '.') }}
                        </p>
                        @if ($target->deadline)
                            <p class="text-sm text-gray-400">Deadline: {{ \Carbon\Carbon::parse($target->deadline)->format('d M Y') }}</p>
                        @endif
                    </div>

                    <div class="text-right space-y-2">
                        <span class="text-sm text-gray-700 font-medium block">
                            {{ $target->progress_percentage }}%
                        </span>

                        {{-- Tombol Hapus --}}
                        <form action="{{ route('target-dana.destroy', $target->id) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus target ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-red-600 text-xs hover:underline">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>

                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-blue-500 h-3 rounded-full"
                        style="width: {{ $target->progress_percentage }}%"></div>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-600 py-12">Belum ada target simpan dana.</div>
        @endforelse
    </div>
</x-app-layout>
