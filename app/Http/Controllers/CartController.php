<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\Menu;


class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'table_number' => 'required|integer',
        ]);

        $token = $request->query('token');

        if (!$token) {
            return abort(404, 'Token tidak ditemukan');
        }
    
        try {
            $table_number = Crypt::decrypt($token);
        } catch (\Exception $e) {
            return abort(403, 'Token tidak valid');
        }

        $existing = DB::table('carts')
            ->where('table_number', $request->table_number)
            ->where('menu_id', $request->menu_id)
            ->first();

        if ($existing) {
            DB::table('carts')->where('id', $existing->id)->update([
                'quantity' => $existing->quantity + $request->quantity,
                'note' => $request->note,
                'updated_at' => now(),
            ]);
        } else {
            DB::table('carts')->insert([
                'table_number' => $request->table_number,
                'menu_id' => $request->menu_id,
                'quantity' => $request->quantity,
                'note' => $request->note,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        return back()->with('success', 'Item ditambahkan ke keranjang!');
    }

    public function showCart(Request $request)
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

        $cartItems = Cart::with('menu')
            ->where('table_number', $table_number)
            ->get();

        return view('user.cart', [
            'cartItems' => $cartItems,
            'table_number' => $table_number,
            'token' => $token,
        ]);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|exists:carts,id',
        ]);

        DB::table('carts')->where('id', $request->cart_id)->delete();

        return back()->with('success', 'Item berhasil dihapus');
    }
}
