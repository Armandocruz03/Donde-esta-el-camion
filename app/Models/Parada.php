<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parada extends Model
{
    protected $fillable = [
        'ruta_id',
        'nombre',
        'lat',
        'lng',
        'hora',
        'duracion_minutos',
        'dias',
        'activa',
    ];

    protected $casts = [
        'dias' => 'array',
    ];

    public function ruta()
    {
        return $this->belongsTo(Ruta::class);
    }
}
