<?php
// app/Http/Controllers/ChallengeController.php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChallengeController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
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
        // Mengambil kategori milik user login ATAU kategori bawaan sistem
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

        $categories = Category::where('user_id', Auth::id())->get();

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
            'status'          => 'required|in:active,completed,failed',
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
