<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Struk Penjualan #{{ $sale->id }}</title>
    <style>
        @page { margin: 10mm; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #4b5563;
            line-height: 1.3;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid #e5e7eb;
            padding: 1rem;
            background: #fff;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 0.5rem;
        }
        .header h1 {
            font-size: 1.25rem;
            margin: 0;
            color: #3b82f6;
            font-weight: bold;
        }
        .header p {
            margin: 0.2rem 0;
            font-size: 0.7rem;
            color: #6b7280;
        }
        .customer-info {
            margin: 1rem 0;
            padding: 0.5rem;
            background: #f9fafb;
        }
        .customer-info div {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.3rem;
        }
        .customer-info strong {
            color: #374151;
            font-weight: 600;
        }
        .section-title {
            font-size: 1rem;
            font-weight: 600;
            color: #3b82f6;
            margin: 1rem 0 0.5rem;
            border-bottom: 1px solid #e5e7eb;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }
        table th, table td {
            padding: 0.5rem;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }
        table th {
            background: #3b82f6;
            color: #fff;
            font-size: 0.7rem;
            text-transform: uppercase;
        }
        .summary {
            margin-top: 1rem;
            padding: 0.5rem;
            background: #f9fafb;
        }
        .summary div {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.3rem;
        }
        .summary .total {
            font-weight: bold;
            color: #3b82f6;
            padding-top: 0.5rem;
            border-top: 1px dashed #d1d5db;
        }
        .footer {
            margin-top: 1rem;
            text-align: center;
            font-size: 0.7rem;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Struk Penjualan</h1>
            <p>Tanggal: {{ $sale->created_at ? date('d F Y H:i', strtotime($sale->created_at)) : 'N/A' }}</p>
            <p>No. Transaksi: {{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>

        <!-- Customer Info -->
        <div class="customer-info">
            <div><strong>Pelanggan:</strong> <span>{{ $customerName }}</span></div>
            @if ($phone)<div><strong>Telepon:</strong> <span>{{ $phone }}</span></div>@endif
            <div><strong>Status:</strong> <span>{{ $is_member ? 'Member' : 'Non-Member' }}</span></div>
            @if ($is_member && isset($sale->use_points) && $sale->use_points)
                <div><strong>Poin:</strong> <span>Ya</span></div>
            @endif
            <div><strong>Kasir:</strong> <span>{{ $sale->user->name ?? 'N/A' }}</span></div>
        </div>

        <!-- Product List -->
        <div class="section-title">Daftar Produk</div>
        @php $totalPrice = 0; @endphp
        <table>
            <thead>
                <tr><th>Produk</th><th>Harga</th><th>Jml</th><th>Subtotal</th></tr>
            </thead>
            <tbody>
                @foreach ($selectedProducts as $product)
                    @php
                        $price = $product->price ?? ($product->pivot->price ?? 0);
                        $quantity = $product->quantity ?? ($product->pivot->quantity ?? 0);
                        $subtotal = $price * $quantity;
                        $totalPrice += $subtotal;
                    @endphp
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>Rp {{ number_format($price, 0, ',', '.') }}</td>
                        <td>{{ $quantity }}</td>
                        <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary -->
        <div class="summary">
            <div class="section-title">Rincian Pembayaran</div>
            @if ($is_member && isset($sale->use_points) && $sale->use_points)
                <div><span>Sebelum Diskon:</span> <span>Rp {{ number_format($totalPrice / 0.9, 0, ',', '.') }}</span></div>
                <div><span>Diskon (10%):</span> <span>Rp {{ number_format(($totalPrice / 0.9) - $totalPrice, 0, ',', '.') }}</span></div>
            @endif
            <div class="total"><span>Total:</span> <span>Rp {{ number_format($totalPrice, 0, ',', '.') }}</span></div>
            <div><span>Dibayar:</span> <span>Rp {{ number_format($amountPaid ?? 0, 0, ',', '.') }}</span></div>
            <div class="total"><span>Kembalian:</span> <span>Rp {{ number_format($change ?? ($amountPaid - $totalPrice), 0, ',', '.') }}</span></div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Terima kasih!</p>
            <p>Dicetak: {{ date('d F Y H:i') }}</p>
        </div>
    </div>
</body>
</html>