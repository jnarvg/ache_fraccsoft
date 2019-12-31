<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleEsquemaComision extends Model
{
    protected $table = 'detalle_esquema_comision';
    protected $primaryKey = 'id_detalle_esquema_comision';
    public $timestamps = false;

    protected $fillable =[
        'rubro',
        'factor',
        'tipo',
        'usuario',
        'persona',
        'esquema_id',
    ];

    protected $guarded =[
    ];
}
