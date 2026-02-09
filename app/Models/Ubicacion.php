<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    use HasFactory;

    protected $table = 'ubicacions';  

    protected $fillable = [
        'ruta_id',
        'orden',
        'nombre',
        'lat',
        'lng',
    ];

    protected $casts = [
        'lat'   => 'float',
        'lng'   => 'float',
        'orden'=> 'integer',
    ];

    public function ruta()
    {
        return $this->belongsTo(Ruta::class);
    }
}
