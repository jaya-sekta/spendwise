<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Category;
use App\Models\Challenge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId       = Auth::id();
        $currentMonth = Carbon::now()->month;
        $currentYear  = Carbon::now()->year;

        // 1. Total pengeluaran bulan ini
        $totalPengeluaran = Expense::where('user_id', $userId)
            ->whereMonth('expense_date', $currentMonth)
            ->whereYear('expense_date', $currentYear)
            ->sum('amount');

        // ✅ Fix: include kategori bawaan (is_default=true) + milik user sendiri
        $totalAnggaran = Category::visibleTo($userId)->sum('monthly_limit');
        $sisaAnggaran  = $totalAnggaran - $totalPengeluaran;

        // 2. Hitung kategori over budget (include bawaan)
        $categories    = Category::visibleTo($userId)->get();
        $overBudgetCount = 0;

        foreach ($categories as $category) {
            $spent = Expense::where('user_id', $userId)
                ->where('category_id', $category->id)
                ->whereMonth('expense_date', $currentMonth)
                ->whereYear('expense_date', $currentYear)
                ->sum('amount');

            if ($spent > $category->monthly_limit) {
                $overBudgetCount++;
            }
        }

        // 3. 5 Transaksi terakhir
        $recentExpenses = Expense::with('category')
            ->where('user_id', $userId)
            ->latest('expense_date')
            ->take(5)
            ->get();

        // 4. Challenge aktif
        $activeChallenge = Challenge::with('category')
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->latest()
            ->first();

        // 5. Data chart
        $chartData = Expense::with('category')
            ->selectRaw('category_id, sum(amount) as total')
            ->where('user_id', $userId)
            ->whereMonth('expense_date', $currentMonth)
            ->whereYear('expense_date', $currentYear)
            ->groupBy('category_id')
            ->get();

        return view('dashboard', compact(
            'totalPengeluaran',
            'totalAnggaran',
            'sisaAnggaran',
            'overBudgetCount',
            'recentExpenses',
            'activeChallenge',
            'chartData'
        ));
    }
}