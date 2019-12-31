<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoPropiedad extends Model
{
    //
    protected $table = 'tipo_propiedad';
    protected $primaryKey = 'id_tipo_propiedad';
    public $timestamps = false;

    protected $fillable =[
        'tipo_propiedad',

    ];

    protected $guarded =[
    ];
}
