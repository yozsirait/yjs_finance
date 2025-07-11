<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Input Transaksi</h2>
    </x-slot>

    <div class="max-w-xl mx-auto mt-6">
        @if(session('success'))
            <div class="mb-4 text-green-500">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('transaksi.store') }}">
            @csrf

            <div class="mb-4">
                <label>Nama Anggota</label>
                <select name="member_id" required class="w-full border rounded">
                    <option value="">Pilih Anggota</option>
                    @foreach($members as $member)
                        <option value="{{ $member->id }}">{{ $member->name }} ({{ $member->role }})</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label>Jenis</label>
                <select name="type" required class="w-full border rounded">
                    <option value="pemasukan">Pemasukan</option>
                    <option value="pengeluaran">Pengeluaran</option>
                </select>
            </div>

            <div class="mb-4">
                <label>Jumlah</label>
                <input type="number" step="0.01" name="amount" required class="w-full border rounded">
            </div>

            <div class="mb-4">
                <label>Tanggal</label>
                <input type="date" name="date" required class="w-full border rounded">
            </div>

            <div class="mb-4">
                <label>Kategori</label>
                <input type="text" name="category" class="w-full border rounded">
            </div>

            <div class="mb-4">
                <label>Deskripsi</label>
                <textarea name="description" class="w-full border rounded"></textarea>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded border border-black">
                Simpan
            </button>
        </form>
    </div>
</x-app-layout>
