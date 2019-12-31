<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoOperacion extends Model
{
    //
    protected $table = 'tipo_operacion';
    protected $primaryKey = 'id_tipo_operacion';
    public $timestamps = false;

    protected $fillable =[
        'tipo_operacion',

    ];

    protected $guarded =[
    ];
}
