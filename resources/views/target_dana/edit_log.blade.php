<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Edit Dana Masuk</h2>
    </x-slot>

    <form method="POST" action="{{ route('target-dana.log.update', [$target->id, $log->id]) }}"
          class="max-w-xl mx-auto bg-white p-6 mt-4 rounded-xl shadow space-y-4">
        @csrf
        @method('PATCH')

        <div>
            <label class="block text-sm font-medium">Tanggal</label>
            <input type="date" name="date" value="{{ old('date', $log->date) }}"
                   class="w-full border-gray-300 rounded-md mt-1" required>
        </div>

        <div>
            <label class="block text-sm font-medium">Jumlah (Rp)</label>
            <input type="text" name="amount"
                   value="{{ old('amount', number_format($log->amount, 0, ',', '.')) }}"
                   class="rupiah w-full border-gray-300 rounded-md mt-1 text-right" required>
        </div>

        <div>
            <label class="block text-sm font-medium">Deskripsi</label>
            <input type="text" name="description"
                   value="{{ old('description', $log->description) }}"
                   class="w-full border-gray-300 rounded-md mt-1">
        </div>

        <div class="flex gap-2 pt-4">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan Perubahan</button>
            <a href="{{ route('target-dana.show', $target->id) }}"
               class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</a>
        </div>
    </form>

    <script>
        document.querySelectorAll('input.rupiah').forEach(function (el) {
            el.addEventListener('input', function (e) {
                let value = e.target.value.replace(/[^\d]/g, '');
                e.target.value = new Intl.NumberFormat('id-ID').format(value);
            });
        });
    </script>
</x-app-layout>
