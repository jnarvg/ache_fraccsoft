<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Propiedad extends Model
{
    //
    protected $table = 'propiedad';
    protected $primaryKey = 'id_propiedad';
    public $timestamps = false;

    protected $fillable =[
        'nombre',
        'tipo_propiedad_id',
        'proyecto_id',
        'construccion_metros',
        'terreno_metros',
        'cuenta_catastral',
        'uso_propiedad_id',
        'estatus_propiedad_id',
        'descripcion_corta',
        'condicion',
        'precio',
        'moneda',
        'pais_id',
        'estado_id',
        'ciudad_id',
        'codigo_postal',
        'latitude',
        'longitude',
        'recamaras',
        'banos',
        'vigilancia',
        'area_rentable_metros',
        'cajones_estacionamiento',
        'sala_tv',
        'cuarto_servicio',
        'infraestructura',
        'terreno',
        'construccion',
        'area_rentable',
        'estacionamiento',
        'acabados',
        'pdf',
        'fecha_registro',
        'coordenadas',
        'direccion',
        'manzana',
        'enganche',
        'numero',
        'metros_fondo',
        'metros_frente',
        'metros_contrato',
        'tipo_modelo_id',
        'precio_mts_interior',
        'precio_mts_exterior',
        'mts_interior',
        'mts_exterior',


    ];

    protected $guarded =[
    ];
}

