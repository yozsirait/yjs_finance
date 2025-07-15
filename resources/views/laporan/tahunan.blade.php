<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Laporan Tahunan ({{ $year }})</h2>
    </x-slot>

    <div class="max-w-5xl mx-auto mt-6 space-y-6">
        <div class="bg-white p-6 rounded-xl shadow">
            {{-- Filter Form --}}
            <form method="GET" class="flex flex-wrap items-center gap-4 mb-6">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Tahun</label>
                    <select name="year"
                        class="rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua</option>
                        @foreach ($availableYears as $y)
                            <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Member</label>
                    <select name="member"
                        class="rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua</option>
                        @foreach ($members as $member)
                            <option value="{{ $member->id }}" {{ $member->id == $memberId ? 'selected' : '' }}>
                                {{ $member->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="self-end">
                    <button type="submit"
                        class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">
                        Filter
                    </button>
                </div>
            </form>

            @if ($monthlyData->count())
                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full table-auto text-sm border border-gray-200 rounded">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700 font-semibold">
                                <th class="px-4 py-2 border-b border-gray-300 text-left">Bulan</th>
                                <th class="px-4 py-2 border-b border-gray-300 text-left">Pemasukan</th>
                                <th class="px-4 py-2 border-b border-gray-300 text-left">Pengeluaran</th>
                                <th class="px-4 py-2 border-b border-gray-300 text-left">Selisih</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($monthlyData as $month => $row)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 border-b border-gray-200">
                                        {{ \Carbon\Carbon::createFromFormat('!m', $month)->translatedFormat('F') }}
                                    </td>
                                    <td class="px-4 py-2 border-b border-gray-200 text-green-600 font-medium">
                                        Rp{{ number_format($row['pemasukan'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-2 border-b border-gray-200 text-red-600 font-medium">
                                        Rp{{ number_format($row['pengeluaran'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-2 border-b border-gray-200 font-semibold">
                                        Rp{{ number_format($row['sisa'], 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Summary --}}
                <div class="mt-6 space-y-1 text-gray-800 text-sm font-semibold">
                    <p><span class="font-semibold">Total Pemasukan:</span>
                        Rp{{ number_format($totalPemasukan, 0, ',', '.') }}</p>
                    <p><span class="font-semibold">Total Pengeluaran:</span>
                        Rp{{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                    <p><span class="font-semibold">Selisih Total:</span>
                        Rp{{ number_format($totalPemasukan - $totalPengeluaran, 0, ',', '.') }}</p>
                </div>

                {{-- Chart --}}
                <canvas id="barChart" height="240" class="mt-6 w-full"></canvas>
            @else
                <p class="text-gray-500 text-center text-sm">Tidak ada data transaksi untuk tahun ini.</p>
            @endif
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const barChart = new Chart(document.getElementById('barChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode(
                    $monthlyData->keys()->map(fn($m) => \Carbon\Carbon::createFromFormat('!m', $m)->translatedFormat('F')),
                ) !!},
                datasets: [{
                        label: 'Pemasukan',
                        data: {!! json_encode($monthlyData->pluck('pemasukan')) !!},
                        backgroundColor: 'rgba(34, 197, 94, 0.6)', // green
                    },
                    {
                        label: 'Pengeluaran',
                        data: {!! json_encode($monthlyData->pluck('pengeluaran')) !!},
                        backgroundColor: 'rgba(239, 68, 68, 0.6)', // red
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
