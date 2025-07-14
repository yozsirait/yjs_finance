<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use App\Models\RecurringExpense;
use App\Models\Transaction;
use Illuminate\Console\Scheduling\Attributes\AsSchedule;
use Carbon\Carbon;

#[AsSchedule(frequency: 'daily')]
class GenerateRecurringExpenses extends Command
{
    protected $signature = 'generate:recurring-expenses';
    protected $description = 'Generate transaksi dari pengeluaran rutin';
    public function handle()
    {
        $today = Carbon::today();

        $rutinList = RecurringExpense::where('active', true)->get();

        foreach ($rutinList as $item) {
            $last = $item->last_generated_at ? Carbon::parse($item->last_generated_at) : Carbon::parse($item->start_date);

            // Hitung tanggal selanjutnya
            $nextDate = match ($item->interval) {
                'harian' => $last->copy()->addDay(),
                'mingguan' => $last->copy()->addWeek(),
                'bulanan' => $last->copy()->addMonth(),
                'tahunan' => $last->copy()->addYear(),
                default => null,
            };

            if ($nextDate && $nextDate->isSameDay($today)) {
                // Buat transaksi baru
                Transaction::create([
                    'user_id' => $item->user_id,
                    'type' => 'pengeluaran',
                    'category' => $item->category,
                    'account_id' => $item->account_id,
                    'member_id' => $item->member_id,
                    'amount' => $item->amount,
                    'description' => '[Auto] ' . ($item->description ?? $item->name),
                    'date' => $today,
                ]);

                // Update last_generated_at
                $item->update(['last_generated_at' => $today]);

                $this->info("Transaksi dibuat untuk: {$item->name}");
            }
        }

        $this->info('Selesai proses generate transaksi rutin.');
    }
}
