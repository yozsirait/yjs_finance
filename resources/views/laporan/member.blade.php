<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Perbandingan Anggota Keluarga</h2>
    </x-slot>

    <div class="max-w-5xl mx-auto mt-6 space-y-6">

        {{-- Filter Bulan dan Tahun --}}
        <form method="GET" class="flex flex-wrap items-end gap-4 bg-white p-4 shadow rounded-xl">
            <div>
                <label>Bulan</label>
                <select name="bulan" class="rounded border-gray-300">
                    @foreach (range(1, 12) as $b)
                        <option value="{{ $b }}" {{ $b == $bulan ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $b)->format('F') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label>Tahun</label>
                <select name="tahun" class="rounded border-gray-300">
                    @foreach (range(date('Y'), date('Y') - 5) as $y)
                        <option value="{{ $y }}" {{ $y == $tahun ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Tampilkan
            </button>
        </form>

        {{-- Chart --}}
        <div class="bg-white p-6 shadow rounded-xl">
            <h3 class="text-lg font-semibold mb-4">Grafik Pemasukan & Pengeluaran</h3>
            <canvas id="memberChart" height="120"></canvas>
        </div>

        {{-- Tabel Ringkasan --}}
        <div class="bg-white p-6 shadow rounded-xl">
            <h3 class="text-lg font-semibold mb-4">Ringkasan</h3>
            <table class="w-full text-sm table-auto">
                <thead>
                    <tr class="text-left border-b">
                        <th>Nama</th>
                        <th>Pemasukan</th>
                        <th>Pengeluaran</th>
                        <th>Sisa</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($perMember as $item)
                        <tr class="border-b">
                            <td>{{ $item['name'] }}</td>
                            <td>Rp{{ number_format($item['pemasukan'], 0, ',', '.') }}</td>
                            <td>Rp{{ number_format($item['pengeluaran'], 0, ',', '.') }}</td>
                            <td class="{{ $item['saldo'] < 0 ? 'text-red-600' : '' }}">
                                Rp{{ number_format($item['saldo'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- ChartJS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('memberChart').getContext('2d');
        const memberChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($perMember->pluck('name')) !!},
                datasets: [
                    {
                        label: 'Pemasukan',
                        backgroundColor: '#4ade80',
                        data: {!! json_encode($perMember->pluck('pemasukan')) !!}
                    },
                    {
                        label: 'Pengeluaran',
                        backgroundColor: '#f87171',
                        data: {!! json_encode($perMember->pluck('pengeluaran')) !!}
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: {
                        display: false,
                        text: 'Perbandingan Member'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => new Intl.NumberFormat('id-ID').format(value)
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>
