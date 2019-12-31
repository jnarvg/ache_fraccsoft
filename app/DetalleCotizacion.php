<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleCotizacion extends Model
{
    ////Agregado el  2 de agosto para el historal d ecotizaciones
    protected $table = 'detalle_cotizacion';
    protected $primaryKey = 'id_detalle_cotizacion';
    public $timestamps = false;

    protected $fillable =[
        'propiedad_id',
        'precio_propiedad',
        'plazos',
        'inicial_a',
        'contraentrega_a',
        'mensualidades_a',
        'descuento_a',
        'inicial_b',
        'contraentrega_b',
        'mensualidades_b',
        'descuento_b',
        'inicial_c',
        'contraentrega_c',
        'mensualidades_c',
        'descuento_c',
        'monto_inicial_d',
        'inicial_d',
        'contraentrega_d',
        'mensualidades_d',
        'descuento_d',
        'created_at',

    ];

    protected $guarded =[
    ];
}
