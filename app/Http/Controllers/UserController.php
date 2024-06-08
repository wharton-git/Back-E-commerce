<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
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

    public function update(Request $request, $id)
    {
        // Récupérer l'utilisateur par ID
        $user = User::findOrFail($id);

        // Validation des données de la requête
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'numero_mobile' => 'nullable|string',
        ]);

        // Mise à jour des champs de l'utilisateur
        $user->name = $request->name;
        $user->email = $request->email;
        $user->numero_mobile = $request->numero_mobile;

        // Sauvegarde de l'utilisateur
        $user->save();

        // Réponse JSON
        return response()->json(['message' => 'User updated successfully', 'user' => $user], 200);
    }

    public function updateLogin(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'login' => 'required|string|unique:users,login,' . $user->id,
        ]);

        $user->login = $request->login;
        $user->save();

        return response()->json(['message' => 'Login updated successfully', 'user' => $user], 200);
    }

    public function updatePassword(Request $request, $id)
    {
            $user = User::findOrFail($id);

            $request->validate([
                'password' => 'required|string|min:6',
            ]);

            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json(['message' => 'Password updated successfully', 'user' => $user], 200);
    }


    public function updateAdresse(Request $request, $id) {

        $user = User::findOrFail($id);

        $request->validate([
            'adresse' => 'nullable|string',
            'adresse_alt' => 'nullable|string',
        ]);
    
        $user->adresse = $request->adresse;
        $user->adresse_alt = $request->adresse_alt;
        $user->save();
    
        return response()->json(['message' => 'Adresse updated successfully', 'user' => $user], 200);
    }

    public function updateCard(Request $request, $id) {
        $user = User::findOrFail($id);

        $request->validate([
            'carte' => 'nullable|string',
        ]);

        $user->carte = $request->carte;
        $user->save();
        
        return response()->json(['message' => 'Carte updated successfully', 'user' => $user], 200);
    }

    public function updatePhone(Request $request, $id) {
        $user = User::findOrFail($id);

        $request->validate([
            'numero_mobile' => 'nullable|string',
            'numero_mobile_2' => 'nullable|string',
        ]);

        $user->numero_mobile = $request->numero_mobile;
        $user->numero_mobile_2 = $request->numero_mobile_2;
        $user->save();
        
        return response()->json(['message' => 'Numero mobile updated successfully', 'user' => $user], 200);
    }

    public function addSolde(Request $request, $id) {
        $user = User::findOrFail($id);

        $request->validate([
            'addSolde' => 'numeric',
        ]);

        $user->solde = $user->solde + $request->addSolde;
        $user->save();
        
        return response()->json(['message' => 'Solde updated successfully', 'user' => $user], 200);
    }

    public function subSolde(Request $request, $id) {
        $user = User::findOrFail($id);

        $request->validate([
            'subSolde' => 'numeric',
        ]);

        $solde = $user->solde;
        if ($solde < $request->subSolde) {
            throw ValidationException::withMessages([
                'subSolde' => 'Solde insuffisant',
            ]);
        }

        $user->solde = $user->solde - $request->subSolde;
        $user->save();
        
        return response()->json(['message' => 'Solde updated successfully', 'user' => $user], 200);
    }

}
