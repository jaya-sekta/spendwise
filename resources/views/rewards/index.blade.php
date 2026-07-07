@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    {{-- Header Halaman --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Reward</h1>
            <p class="text-gray-500 text-sm mt-1">Tukarkan poin yang sudah Anda kumpulkan dengan berbagai hadiah menarik.</p>
        </div>
        
        {{-- Tombol Tambah Reward hanya muncul jika user adalah Admin --}}
        @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.rewards.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-xl shadow-sm transition inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Reward
            </a>
        @endif
    </div>

    {{-- Notifikasi Sukses / Gagal --}}
    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2 shadow-sm">
            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2 shadow-sm">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Kondisi jika Data Reward Kosong --}}
    @if($rewards->isEmpty())
        <div class="bg-white border border-gray-100 rounded-2xl p-16 text-center shadow-sm flex flex-col items-center justify-center min-h-[400px]">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-50 text-blue-500 mb-5 shadow-inner">
                {{-- Icon Gift Box --}}
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-14L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Reward</h3>
            <p class="text-gray-400 text-sm max-w-sm mb-6">Katalog penukaran hadiah saat ini belum tersedia. Silakan hubungi admin atau kembali lagi nanti.</p>
            
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.rewards.create') }}" class="bg-gray-50 border border-gray-200 hover:bg-gray-100 text-gray-700 font-medium py-2 px-5 rounded-xl transition text-sm flex items-center gap-2 shadow-sm">
                    + Buat Reward Baru
                </a>
            @endif
        </div>
    @else
        {{-- Grid Katalog Reward --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($rewards as $reward)
                <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between">
                    <div>
                        {{-- Badge Stok --}}
                        <div class="flex justify-between items-start mb-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $reward->stock > 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                                {{ $reward->stock > 0 ? 'Stok: ' . $reward->stock : 'Stok Habis' }}
                            </span>
                        </div>
                        
                        <h3 class="text-lg font-bold text-gray-800 leading-snug mb-1">{{ $reward->reward_name }}</h3>
                        <div class="flex items-center gap-1 text-amber-500 font-bold text-lg mb-4">
                            <span>⭐</span>
                            <span>{{ number_format($reward->required_points) }} <span class="text-sm font-normal text-gray-400">Poin</span></span>
                        </div>
                    </div>

                    {{-- Form Aksi Penukaran --}}
                    <form action="{{ route('rewards.redeem', $reward->id) }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit" 
                                {{ $reward->stock <= 0 ? 'disabled' : '' }}
                                class="w-full text-center font-semibold py-2.5 px-4 rounded-xl transition duration-200 text-sm shadow-sm
                                {{ $reward->stock > 0 
                                    ? 'bg-blue-600 hover:bg-blue-700 text-white' 
                                    : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}">
                            {{ $reward->stock > 0 ? 'Tukar Sekarang' : 'Tidak Tersedia' }}
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection