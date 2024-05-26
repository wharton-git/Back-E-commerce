<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $user = User::all(); 

        return response()->json($user);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'login' => 'nullable|string|unique:users',
            'adresse' => 'nullable|string',
            'adresse_alt' => 'nullable|string',
            'numero_mobile' => 'nullable|string',
            'carte' => 'nullable|string',
            'solde' => 'numeric|nullable',
            'password' => 'required|string|min:6',
        ]);

        $user = new User([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'login' => $request->input('username'),
            'adresse' => $request->input('adresse'),
            'adresse_alt' => $request->input('adresse_alt'),
            'numero_mobile' => $request->input('numero_mobile'),
            'carte' => $request->input('carte'),
            'solde' => $request->input('solde', 0), 
            'password' => Hash::make($request->input('password')),
        ]);

        $user->save();

        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }
}
