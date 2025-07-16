<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = Transaction::with(['account', 'member'])
            ->where('user_id', $request->user()->id)
            ->orderBy('date', 'desc')
            ->paginate(20);

        return response()->json($transactions);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'member_id' => 'required|exists:members,id',
            'account_id' => 'nullable|exists:accounts,id',
            'type' => 'required|in:pemasukan,pengeluaran',
            'amount' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'date' => 'required|date',
        ]);

        $data['user_id'] = $request->user()->id;

        $transaction = Transaction::create($data);

        return response()->json($transaction, 201);
    }

    public function show(Request $request, Transaction $transaction)
    {
        $this->authorize('view', $transaction);
        return response()->json($transaction->load(['account', 'member']));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $data = $request->validate([
            'member_id' => 'sometimes|exists:members,id',
            'account_id' => 'nullable|exists:accounts,id',
            'type' => 'sometimes|in:pemasukan,pengeluaran',
            'amount' => 'sometimes|numeric|min:0',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'date' => 'sometimes|date',
        ]);

        $transaction->update($data);

        return response()->json($transaction);
    }

    public function destroy(Request $request, Transaction $transaction)
    {
        $this->authorize('delete', $transaction);

        $transaction->delete();

        return response()->json(null, 204);
    }
}
