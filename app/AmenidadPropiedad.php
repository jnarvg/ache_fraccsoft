<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AmenidadPropiedad extends Model
{
    //
    protected $table = 'amenidad_propiedad';
    protected $primaryKey = 'id_amenidad_propiedad';
    public $timestamps = false;

    protected $fillable =[
        'propiedad_id',
        'amenidad_id',

    ];

    protected $guarded =[
    ];
}
