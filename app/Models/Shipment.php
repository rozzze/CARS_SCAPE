<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'ciudad_destino',
        'direccion_entrega_completa',
        'fecha_estimada_entrega',
        'estado',
        'fecha_salida',
        'fecha_entrega',
        'observaciones',
    ];

    // Relación
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}