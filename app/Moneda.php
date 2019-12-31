<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Moneda extends Model
{
    protected $table = 'moneda';
    protected $primaryKey = 'id_moneda';
    public $timestamps = false;

    protected $fillable =[
        'moneda',
        'tipo_cambio',
        'siglas',

    ];

    protected $guarded =[
    ];
}
