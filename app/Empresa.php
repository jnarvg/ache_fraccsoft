<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresa';
    protected $primaryKey = 'id_empresa';
    public $timestamps = false;

    protected $fillable =[
        'nombre_comercial',
        'razon_social',
        'n_interior',
        'n_exterior',
        'rfc',
        'correo',
        'cuenta_bancaria',
        'rfc_banco',
        'banco_id',
        'regimen_fiscal_id',
        'pais_id',
        'estado_id',
        'ciudad_id',
        'cuenta_catastral',
        'tipo',
        'colonia',
        'calle',
        'codigo_postal',

    ];

    protected $guarded =[
    ];
}
