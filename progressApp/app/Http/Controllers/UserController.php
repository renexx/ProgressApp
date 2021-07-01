<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function me()
    {
        $user = auth()->user();
        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        request()->validate([
            'password' => 'min:8'
        ]);
        $id = auth()->user()->id;
        $user = User::find($id);
        if (request()->login)
            $user->update(['login' => request()->login]);
        if (request()->height)
            $user->update(['height' => request()->height]);
        if (request()->email)
            $user->update(['email' => request()->email]);
        if (request()->password)
            $user->update(['password' => Hash::make(request()->password)]);
        return $user;
    }
}
