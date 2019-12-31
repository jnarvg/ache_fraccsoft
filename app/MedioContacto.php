<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedioContacto extends Model
{
    protected $table = 'medio_contacto';
    protected $primaryKey = 'id_medio_contacto';
    public $timestamps = false;

    protected $fillable =[
        'medio_contacto',

    ];

    protected $guarded =[
    ];
}
