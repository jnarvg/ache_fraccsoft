<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Requisitos extends Model
{
    //
    protected $table = 'requisitos';
    protected $primaryKey = 'id_requisito';
    public $timestamps = false;

    protected $fillable =[
        'requisito',

    ];

    protected $guarded =[
    ];
}
