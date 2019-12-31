<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstatusPropiedad extends Model
{
    //
    protected $table = 'estatus_propiedad';
    protected $primaryKey = 'id_estatus_propiedad';
    public $timestamps = false;

    protected $fillable =[
        'estatus_propiedad',
        'color_id',
        'codigo_hx',

    ];

    protected $guarded =[
    ];
}
