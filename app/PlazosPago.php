<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlazosPago extends Model
{
    //
    protected $table = 'plazos_pago';
    protected $primaryKey = 'id_plazo_pago';
    public $timestamps = false;

    protected $fillable =[
        'prospecto_id',
        'fecha',
        'estatus', //Programado No programado Inactivo
        'num_plazo',
        'total',
        'saldo',
        'pagado',
        'dias_retraso',
        'monto_mora',
        'notas',
        'deuda',
        'amortizacion ',
        'interes',
        'interes_acumulado',
        'capital_acumulado',
        'capital_inicial',
        'capital',
        'total_acumulado',
        'moneda_id',

    ];

    protected $guarded =[
    ];
}
