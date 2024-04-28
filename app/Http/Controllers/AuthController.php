<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function loginView()
    {
        return view('pages.auth.login');
    }

    public function login(LoginRequest $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            Auth::user();
            return redirect('/gaun');
        } else {
            throw ValidationException::withMessages([
                'invalid' => 'Username tidak ditemukan!'
            ]);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login')->with('logged-out', 'Berhasil melakukan logout');
    }

    //api

    public function registerApi(Request $request)
    {
        $rules = [
            'email' => 'required|unique:users',
            'name' => 'required',
            'password' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, $validator->errors()], 422);
        }
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_verified' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil melakukan registrasi, menunggu verifikasi dari atasan.'
        ]);
    }

    public function loginApi(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
        }
        if ($user->is_verified == false) {
            return response()->json(['success' => false, 'message' => 'Akun belum diverifikasi.'], 401);
        }
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logoutApi()
    {
        Auth::user()->tokens->each(function ($token, $key) {
            $token->delete();
        });
        return response()->json(['success' => true, 'message' => 'Logged out']);
    }

    
    public function grantAccessScreen()
    {
        $users = User::where('is_verified', false)->get();
        return view('pages.grant_access', compact('users'));
    }

    public function grantAccess(Request $request){
        $request->validate([
            'email' => 'required'
        ]);
        User::where('email', $request->email)->update([
            'is_verified' => true
        ]);
        return redirect('grant-access')->with('success', 'Sukses mengupdate data');
    }
}
