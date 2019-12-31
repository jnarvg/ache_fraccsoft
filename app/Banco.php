<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    protected $table = 'banco';
    protected $primaryKey = 'id_banco';
    public $timestamps = false;

    protected $fillable =[
        'banco',
        'razon_social',
        'rfc',

    ];

    protected $guarded =[
    ];
}
