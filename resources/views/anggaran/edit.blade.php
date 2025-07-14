<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Edit Anggaran Kategori</h2>
    </x-slot>

    <form action="{{ route('anggaran.update', $budget->id) }}" method="POST"
        class="bg-white p-6 rounded-xl shadow max-w-xl mx-auto mt-6 space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700">Jenis</label>
            <p class="font-semibold capitalize">{{ $budget->type }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Kategori</label>
            <p class="font-semibold">{{ $budget->category }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Bulan & Tahun</label>
            <p class="font-semibold">
                {{ \Carbon\Carbon::create()->month($budget->month)->format('F') }} {{ $budget->year }}
            </p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Nominal Anggaran</label>
            <input type="text" name="amount" id="amount"
                value="{{ old('amount', number_format($budget->amount, 0, ',', '.')) }}"
                class="w-full rounded border-gray-300 text-right rupiah" required>
        </div>

        <div class="flex justify-between">
            <a href="{{ route('anggaran.index', ['month' => $budget->month, 'year' => $budget->year]) }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Batal</a>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
                Update
            </button>
        </div>
    </form>

    <script>
        document.getElementById('amount').addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^\d]/g, '');
            e.target.value = new Intl.NumberFormat('id-ID').format(value);
        });
    </script>
</x-app-layout>
