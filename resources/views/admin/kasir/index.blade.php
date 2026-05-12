@extends('admin.layouts.app')
@section('title', 'Kasir — Pembelian Langsung')

@section('content')

<div class="grid grid-cols-1 xl:grid-cols-[1fr_380px] gap-6 items-start">

    {{-- ══ KIRI: DAFTAR PRODUK ══ --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 class="font-['Playfair_Display'] text-lg font-bold text-gray-900">🛒 Pilih Produk</h2>
            <span class="text-xs text-gray-400" id="product-count">{{ $products->count() }} produk tersedia</span>
        </div>

        {{-- Search --}}
        <div class="px-6 py-3 border-b border-gray-50">
            <input
                type="text"
                id="search-product"
                placeholder="🔍  Cari nama produk..."
                class="w-full px-4 py-2 text-sm border border-gray-200 rounded-xl outline-none transition-colors focus:border-[#8B1A1A] font-['Nunito']"
            >
        </div>

        {{-- Grid Produk --}}
        <div
            id="product-grid"
            class="grid grid-cols-2 md:grid-cols-3 gap-4 p-6 max-h-[calc(100vh-260px)] overflow-y-auto"
        >
            @foreach($products as $product)
            @php $isHabis = $product->status === 'habis'; @endphp
            <div
    class="product-card relative border-2 rounded-2xl overflow-hidden transition-all duration-200
        {{ $isHabis
            ? 'border-gray-100 cursor-default'
            : 'border-gray-100 cursor-pointer hover:-translate-y-0.5 hover:border-[#8B1A1A] hover:shadow-lg' }}"
    data-id="{{ $product->id }}"
    data-name="{{ $product->name }}"
    data-price="{{ $product->price }}"
    data-status="{{ $product->status }}"
    onclick="if(this.dataset.status !== 'habis') addToCart(this)"
>
                {{-- Badge qty (muncul saat produk ada di cart) --}}
                <div
                    id="badge-{{ $product->id }}"
                    class="product-card-badge hidden absolute top-2 right-2 bg-[#8B1A1A] text-white w-6 h-6 rounded-full text-xs font-black items-center justify-center z-10"
                >0</div>

                {{-- Label HABIS --}}
                @if($isHabis)
                <div class="absolute top-2 left-2 bg-red-100 text-red-600 text-[10px] font-black px-2 py-0.5 rounded-full z-10 uppercase tracking-wide">
                    Habis
                </div>
                @endif

                <img
                    src="{{ $product->image && Str::startsWith($product->image, 'products/') ? asset('storage/' . $product->image) : ($product->image ?? asset('images/placeholder.jpg')) }}"
                    alt="{{ $product->name }}"
                    class="w-full h-24 object-cover block {{ $isHabis ? 'grayscale brightness-50' : '' }}"
                    onerror="this.src='https://via.placeholder.com/200x100?text=No+Image'"
                >

                <div class="p-3">
                    <div class="text-xs font-bold truncate mb-1 {{ $isHabis ? 'text-gray-400' : 'text-gray-900' }}">
                        {{ $product->name }}
                    </div>
                    <div class="text-xs font-extrabold {{ $isHabis ? 'text-gray-400' : 'text-[#8B1A1A]' }}">
                        Rp{{ number_format($product->price, 0, ',', '.') }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ══ KANAN: KERANJANG + FORM ══ --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm sticky top-20">

        {{-- Header merah --}}
        <div class="px-6 py-4 bg-gradient-to-br from-[#8B1A1A] to-[#6B1414]">
            <h3 class="font-['Playfair_Display'] text-base font-bold text-white m-0">Transaksi Kasir</h3>
            <p class="text-xs text-white/60 mt-0.5 mb-0">Pembelian langsung di outlet</p>
        </div>

        {{-- Info pembeli --}}
        <div class="px-5 py-4 border-b border-gray-50 space-y-2">
            <div>
                <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Nama Pembeli</label>
                <input
                    type="text"
                    id="nama_penerima"
                    placeholder="Contoh: Budi Santoso"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl outline-none transition-colors focus:border-[#8B1A1A] font-['Nunito']"
                >
            </div>
            <div>
                <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Nomor HP</label>
                <input
                    type="text"
                    id="no_telepon"
                    placeholder="Contoh: 08123456789"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl outline-none transition-colors focus:border-[#8B1A1A] font-['Nunito']"
                >
            </div>
        </div>

        {{-- Daftar item cart --}}
        <div id="cart-items" class="px-5 py-3 max-h-56 overflow-y-auto border-b border-gray-50">
            <div id="cart-empty" class="text-center py-6 text-gray-300 text-sm">
                <span class="block text-3xl mb-2">🛍️</span>
                Belum ada produk dipilih.<br>Klik produk di kiri untuk menambahkan.
            </div>
        </div>

        {{-- Footer: pembayaran, catatan, total, tombol --}}
        <div class="px-5 py-4 space-y-3">

            {{-- Metode Pembayaran --}}
            <div>
                <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Metode Pembayaran</label>
                <select
                    id="metode_pembayaran"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl outline-none cursor-pointer transition-colors focus:border-[#8B1A1A] font-['Nunito'] bg-white"
                >
                    <option value="">-- Pilih metode --</option>
                    <option value="Tunai">Tunai</option>
                    <option value="QRIS">QRIS</option>
                    <option value="BNI Virtual Account">BNI Virtual Account</option>
                    <option value="BRI Virtual Account">BRI Virtual Account</option>
                    <option value="BCA Virtual Account">BCA Virtual Account</option>
                </select>
            </div>

            {{-- Catatan --}}
            <div>
                <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">
                    Catatan <span class="text-gray-300 font-normal normal-case">(opsional)</span>
                </label>
                <textarea
                    id="catatan"
                    placeholder="Catatan untuk pesanan ini..."
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl outline-none resize-none h-16 transition-colors focus:border-[#8B1A1A] font-['Nunito']"
                ></textarea>
            </div>

            {{-- Total --}}
            <div class="flex items-center justify-between bg-[#fdf5f5] rounded-xl px-4 py-3">
                <span class="text-sm font-bold text-gray-500">Total Pembayaran</span>
                <strong id="total-display" class="text-xl font-extrabold text-[#8B1A1A]">Rp0</strong>
            </div>

            {{-- Tombol Proses --}}
            <button
                id="btn-proses"
                onclick="prosesOrder()"
                disabled
                class="w-full py-3 bg-[#8B1A1A] text-white rounded-xl text-sm font-extrabold font-['Nunito'] cursor-pointer transition-all hover:bg-[#6B1414] hover:-translate-y-0.5 hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none flex items-center justify-center gap-2"
            >
                ✅ Proses Pesanan
            </button>

        </div>
    </div>

</div>

{{-- ══ MODAL SUKSES ══ --}}
<div id="modal-sukses" class="hidden fixed inset-0 bg-black/50 z-[999] items-center justify-center">
    <div class="bg-white rounded-2xl p-9 max-w-sm w-[90%] text-center">
        <div class="w-16 h-16 rounded-full bg-green-100 text-green-700 flex items-center justify-center text-3xl mx-auto mb-4">✅</div>
        <h3 class="font-['Playfair_Display'] text-xl text-gray-900 mb-2">Pesanan Berhasil!</h3>
        <p class="text-sm text-gray-400 mb-1">Pesanan telah dicatat atas nama</p>
        <p class="text-sm text-gray-400 mb-0">
            <strong id="modal-nama" class="text-gray-700">-</strong>
            · <span id="modal-hp" class="text-gray-500">-</span>
        </p>
        <div id="modal-total" class="text-2xl font-extrabold text-[#8B1A1A] my-4">Rp0</div>
        <p class="text-xs text-gray-400 mb-5">
            Pesanan masuk ke menu <strong>Pesanan</strong> dengan status <strong>Selesai</strong>
        </p>
        <button
            onclick="resetKasir()"
            class="w-full py-3 bg-[#8B1A1A] text-white rounded-xl text-sm font-extrabold font-['Nunito'] cursor-pointer transition-colors hover:bg-[#6B1414]"
        >
            🛒 Transaksi Berikutnya
        </button>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let cart = [];

    function formatRp(n) {
        return 'Rp' + Number(n).toLocaleString('id-ID');
    }

    // Tambah produk ke cart — produk habis tidak bisa masuk
    function addToCart(el) {
        if (el.dataset.status === 'habis') return;

        const id    = parseInt(el.dataset.id);
        const name  = el.dataset.name;
        const price = parseInt(el.dataset.price);

        const existing = cart.find(c => c.product_id === id);
        if (existing) {
            existing.quantity++;
        } else {
            cart.push({ product_id: id, name, price, quantity: 1 });
        }
        renderCart();
    }

    function renderCart() {
        const container = document.getElementById('cart-items');

        if (cart.length === 0) {
            container.innerHTML = `
                <div id="cart-empty" class="text-center py-6 text-gray-300 text-sm">
                    <span class="block text-3xl mb-2">🛍️</span>
                    Belum ada produk dipilih.<br>Klik produk di kiri untuk menambahkan.
                </div>`;
            updateTotal();
            updateProductCardBadges();
            return;
        }

        let html = '';
        cart.forEach((item, index) => {
            html += `
            <div class="flex items-center gap-2 mb-2 last:mb-0">
                <div class="flex-1 text-xs font-bold text-gray-900 truncate">${item.name}</div>
                <div class="flex items-center gap-1 shrink-0">
                    <button onclick="changeQty(${index}, -1)"
                        class="w-6 h-6 rounded-md border border-gray-200 bg-gray-50 text-sm font-black cursor-pointer flex items-center justify-center transition-colors hover:border-[#8B1A1A] hover:text-[#8B1A1A] hover:bg-[#fdf5f5] text-gray-500 leading-none">−</button>
                    <span class="text-sm font-black min-w-[20px] text-center text-gray-900">${item.quantity}</span>
                    <button onclick="changeQty(${index}, 1)"
                        class="w-6 h-6 rounded-md border border-gray-200 bg-gray-50 text-sm font-black cursor-pointer flex items-center justify-center transition-colors hover:border-[#8B1A1A] hover:text-[#8B1A1A] hover:bg-[#fdf5f5] text-gray-500 leading-none">+</button>
                </div>
                <div class="text-xs font-bold text-[#8B1A1A] shrink-0">${formatRp(item.price * item.quantity)}</div>
                <button onclick="removeItem(${index})"
                    class="text-gray-300 text-xs cursor-pointer border-none bg-transparent transition-colors hover:text-red-600 shrink-0 p-0">✕</button>
            </div>`;
        });

        container.innerHTML = html;
        updateTotal();
        updateProductCardBadges();
    }

    function changeQty(index, delta) {
        cart[index].quantity += delta;
        if (cart[index].quantity <= 0) cart.splice(index, 1);
        renderCart();
    }

    function removeItem(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function updateTotal() {
        const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
        document.getElementById('total-display').textContent = formatRp(total);
        document.getElementById('btn-proses').disabled = cart.length === 0;
    }

    function updateProductCardBadges() {
        // Reset semua badge
        document.querySelectorAll('.product-card').forEach(card => {
            if (card.dataset.status === 'habis') return; // skip produk habis
            card.classList.remove('border-[#8B1A1A]', 'bg-[#fdf5f5]');
            card.classList.add('border-gray-100');
            const badge = card.querySelector('.product-card-badge');
            if (badge) {
                badge.classList.add('hidden');
                badge.classList.remove('flex');
                badge.textContent = '0';
            }
        });

        // Tandai produk yang ada di cart
        cart.forEach(item => {
            const card = document.querySelector(`.product-card[data-id="${item.product_id}"]`);
            if (card) {
                card.classList.remove('border-gray-100');
                card.classList.add('border-[#8B1A1A]', 'bg-[#fdf5f5]');
                const badge = card.querySelector('.product-card-badge');
                if (badge) {
                    badge.classList.remove('hidden');
                    badge.classList.add('flex');
                    badge.textContent = item.quantity;
                }
            }
        });
    }

    function prosesOrder() {
        const nama    = document.getElementById('nama_penerima').value.trim();
        const hp      = document.getElementById('no_telepon').value.trim();
        const bayar   = document.getElementById('metode_pembayaran').value;
        const catatan = document.getElementById('catatan').value.trim();

        if (!nama)  { alert('Nama pembeli wajib diisi!'); document.getElementById('nama_penerima').focus(); return; }
        if (!hp)    { alert('Nomor HP wajib diisi!'); document.getElementById('no_telepon').focus(); return; }
        if (!bayar) { alert('Pilih metode pembayaran terlebih dahulu!'); document.getElementById('metode_pembayaran').focus(); return; }
        if (cart.length === 0) { alert('Pilih produk terlebih dahulu!'); return; }

        const btn = document.getElementById('btn-proses');
        btn.disabled = true;
        btn.textContent = '⏳ Memproses...';

        fetch('{{ route("admin.kasir.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                nama_penerima:     nama,
                no_telepon:        hp,
                metode_pembayaran: bayar,
                catatan:           catatan,
                items:             cart,
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('modal-nama').textContent  = nama;
                document.getElementById('modal-hp').textContent    = hp;
                document.getElementById('modal-total').textContent = formatRp(data.total);
                const modal = document.getElementById('modal-sukses');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            } else {
                alert('Gagal memproses pesanan. Coba lagi.');
                btn.disabled = false;
                btn.innerHTML = '✅ Proses Pesanan';
            }
        })
        .catch(() => {
            alert('Terjadi kesalahan. Periksa koneksi internet.');
            btn.disabled = false;
            btn.innerHTML = '✅ Proses Pesanan';
        });
    }

    function resetKasir() {
        cart = [];
        document.getElementById('nama_penerima').value     = '';
        document.getElementById('no_telepon').value        = '';
        document.getElementById('metode_pembayaran').value = '';
        document.getElementById('catatan').value           = '';
        const modal = document.getElementById('modal-sukses');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        renderCart();
    }

    document.getElementById('search-product').addEventListener('input', function () {
        const keyword = this.value.toLowerCase();
        document.querySelectorAll('.product-card').forEach(card => {
            card.style.display = card.dataset.name.toLowerCase().includes(keyword) ? '' : 'none';
        });
    });
</script>
@endpush