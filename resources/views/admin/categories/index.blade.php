@extends('admin.layouts.app')
@section('title', 'Kategori')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h2 class="font-playfair text-3xl font-bold text-gray-800">Kategori</h2>
        <p class="text-gray-500 mt-1">Kelola kategori produk</p>
    </div>
    <button onclick="document.getElementById('modalTambah').classList.remove('hidden')"
        class="px-5 py-2.5 rounded-xl text-white text-sm font-semibold hover:opacity-90 transition bg-[#8B1A1A]">
        + Tambah Kategori
    </button>
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="border-b border-gray-100">
            <tr>
                <th class="text-left py-4 px-6 text-gray-500 font-semibold">Nama Kategori</th>
                <th class="text-left py-4 px-6 text-gray-500 font-semibold">Jumlah Produk</th>
                <th class="text-left py-4 px-6 text-gray-500 font-semibold">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $cat)
            <tr class="border-b border-gray-50 hover:bg-gray-50">
                <td class="py-4 px-6 font-semibold text-gray-800">{{ $cat->name }}</td>
                <td class="py-4 px-6 text-gray-500">{{ $cat->products_count }} produk</td>
                <td class="py-4 px-6 flex gap-2">
                    <button onclick="openEdit({{ $cat->id }}, '{{ $cat->name }}')"
                        class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-gray-200 hover:bg-gray-50">
                        Edit
                    </button>
                    <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}"
                          onsubmit="return confirm('Hapus kategori ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-red-50 text-red-600 hover:bg-red-100">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="3" class="py-8 text-center text-gray-400">Belum ada kategori</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal Tambah --}}
<div id="modalTambah" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl">
        <h3 class="font-playfair text-xl font-bold text-gray-800 mb-4">Tambah Kategori</h3>
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Kategori</label>
                <input type="text" name="name" required placeholder="contoh: Dimsum" value="{{ old('name') }}"
                    class="w-full px-4 py-2.5 rounded-xl border @error('name') border-red-500 @else border-gray-200 @enderror text-sm focus:outline-none focus:border-red-800">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')"
                    class="flex-1 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-2.5 rounded-xl text-white text-sm font-semibold hover:opacity-90 bg-[#8B1A1A]">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
<div id="modalEdit" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl">
        <h3 class="font-playfair text-xl font-bold text-gray-800 mb-4">Edit Kategori</h3>
        <form method="POST" id="formEdit">
            @csrf @method('PUT')
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Kategori</label>
                <input type="text" name="name" id="editName" required
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-red-800">
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('modalEdit').classList.add('hidden')"
                    class="flex-1 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-2.5 rounded-xl text-white text-sm font-semibold hover:opacity-90 bg-[#8B1A1A]">
                    Perbarui
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEdit(id, name) {
    document.getElementById('editName').value = name;
    document.getElementById('formEdit').action = '/admin/categories/' + id;
    document.getElementById('modalEdit').classList.remove('hidden');
}

@if($errors->has('name'))
    document.getElementById('modalTambah').classList.remove('hidden');
@endif
</script>
@endsection