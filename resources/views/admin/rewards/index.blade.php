@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Katalog Hadiah</h1>
    
    @if(session('success')) <div class="bg-green-500 text-white p-3 rounded mb-4">{{ session('success') }}</div> @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($rewards as $reward)
        <div class="bg-white p-4 rounded-lg shadow border">
            <h2 class="font-bold text-lg">{{ $reward->reward_name }}</h2>
            <p class="text-green-600 font-semibold">{{ $reward->required_points }} Poin</p>
            <p class="text-gray-500 text-sm">Stok: {{ $reward->stock }}</p>
            
            <form action="{{ route('rewards.redeem', $reward->id) }}" method="POST" class="mt-4">
                @csrf
                <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">
                    Tukar Sekarang
                </button>
            </form>
        </div>
        @endforeach
    </div>
</div>
@endsection