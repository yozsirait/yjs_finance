<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Perbandingan Bulanan</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto mt-6 space-y-4">

        {{-- Form Pilih Bulan --}}
        <form method="GET" class="bg-white p-4 rounded shadow grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label>Bulan 1</label>
                <input type="month" name="bulan1_full" value="{{ $tahun1 . '-' . str_pad($bulan1, 2, '0', STR_PAD_LEFT) }}"
                    class="rounded border-gray-300 w-full">
            </div>
            <div>
                <label>Bulan 2</label>
                <input type="month" name="bulan2_full" value="{{ $tahun2 . '-' . str_pad($bulan2, 2, '0', STR_PAD_LEFT) }}"
                    class="rounded border-gray-300 w-full">
            </div>
            <div class="md:col-span-2">
                <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Bandingkan</button>
            </div>
        </form>

        {{-- Tabel Perbandingan --}}
        <div class="bg-white p-4 rounded shadow">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr>
                        <th class="py-2">Kategori</th>
                        <th>Bulan 1</th>
                        <th>Bulan 2</th>
                        <th>Selisih</th>
                        <th>%</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        function selisih($a, $b) {
                            return $a - $b;
                        }

                        function persen($a, $b) {
                            return $b == 0 ? '-' : number_format((($a - $b) / $b) * 100, 1) . '%';
                        }
                    @endphp

                    <tr>
                        <td class="py-2 font-semibold">Pemasukan</td>
                        <td>Rp{{ number_format($pemasukan1, 0, ',', '.') }}</td>
                        <td>Rp{{ number_format($pemasukan2, 0, ',', '.') }}</td>
                        <td>Rp{{ number_format(selisih($pemasukan1, $pemasukan2), 0, ',', '.') }}</td>
                        <td>{{ persen($pemasukan1, $pemasukan2) }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 font-semibold">Pengeluaran</td>
                        <td>Rp{{ number_format($pengeluaran1, 0, ',', '.') }}</td>
                        <td>Rp{{ number_format($pengeluaran2, 0, ',', '.') }}</td>
                        <td>Rp{{ number_format(selisih($pengeluaran1, $pengeluaran2), 0, ',', '.') }}</td>
                        <td>{{ persen($pengeluaran1, $pengeluaran2) }}</td>
                    </tr>
                    <tr class="font-bold">
                        <td class="py-2">Sisa</td>
                        <td>Rp{{ number_format($sisa1, 0, ',', '.') }}</td>
                        <td>Rp{{ number_format($sisa2, 0, ',', '.') }}</td>
                        <td>Rp{{ number_format(selisih($sisa1, $sisa2), 0, ',', '.') }}</td>
                        <td>{{ persen($sisa1, $sisa2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<canvas id="comparisonChart" class="w-full mt-6"></canvas>

<script>
    const ctx = document.getElementById('comparisonChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Pemasukan', 'Pengeluaran', 'Sisa'],
            datasets: [
                {
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
