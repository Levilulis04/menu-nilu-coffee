<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminAuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'User registered successfully.',
            'user' => $user
        ], 201);
    }

    public function getLoginAdmin(){
        return view('admin.login');
    }

    public function login(Request $request)
    {
       $credentials = $request->only('email', 'password');

       if (auth()->attempt($credentials)) {
           $request->session()->regenerate();
           return redirect()->intended('/admin');
       }

       return back()->with('error', 'Email atau password salah')->withInput();
    }
}
