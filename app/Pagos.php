<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pagos extends Model
{
    //
    protected $table = 'pagos';
    protected $primaryKey = 'id_pago';
    public $timestamps = false;

    protected $fillable =[
        'estatus', ///Aplicado Cancelado
        'fecha',
        'monto',
        'forma_pago_id',
        'plazo_pago_id',
        'fecha_cancelacion',
        'dias_retraso',
        'mora',

    ];

    protected $guarded =[
    ];
}
