<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    use HasFactory;

    protected $table = 'rutas';

    protected $fillable = [
        'nombre',
        'descripcion',
        'activa',
        'color',
        'segundos_avance',
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];

    public function puntos()
    {
        return $this->hasMany(RutaPunto::class)->orderBy('orden');
    }
    public function paradas()
    {
        return $this->hasMany(Parada::class);
    }
}
