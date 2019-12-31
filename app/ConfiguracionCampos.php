<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionCampos extends Model
{
    protected $table = 'campos_configuracion';
    protected $primaryKey = 'id_campos_configuracion';
    public $timestamps = false;

    protected $fillable =[
    'tabla',
    'campo',
    'label',
    'tipo_dato',
    'pk',
    'unique',
    'readonly',
    'hidden',
    'fk_tabla',
    'fk_campo',
    'importable',
    ];

    protected $guarded =[
    ];
}
