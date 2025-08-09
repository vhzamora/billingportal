<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DestinoDesconocido extends Model
{
    use HasFactory;

    protected $table = 'destinos_desconocidos';

    protected $fillable = [
        'numero_destino', 'cantidad_llamadas', 'notificado', 'procesado'
    ];

    protected $casts = [
        'cantidad_llamadas' => 'integer',
        'notificado' => 'boolean',
        'procesado' => 'boolean',
    ];
}
