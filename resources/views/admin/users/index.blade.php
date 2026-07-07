@extends('layouts.admin') {{-- Pastikan mengarah ke layouts admin Anda --}}

@section('content')
<div class="max-w-6xl mx-auto p-6">
    {{-- Header Halaman --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Pengguna</h1>
            <p class="text-gray-500 text-sm mt-1">Melihat detail informasi akun, hak akses role, serta perolehan poin setiap pengguna secara rinci.</p>
        </div>
        
        {{-- Tombol Kembali tanpa menggunakan ikon panah --}}
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 px-4 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm">
            <span>Kembali ke Dashboard</span>
        </a>
    </div>

    {{-- Notifikasi Alert Sukses / Gagal --}}
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

    {{-- Kontainer Tabel Utama --}}
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-gray-600 text-xs font-semibold uppercase tracking-wider">
                        <th class="p-4 pl-6">Profil & Nama</th>
                        <th class="p-4">Alamat Email</th>
                        <th class="p-4">Hak Akses (Role)</th>
                        <th class="p-4">Akumulasi Poin</th>
                        <th class="p-4">Tanggal Bergabung</th>
                        <th class="p-4 pr-6 text-center">Aksi Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-gray-700 text-sm">
                    @forelse($users as $u)
                        <tr class="hover:bg-gray-50/50 transition duration-150">
                            {{-- Kolom Profil & Nama --}}
                            <td class="p-4 pl-6 font-medium text-gray-900 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-50 border border-blue-100 text-blue-600 font-bold flex items-center justify-center text-sm uppercase shadow-sm">
                                    {{ substr($u->name, 0, 2) }}
                                </div>
                                <div>
                                    <span class="block font-semibold text-gray-800">{{ $u->name }}</span>
                                    <span class="text-xs text-gray-400">ID: #{{ $u->id }}</span>
                                </div>
                            </td>
                            
                            {{-- Kolom Email --}}
                            <td class="p-4 text-gray-500 font-mono text-xs">{{ $u->email }}</td>
                            
                            {{-- Kolom Role Badge --}}
                            <td class="p-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold shadow-sm {{ $u->role === 'admin' ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                    {{ ucfirst($u->role) }}
                                </span>
                            </td>
                            
                            {{-- Kolom Poin --}}
                            <td class="p-4 font-bold text-amber-500">
                                ⭐ {{ number_format($u->points ?? 0) }} <span class="text-xs font-normal text-gray-400">Pts</span>
                            </td>

                            {{-- Kolom Tanggal Bergabung --}}
                            <td class="p-4 text-gray-400 text-xs">
                                {{ $u->created_at ? $u->created_at->format('d M Y, H:i') : '-' }}
                            </td>
                            
                            {{-- Kolom Aksi Dinamis --}}
                            <td class="p-4 pr-6 text-center">
                                <div class="flex items-center justify-center gap-3">
                                    {{-- Tombol Edit Akun --}}
                                    <a href="{{ route('admin.users.edit', $u->id) }}" class="text-gray-400 hover:text-blue-600 p-1.5 rounded-lg hover:bg-blue-50 transition" title="Edit Data Pengguna">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    
                                    {{-- Tombol Hapus Akun (Mencegah Admin menghapus dirinya sendiri) --}}
                                    @if($u->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun pengguna ini secara permanen?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-600 p-1.5 rounded-lg hover:bg-red-50 transition" title="Hapus Pengguna">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-16 text-center text-gray-400">
                                <div class="text-4xl mb-2">👥</div>
                                <p class="text-gray-500 font-medium">Tidak ada data pengguna terdaftar lainnya.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Navigasi Pagination Otomatis --}}
        @if($users->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection