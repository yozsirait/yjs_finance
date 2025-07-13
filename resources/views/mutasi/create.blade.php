<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Mutasi Rekening</h2>
    </x-slot>

    <form action="{{ route('mutasi.store') }}" method="POST"
        class="bg-white p-6 rounded-xl shadow max-w-2xl mx-auto space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium">Jenis Mutasi</label>
            <select name="mutation_type" class="w-full border rounded" required>
                <option value="transfer">Transfer Rekening</option>
                <option value="tarik_tunai">Tarik Tunai</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium">Dari Akun</label>
            <select name="from_account" class="w-full border rounded" required>
                <option value="">Pilih Akun Asal</option>
                @foreach ($accounts as $acc)
                    <option value="{{ $acc->id }}">{{ $acc->name }} ({{ ucfirst($acc->type) }})</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium">Ke Akun</label>
            <select name="to_account" class="w-full border rounded" required>
                <option value="">Pilih Akun Tujuan</option>
                @foreach ($accounts as $acc)
                    <option value="{{ $acc->id }}">{{ $acc->name }} ({{ ucfirst($acc->type) }})</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium">Jumlah (Rp)</label>
            <input type="text" name="amount" id="amount" class="w-full border rounded text-right" required>
        </div>

        <div>
            <label class="block text-sm font-medium">Tanggal</label>
            <input type="date" name="date" class="w-full border rounded" required>
        </div>

        <div>
            <label class="block text-sm font-medium">Catatan</label>
            <textarea name="description" rows="2" class="w-full border rounded"></textarea>
        </div>

        <div class="pt-2">
            <a href="{{ route('transaksi.index') }}"
                class="inline-block px-4 py-2 rounded bg-gray-500 text-white hover:bg-gray-600 mr-2">Batal</a>
            <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Simpan</button>
        </div>
    </form>

    <script>
        document.getElementById('amount').addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^\d]/g, '');
            e.target.value = new Intl.NumberFormat('id-ID').format(value);
        });
    </script>
</x-app-layout>
