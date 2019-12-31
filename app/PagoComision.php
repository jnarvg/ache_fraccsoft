<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PagoComision extends Model
{
    //
    protected $table = 'pago_comision';
    protected $primaryKey = 'id_pago_comision';
    public $timestamps = false;

    protected $fillable =[
        'estatus',
        'fecha_pago',
        'monto', //Programado No programado
        'forma_pago_id',
        'descripcion',
        'comision_id',
        'comision_detalle_id', 
    ];

    protected $guarded =[
    ];
}
