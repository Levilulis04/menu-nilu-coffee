<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

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
            ->where('is_paid', false)
            ->where('status', 'served')
            ->with('items.menu')
            ->get();
    
        if ($orders->isEmpty()) {
            return redirect()->route('admin.cashier')->with('error', 'Tidak ada pesanan yang bisa dibayar untuk meja ini.');
        }
    
        return view('admin.cashier-detail', [
            'tableNumber' => $table_number,
            'orders' => $orders
        ]);
    }
}
