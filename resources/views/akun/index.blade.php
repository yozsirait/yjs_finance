<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Daftar Akun Bank & Dompet</h2>
    </x-slot>

    <div class="mb-4">
        <a href="{{ route('akun.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ Tambah Akun</a>
    </div>

    <div class="bg-white shadow rounded p-4">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="text-left px-2 py-1">Nama</th>
                    <th class="text-left px-2 py-1">Tipe</th>
                    <th class="text-left px-2 py-1">Saldo</th>
                    <th class="text-left px-2 py-1">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($accounts as $account)
                    <tr>
                        <td class="px-2 py-1">{{ $account->name }}</td>
                        <td class="px-2 py-1 capitalize">{{ $account->type }}</td>
                        <td class="px-2 py-1 text-right">Rp{{ number_format($account->balance, 0, ',', '.') }}</td>
                        <td class="px-2 py-1">
                            <a href="{{ route('akun.edit', $account) }}" class="text-blue-600 hover:underline text-sm">Edit</a>
                            <form action="{{ route('akun.destroy', $account) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus akun?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline text-sm" type="submit">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
