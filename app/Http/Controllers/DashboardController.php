<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
{
    $user = Auth::user();
    $categories = Category::all();

    $query = Transaction::where('user_id', $user->id);

    $today = now();
    $startDate = $today->copy()->subDays(30);
    $endDate = $today;

    if ($request->has('range')) {
        $range = $request->get('range');
        switch ($range) {
            case '30-days':
                $startDate = $today->copy()->subDays(30);
                break;
            case 'current-month':
                $startDate = $today->copy()->startOfMonth();
                break;
            case '90-days':
                $startDate = $today->copy()->subDays(90);
                break;
            case 'current-year':
                $startDate = $today->copy()->startOfYear();
                break;
            case 'custom':
                $startDate = Carbon::parse($request->get('start_date'));
                $endDate = Carbon::parse($request->get('end_date'));
                $query->whereBetween('transaction_date', [$startDate, $endDate]);
                break;
            default:
                $startDate = $today->copy()->subDays(30);
                break;
        }

        if ($range !== 'custom') {
            $query->where('transaction_date', '>=', $startDate);
        }
    } else {
        $query->where('transaction_date', '>=', now()->subDays(30));
    }

    $transactions = $query->orderBy('transaction_date', 'desc')->get();
    $highestCategory = $transactions->groupBy('category_id')
        ->map(function ($row) {
            return $row->sum('amount');
        })
        ->sortDesc()
        ->keys()
        ->first();

    $highestCategoryName = $highestCategory ? Category::find($highestCategory)->name : 'N/A';

    $totalDays = $startDate->diffInDays($endDate);

    $categoryTotals = $transactions->groupBy('category_id')
        ->map(function ($row) {
            return $row->sum('amount');
        });

    $categoryNames = Category::whereIn('id', $categoryTotals->keys())->pluck('name', 'id');

    $categoryTotals = $categoryTotals->mapWithKeys(function ($total, $categoryId) use ($categoryNames) {
        return [$categoryNames[$categoryId] => $total];
    });

    $thisMonthStart = $today->copy()->startOfMonth();
    $lastMonthStart = $thisMonthStart->copy()->subMonth();
    $lastMonthEnd = $thisMonthStart->copy()->subDay();

    $thisMonthTransactions = Transaction::where('user_id', $user->id)
        ->whereBetween('transaction_date', [$thisMonthStart, $today])
        ->get();
    
    $lastMonthTransactions = Transaction::where('user_id', $user->id)
        ->whereBetween('transaction_date', [$lastMonthStart, $lastMonthEnd])
        ->get();

    $thisMonthTotal = $thisMonthTransactions->sum('amount');
    $lastMonthTotal = $lastMonthTransactions->sum('amount');

    $thisMonthDays = $today->diffInDays($thisMonthStart) ;
    $lastMonthDays = $lastMonthEnd->diffInDays($lastMonthStart);

    $avgDailyThisMonth = $thisMonthTotal / $thisMonthDays;
    $avgDailyLastMonth = $lastMonthTotal / $lastMonthDays;

    $trendDifference = $avgDailyThisMonth - $avgDailyLastMonth;
    $trendPercentage = $avgDailyLastMonth ? ($trendDifference / $avgDailyLastMonth) * 100 : 0;

    return view('dashboard', compact(
        'transactions', 
        'highestCategoryName', 
        'categories', 
        'totalDays', 
        'categoryTotals', 
        'avgDailyThisMonth', 
        'avgDailyLastMonth', 
        'trendDifference', 
        'trendPercentage'
    ));
}


    public function getCategoryTotals(Request $request)
    {
        $userId = Auth::id();
        $range = $request->input('range', '30-days');
        $startDate = now()->subDays(30);
        $endDate = now();

        if ($range === 'current-month') {
            $startDate = now()->startOfMonth();
        } elseif ($range === '90-days') {
            $startDate = now()->subDays(90);
        } elseif ($range === 'current-year') {
            $startDate = now()->startOfYear();
        } elseif ($range === 'custom') {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
        }

        $transactions = Transaction::where('user_id', $userId)
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->get();

        $categoryTotals = $transactions->groupBy('category_id')
            ->map(function ($transactions) {
                return $transactions->sum('amount');
            });

        $categoryNames = Category::whereIn('id', $categoryTotals->keys())->pluck('name', 'id');

        $categoryTotals = $categoryTotals->mapWithKeys(function ($total, $categoryId) use ($categoryNames) {
            return [$categoryNames[$categoryId] => $total];
        });

        return response()->json($categoryTotals);
    }
}
