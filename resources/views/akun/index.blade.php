<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Daftar Akun Bank & Dompet</h2>
    </x-slot>

    <div class="mb-4">
        <a href="{{ route('akun.create') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
            + Tambah Akun
        </a>
    </div>

    <div class="bg-white shadow rounded p-4 overflow-x-auto">
        <table class="w-full min-w-[400px]">
            <thead>
                <tr class="bg-gray-50 text-gray-700 text-left text-sm">
                    <th class="px-3 py-2">Nama</th>
                    <th class="px-3 py-2 capitalize">Tipe</th>
                    <th class="px-3 py-2 text-right">Saldo</th>
                    <th class="px-3 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($accounts as $account)
                    <tr class="border-b last:border-0 hover:bg-gray-50">
                        <td class="px-3 py-2 align-middle">{{ $account->name }}</td>
                        <td class="px-3 py-2 align-middle capitalize">{{ $account->type }}</td>
                        <td class="px-3 py-2 text-right font-semibold align-middle">
                            Rp{{ number_format($account->balance, 0, ',', '.') }}
                        </td>
                        <td class="px-3 py-2 align-middle">
                            <div class="flex gap-3">
                                <a href="{{ route('akun.edit', $account) }}"
                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Edit
                                </a>
                                <form action="{{ route('akun.destroy', $account) }}" method="POST"
                                    onsubmit="return confirm('Yakin hapus akun?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
