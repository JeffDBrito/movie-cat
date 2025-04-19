<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            // 'password' => 'required|string|min:8|confirmed',
            'password' => 'min:6',
            'password_confirmation' => 'required_with:password|same:password|min:6'
        ],
        [
            'name.required' => 'Necessário informar o nome',
            'name.string' => 'O nome deve ser uma string',
            'name.max' => 'O nome deve ter no máximo 255 caracteres',
            'email.required' => 'Necessário informar o e-mail',
            'email.email' => 'O e-mail deve ser um endereço de e-mail válido',
            'email.unique' => 'O e-mail já está em uso',
            'password.required' => 'Necessário informar a senha',
            'password.string' => 'A senha deve ser uma string',
            'password.min' => 'A senha deve ter no mínimo 6 caracteres',
            'password_confirmation.required_with' => 'Necessário confirmar a senha',
            'password_confirmation.same' => 'A confirmação da senha deve ser igual à senha',
            'password_confirmation.min' => 'A confirmação da senha deve ter no mínimo 6 caracteres',
        ]);

        // Create a new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }



}
