<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionGeneral extends Model
{
    //
    protected $table = 'configuracion_general';
    protected $primaryKey = 'id_configuracion_general';
    public $timestamps = false;

    protected $fillable =[
        'nombre_cliente',
        'empresa_principal_id',
        'jefe_proyecto',
        'correo_contacto',
        'tasa_interes_mora',
        'limite_usuarios',
        'limite_propiedades',
        'estatus', /*Activo*/ /*Inactivo*/ /*Suspendido*/
    ];

    protected $guarded =[
    ];
}
