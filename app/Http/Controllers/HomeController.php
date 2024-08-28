<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Transaction;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $categories = Category::all();
        $transactions = Transaction::where('user_id', auth()->id())->get();
        return view('home', compact('categories', 'transactions'));
    }

    public function storeTransaction(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'place' => 'required|string|max:255',
            'category' => 'required|exists:categories,id',
            'date' => 'required|date',
        ]);

        Transaction::create([
            'user_id' => auth()->id(),
            'category_id' => $request->category,
            'amount' => $request->amount,
            'transaction_date' => $request->date,
            'description' => $request->place,
        ]);

        return redirect()->route('home');
    }

    public function addCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:categories,name|max:255',
        ]);

        Category::create(['name' => $request->name]);

        return redirect()->route('home');
    }
}
