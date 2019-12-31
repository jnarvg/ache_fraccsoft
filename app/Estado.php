<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    //
    protected $table = 'estado';
    protected $primaryKey = 'id_estado';
    public $timestamps = false;

    protected $fillable =[
        'estado',
        'clave',
        'pais_id',

    ];

    protected $guarded =[
    ];
}
