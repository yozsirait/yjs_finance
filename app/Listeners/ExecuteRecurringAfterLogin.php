<?php
namespace App\Listeners;

use Illuminate\Auth\Events\Authenticated;
use App\Models\RecurringExpense;
use App\Models\Transaction;
use Carbon\Carbon;

class ExecuteRecurringAfterLogin
{
    public function handle(Authenticated $event)
    {
        $user = $event->user;

        // Jangan eksekusi lagi kalau sudah di-set di session (untuk satu sesi login)
        if (session()->has('recurring_executed')) return;

        $today = now()->toDateString();

        $routines = RecurringExpense::where('user_id', $user->id)
            ->whereDate('next_date', '<=', $today)
            ->get();

        foreach ($routines as $routine) {
            Transaction::create([
                'user_id'     => $user->id,
                'account_id'  => $routine->account_id,
                'member_id'   => $routine->member_id,
                'type'        => 'pengeluaran',
                'category'    => $routine->category,
                'amount'      => $routine->amount,
                'description' => 'Pengeluaran Rutin: ' . $routine->description,
                'date'        => $today,
            ]);

            $next = match ($routine->interval) {
                'harian'   => Carbon::parse($routine->next_date)->addDay(),
                'mingguan' => Carbon::parse($routine->next_date)->addWeek(),
                'bulanan'  => Carbon::parse($routine->next_date)->addMonth(),
                'tahunan'  => Carbon::parse($routine->next_date)->addYear(),
                default    => Carbon::parse($routine->next_date),
            };

            $routine->update(['next_date' => $next]);
        }

        // Set flag supaya tidak jalan terus-menerus
        session(['recurring_executed' => true]);
    }
}
