<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'marca',
        'modelo',
        'anio_fabricacion',
        'color',
        'precio_venta',
        'numero_motor',
        'numero_chasis',
        'tipo_combustible',
        'transmision',
        'kilometraje',
        'caracteristicas_adicionales',
        'estado', // <-- ¡Importante!
    ];
}