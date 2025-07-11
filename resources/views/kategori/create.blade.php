<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Tambah Kategori</h2>
    </x-slot>

    @if(session('success'))
        <div class="max-w-4xl mx-auto mt-6 px-4 py-3 bg-green-100 text-green-800 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    <x-form-section title="Form Kategori" action="{{ route('kategori.store') }}">
        @csrf

        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Nama Kategori</label>
            <input type="text" name="name" required class="mt-1 w-full border-gray-300 rounded-md shadow-sm" placeholder="Contoh: Makan, Gaji">
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Tipe</label>
            <select name="type" required class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Pilih Tipe</option>
                <option value="pemasukan">Pemasukan</option>
                <option value="pengeluaran">Pengeluaran</option>
            </select>
        </div>
    </x-form-section>
</x-app-layout>
