<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComisionDetalle extends Model
{
    //
    protected $table = 'comision_detalle';
    protected $primaryKey = 'id_comision_detalle';
    public $timestamps = false;

    protected $fillable =[
    'rubro',
    'tipo',
    'usuario_id',
    'persona',
    'factor',
    'total_venta',
    'comision',
    'saldo_comision',
    'comision_id',
    ];

    protected $guarded =[
    ];
}
