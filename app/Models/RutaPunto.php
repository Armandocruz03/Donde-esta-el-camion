<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RutaPunto extends Model
{
    protected $fillable = ['ruta_id', 'lat', 'lng', 'orden'];

    public function ruta()
    {
        return $this->belongsTo(Ruta::class);
    }
}
