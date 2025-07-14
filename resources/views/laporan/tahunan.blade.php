<!-- resources/views/laporan/tahunan.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Laporan Tahunan ({{ $year }})</h2>
    </x-slot>

    <div class="max-w-5xl mx-auto mt-6 space-y-6">
        <div class="bg-white p-4 rounded-xl shadow">
            <form method="GET" class="flex items-center gap-4 mb-6">
                <label class="text-sm text-gray-600">Tahun</label>
                <select name="year" class="mt-1 border-gray-300 rounded-md">
                    <option value="">Semua</option>
                    @foreach ($availableYears as $y)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>

                <label class="text-sm text-gray-600">Member</label>
                <select name="member" class="mt-1 border-gray-300 rounded-md">
                    <option value="">Semua</option>
                    @foreach ($members as $member)
                        <option value="{{ $member->id }}" {{ $member->id == $memberId ? 'selected' : '' }}>
                            {{ $member->name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="bg-blue-600 text-white px-4 py-1 rounded hover:bg-blue-700">
                    Filter
                </button>
            </form>

            @if ($monthlyData->count())
                <table class="w-full table-auto text-sm">
                    <thead>
                        <tr class="bg-gray-100 text-left">
                            <th class="px-4 py-2">Bulan</th>
                            <th class="px-4 py-2">Pemasukan</th>
                            <th class="px-4 py-2">Pengeluaran</th>
                            <th class="px-4 py-2">Selisih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($monthlyData as $month => $row)
                            <tr>
                                <td class="px-4 py-2">
                                    {{ \Carbon\Carbon::createFromFormat('!m', $month)->translatedFormat('F') }}
                                </td>
                                <td class="px-4 py-2 text-green-600">Rp{{ number_format($row['pemasukan'], 0, ',', '.') }}</td>
                                <td class="px-4 py-2 text-red-600">Rp{{ number_format($row['pengeluaran'], 0, ',', '.') }}</td>
                                <td class="px-4 py-2">Rp{{ number_format($row['sisa'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-6 space-y-1">
                    <p><strong>Total Pemasukan:</strong> Rp{{ number_format($totalPemasukan, 0, ',', '.') }}</p>
                    <p><strong>Total Pengeluaran:</strong> Rp{{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                    <p><strong>Selisih Total:</strong> 
                        Rp{{ number_format($totalPemasukan - $totalPengeluaran, 0, ',', '.') }}
                    </p>
                </div>

                <canvas id="barChart" height="100" class="mt-6"></canvas>
            @else
                <p class="text-gray-500 text-sm">Tidak ada data transaksi untuk tahun ini.</p>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const barChart = new Chart(document.getElementById('barChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($monthlyData->keys()->map(fn($m) => \Carbon\Carbon::createFromFormat('!m', $m)->translatedFormat('F'))) !!},
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: {!! json_encode($monthlyData->pluck('pemasukan')) !!},
                        backgroundColor: 'rgba(34, 197, 94, 0.6)'
                    },
                    {
                        label: 'Pengeluaran',
                        data: {!! json_encode($monthlyData->pluck('pengeluaran')) !!},
                        backgroundColor: 'rgba(239, 68, 68, 0.6)'
                    }
                ]
            }
        });
    </script>
</x-app-layout>
