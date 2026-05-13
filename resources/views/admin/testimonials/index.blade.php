@extends('admin.layouts.app')

@section('title', 'Kelola Testimoni')

@section('content')
<div class="px-6 py-8 max-w-7xl mx-auto">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800 font-['Playfair_Display']">Kelola Testimoni</h1>
        <p class="text-sm text-gray-500 mt-1">Semua ulasan pelanggan ditampilkan otomatis. Hapus jika ada konten yang tidak pantas.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 p-4 rounded-xl text-sm font-semibold bg-green-50 text-green-600 border border-green-200">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- STAT CARDS --}}
    <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Testimoni</p>
            <p class="text-3xl font-bold text-gray-800">{{ $testimonials->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider mb-1 text-amber-600">Rata-rata Rating</p>
            <p class="text-3xl font-bold text-amber-600">
                {{ $testimonials->count() ? number_format($testimonials->avg('rating'), 1) : '-' }}
                <span class="text-base">★</span>
            </p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider mb-1 text-green-600">Bintang 5</p>
            <p class="text-3xl font-bold text-green-600">{{ $testimonials->where('rating', 5)->count() }}</p>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @if($testimonials->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                <svg class="w-16 h-16 mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
                <p class="font-semibold text-gray-500">Belum ada testimoni</p>
            </div>
        @else
            <table class="w-full">
                <thead>
                    <tr class="bg-[#fafafa] border-b border-[#f0e8e8]">
                        <th class="text-left px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">#</th>
                        <th class="text-left px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Pelanggan</th>
                        <th class="text-left px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Order</th>
                        <th class="text-left px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Rating</th>
                        <th class="text-left px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Komentar</th>
                        <th class="text-left px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal</th>
                        <th class="text-left px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($testimonials as $t)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-400 font-medium">{{ $loop->iteration }}</td>

                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0 bg-[#8B1A1A]">
                                    {{ strtoupper(substr($t->nama, 0, 1)) }}
                                </div>
                                <span class="text-sm font-semibold text-gray-700">{{ $t->nama }}</span>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <span class="text-sm font-bold px-3 py-1 rounded-lg bg-[#fdf5f5] text-[#8B1A1A]">
                                #{{ str_pad($t->order_id, 5, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4" fill="{{ $i <= $t->rating ? '#f59e0b' : '#e5e7eb' }}" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                                <span class="text-xs text-gray-400 ml-1">({{ $t->rating }}/5)</span>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-600 max-w-xs truncate" title="{{ $t->komentar }}">
                                "{{ $t->komentar }}"
                            </p>
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            {{ $t->created_at->format('d M Y') }}
                        </td>

                        <td class="px-6 py-4">
                            <form action="{{ route('admin.testimonials.destroy', $t->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus testimoni ini?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold transition-all bg-red-50 text-red-600 border border-red-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection