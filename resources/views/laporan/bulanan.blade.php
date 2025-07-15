<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Perbandingan Bulanan</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto mt-6 space-y-6">

        {{-- Form Pilih Bulan --}}
        <form method="GET" class="bg-white p-4 rounded shadow grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block mb-1 font-medium text-gray-700">Bulan 1</label>
                <input type="month" name="bulan1_full"
                    value="{{ $tahun1 . '-' . str_pad($bulan1, 2, '0', STR_PAD_LEFT) }}"
                    class="w-full rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block mb-1 font-medium text-gray-700">Bulan 2</label>
                <input type="month" name="bulan2_full"
                    value="{{ $tahun2 . '-' . str_pad($bulan2, 2, '0', STR_PAD_LEFT) }}"
                    class="w-full rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="md:col-span-2 text-right">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                    Bandingkan
                </button>
            </div>
        </form>

        {{-- Tabel Perbandingan --}}
        <div class="bg-white p-4 rounded shadow overflow-x-auto">
            <table class="w-full text-sm text-left min-w-[400px]">
                <thead>
                    <tr class="bg-gray-50 text-gray-700 font-semibold">
                        <th class="py-2 px-3">Kategori</th>
                        <th class="py-2 px-3">Bulan 1</th>
                        <th class="py-2 px-3">Bulan 2</th>
                        <th class="py-2 px-3">Selisih</th>
                        <th class="py-2 px-3">%</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        function selisih($a, $b)
                        {
                            return $a - $b;
                        }
                        function persen($a, $b)
                        {
                            return $b == 0 ? '-' : number_format((($a - $b) / $b) * 100, 1) . '%';
                        }
                    @endphp

                    <tr>
                        <td class="py-2 px-3 font-semibold">Pemasukan</td>
                        <td class="py-2 px-3">Rp{{ number_format($pemasukan1, 0, ',', '.') }}</td>
                        <td class="py-2 px-3">Rp{{ number_format($pemasukan2, 0, ',', '.') }}</td>
                        <td class="py-2 px-3">Rp{{ number_format(selisih($pemasukan1, $pemasukan2), 0, ',', '.') }}</td>
                        <td class="py-2 px-3">{{ persen($pemasukan1, $pemasukan2) }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 px-3 font-semibold">Pengeluaran</td>
                        <td class="py-2 px-3">Rp{{ number_format($pengeluaran1, 0, ',', '.') }}</td>
                        <td class="py-2 px-3">Rp{{ number_format($pengeluaran2, 0, ',', '.') }}</td>
                        <td class="py-2 px-3">Rp{{ number_format(selisih($pengeluaran1, $pengeluaran2), 0, ',', '.') }}
                        </td>
                        <td class="py-2 px-3">{{ persen($pengeluaran1, $pengeluaran2) }}</td>
                    </tr>
                    <tr class="font-bold">
                        <td class="py-2 px-3">Sisa</td>
                        <td class="py-2 px-3">Rp{{ number_format($sisa1, 0, ',', '.') }}</td>
                        <td class="py-2 px-3">Rp{{ number_format($sisa2, 0, ',', '.') }}</td>
                        <td class="py-2 px-3">Rp{{ number_format(selisih($sisa1, $sisa2), 0, ',', '.') }}</td>
                        <td class="py-2 px-3">{{ persen($sisa1, $sisa2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Grafik Perbandingan --}}
        <canvas id="comparisonChart" class="w-full mt-6" height="240"></canvas>

    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('comparisonChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Pemasukan', 'Pengeluaran', 'Sisa'],
                datasets: [{
                        label: 'Bulan 1',
                        backgroundColor: '#3b82f6',
                        data: [
                            {{ $pemasukan1 }},
                            {{ $pengeluaran1 }},
                            {{ $sisa1 }}
                        ]
                    },
                    {
                        label: 'Bulan 2',
                        backgroundColor: '#f97316',
                        data: [
                            {{ $pemasukan2 }},
                            {{ $pengeluaran2 }},
                            {{ $sisa2 }}
                        ]
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        ticks: {
                            callback: function(value) {
                                return 'Rp' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>
