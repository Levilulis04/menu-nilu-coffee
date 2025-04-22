<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Crypt;

class StatusController extends Controller
{
    public function index(Request $request)
    {
        $token = $request->query('token');

        if (!$token) {
            return abort(404, 'Token tidak ditemukan');
        }

        try {
            $table_number = Crypt::decrypt($token);
        } catch (\Exception $e) {
            return abort(403, 'Token tidak valid');
        }

        $maxQueue = Order::max('queue_number');

        $orders = Order::with(['items.menu'])
            ->where('table_number', $table_number)
            ->latest()
            ->get();

        return view('user.status', [
            'orders' => $orders,
            'token' => $token,
            'maxQueue' => $maxQueue,
        ]);
    }
}
