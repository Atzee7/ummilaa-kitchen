<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Invoice {{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1a1a1a; background: #fff; }
    .page { padding: 48px 52px; position: relative; overflow: hidden; }

    /* DECORATIVE BACKGROUND SHAPE (top-left like the image) */
    .deco-top { position: absolute; top: -30px; left: -30px; width: 160px; height: 160px;
        background: #f7eded; border-radius: 50%; opacity: 0.6; }
    .deco-top2 { position: absolute; top: 30px; left: 10px; width: 90px; height: 90px;
        background: #f0dede; border-radius: 50%; opacity: 0.4; }

    /* HEADER — logo right, chevron accent */
    .header { display: table; width: 100%; margin-bottom: 40px; }
    .header-left  { display: table-cell; vertical-align: top; }
    .header-right { display: table-cell; vertical-align: top; text-align: right; }

    /* Chevron / arrow accent (like image) */
    .chevron { font-size: 28px; color: #8B1A1A; font-weight: bold; line-height: 1; margin-bottom: 6px; }

    .brand-name    { font-size: 15px; font-weight: bold; color: #1a1a1a; }
    .brand-address { font-size: 9px; color: #888; line-height: 1.8; margin-top: 3px; }

    /* BIG INVOICE TITLE */
    .invoice-big { font-size: 36px; font-weight: bold; color: #8B1A1A; letter-spacing: 2px;
        line-height: 1; margin-bottom: 28px; }

    /* META ROW */
    .meta-table { display: table; width: 100%; margin-bottom: 28px; border-top: 1px solid #e0d0d0; border-bottom: 1px solid #e0d0d0; padding: 14px 0; }
    .meta-col   { display: table-cell; vertical-align: top; }
    .meta-col + .meta-col { padding-left: 40px; }
    .meta-col-right { text-align: right; }
    .meta-label { font-size: 9px; color: #999; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 3px; }
    .meta-value { font-size: 11px; color: #1a1a1a; font-weight: bold; }
    .meta-sub   { font-size: 10px; color: #666; margin-top: 2px; }

    /* BILL TO */
    .bill-section { display: table; width: 100%; margin-bottom: 28px; }
    .bill-left  { display: table-cell; vertical-align: top; width: 50%; }
    .bill-right { display: table-cell; vertical-align: top; width: 50%; text-align: right; }
    .bill-label { font-size: 9px; color: #999; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px; }
    .bill-name  { font-size: 12px; font-weight: bold; color: #1a1a1a; }
    .bill-info  { font-size: 10px; color: #666; line-height: 1.8; margin-top: 2px; }

    /* ITEMS TABLE */
    .items-table { width: 100%; border-collapse: collapse; margin-bottom: 0; }
    .items-table thead th {
        font-size: 9px; text-transform: uppercase; letter-spacing: 1px;
        color: #888; font-weight: bold;
        padding: 0 0 8px 0;
        border-bottom: 2px solid #1a1a1a;
        text-align: left;
    }
    .items-table thead th.right { text-align: right; }
    .items-table thead th.center { text-align: center; }
    .items-table tbody tr { border-bottom: 1px solid #f0eeec; }
    .items-table tbody td {
        padding: 10px 0;
        font-size: 11px; color: #333; vertical-align: middle;
    }
    .items-table tbody td.right  { text-align: right; }
    .items-table tbody td.center { text-align: center; }
    .items-table tbody td.num    { color: #999; font-size: 10px; }

    /* TOTAL SECTION */
    .total-section { display: table; width: 100%; margin-top: 6px; border-top: 2px solid #1a1a1a; padding-top: 10px; }
    .total-left  { display: table-cell; vertical-align: top; width: 55%; }
    .total-right { display: table-cell; vertical-align: top; width: 45%; }

    .total-row { display: table; width: 100%; margin-bottom: 4px; }
    .total-key { display: table-cell; font-size: 10px; color: #999; }
    .total-val { display: table-cell; font-size: 10px; color: #555; text-align: right; }
    .total-final-row { display: table; width: 100%; margin-top: 8px; border-top: 1px solid #e0d0d0; padding-top: 8px; }
    .total-final-key { display: table-cell; font-size: 14px; font-weight: bold; color: #1a1a1a; }
    .total-final-val { display: table-cell; font-size: 16px; font-weight: bold; color: #8B1A1A; text-align: right; }

    /* PAYMENT INFO (bottom left) */
    .pay-label { font-size: 9px; color: #999; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px; }
    .pay-row   { display: table; width: 100%; margin-bottom: 3px; }
    .pay-key   { display: table-cell; font-size: 10px; color: #888; width: 110px; }
    .pay-val   { display: table-cell; font-size: 10px; color: #1a1a1a; font-weight: bold; }

    /* FOOTER */
    .footer { margin-top: 36px; border-top: 1px solid #e8e0e0; padding-top: 12px; text-align: center; }
    .footer-text { font-size: 9px; color: #bbb; }
</style>
</head>
<body>
<div class="page">

    {{-- Decorative circles top-left --}}
    <div class="deco-top"></div>
    <div class="deco-top2"></div>

    {{-- HEADER: chevron + brand top right --}}
    <div class="header">
        <div class="header-left"></div>
        <div class="header-right">
            <div class="chevron">&#10095;&#10095;</div>
            <div class="brand-name">Ummilaa Kitchen</div>
            <div class="brand-address">
                Jl. Kapi Anala 1 Blok 15N No. 18<br>
                Sawojajar 2, Kota Malang<br>
                08.00 – 20.00 WIB
            </div>
        </div>
    </div>

    {{-- BIG TITLE --}}
    <div class="invoice-big">INVOICE</div>

    {{-- META ROW: No, Tanggal, Metode --}}
    <div class="meta-table">
        <div class="meta-col">
            <div class="meta-label">Invoice No</div>
            <div class="meta-value">{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</div>
        </div>
        <div class="meta-col">
            <div class="meta-label">Tanggal</div>
            <div class="meta-value">{{ $order->created_at->translatedFormat('d F Y') }}</div>
        </div>
        <div class="meta-col meta-col-right">
            <div class="meta-label">Metode Pembayaran</div>
            <div class="meta-value">{{ $order->getPaymentLabel() }}</div>
            <div class="meta-sub">{{ $order->metode_pengiriman === 'delivery' ? 'Delivery' : 'Ambil Sendiri' }}</div>
        </div>
    </div>

    {{-- BILL TO --}}
    <div class="bill-section">
        <div class="bill-left">
            <div class="bill-label">Bill to:</div>
            <div class="bill-name">{{ $order->nama_penerima }}</div>
            <div class="bill-info">
                {{ $order->no_telepon }}<br>
                {{ $order->alamat }}
                @if($order->detail_alamat)<br>{{ $order->detail_alamat }}@endif
            </div>
        </div>
        @if($order->metode_pengiriman === 'pickup')
        <div class="bill-right">
            <div class="bill-label">Lokasi Pengambilan:</div>
            <div class="bill-info" style="text-align:right;">
                Jl. Kapi Anala 1 Blok 15N No. 18<br>
                Sawojajar 2, Kota Malang
            </div>
        </div>
        @elseif($order->tanggal_pengiriman)
        <div class="bill-right">
            <div class="bill-label">Jadwal Pengiriman:</div>
            <div class="bill-info" style="text-align:right;">
                {{ $order->tanggal_pengiriman->translatedFormat('d F Y') }}
                @if($order->waktu_pengiriman)<br>{{ $order->waktu_pengiriman }}@endif
            </div>
        </div>
        @endif
    </div>

    {{-- ITEMS TABLE --}}
    <table class="items-table">
        <thead>
            <tr>
                <th class="center" style="width:6%">No</th>
                <th style="width:44%; padding-left:8px;">Deskripsi</th>
                <th class="right" style="width:14%">Qty</th>
                <th class="right" style="width:18%">Harga</th>
                <th class="right" style="width:18%">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $i => $item)
            <tr>
                <td class="center num">{{ $i + 1 }}.</td>
                <td style="padding-left:8px;">{{ $item->product ? $item->product->name : 'Produk tidak tersedia' }}</td>
                <td class="right">{{ $item->quantity }}</td>
                <td class="right">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                <td class="right">Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- TOTAL --}}
    <div class="total-section">
        {{-- Payment info kiri --}}
        <div class="total-left">
            <div class="pay-label">Info Pembayaran</div>
            <div class="pay-row">
                <div class="pay-key">Metode</div>
                <div class="pay-val">{{ $order->getPaymentLabel() }}</div>
            </div>
            @if($order->midtrans_transaction_id)
            <div class="pay-row">
                <div class="pay-key">ID Transaksi</div>
                <div class="pay-val">{{ $order->midtrans_transaction_id }}</div>
            </div>
            @endif
            @if($order->catatan)
            <div class="pay-row">
                <div class="pay-key">Catatan</div>
                <div class="pay-val">{{ $order->catatan }}</div>
            </div>
            @endif
        </div>

        {{-- Total kanan --}}
        <div class="total-right">
            <div class="total-row">
                <div class="total-key">Subtotal</div>
                <div class="total-val">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</div>
            </div>
            <div class="total-row">
                <div class="total-key">Ongkos Kirim</div>
                <div class="total-val">Rp{{ number_format($order->ongkir, 0, ',', '.') }}</div>
            </div>
            <div class="total-final-row">
                <div class="total-final-key">Total</div>
                <div class="total-final-val">Rp{{ number_format($order->total, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="footer">
        <div class="footer-text">
            Jika ada pertanyaan, hubungi kami &nbsp;·&nbsp;
            Dicetak {{ now()->translatedFormat('d F Y, H:i') }} WIB
        </div>
    </div>

</div>
</body>
</html>
