<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function register()
    {
        request()->validate([
            'login' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8'
        ]);
        if (User::create([
            'login' => request()->login,
            'height' => request()->height,
            'email' => request()->email,
            'password' => Hash::make(request()->password)
        ]))
            return response(['message' => 'User registered.'], 201);
    }

    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function unregister()
    {
        $user = auth()->user();
        if (User::destroy($user->id))
            return response(['message' => 'User account deleted.'], 200);
    }

    /**
     *
     * @return JWT token if successfull or \Illuminate\Http\Response if failed
     */
    public function login()
    {
        request()->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        $credentials = request()->only(['email', 'password']);
        $token = auth()->attempt($credentials);
        if (!$token)
            return response(['message' => 'Bad email or password.'], 401);
        return response()->json(compact('token'));
    }

    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        auth()->logout();
        return response(['message' => 'Logout.'], 200);
    }
}
