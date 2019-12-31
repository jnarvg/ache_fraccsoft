<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    //Agregado el  2 de agosto para el historal d ecotizaciones
    protected $table = 'cotizacion';
    protected $primaryKey = 'id_cotizacion';
    public $timestamps = false;

    protected $fillable =[
        'createdDate',
        'fecha_cotizacion',
        'proyecto',
        'proyecto_id',
        'propiedad',
        'cliente',
        'correo',
        'telefono',
        'asesor_id',
        'moneda_id',
        'estatus', /// abierta 
        'fecha_cierre',
        'prospecto_id',

    ];

    protected $guarded =[
    ];
}
