<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Tambah Transaksi</h2>
    </x-slot>

    @if(session('success'))
        <div class="max-w-4xl mx-auto mt-6 px-4 py-3 bg-green-100 text-green-800 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    <x-form-section title="Form Transaksi" action="{{ route('transaksi.store') }}">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700">Nama Anggota</label>
            <select name="member_id" required class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Pilih Anggota</option>
                @foreach($members as $member)
                    <option value="{{ $member->id }}">{{ $member->name }} ({{ $member->role }})</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Jenis Transaksi</label>
            <select name="type" required class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                <option value="pemasukan">Pemasukan</option>
                <option value="pengeluaran">Pengeluaran</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Tanggal</label>
            <input type="date" name="date" required class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Jumlah (Rp)</label>
            <input type="text" name="amount" id="amount" required
                class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-right"
                placeholder="0" inputmode="numeric">
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Kategori</label>
            <input type="text" name="category" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea name="description" rows="3" class="mt-1 w-full border-gray-300 rounded-md shadow-sm"></textarea>
        </div>
    </x-form-section>

    <script>
        // Format Rupiah
        document.getElementById('amount').addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^\d]/g, '');
            value = new Intl.NumberFormat('id-ID').format(value);
            e.target.value = value;
        });
    </script>
</x-app-layout>
