<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tipo_documento',
        'numero_documento',
        'nombres_completos',
        'direccion_completa',
        'ciudad',
        'telefono',
        'correo_electronico',
    ];

    /**
     * Un cliente puede tener muchas ventas.
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

}