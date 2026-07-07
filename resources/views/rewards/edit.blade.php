<div class="max-w-2xl mx-auto p-6 bg-white border border-gray-200 rounded-xl mt-10 shadow-xs">
    <h1 class="text-xl font-bold text-gray-800 mb-6">Edit Detail Hadiah</h1>

    <form action="{{ route('admin.rewards.update', $reward->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Hadiah / Voucher</label>
            <input type="text" name="reward_name" value="{{ $reward->reward_name }}" required class="w-full border border-gray-300 rounded-lg p-2.5 text-sm">
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Poin Penukaran</label>
                <input type="number" name="required_points" value="{{ $reward->required_points }}" min="0" required class="w-full border border-gray-300 rounded-lg p-2.5 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sisa Stok</label>
                <input type="number" name="stock" value="{{ $reward->stock }}" min="0" required class="w-full border border-gray-300 rounded-lg p-2.5 text-sm">
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.rewards.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">Batal</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium cursor-pointer">Perbarui Data</button>
        </div>
    </form>
</div>