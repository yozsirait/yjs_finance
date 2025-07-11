<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Tambah Pengeluaran</h2>
    </x-slot>

    <x-form-section title="Form Pengeluaran" action="{{ route('pengeluaran.store') }}">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700">Anggota</label>
            <select name="member_id" required class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                @foreach ($members as $member)
                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Tanggal</label>
            <input type="date" name="date" required class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Kategori</label>
            <select name="category_id" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Tanpa Kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Jumlah (Rp)</label>
            <input type="text" name="amount" id="amount" required
                class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-right">
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea name="description" rows="3" class="mt-1 w-full border-gray-300 rounded-md shadow-sm"></textarea>
        </div>
    </x-form-section>

    <script>
        document.getElementById('amount').addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^\d]/g, '');
            value = new Intl.NumberFormat('id-ID').format(value);
            e.target.value = value;
        });
    </script>
</x-app-layout>
