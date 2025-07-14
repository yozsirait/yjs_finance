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

    {{-- Grafik --}}
    <div class="bg-white rounded shadow p-6 mb-6">
        <h3 class="text-lg font-semibold mb-4">Grafik Pemasukan & Pengeluaran {{ now()->year }}</h3>
        <canvas id="monthlyChart" height="100"></canvas>
    </div>

    {{-- Transaksi terbaru --}}
    <div class="bg-white rounded shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Transaksi Terbaru</h3>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b">
                    <th class="py-2">Tanggal</th>
                    <th>Jenis</th>
                    <th>Kategori</th>
                    <th>Anggota</th>
                    <th>Akun</th>
                    <th class="text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse($latestTransactions as $trx)
                    <tr class="border-b">
                        <td class="py-1">{{ $trx->date->format('d/m/Y') }}</td>
                        <td>{{ ucfirst($trx->type) }}</td>
                        <td>{{ $trx->category }}</td>
                        <td>{{ $trx->member->name ?? '-' }}</td>
                        <td>{{ $trx->account->name ?? '-' }}</td>
                        <td class="text-right text-{{ $trx->type === 'pengeluaran' ? 'red' : 'green' }}-600">
                            Rp{{ number_format($trx->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-gray-500 py-4">Belum ada transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>


    
    {{-- Script grafik --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('monthlyChart').getContext('2d');
        const data = {
            labels: @json(range(1, 12)),
            datasets: [{
                    label: 'Pemasukan',
                    data: [
                        @foreach (range(1, 12) as $month)
                            {{ $monthly[$month]['pemasukan'] }},
                        @endforeach
                    ],
                    backgroundColor: '#4ade80'
                },
                {
                    label: 'Pengeluaran',
                    data: [
                        @foreach (range(1, 12) as $month)
                            {{ $monthly[$month]['pengeluaran'] }},
                        @endforeach
                    ],
                    backgroundColor: '#f87171'
                }
            ]
        };

        new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</x-app-layout>
