<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\Select;

class Vehiculo extends Model
{
    use HasFactory;

    protected $table = 'vehiculos';

    protected $fillable = [
        'placas',
        'anio',
        'color',
        'user_id',
        'tipo_de_vehiculo_id' 
    ];

    public function tipoDeVehiculo()
    {
        return $this->belongsTo(TipoDeVehiculo::class, 'tipo_de_vehiculo_id');
    }

    public function conductor()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
