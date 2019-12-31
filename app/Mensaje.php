<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    protected $table = 'mensaje';
    protected $primaryKey = 'id_mensaje';
    public $timestamps = false;

    protected $fillable =[
        'titulo',
        'nota',
        'fecha',
        'creador_id',
        'dirigido_id',
        'prospecto_id',

    ];

    protected $guarded =[
    ];
}
