<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{


    public function showRegisterForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->save();

        return redirect('/login');
    }

    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        // 1. caut userul dupa email in baza de date
        $user = User::where('email', $request->input('email'))->first();

        // 2. daca exista SI parola se potriveste
        if ($user && Hash::check($request->input('password'), $user->password)) {
            return redirect('/');
        }

        // altfel, inapoi la login
        return redirect('/login');
    }
}
