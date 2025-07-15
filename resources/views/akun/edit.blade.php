<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Edit Akun</h2>
    </x-slot>

    <form action="{{ route('akun.update', $account->id) }}" method="POST"
        class="bg-white p-6 rounded shadow max-w-xl mx-auto space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700">Nama Akun</label>
            <input type="text" name="name" value="{{ old('name', $account->name) }}" required
                class="mt-1 w-full rounded border-gray-300" />
        </div>

        <x-form-group label="Pemilik (Anggota)" name="member_id">
            <select name="member_id" class="w-full border-gray-300 rounded">
                <option value="">-- Pilih Anggota --</option>
                @foreach (auth()->user()->members as $member)
                    <option value="{{ $member->id }}"
                        {{ old('member_id', $account->member_id ?? '') == $member->id ? 'selected' : '' }}>
                        {{ $member->name }}
                    </option>
                @endforeach
            </select>
        </x-form-group>

        <div>
            <label class="block text-sm font-medium text-gray-700">Jenis</label>
            <select name="type" required class="mt-1 w-full rounded border-gray-300">
                <option value="bank" {{ $account->type === 'bank' ? 'selected' : '' }}>Bank</option>
                <option value="ewallet" {{ $account->type === 'ewallet' ? 'selected' : '' }}>E-Wallet</option>
                <option value="tunai" {{ $account->type === 'tunai' ? 'selected' : '' }}>Tunai</option>
            </select>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan Perubahan</button>
    </form>
</x-app-layout>
