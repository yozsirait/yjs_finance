<x-app-layout>

    <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Daftar Akun</h2>
        <a href="{{ route('akun.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
            + Tambah Akun
        </a>
    </div>

    <div class="mt-4 max-w-6xl mx-auto space-y-4">

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded shadow">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto bg-white shadow rounded-xl">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Jenis</th>
                        <th class="px-4 py-3">Saldo</th>
                        <th class="px-4 py-3">Anggota</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($accounts as $account)
                        <tr class="border-t">
                            <td class="px-4 py-3 font-medium">{{ $account->name }}</td>
                            <td class="px-4 py-3 capitalize">{{ $account->type }}</td>
                            <td class="px-4 py-3">Rp{{ number_format($account->balance, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                {{ $account->member->name ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <a href="{{ route('akun.edit', $account->id) }}"
                                    class="text-blue-600 hover:underline">Edit</a>

                                <form action="{{ route('akun.destroy', $account->id) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Hapus akun ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500">Belum ada akun ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
