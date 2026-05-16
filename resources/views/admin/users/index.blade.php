@extends('admin.layouts.app')
@section('title', 'Pengguna')

@section('content')
<div class="mb-8">
    <h2 class="font-playfair text-3xl font-bold text-gray-800">Pengguna</h2>
    <p class="text-gray-500 mt-1">Daftar pelanggan terdaftar</p>
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="border-b border-gray-100">
            <tr>
                <th class="text-left py-4 px-6 text-gray-500 font-semibold">No</th>
                <th class="text-left py-4 px-6 text-gray-500 font-semibold">Nama</th>
                <th class="text-left py-4 px-6 text-gray-500 font-semibold">Email</th>
                <th class="text-left py-4 px-6 text-gray-500 font-semibold">No. Telepon</th>
                <th class="text-left py-4 px-6 text-gray-500 font-semibold">Total Pesanan</th>
                <th class="text-left py-4 px-6 text-gray-500 font-semibold">Total Belanja</th>
                <th class="text-left py-4 px-6 text-gray-500 font-semibold">Terdaftar</th>
                <th class="text-left py-4 px-6 text-gray-500 font-semibold">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr class="border-b border-gray-50 hover:bg-gray-50">
                <td class="py-4 px-6 text-gray-500">{{ $users->firstItem() + $loop->index }}</td>
                <td class="py-4 px-6">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-red-100 text-red-700 flex items-center justify-center text-sm font-bold flex-shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <span class="font-semibold text-gray-800">{{ $user->name }}</span>
                    </div>
                </td>
                <td class="py-4 px-6 text-gray-600">{{ $user->email }}</td>
                <td class="py-4 px-6 text-gray-600">{{ $user->no_telepon ?? '-' }}</td>
                <td class="py-4 px-6">
                    <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-semibold">
                        {{ $user->orders_count }} pesanan
                    </span>
                </td>
                <td class="py-4 px-6 font-semibold text-gray-800">
                    Rp{{ number_format($user->orders_sum_total ?? 0, 0, ',', '.') }}
                </td>
                <td class="py-4 px-6 text-gray-500 text-xs">{{ $user->created_at->format('d M Y') }}</td>
                <td class="py-4 px-6">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.users.show', $user->id) }}"
                           class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-gray-200 hover:bg-gray-50">
                            Detail
                        </a>
                        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                              onsubmit="return confirm('Yakin ingin menghapus akun {{ addslashes($user->name) }}?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="py-10 text-center text-gray-400">Belum ada pelanggan terdaftar</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $users->links() }}</div>
</div>
@endsection