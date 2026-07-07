<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use App\Models\UserReward;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RewardController extends Controller
{
    public function index()
    {
        $user = auth()->user(); 
        $rewards = \App\Models\Reward::all(); 
        $myRewards = \App\Models\UserReward::where('user_id', $user->id)->get();

        return view('rewards.index', compact('user', 'rewards', 'myRewards'));
    }

    public function redeem(Request $request, $id)
    {
        $reward = Reward::findOrFail($id);
        $user = Auth::user();

        DB::transaction(function () use ($user, $reward) {
            $user->decrement('points', $reward->required_points);
            $reward->decrement('stock');
            UserReward::create([
                'user_id' => $user->id,
                'reward_id' => $reward->id,
                'redemption_date' => now(),
                'voucher_code' => 'VCH-' . strtoupper(\Illuminate\Support\Str::random(8)),
            ]);
        });

        return back()->with('success', 'Berhasil menukar reward! Kode voucher Anda telah dibuat.');
    } 
}