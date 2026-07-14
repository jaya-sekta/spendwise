<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChallengeController extends Controller
{
    public function index(Request $request)
    {
        // ✅ Cek dan selesaikan challenge yang sudah melewati end_date
        $this->settleCompletedChallenges(Auth::id());

        $tab = $request->query('tab', 'active');

        $query = Challenge::with('category')->where('user_id', Auth::id());

        if ($tab === 'completed') {
            $query->whereIn('status', ['successful', 'failed']);
        } else {
            $query->where('status', 'active');
        }

        $challenges = $query->latest()->paginate(10);

        return view('challenges.index', compact('challenges', 'tab'));
    }

    public function create()
    {
        $categories = Category::where('user_id', Auth::id())
            ->orWhere('is_default', true)
            ->get();

        return view('challenges.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'     => 'required|exists:categories,id',
            'start_date'      => 'required|date',
            'end_date'        => 'required|date|after:start_date',
            'remaining_lives' => 'required|integer|min:0',
        ]);

        Challenge::create([
            'user_id'         => Auth::id(),
            'category_id'     => $validated['category_id'],
            'start_date'      => $validated['start_date'],
            'end_date'        => $validated['end_date'],
            'remaining_lives' => $validated['remaining_lives'],
            'status'          => 'active',
        ]);

        return redirect()->route('challenges.index')->with('success', 'Challenge berhasil dibuat.');
    }

    public function show(Challenge $challenge)
    {
        $this->authorizeOwner($challenge->user_id);

        return view('challenges.show', compact('challenge'));
    }

    public function edit(Challenge $challenge)
    {
        $this->authorizeOwner($challenge->user_id);

        $categories = Category::where('user_id', Auth::id())
            ->orWhere('is_default', true)
            ->get();

        return view('challenges.edit', compact('challenge', 'categories'));
    }

    public function update(Request $request, Challenge $challenge)
    {
        $this->authorizeOwner($challenge->user_id);

        $validated = $request->validate([
            'category_id'     => 'required|exists:categories,id',
            'start_date'      => 'required|date',
            'end_date'        => 'required|date|after:start_date',
            'remaining_lives' => 'required|integer|min:0',
            'status'          => 'required|in:active,successful,failed',
        ]);

        $challenge->update($validated);

        return redirect()->route('challenges.index')->with('success', 'Challenge berhasil diupdate.');
    }

    public function destroy(Challenge $challenge)
    {
        $this->authorizeOwner($challenge->user_id);
        $challenge->delete();

        return redirect()->route('challenges.index')->with('success', 'Challenge berhasil dihapus.');
    }

    // ✅ Cek challenge aktif yang sudah melewati end_date
    // Kalau nyawa masih ada → successful + beri poin
    // Kalau nyawa 0 → tetap failed (sudah diset di ExpenseController)
    private function settleCompletedChallenges(int $userId): void
    {
        $expired = Challenge::where('user_id', $userId)
            ->where('status', 'active')
            ->whereDate('end_date', '<', now()->toDateString())
            ->get();

        foreach ($expired as $challenge) {
            if ($challenge->remaining_lives > 0) {
                // Hitung poin: 10 poin per hari
                $totalDays    = max(1, (int) $challenge->start_date->diffInDays($challenge->end_date));
                $rewardPoints = $totalDays * 10;

                // Tandai berhasil
                $challenge->update(['status' => 'successful']);

                // Tambah poin ke user
                User::where('id', $userId)->increment('points', $rewardPoints);
            } else {
                // Nyawa sudah 0, pastikan status failed
                $challenge->update(['status' => 'failed']);
            }
        }
    }

    private function authorizeOwner(int $userId): void
    {
        if ($userId !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> 3497b1b0449ac3de36406bec14c395d270bd056c
