@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-6">
    <div class="mb-6">
        <a href="{{ route('admin.rewards.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
            ← Kembali ke Daftar Reward
        </a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">Tambah Reward Baru</h1>
        <p class="text-gray-500 text-sm">Masukkan detail hadiah beserta jumlah stok yang tersedia.</p>
    </div>

    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
        <form action="{{ route('admin.rewards.store') }}" method="POST">
            @csrf

            {{-- Nama Reward --}}
            <div class="mb-4">
                <label for="reward_name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Reward</label>
                <input type="text" name="reward_name" id="reward_name" value="{{ old('reward_name') }}" 
                       class="w-full rounded-xl border-gray-200 p-3 text-sm focus:border-blue-500 focus:ring-blue-500 @error('reward_name') border-red-500 @enderror" 
                       placeholder="Contoh: Voucher Belanja Rp50.000" required>
                @error('reward_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                {{-- Poin yang Dibutuhkan --}}
                <div>
                    <label for="required_points" class="block text-sm font-semibold text-gray-700 mb-1">Poin yang Dibutuhkan</label>
                    <input type="number" name="required_points" id="required_points" value="{{ old('required_points') }}" min="0"
                           class="w-full rounded-xl border-gray-200 p-3 text-sm focus:border-blue-500 focus:ring-blue-500 @error('required_points') border-red-500 @enderror" 
                           placeholder="Contoh: 500" required>
                    @error('required_points')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jumlah Stok --}}
                <div>
                    <label for="stock" class="block text-sm font-semibold text-gray-700 mb-1">Jumlah Stok Awal</label>
                    <input type="number" name="stock" id="stock" value="{{ old('stock') }}" min="0"
                           class="w-full rounded-xl border-gray-200 p-3 text-sm focus:border-blue-500 focus:ring-blue-500 @error('stock') border-red-500 @enderror" 
                           placeholder="Contoh: 10" required>
                    @error('stock')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tombol Submit --}}
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl shadow-sm transition text-sm">
                Simpan Reward ke Katalog
            </button>
        </form>
    </div>
</div>
@endsection