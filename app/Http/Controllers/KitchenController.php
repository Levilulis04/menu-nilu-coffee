<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class KitchenController extends Controller
{
    public function dashboard()
    {
        return view('kitchen.dashboard');
    }
    
    public function fetchOrders()
    {
        $orders = Order::with(['items.menu'])
            ->orderBy('queue_number')
            ->whereIn('status', ['waiting', 'served']) // atau 'processing'
            ->get();
    
        return response()->json($orders);
    }
    
    public function updateStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'status' => 'required|in:waiting,served',
        ]);
    
        Order::where('id', $request->order_id)->update(['status' => $request->status]);
    
        return response()->json(['success' => true]);
    }
}
