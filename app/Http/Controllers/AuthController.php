<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        $passwordConfirmation = $request->input('password_confirmation');

        $errors = [];

        if (empty($name)) {
            $errors[] = 'El nombre es obligatorio';
        }

        if (empty($email)) {
            $errors[] = 'El correo es obligatorio';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'El correo no es válido';
        } elseif (User::where('email', $email)->exists()) {
            $errors[] = 'El correo ya está registrado';
        }

        if (empty($password)) {
            $errors[] = 'La contraseña es obligatoria';
        } elseif (strlen($password) < 8) {
            $errors[] = 'La contraseña debe tener al menos 8 caracteres';
        }

        if ($password !== $passwordConfirmation) {
            $errors[] = 'Las contraseñas no coinciden';
        }

        if (!empty($errors)) {
            return response()->json([
                'message' => 'Error al registrarse',
                'errors' => $errors
            ], 422);
        }

        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->save();

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Registrado exitosamente',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $errors = [];

        if (empty($email)) {
            $errors[] = 'El correo es obligatorio';
        }

        if (empty($password)) {
            $errors[] = 'La contraseña es obligatoria';
        }

        if (!empty($errors)) {
            return response()->json([
                'message' => 'Error al iniciar sesión',
                'errors' => $errors
            ], 422);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        if (!Hash::check($password, $user->password)) {
            return response()->json([
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Sesión iniciada correctamente',
            'user' => $user,
            'token' => $token
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $name = $request->input('name');
        $email = $request->input('email');

        $errors = [];

        if (empty($name)) {
            $errors[] = 'El nombre es obligatorio';
        }

        if (empty($email)) {
            $errors[] = 'El correo es obligatorio';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'El correo no es válido';
        } elseif (User::where('email', $email)->where('id', '!=', $user->id)->exists()) {
            $errors[] = 'El correo ya está en uso';
        }

        if (!empty($errors)) {
            return response()->json([
                'message' => 'Error al actualizar',
                'errors' => $errors
            ], 422);
        }

        $user->name = $name;
        $user->email = $email;

        $password = $request->input('password');

        if (!empty($password)) {
            $passwordConfirmation = $request->input('password_confirmation');

            if (strlen($password) < 8) {
                $errors[] = 'La contraseña debe tener al menos 8 caracteres';
            }

            if ($password !== $passwordConfirmation) {
                $errors[] = 'Las contraseñas no coinciden';
            }

            if (!empty($errors)) {
                return response()->json([
                    'message' => 'Error al actualizar',
                    'errors' => $errors
                ], 422);
            }

            $user->password = Hash::make($password);
        }

        $user->save();

        return response()->json([
            'message' => 'Usuario actualizado correctamente',
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $token = $user->currentAccessToken();

        if ($token) {
            $token->delete();
        }

        return response()->json([
            'message' => 'Sesión cerrada correctamente'
        ]);
    }
}
