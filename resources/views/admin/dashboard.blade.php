@extends('layouts.admin') @section('title', 'Admin Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-800">Halo, {{ Auth::user()->name }} 🛡️</h1>
    <p class="text-gray-500 mt-1">Pantau performa sistem, kelola pengguna, dan awasi stok reward.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 border-l-4 border-l-blue-500 hover:shadow-md transition">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Pengguna</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($totalUsers ?? 0) }}</h3>
                <p class="text-xs text-blue-500 mt-2 font-medium">Terdaftar di sistem</p>
            </div>
            <div class="bg-blue-50 text-blue-500 w-10 h-10 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 border-l-4 border-l-emerald-500 hover:shadow-md transition">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Kategori</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($totalCategories ?? 0) }}</h3>
                <p class="text-xs text-emerald-500 mt-2 font-medium">Tersedia untuk user</p>
            </div>
            <div class="bg-emerald-50 text-emerald-500 w-10 h-10 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-tags"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 border-l-4 border-l-orange-400 hover:shadow-md transition">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm text-gray-500 font-medium">Challenge Berjalan</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($activeChallenges ?? 0) }}</h3>
                <p class="text-xs text-orange-400 mt-2 font-medium">Sedang diikuti user</p>
            </div>
            <div class="bg-orange-50 text-orange-400 w-10 h-10 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-trophy"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-slate-700 to-slate-900 rounded-2xl shadow-sm p-6 text-white hover:shadow-lg transition relative overflow-hidden">
        <i class="fa-solid fa-box-open absolute -right-4 -bottom-4 text-7xl opacity-10"></i>
        <div class="relative z-10 flex justify-between items-start">
            <div>
                <p class="text-sm text-slate-300 font-medium">Total Stok Reward</p>
                <h3 class="text-3xl font-bold mt-1 text-emerald-400">{{ number_format($totalRewards ?? 0) }}</h3>
                <a href="{{ route('admin.rewards.index') }}" class="text-xs text-white underline mt-2 inline-block hover:text-emerald-300">Kelola Reward ></a>
            </div>
            <div class="bg-white/20 w-10 h-10 rounded-full flex items-center justify-center backdrop-blur-sm">
                <i class="fa-solid fa-gift text-emerald-400"></i>
            </div>
        </div>
    </div>
</div>

<div class="lg:col-span-2 bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden p-6">
    <div class="flex justify-between items-center mb-5">
        <h3 class="font-bold text-gray-800 text-base">Pengguna Baru Terdaftar</h3>
        {{-- Tombol Lihat Semua User yang disamakan dengan gaya tombol "+ Tambah Kategori" / Halaman Lain --}}
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-xs font-semibold shadow-sm transition">
            <i class="fa-solid fa-users text-[10px]"></i>
            <span>Lihat Semua User</span>
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-gray-600 text-xs font-semibold uppercase">
                    <th class="p-3 pl-4">Nama User</th>
                    <th class="p-3">Email</th>
                    <th class="p-3">Tanggal Daftar</th>
                    <th class="p-3 text-right pr-4">Total Poin</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-gray-700 text-sm">
                @forelse($recentUsers as $u)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="p-3 pl-4 font-medium text-gray-900 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-50 border border-blue-100 text-blue-600 font-bold flex items-center justify-center text-xs uppercase">
                                {{ substr($u->name, 0, 2) }}
                            </div>
                            <span class="truncate max-w-[120px]">{{ $u->name }}</span>
                        </td>
                        <td class="p-3 text-gray-500 text-xs font-mono">{{ $u->email }}</td>
                        <td class="p-3 text-gray-400 text-xs">
                            {{ $u->created_at ? $u->created_at->format('d M Y') : '-' }}
                        </td>
                        <td class="p-3 font-semibold text-amber-500 text-right pr-4">⭐ {{ number_format($u->points ?? 0) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-12 text-center text-gray-400 text-sm">
                            Belum ada data pengguna baru terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

    <div class="space-y-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-bl-xl">Perhatian</div>
            
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-red-500"></i> Stok Reward Menipis
            </h3>
            
            <div class="space-y-4">
                @forelse($lowStockRewards ?? [] as $rw)
                <div class="flex justify-between items-center bg-red-50 p-3 rounded-xl border border-red-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center text-xl shrink-0" 
                             style="background-color: {{ $rw->color ?? '#EF4444' }}33; color: {{ $rw->color ?? '#EF4444' }}">
                            <i class="{{ $rw->icon ?? 'fa-solid fa-gift' }}"></i>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 text-sm">{{ $rw->reward_name }}</p>
                            <p class="text-xs text-gray-500">Butuh Restock</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-block bg-red-200 text-red-700 text-xs font-bold px-2 py-1 rounded-md">
                            Sisa: {{ $rw->stock }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="py-4 text-center">
                    <div class="text-3xl mb-2 text-emerald-400">⚡</div>
                    <p class="text-sm text-gray-500">Semua stok reward dalam kondisi aman.</p>
                </div>
                @endforelse
            </div>
            
            @if(count($lowStockRewards ?? []) > 0)
                <a href="{{ route('admin.rewards.index') }}" class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold py-2.5 rounded-xl transition mt-4">
                    Kelola Stok Reward
                </a>
            @endif
        </div>
    </div>
</div>
@endsection