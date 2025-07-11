<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Edit Transaksi</h2>
    </x-slot>

    <form action="{{ route('transaksi.update', $transaction->id) }}" method="POST" class="bg-white p-6 rounded-xl shadow max-w-2xl mx-auto space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700">Tanggal</label>
            <input type="date" name="date" value="{{ $transaction->date }}" required class="mt-1 w-full rounded-md border-gray-300">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Jenis Transaksi</label>
            <select name="type" id="type" required class="mt-1 w-full rounded-md border-gray-300">
                <option value="pemasukan" {{ $transaction->type == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                <option value="pengeluaran" {{ $transaction->type == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Kategori</label>
            <select name="category" id="category" required class="mt-1 w-full rounded-md border-gray-300">
                @foreach ($categories as $cat)
                    <option value="{{ $cat->name }}" {{ $transaction->category == $cat->name ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Anggota</label>
            <select name="member_id" required class="mt-1 w-full rounded-md border-gray-300">
                @foreach($members as $member)
                    <option value="{{ $member->id }}" {{ $transaction->member_id == $member->id ? 'selected' : '' }}>
                        {{ $member->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Jumlah (Rp)</label>
            <input type="text" name="amount" id="amount" required class="mt-1 w-full rounded-md border-gray-300 text-right"
                   value="{{ number_format($transaction->amount, 0, ',', '.') }}">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea name="description" rows="3" class="mt-1 w-full rounded-md border-gray-300">{{ $transaction->description }}</textarea>
        </div>

        <div class="pt-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Update
            </button>
        </div>
    </form>

    <script>
        const typeSelect = document.getElementById('type');
        const categorySelect = document.getElementById('category');

        function loadCategories(type, selected = null) {
            categorySelect.innerHTML = '<option value="">Memuat kategori...</option>';
            fetch(`/kategori/by-type/${type}`)
                .then(res => res.json())
                .then(data => {
                    let options = '<option value="">Pilih Kategori</option>';
                    data.forEach(cat => {
                        let isSelected = selected === cat.name ? 'selected' : '';
                        options += `<option value="${cat.name}" ${isSelected}>${cat.name}</option>`;
                    });
                    categorySelect.innerHTML = options;
                });
        }

        // Load kategori saat load page dan saat ubah tipe
        document.addEventListener('DOMContentLoaded', function () {
            loadCategories(typeSelect.value, "{{ $transaction->category }}");
        });

        typeSelect.addEventListener('change', function () {
            loadCategories(this.value);
        });

        // Format rupiah saat edit
        document.getElementById('amount').addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^\d]/g, '');
            e.target.value = new Intl.NumberFormat('id-ID').format(value);
        });
    </script>
</x-app-layout>
