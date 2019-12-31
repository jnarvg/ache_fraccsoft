<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    //
    protected $table = 'proyecto';
    protected $primaryKey = 'id_proyecto';
    public $timestamps = false;

    protected $fillable =[
        'nombre',
        'metros_construccion',
        'metros_terreno',
        'direccion',
        'latitude',
        'longitude',
        'pais_id',
        'estado_id',
        'ciudad_id',
        'cuenta_catastral',
        'plano',
        'plano_mapa',
        'portada_cotizacion',
        'logo_cotizacion',
        'header_contrato',
        'footer_contrato',
        'calle',
        'num_exterior',
        'num_interior',
        'codigo_postal',
        'colonia',

    ];

    protected $guarded =[
    ];
}

