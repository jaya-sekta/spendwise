<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\Expense;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with('category')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('expense.index', compact('expenses'));
    }

    public function create()
    {
        $categories = Category::where('user_id', auth()->id())
            ->orWhere('is_default', true)
            ->get();

        return view('expense.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'expense_name' => 'required|string|max:255',
            'category_id'  => 'required|exists:categories,id',
            'amount'       => 'required|numeric|min:1',
            'expense_date' => 'required|date',
        ]);

        // Hitung total pengeluaran bulan ini di kategori yang sama
        $category   = Category::findOrFail($validated['category_id']);
        $totalMonth = Expense::where('user_id', Auth::id())
            ->where('category_id', $category->id)
            ->whereMonth('expense_date', now()->month)
            ->whereYear('expense_date', now()->year)
            ->sum('amount');

        // ✅ Cek apakah setelah ditambah expense baru akan over limit
        $isOverLimit = ($totalMonth + $validated['amount']) > $category->monthly_limit;

        // Simpan expense
        Expense::create([
            'user_id'      => Auth::id(),
            'category_id'  => $validated['category_id'],
            'expense_name' => $validated['expense_name'],
            'amount'       => $validated['amount'],
            'expense_date' => $validated['expense_date'],
            'is_over_limit' => $isOverLimit,
        ]);

        // ✅ Kurangi nyawa challenge jika over limit
        if ($isOverLimit) {
            $this->deductChallengeLife($validated['category_id']);
        }

        $message = $isOverLimit
            ? 'Pengeluaran dicatat, tapi kamu sudah over budget bulan ini!'
            : 'Pengeluaran berhasil dicatat.';

        return redirect()->route('expense.index')->with(
            $isOverLimit ? 'warning' : 'success',
            $message
        );
    }

    public function show(Expense $expense)
    {
        $this->authorizeOwner($expense->user_id);

        return view('expense.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        $this->authorizeOwner($expense->user_id);

        $categories = Category::where('user_id', Auth::id())
            ->orWhere('is_default', true)
            ->get();

        return view('expense.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        $this->authorizeOwner($expense->user_id);

        $validated = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'expense_name' => 'required|string|max:255',
            'amount'       => 'required|numeric|min:0',
            'expense_date' => 'required|date',
        ]);

        $category   = Category::findOrFail($validated['category_id']);
        $totalMonth = Expense::where('user_id', Auth::id())
            ->where('category_id', $category->id)
            ->whereMonth('expense_date', now()->month)
            ->whereYear('expense_date', now()->year)
            ->where('id', '!=', $expense->id) // exclude expense yang sedang diedit
            ->sum('amount');

        $wasOverLimit = $expense->is_over_limit;
        $isOverLimit  = ($totalMonth + $validated['amount']) > $category->monthly_limit;

        $expense->update([
            'category_id'   => $validated['category_id'],
            'expense_name'  => $validated['expense_name'],
            'amount'        => $validated['amount'],
            'expense_date'  => $validated['expense_date'],
            'is_over_limit' => $isOverLimit,
        ]);

        // ✅ Kurangi nyawa hanya kalau sebelumnya tidak over limit,
        //    sekarang jadi over limit (hindari kurangi 2x saat edit)
        if ($isOverLimit && ! $wasOverLimit) {
            $this->deductChallengeLife($validated['category_id']);
        }

        $message = $isOverLimit
            ? 'Pengeluaran diupdate, tapi kamu sudah over budget bulan ini!'
            : 'Pengeluaran berhasil diupdate.';

        return redirect()->route('expense.index')->with(
            $isOverLimit ? 'warning' : 'success',
            $message
        );
    }

    public function destroy(Expense $expense)
    {
        $this->authorizeOwner($expense->user_id);
        $expense->delete();

        return redirect()->route('expense.index')->with('success', 'Pengeluaran berhasil dihapus.');
    }

    // ✅ Kurangi nyawa challenge aktif yang kategorinya sama
    private function deductChallengeLife(int $categoryId): void
    {
        $challenge = Challenge::where('user_id', Auth::id())
            ->where('category_id', $categoryId)
            ->where('status', 'active')
            ->first();

        if (! $challenge) return;

        if ($challenge->remaining_lives > 1) {
            // Masih ada nyawa tersisa, kurangi 1
            $challenge->decrement('remaining_lives');
        } else {
            // Nyawa habis → challenge gagal
            $challenge->update([
                'remaining_lives' => 0,
                'status'          => 'failed',
            ]);
        }
    }

    private function authorizeOwner(int $userId): void
    {
        if ($userId !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
    }
}