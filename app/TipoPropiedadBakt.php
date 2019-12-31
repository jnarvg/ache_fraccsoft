<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoPropiedadBakt extends Model
{
    protected $table = 'tipo_modelo';
    protected $primaryKey = 'id_tipo_modelo';
    public $timestamps = false;

    protected $fillable =[
        'tipo_modelo',

    ];

    protected $guarded =[
    ];
}
