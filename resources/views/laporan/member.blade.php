<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Perbandingan Anggota Keluarga</h2>
    </x-slot>

    <div class="max-w-5xl mx-auto mt-6 space-y-6">

        {{-- Filter Bulan dan Tahun --}}
        <form method="GET" class="flex flex-wrap items-end gap-4 bg-white p-4 shadow rounded-xl">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Bulan</label>
                <select name="bulan"
                    class="rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach (range(1, 12) as $b)
                        <option value="{{ $b }}" {{ $b == $bulan ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $b)->format('F') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Tahun</label>
                <select name="tahun"
                    class="rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach (range(date('Y'), date('Y') - 5) as $y)
                        <option value="{{ $y }}" {{ $y == $tahun ? 'selected' : '' }}>{{ $y }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">
                    Tampilkan
                </button>
            </div>
        </form>

        <div class="bg-white p-6 shadow rounded-xl mb-6 flex flex-col md:flex-row gap-6">
            <div class="flex-1">
                <h3 class="text-lg font-semibold mb-4">Distribusi Pemasukan per Anggota</h3>
                <canvas id="incomePieChart" height="200" class="w-full"></canvas>
            </div>

            <div class="flex-1">
                <h3 class="text-lg font-semibold mb-4">Distribusi Pengeluaran per Anggota</h3>
                <canvas id="expensePieChart" height="200" class="w-full"></canvas>
            </div>
        </div>


        {{-- ChartJS --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const incomeCtx = document.getElementById('incomePieChart').getContext('2d');
            const expenseCtx = document.getElementById('expensePieChart').getContext('2d');

            const incomeData = {!! json_encode($perMember->pluck('pemasukan')) !!};
            const expenseData = {!! json_encode($perMember->pluck('pengeluaran')) !!};
            const labels = {!! json_encode($perMember->pluck('name')) !!};

            new Chart(incomeCtx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pemasukan',
                        data: incomeData,
                        backgroundColor: labels.map((_, i) => `hsl(${i * 360 / labels.length}, 70%, 60%)`),
                        borderColor: '#fff',
                        borderWidth: 1,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right'
                        },
                        title: {
                            display: false
                        }
                    }
                }
            });

            new Chart(expenseCtx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pengeluaran',
                        data: expenseData,
                        backgroundColor: labels.map((_, i) => `hsl(${i * 360 / labels.length}, 70%, 50%)`),
                        borderColor: '#fff',
                        borderWidth: 1,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right'
                        },
                        title: {
                            display: false
                        }
                    }
                }
            });
        </script>


        {{-- Tabel Ringkasan --}}
        <div class="bg-white p-6 shadow rounded-xl overflow-x-auto">
            <h3 class="text-lg font-semibold mb-4">Ringkasan</h3>
            <table class="w-full text-sm table-auto border border-gray-200 rounded">
                <thead class="bg-gray-100 text-gray-700 font-semibold">
                    <tr>
                        <th class="px-4 py-2 border-b border-gray-300 text-left">Nama</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left">Pemasukan</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left">Pengeluaran</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left">Sisa</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($perMember as $item)
                        <tr class="hover:bg-gray-50 border-b border-gray-200">
                            <td class="px-4 py-2">{{ $item['name'] }}</td>
                            <td class="px-4 py-2">Rp{{ number_format($item['pemasukan'], 0, ',', '.') }}</td>
                            <td class="px-4 py-2">Rp{{ number_format($item['pengeluaran'], 0, ',', '.') }}</td>
                            <td
                                class="px-4 py-2 font-semibold {{ $item['saldo'] < 0 ? 'text-red-600' : 'text-green-600' }}">
                                Rp{{ number_format($item['saldo'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


</x-app-layout>
