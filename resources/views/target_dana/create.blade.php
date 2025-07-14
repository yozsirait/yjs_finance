<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Tambah Target Simpan Dana</h2>
    </x-slot>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 p-3 rounded mb-4">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <form action="{{ route('target-dana.store') }}" method="POST"
        class="max-w-xl mx-auto bg-white p-6 mt-6 rounded-xl shadow space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium">Nama Target</label>
            <input type="text" name="name" required
                class="w-full mt-1 border-gray-300 rounded-md" value="{{ old('name') }}">
        </div>

        <div>
            <label class="block text-sm font-medium">Jumlah Target (Rp)</label>
            <input type="text" name="target_amount" id="target_amount"
                value="{{ old('target_amount') }}" required
                class="rupiah w-full mt-1 rounded-md border-gray-300 text-right">
        </div>

        <div>
            <label class="block text-sm font-medium">Tenggat Waktu</label>
            <input type="date" name="deadline" class="w-full mt-1 border-gray-300 rounded-md"
                value="{{ old('deadline') }}">
        </div>

        <div class="pt-2">
            <a href="{{ route('target-dana.index') }}"
                class="inline-block bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 mr-2">
                Batal
            </a>
            <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Simpan
            </button>
        </div>
    </form>

    <script>
        document.getElementById('target_amount').addEventListener('input', function (e) {
            let value = e.target.value.replace(/[^\d]/g, '');
            e.target.value = new Intl.NumberFormat('id-ID').format(value);
        });
    </script>
</x-app-layout>
