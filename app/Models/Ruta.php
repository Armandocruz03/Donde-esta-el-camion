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
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];

    /**
     * Relación: una Ruta tiene muchas Ubicaciones
     */
    public function ubicaciones()
    {
        return $this->hasMany(Ubicacion::class, 'ruta_id')
                    ->orderBy('orden');
    }
}
