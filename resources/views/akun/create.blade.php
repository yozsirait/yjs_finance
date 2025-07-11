<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Tambah Akun</h2>
    </x-slot>

    <form action="{{ route('akun.store') }}" method="POST" class="bg-white p-6 rounded shadow max-w-xl mx-auto space-y-4">
        @csrf
        <div>
            <label class="block">Nama Akun</label>
            <input name="name" required class="w-full rounded border-gray-300" placeholder="Contoh: BCA, Gopay">
        </div>
        <div>
            <label class="block">Tipe</label>
            <select name="type" required class="w-full rounded border-gray-300">
                <option value="bank">Bank</option>
                <option value="ewallet">E-Wallet</option>
                <option value="cash">Tunai</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Saldo Awal (Rp)</label>
            <input type="text" name="initial_balance" id="initial_balance"
                class="mt-1 w-full rounded border-gray-300 text-right" value="{{ old('initial_balance', '0') }}">
        </div>

        <script>
            // Format rupiah saat ketik
            document.getElementById('initial_balance').addEventListener('input', function(e) {
                let value = e.target.value.replace(/[^\d]/g, '');
                e.target.value = new Intl.NumberFormat('id-ID').format(value);
            });
        </script>

        <div>
            <button class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
        </div>
    </form>
</x-app-layout>
