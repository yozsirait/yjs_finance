<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Detail Target: {{ $target->name }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto mt-6 space-y-6">
        <div class="p-4 bg-white shadow rounded-xl">
            <div class="mb-2 text-sm text-gray-500">
                Target: Rp{{ number_format($target->target_amount, 0, ',', '.') }} <br>
                Tersimpan: Rp{{ number_format($target->saved_amount, 0, ',', '.') }} <br>
                Deadline: {{ $target->deadline ? \Carbon\Carbon::parse($target->deadline)->format('d M Y') : '-' }} <br>
                Status: <span class="font-semibold text-green-600">{{ ucfirst($target->status) }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3 mt-2">
                <div class="bg-blue-500 h-3 rounded-full" style="width: {{ $target->progress_percentage }}%"></div>
            </div>
        </div>

        {{-- Form Tambah Dana --}}
        <div class="p-4 bg-white shadow rounded-xl">
            <h3 class="font-semibold mb-2">Tambah Dana</h3>
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 p-2 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('target-dana.simpan', $target->id) }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium">Tanggal</label>
                    <input type="date" name="date" required class="w-full mt-1 border-gray-300 rounded-md"
                        value="{{ old('date', now()->toDateString()) }}">
                </div>

                <div>
                    <label class="block text-sm font-medium">Jumlah Dana (Rp)</label>
                    <input type="text" name="amount" class="rupiah w-full mt-1 border-gray-300 rounded-md text-right"
                        required value="{{ old('amount') }}">
                </div>

                <div>
                    <label class="block text-sm font-medium">Deskripsi (Opsional)</label>
                    <input type="text" name="description" class="w-full mt-1 border-gray-300 rounded-md"
                        value="{{ old('description') }}">
                </div>

                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>
            </form>
        </div>

        {{-- Riwayat Simpan Dana --}}
        <div class="p-4 bg-white shadow rounded-xl">
            <h3 class="font-semibold mb-2">Riwayat Dana Masuk</h3>
            @forelse ($target->logs->sortByDesc('date') as $log)
                <div class="flex justify-between items-center border-b py-2 text-sm">
                    <div>
                        <div class="text-gray-800 font-medium">Rp{{ number_format($log->amount, 0, ',', '.') }}</div>
                        <div class="text-gray-500">{{ $log->description ?? '-' }}</div>
                    </div>
                    <div class="text-right space-y-1">
                        <div class="text-gray-400">{{ \Carbon\Carbon::parse($log->date)->format('d M Y') }}</div>
                        <div class="flex gap-2 justify-end text-xs">
                            <a href="{{ route('target-dana.log.edit', [$target->id, $log->id]) }}"
                            class="text-blue-600 hover:underline">Edit</a>
                            <form method="POST" action="{{ route('target-dana.log.destroy', [$target->id, $log->id]) }}"
                                onsubmit="return confirm('Yakin hapus log ini?')" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>

            @empty
                <p class="text-gray-500 text-sm">Belum ada dana masuk.</p>
            @endforelse
        </div>
    </div>

    <script>
        // Auto-format rupiah
        document.querySelectorAll('input.rupiah').forEach(function (el) {
            el.addEventListener('input', function (e) {
                let value = e.target.value.replace(/[^\d]/g, '');
                e.target.value = new Intl.NumberFormat('id-ID').format(value);
            });
        });
    </script>
</x-app-layout>
