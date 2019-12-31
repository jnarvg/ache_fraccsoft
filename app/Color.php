<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    //
    protected $table = 'color';
    protected $primaryKey = 'id_color';
    public $timestamps = false;

    protected $fillable =[
        'color',
        'codigo_hexadecimal',

    ];

    protected $guarded =[
    ];
}
