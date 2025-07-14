<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Mutasi Rekening</h2>
    </x-slot>

    <form action="{{ route('mutasi.store') }}" method="POST"
      class="bg-white p-6 rounded-xl shadow max-w-4xl mx-auto space-y-6">
    @csrf

    <div class="grid md:grid-cols-2 gap-6">

        <x-form-group label="Jenis Mutasi" name="mutation_type">
            <select name="mutation_type"
                    class="w-full rounded-md border-gray-300" required>
                <option value="transfer"    {{ old('mutation_type') == 'transfer'    ? 'selected' : '' }}>Transfer Rekening</option>
                <option value="tarik_tunai" {{ old('mutation_type') == 'tarik_tunai' ? 'selected' : '' }}>Tarik Tunai</option>
            </select>
        </x-form-group>

        <x-form-group label="Tanggal" name="date">
            <input type="date" name="date"
                   value="{{ old('date') }}"
                   class="w-full rounded-md border-gray-300" required>
        </x-form-group>

        <x-form-group label="Dari Akun" name="from_account">
            <select name="from_account"
                    class="w-full rounded-md border-gray-300" required>
                <option value="">Pilih Akun Asal</option>
                @foreach ($accounts as $acc)
                    <option value="{{ $acc->id }}" {{ old('from_account') == $acc->id ? 'selected' : '' }}>
                        {{ $acc->name }} ({{ ucfirst($acc->type) }})
                    </option>
                @endforeach
            </select>
        </x-form-group>

        <x-form-group label="Ke Akun" name="to_account">
            <select name="to_account"
                    class="w-full rounded-md border-gray-300" required>
                <option value="">Pilih Akun Tujuan</option>
                @foreach ($accounts as $acc)
                    <option value="{{ $acc->id }}" {{ old('to_account') == $acc->id ? 'selected' : '' }}>
                        {{ $acc->name }} ({{ ucfirst($acc->type) }})
                    </option>
                @endforeach
            </select>
        </x-form-group>

        <x-form-group label="Jumlah (Rp)" name="amount">
            <input type="text" name="amount" id="amount"
                   value="{{ old('amount') }}"
                   class="w-full rounded-md border-gray-300 text-right rupiah" required>
        </x-form-group>

        <x-form-group label="Anggota" name="member_id">
            <select name="member_id"
                    class="w-full rounded-md border-gray-300" required>
                <option value="">Pilih Anggota</option>
                @foreach ($members as $member)
                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                @endforeach
            </select>
        </x-form-group>

        <x-form-group label="Catatan" name="description" class="md:col-span-2">
            <textarea name="description" rows="2"
                      class="w-full rounded-md border-gray-300">{{ old('description') }}</textarea>
        </x-form-group>
    </div>

    <div class="pt-4 flex justify-between">
        <a href="{{ route('transaksi.index') }}"
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
            Batal
        </a>

        <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
            Simpan
        </button>
    </div>
</form>


    <script>
        document.getElementById('amount').addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^\d]/g, '');
            e.target.value = new Intl.NumberFormat('id-ID').format(value);
        });
    </script>
</x-app-layout>
