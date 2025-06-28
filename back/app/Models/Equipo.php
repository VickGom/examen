<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipo extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tipo_motor',
        'nombre',
        'fecha_fabricacion',
        'potencia',
        'velocidad'
    ];

    protected $casts = [
        'fecha_fabricacion' => 'date',
        'potencia' => 'decimal:2',
        'velocidad' => 'integer'
    ];
}
