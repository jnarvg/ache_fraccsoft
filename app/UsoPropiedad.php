<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsoPropiedad extends Model
{
    //
    protected $table = 'uso_propiedad';
    protected $primaryKey = 'id_uso_propiedad';
    public $timestamps = false;

    protected $fillable =[
        'uso_propiedad',

    ];

    protected $guarded =[
    ];
}
