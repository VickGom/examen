<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Equipo;

class EquipoController extends Controller
{
    //Muestra los equipos por busqueda
    public function index(Request $request)
    {
        try {
            $query = Equipo::query();
            
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                      ->orWhere('tipo_motor', 'like', "%{$search}%");
                });
            }
            
            $equipos = $query->get();
            
            return response()->json([
                'success' => true,
                'data' => $equipos
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener equipos'
            ], 500);
        }
    }

    //Guarda el equipo
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tipo_motor' => 'required|string|max:255|min:2',
            'nombre' => 'required|string|max:255|min:2',
            'fecha_fabricacion' => 'required|date|before_or_equal:today',
            'potencia' => 'required|numeric|min:0|max:999999.99',
            'velocidad' => 'required|integer|min:0|max:999999'
        ], [
            'tipo_motor.required' => 'El tipo de motor es obligatorio',
            'tipo_motor.min' => 'El tipo de motor debe tener al menos 2 caracteres',
            'tipo_motor.max' => 'El tipo de motor no puede tener más de 255 caracteres',
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.min' => 'El nombre debe tener al menos 2 caracteres',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres',
            'fecha_fabricacion.required' => 'La fecha de fabricación es obligatoria',
            'fecha_fabricacion.date' => 'La fecha de fabricación debe ser una fecha válida',
            'fecha_fabricacion.before_or_equal' => 'La fecha de fabricación no puede ser futura',
            'potencia.required' => 'La potencia es obligatoria',
            'potencia.numeric' => 'La potencia debe ser un número',
            'potencia.min' => 'La potencia debe ser mayor o igual a 0',
            'potencia.max' => 'La potencia no puede ser mayor a 999999.99',
            'velocidad.required' => 'La velocidad es obligatoria',
            'velocidad.integer' => 'La velocidad debe ser un número entero',
            'velocidad.min' => 'La velocidad debe ser mayor o igual a 0',
            'velocidad.max' => 'La velocidad no puede ser mayor a 999999'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $equipo = Equipo::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Equipo creado exitosamente',
                'data' => $equipo
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el equipo'
            ], 500);
        }
    }

    //Muestra el equipo
    public function show($id)
    {
        try {
            $equipo = Equipo::find($id);
            
            if (!$equipo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Equipo no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $equipo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el equipo'
            ], 500);
        }
    }

    //Actualiza el equipo
    public function update(Request $request, $id)
    {
        $equipo = Equipo::find($id);
        
        if (!$equipo) {
            return response()->json([
                'success' => false,
                'message' => 'Equipo no encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'tipo_motor' => 'required|string|max:255|min:2',
            'nombre' => 'required|string|max:255|min:2',
            'fecha_fabricacion' => 'required|date|before_or_equal:today',
            'potencia' => 'required|numeric|min:0|max:999999.99',
            'velocidad' => 'required|integer|min:0|max:999999'
        ], [
            'tipo_motor.required' => 'El tipo de motor es obligatorio',
            'tipo_motor.min' => 'El tipo de motor debe tener al menos 2 caracteres',
            'tipo_motor.max' => 'El tipo de motor no puede tener más de 255 caracteres',
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.min' => 'El nombre debe tener al menos 2 caracteres',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres',
            'fecha_fabricacion.required' => 'La fecha de fabricación es obligatoria',
            'fecha_fabricacion.date' => 'La fecha de fabricación debe ser una fecha válida',
            'fecha_fabricacion.before_or_equal' => 'La fecha de fabricación no puede ser futura',
            'potencia.required' => 'La potencia es obligatoria',
            'potencia.numeric' => 'La potencia debe ser un número',
            'potencia.min' => 'La potencia debe ser mayor o igual a 0',
            'potencia.max' => 'La potencia no puede ser mayor a 999999.99',
            'velocidad.required' => 'La velocidad es obligatoria',
            'velocidad.integer' => 'La velocidad debe ser un número entero',
            'velocidad.min' => 'La velocidad debe ser mayor o igual a 0',
            'velocidad.max' => 'La velocidad no puede ser mayor a 999999'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $equipo->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Equipo actualizado exitosamente',
                'data' => $equipo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el equipo'
            ], 500);
        }
    }

    //Elimina el equipo logicamente
    public function destroy($id)
    {
        try {
            $equipo = Equipo::find($id);
            
            if (!$equipo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Equipo no encontrado'
                ], 404);
            }

            $equipo->delete();

            return response()->json([
                'success' => true,
                'message' => 'Equipo eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el equipo'
            ], 500);
        }
    }
}
