<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class UserController extends Controller
{
    //Muestra los usuarios
    public function index()
    {
        try {
            $users = User::all();
            return response()->json([
                'success' => true,
                'data' => $users
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener usuarios'
            ], 500);
        }
    }


    //Guarda los usuarios
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|min:2',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,user'
        ], [
            'name.required' => 'El nombre es obligatorio',
            'name.min' => 'El nombre debe tener al menos 2 caracteres',
            'name.max' => 'El nombre no puede tener más de 255 caracteres',
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El email debe tener un formato válido',
            'email.unique' => 'Este email ya está registrado',
            'email.max' => 'El email no puede tener más de 255 caracteres',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'role.required' => 'El rol es obligatorio',
            'role.in' => 'El rol debe ser admin o user'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Usuario creado exitosamente',
                'data' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el usuario'
            ], 500);
        }
    }

    //Muestra un usuario
    public function show($id)
    {
        try {
            $user = User::find($id);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el usuario'
            ], 500);
        }
    }

    //Actualiza el usuario
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|min:2',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,user'
        ], [
            'name.required' => 'El nombre es obligatorio',
            'name.min' => 'El nombre debe tener al menos 2 caracteres',
            'name.max' => 'El nombre no puede tener más de 255 caracteres',
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El email debe tener un formato válido',
            'email.unique' => 'Este email ya está registrado',
            'email.max' => 'El email no puede tener más de 255 caracteres',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'role.required' => 'El rol es obligatorio',
            'role.in' => 'El rol debe ser admin o user'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->role = $request->role;
            
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Usuario actualizado exitosamente',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el usuario'
            ], 500);
        }
    }

    //Elimina logicamente el usuario
    public function destroy($id)
    {
        try {
            $user = User::find($id);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Usuario eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el usuario'
            ], 500);
        }
    }
}
