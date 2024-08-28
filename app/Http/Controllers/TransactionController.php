<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'amount' => 'required|numeric',
        'description' => 'required|string',
        'category_id' => 'required|exists:categories,id',
        'transaction_date' => 'required|date',
    ]);

    $transaction = Transaction::create([
        'user_id' => auth()->id(),
        'amount' => $request->amount,
        'description' => $request->description,
        'category_id' => $request->category_id,
        'transaction_date' => $request->transaction_date,
    ]);

    return redirect()->route('dashboard')->with('success', 'Transaction added successfully');
}


    public function show(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        return response()->json($transaction);
    }

    public function update(Request $request, Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'amount' => 'required|numeric',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'transaction_date' => 'required|date',
        ]);

        $transaction->update([
            'amount' => $request->amount,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'transaction_date' => $request->transaction_date,
        ]);

        return response()->json($transaction);
    }

    public function destroy(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        $transaction->delete();

        return response()->json(null, 204);
    }
}
