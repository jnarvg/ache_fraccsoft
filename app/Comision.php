<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comision extends Model
{
    //
    protected $table = 'comision';
    protected $primaryKey = 'id_comision';
    public $timestamps = false;

    protected $fillable =[
        'cliente_id',
        'propiedad_id',
        'estatus',
        'estatus_pago',
        'monto_operacion',
        'fecha_venta',
        'comision_total',
        'saldo_comision',
    ];

    protected $guarded =[
    ];
}
