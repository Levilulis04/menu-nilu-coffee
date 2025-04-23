<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
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
            if ($orderGroup->every(fn($order) => $order->status === 'served')) {
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
            ->where('status', 'served')
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
        \App\Models\Order::where('table_number', $table_number)
            ->where('is_paid', 0)
            ->update(['is_paid' => 1]);

        return redirect()->route('admin.cashier.show', ['table_number' => $table_number])
            ->with('success', 'Pembayaran berhasil diproses.');
    }
}
