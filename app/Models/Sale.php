<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_id',
        'vehicle_id',
        'numero_boleta',
        'monto_total',
        'metodo_pago',
        'observaciones',
    ];

    // Relaciones (útil para el futuro)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function shipment()
    {
        return $this->hasOne(Shipment::class);
    }
}