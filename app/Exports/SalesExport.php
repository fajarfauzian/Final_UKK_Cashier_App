<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Sale::with(['user', 'salesDetails.product'])->get()->map(function ($sale) {
            $products = $sale->salesDetails->map(function ($detail) {
                return $detail->product ? "{$detail->product->name} (x{$detail->quantity})" : 'Unknown Product';
            })->implode(', ');

            return [
                'ID' => $sale->id,
                'Nama Pelanggan' => $sale->is_member && $sale->customer_name ? $sale->customer_name : 'NON-MEMBER',
                'Produk' => $products ?: 'No Products',
                'Tanggal Penjualan' => $sale->created_at->format('d-m-Y H:i'),
                'Total Harga' => 'Rp ' . number_format($sale->total_price, 0, ',', '.'),
                'Jumlah Dibayar' => 'Rp ' . number_format($sale->amount_paid, 0, ',', '.'),
                'Kembalian' => 'Rp ' . number_format($sale->change, 0, ',', '.'),
                'Dibuat Oleh' => $sale->user->name,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Pelanggan',
            'Produk',
            'Tanggal Penjualan',
            'Total Harga',
            'Jumlah Dibayar',
            'Kembalian',
            'Dibuat Oleh',
        ];
    }
}
