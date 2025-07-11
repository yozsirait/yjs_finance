<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            {{ isset($duplicate) ? 'Duplicate Transaksi' : 'Tambah Transaksi' }}
        </h2>
    </x-slot>

    <form action="{{ route('transaksi.store') }}" method="POST"
        class="bg-white p-6 rounded-xl shadow max-w-2xl mx-auto space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700">Tanggal</label>
            <input type="date" name="date" value="{{ old('date', $duplicate->date ?? '') }}" required
                class="mt-1 w-full rounded-md border-gray-300">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Jenis Transaksi</label>
            <select name="type" id="type" required class="mt-1 w-full rounded-md border-gray-300">
                <option value="pemasukan"
                    {{ old('type', $duplicate->type ?? ($type ?? '')) == 'pemasukan' ? 'selected' : '' }}>Pemasukan
                </option>
                <option value="pengeluaran"
                    {{ old('type', $duplicate->type ?? ($type ?? '')) == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran
                </option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700">Akun</label>
            <select name="account_id" class="mt-1 w-full rounded-md border-gray-300">
                <option value="">Pilih Akun</option>
                @foreach (auth()->user()->accounts as $acc)
                    <option value="{{ $acc->id }}"
                        {{ (isset($transaction) && $transaction->account_id == $acc->id) || old('account_id') == $acc->id ? 'selected' : '' }}>
                        {{ $acc->name }} ({{ ucfirst($acc->type) }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Kategori</label>
            <select name="category" id="category" required class="mt-1 w-full rounded-md border-gray-300">
                <option value="">Pilih Kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->name }}"
                        {{ old('category', $duplicate->category ?? '') == $cat->name ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>

        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Anggota</label>
            <select name="member_id" required class="mt-1 w-full rounded-md border-gray-300">
                @foreach ($members as $member)
                    <option value="{{ $member->id }}"
                        {{ old('member_id', $duplicate->member_id ?? '') == $member->id ? 'selected' : '' }}>
                        {{ $member->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Jumlah (Rp)</label>
            <input type="text" name="amount" id="amount"
                value="{{ old('amount', isset($duplicate) ? number_format($duplicate->amount, 0, ',', '.') : '') }}"
                required class="mt-1 w-full rounded-md border-gray-300 text-right">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea name="description" rows="3" class="mt-1 w-full rounded-md border-gray-300">{{ old('description', $duplicate->description ?? '') }}</textarea>
        </div>

        <div class="pt-4">
            @if (isset($duplicate))
                <a href="{{ route('transaksi.index') }}"
                    class="inline-block bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded mr-2">
                    Batal
                </a>
            @endif
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Simpan
            </button>
        </div>
    </form>

    <script>
        const typeSelect = document.getElementById('type');
        const categorySelect = document.getElementById('category');

        function loadCategories(type) {
            categorySelect.innerHTML = '<option value="">Memuat kategori...</option>';
            fetch(`/kategori/by-type/${type}`)
                .then(res => res.json())
                .then(data => {
                    let options = '<option value="">Pilih Kategori</option>';
                    data.forEach(cat => {
                        options += `<option value="${cat.name}">${cat.name}</option>`;
                    });
                    categorySelect.innerHTML = options;
                });
        }

        // Initial load
        loadCategories(typeSelect.value);

        // Load again when type changes
        typeSelect.addEventListener('change', function() {
            loadCategories(this.value);
        });

        // Format rupiah
        document.getElementById('amount').addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^\d]/g, '');
            e.target.value = new Intl.NumberFormat('id-ID').format(value);
        });
    </script>
</x-app-layout>
