<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Table;
use App\Models\Receipt;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CashierController extends Controller
{
    public function index()
    {
        return view('admin.cashier');
    }

    public function getData()
    {
        $orders = Order::where('is_paid', false)
            ->with(['items.menu'])
            ->get()
            ->groupBy('table_number');

        $tables = [];

        foreach ($orders as $table_number => $orderGroup) {
            if ($orderGroup->every(fn($order) => $order->status === 'Selesai')) {
                $total = 0;
                foreach ($orderGroup as $order) {
                    foreach ($order->items as $item) {
                        $total += $item->quantity * $item->menu->price;
                    }
                }

                $tables[] = [
                    'table_number' => $table_number,
                    'total' => $total,
                ];
            }
        }

        return response()->json($tables);
    }

    public function show($table_number)
    {
        $orders = Order::where('table_number', $table_number)
            ->where('is_paid', 0)
            ->where('status', 'Selesai')
            ->with('items.menu')
            ->get();
    
            $isPaid = $orders->first()?->is_paid ?? 0;

            return view('admin.cashier-detail', [
                'tableNumber' => $table_number,
                'orders' => $orders,
                'isPaid' => $isPaid
            ]);
    }

    public function storeReceipt($table_number)
    {
        $orders = Order::where('table_number', $table_number)
            ->where('is_paid', false)
            ->with('items.menu')
            ->get();

        if ($orders->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada pesanan yang perlu dibayar.');
        }

        $subtotal = 0;
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $subtotal += $item->quantity * $item->menu->price;
            }
        }

        $tax = $subtotal * 0.11;
        $service = $subtotal * 0.05;
        $total = $subtotal + $tax + $service;

        // Simpan ke tabel receipts
        $receipt = Receipt::create([
            'invoice_number' => strtoupper(Str::random(8)),
            'table_number' => $table_number,
            'total_price' => $subtotal,
            'tax_amount' => $tax,
            'service_charge' => $service,
            'grand_total' => $total,
            'cashier_name' => 'Levi', // bisa diganti nanti
            'paid_at' => Carbon::now(),
        ]);

        // Update semua order jadi paid
        //Order::where('table_number', $table_number)->where('is_paid', false)->update(['is_paid' => true]);

        return redirect()->route('admin.cashier.show', ['table_number' => $table_number])
            ->with('success', 'Receipt berhasil dibuat.');
    }

    public function payBill(Request $request, $table_number)
    {
        // 1. Hitung total harga
        $orders = Order::where('table_number', $table_number)
            ->where('is_paid', 0)
            ->get();
    
        $totalPrice = $orders->sum(function($order) {
            return $order->items->sum(function($item) {
                return $item->menu->price * $item->quantity;
            });
        });
    
        // 2. Create receipt
        $receipt = Receipt::create([
            'invoice_number' => 'INV-' . now()->format('YmdHis'),
            'table_number' => $table_number,
            'total_price' => $totalPrice,
            'tax_amount' => $totalPrice * 0.1, // misal 10% pajak
            'service_charge' => $totalPrice * 0.05, // misal 5% service
            'grand_total' => $totalPrice * 1.15,
            'cashier_name' => 'cashier nilu',
            'paid_at' => now(),
        ]);
    
        // 3. Update semua orders
        foreach ($orders as $order) {
            $order->update([
                'is_paid' => 1,
                'receipt_id' => $receipt->id,
            ]);
        }
    
        // 4. Update meja jadi tidak aktif
        Table::where('table_number', $table_number)->update(['is_active' => 0]);
    
        return redirect()->route('admin.cashier.show', ['table_number' => $table_number])
            ->with('success', 'Pembayaran berhasil diproses.')
            ->with('receipt_id', $receipt->id);

    }
}
