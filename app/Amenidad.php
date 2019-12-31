<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Amenidad extends Model
{
    //
    protected $table = 'amenidad';
    protected $primaryKey = 'id_amenidad';
    public $timestamps = false;

    protected $fillable =[
        'amenidad',
        'grubsa_id',

    ];

    protected $guarded =[
    ];
}
