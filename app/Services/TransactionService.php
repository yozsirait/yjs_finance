<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function create(array $data): Transaction
    {
        return DB::transaction(function () use ($data) {
            $transaction = Transaction::create([
                'user_id'    => Auth::id(),
                'member_id'  => $data['member_id'],
                'type'       => $data['type'],
                'account_id' => $data['account_id'],
                'amount'     => $this->parseAmount($data['amount']),
                'date'       => $data['date'],
                'category'   => $data['category'],
                'description'=> $data['description'] ?? null,
            ]);

            $this->updateAccountBalance($data['account_id']);

            return $transaction;
        });
    }

    public function update(Transaction $transaction, array $data): Transaction
    {
        return DB::transaction(function () use ($transaction, $data) {
            $oldAccountId = $transaction->account_id;

            $transaction->update([
                'member_id'   => $data['member_id'],
                'type'        => $data['type'],
                'account_id'  => $data['account_id'],
                'amount'      => $this->parseAmount($data['amount']),
                'date'        => $data['date'],
                'category'    => $data['category'],
                'description' => $data['description'] ?? null,
            ]);

            if ($oldAccountId !== $data['account_id']) {
                $this->updateAccountBalance($oldAccountId);
            }
            $this->updateAccountBalance($data['account_id']);

            return $transaction;
        });
    }

    public function delete(Transaction $transaction): void
    {
        DB::transaction(function () use ($transaction) {
            $accountId = $transaction->account_id;
            $transaction->delete();
            $this->updateAccountBalance($accountId);
        });
    }

    private function parseAmount(string $amount): int
    {
        return (int) str_replace(['.', ','], '', $amount);
    }

    private function updateAccountBalance($accountId): void
    {
        Account::find($accountId)?->updateBalance();
    }
}
