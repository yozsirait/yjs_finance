<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Pengeluaran Rutin</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto mt-6 space-y-6">

        {{-- Tambah Pengeluaran Rutin --}}
        <div class="bg-white p-4 shadow rounded-xl">
            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('pengeluaran-rutin.store') }}" method="POST"
                class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf

                <input type="text" name="name" placeholder="Nama Rutin (mis. Listrik, Internet)"
                    class="rounded border-gray-300" required>

                <select name="category" required class="rounded border-gray-300">
                    <option value="">Pilih Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                    @endforeach
                </select>

                <select name="account_id" class="rounded border-gray-300">
                    <option value="">Pilih Akun</option>
                    @foreach ($accounts as $acc)
                        <option value="{{ $acc->id }}">{{ $acc->name }} ({{ $acc->type }})</option>
                    @endforeach
                </select>

                <select name="member_id" class="rounded border-gray-300">
                    <option value="">Pilih Anggota</option>
                    @foreach ($members as $member)
                        <option value="{{ $member->id }}">{{ $member->name }}</option>
                    @endforeach
                </select>

                <input type="text" name="amount" placeholder="Nominal (Rp)" class="rupiah text-right border-gray-300 rounded" required>

                <input type="date" name="start_date" class="rounded border-gray-300" required>

                <select name="interval" required class="rounded border-gray-300">
                    <option value="">Interval</option>
                    <option value="harian">Harian</option>
                    <option value="mingguan">Mingguan</option>
                    <option value="bulanan">Bulanan</option>
                    <option value="tahunan">Tahunan</option>
                </select>

                <input type="text" name="description" placeholder="Deskripsi (opsional)"
                    class="rounded border-gray-300 md:col-span-2">
                
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 md:col-span-2">
                    Simpan Pengeluaran Rutin
                </button>
            </form>
        </div>

        {{-- Daftar Pengeluaran Rutin --}}
        <div class="bg-white p-4 shadow rounded-xl">
            <h3 class="text-lg font-semibold mb-4">Daftar Pengeluaran Rutin</h3>

            @forelse ($rutin as $item)
                <div class="border-b py-3 text-sm">
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="font-semibold text-gray-800">{{ $item->name }}</div>
                            <div class="text-gray-600">Rp{{ number_format($item->amount, 0, ',', '.') }} -
                                {{ $item->interval }} -
                                mulai {{ \Carbon\Carbon::parse($item->start_date)->format('d M Y') }}</div>
                            <div class="text-xs text-gray-400">{{ $item->category }} |
                                {{ $item->member->name ?? '-' }} |
                                {{ $item->account->name ?? '-' }}</div>
                        </div>
                        <div class="flex gap-2">
                            {{-- nanti bisa tambahkan tombol edit di sini --}}
                            <form action="{{ route('pengeluaran-rutin.destroy', $item->id) }}" method="POST"
                                onsubmit="return confirm('Hapus pengeluaran rutin ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 text-sm hover:underline">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-sm">Belum ada data pengeluaran rutin.</p>
            @endforelse
        </div>
    </div>

    <script>
        document.querySelectorAll('input.rupiah').forEach(function (el) {
            el.addEventListener('input', function (e) {
                let value = e.target.value.replace(/[^\d]/g, '');
                e.target.value = new Intl.NumberFormat('id-ID').format(value);
            });
        });
    </script>
</x-app-layout>
