<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Edit Pengeluaran Rutin</h2>
    </x-slot>

    <form action="{{ route('pengeluaran-rutin.update', $recurring->id) }}" method="POST"
        class="max-w-3xl bg-white rounded-xl shadow p-6 mx-auto mt-6 space-y-6">
        @csrf
        @method('PUT')

        <x-form-group label="Nama Pengeluaran" name="name">
            <input type="text" name="name" value="{{ old('name', $recurring->name) }}"
                class="w-full rounded border-gray-300" required>
        </x-form-group>

        <x-form-group label="Nominal (Rp)" name="amount">
            <input type="text" name="amount" id="amount"
                value="{{ old('amount', number_format($recurring->amount, 0, ',', '.')) }}"
                class="w-full rounded border-gray-300 text-right rupiah" required>
        </x-form-group>

        <x-form-group label="Tanggal Berulang (1–31)" name="repeat_day">
            <input type="number" name="repeat_day" value="{{ old('repeat_day', $recurring->repeat_day) }}"
                class="w-full rounded border-gray-300" required>
        </x-form-group>

        <x-form-group label="Deskripsi (Opsional)" name="description">
            <textarea name="description" rows="3"
                class="w-full rounded border-gray-300">{{ old('description', $recurring->description) }}</textarea>
        </x-form-group>

        <div class="flex justify-between">
            <a href="{{ route('pengeluaran-rutin.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Batal</a>

            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">Update</button>
        </div>
    </form>

    <script>
        document.getElementById('amount').addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^\d]/g, '');
            e.target.value = new Intl.NumberFormat('id-ID').format(value);
        });
    </script>
</x-app-layout>
