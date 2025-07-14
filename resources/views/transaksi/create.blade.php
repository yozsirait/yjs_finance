<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            {{ isset($duplicate) ? 'Duplicate Transaksi' : 'Tambah Transaksi' }}
        </h2>
    </x-slot>

    <form action="{{ route('transaksi.update', $transaction->id) }}" method="POST"
        class="bg-white p-6 rounded-xl shadow max-w-4xl mx-auto space-y-6">
        @csrf
        @method('PUT')

        {{-- Grid 2 kolom desktop / 1 kolom mobile --}}
        <div class="grid md:grid-cols-2 gap-6">

            <x-form-group label="Tanggal" name="date">
                <input type="date" name="date" id="date"
                    value="{{ old('date', $transaction->date->format('Y-m-d')) }}"
                    class="w-full rounded-md border-gray-300" required>
            </x-form-group>

            <x-form-group label="Jenis Transaksi" name="type">
                <select name="type" id="type"
                        class="w-full rounded-md border-gray-300" required>
                    <option value="pemasukan"  {{ old('type', $transaction->type) == 'pemasukan'  ? 'selected' : '' }}>Pemasukan</option>
                    <option value="pengeluaran"{{ old('type', $transaction->type) == 'pengeluaran'? 'selected' : '' }}>Pengeluaran</option>
                </select>
            </x-form-group>

            <x-form-group label="Akun" name="account_id">
                <select name="account_id"
                        class="w-full rounded-md border-gray-300">
                    <option value="">Pilih Akun</option>
                    @foreach (auth()->user()->accounts as $acc)
                        <option value="{{ $acc->id }}"
                            {{ old('account_id', $transaction->account_id) == $acc->id ? 'selected' : '' }}>
                            {{ $acc->name }} ({{ ucfirst($acc->type) }})
                        </option>
                    @endforeach
                </select>
            </x-form-group>

            <x-form-group label="Kategori" name="category">
                <select name="category" id="category"
                        class="w-full rounded-md border-gray-300" required>
                    <option value="">Pilih Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->name }}"
                            {{ old('category', $transaction->category) == $cat->name ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </x-form-group>

            <x-form-group label="Anggota" name="member_id">
                <select name="member_id"
                        class="w-full rounded-md border-gray-300" required>
                    @foreach ($members as $member)
                        <option value="{{ $member->id }}"
                            {{ old('member_id', $transaction->member_id) == $member->id ? 'selected' : '' }}>
                            {{ $member->name }}
                        </option>
                    @endforeach
                </select>
            </x-form-group>

            <x-form-group label="Jumlah (Rp)" name="amount">
                <input type="text" name="amount" id="amount"
                    value="{{ old('amount', number_format($transaction->amount,0,',','.')) }}"
                    class="w-full rounded-md border-gray-300 text-right rupiah" required>
            </x-form-group>
        </div>

        <x-form-group label="Deskripsi" name="description">
            <textarea name="description" rows="3"
                    class="w-full rounded-md border-gray-300">{{ old('description', $transaction->description) }}</textarea>
        </x-form-group>

        <div class="pt-4 flex justify-between">
            <a href="{{ route('transaksi.index') }}"
            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                Batal
            </a>

            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
                Update
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
