<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Dashboard</h2>
    </x-slot>

    @if (!empty($overbudgetCategories))
        <div class="bg-red-100 text-red-700 p-4 rounded-xl mb-6">
            <h3 class="font-semibold mb-2">⚠️ Kategori Melebihi Anggaran:</h3>
            <ul class="list-disc ml-5 space-y-1">
                @foreach ($overbudgetCategories as $item)
                    <li>
                        <strong>{{ ucfirst($item['type']) }} - {{ $item['name'] }}</strong> telah melebihi anggaran.<br>
                        Anggaran: Rp{{ number_format($item['budget'], 0, ',', '.') }},
                        Terpakai: Rp{{ number_format($item['spent'], 0, ',', '.') }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Ringkasan --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-dashboard-card title="Pemasukan Bulan Ini" value="Rp{{ number_format($totalPemasukan, 0, ',', '.') }}"
            color="green" />
        <x-dashboard-card title="Pengeluaran Bulan Ini" value="Rp{{ number_format($totalPengeluaran, 0, ',', '.') }}"
            color="red" />
        <x-dashboard-card title="Saldo Bulan Ini" value="Rp{{ number_format($saldoBulanIni, 0, ',', '.') }}"
            color="{{ $saldoBulanIni >= 0 ? 'blue' : 'orange' }}" />
        <x-dashboard-card title="Total Saldo Akun" value="Rp{{ number_format($totalSaldoAkun, 0, ',', '.') }}"
            color="gray" />
    </div>

    {{-- Grafik Pemasukan & Pengeluaran --}}
    <div class="bg-white rounded shadow p-6 mb-6">
        <h3 class="text-lg font-semibold mb-4">Grafik Pemasukan & Pengeluaran {{ now()->year }}</h3>
        <div class="relative w-full overflow-x-auto">
            <canvas id="monthlyChart" class="w-full" height="250"></canvas>
        </div>
    </div>

    {{-- Transaksi terbaru --}}
    <div class="bg-white rounded shadow p-6">
        <h3 class="mb-4 text-lg font-semibold">Transaksi Terbaru</h3>

        <!-- Wrapper agar tabel bisa di‑scroll pada layar sempit -->
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr class="border-b text-left">
                        <th class="py-2 px-3 whitespace-nowrap">Tanggal</th>
                        <th class="px-3 whitespace-nowrap">Jenis</th>
                        <th class="px-3 whitespace-nowrap">Kategori</th>
                        <th class="px-3 whitespace-nowrap">Anggota</th>
                        <th class="px-3 whitespace-nowrap">Akun</th>
                        <th class="px-3 whitespace-nowrap text-right">Jumlah</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($latestTransactions as $trx)
                        <tr>
                            <td class="py-2 px-3">{{ $trx->date->format('d/m/Y') }}</td>
                            <td class="px-3">{{ ucfirst($trx->type) }}</td>
                            <td class="px-3">{{ $trx->category }}</td>
                            <td class="px-3">{{ $trx->member->name ?? '-' }}</td>
                            <td class="px-3">{{ $trx->account->name ?? '-' }}</td>
                            <td
                                class="px-3 text-right font-medium
                            @if ($trx->type === 'pengeluaran') text-red-600
                            @else text-green-600 @endif">
                                Rp{{ number_format($trx->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center text-gray-500">
                                Belum ada transaksi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    <!-- Script Chart.js HARUS di atas -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const monthlyLabels = @json(range(1, 12));
        const pemasukanData = @json($monthly->pluck('pemasukan'));
        const pengeluaranData = @json($monthly->pluck('pengeluaran'));

        const ctx = document.getElementById('monthlyChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: monthlyLabels,
                datasets: [{
                        label: 'Pemasukan',
                        data: pemasukanData,
                        backgroundColor: '#4ade80'
                    },
                    {
                        label: 'Pengeluaran',
                        data: pengeluaranData,
                        backgroundColor: '#f87171'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: ctx => `Rp${Number(ctx.raw).toLocaleString('id-ID')}`
                        }
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: v => 'Rp' + v.toLocaleString('id-ID')
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>
