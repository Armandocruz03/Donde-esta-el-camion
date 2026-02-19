<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Vehiculo; 

class TipoDeVehiculo extends Model
{
    use HasFactory;

    protected $table = 'tipo_de_vehiculos';

    protected $fillable = ['capacidad','marca','modelo','dimensiones'];

    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class, 'tipo_de_vehiculo_id');
    }
}
