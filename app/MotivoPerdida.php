<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MotivoPerdida extends Model
{
    protected $table = 'motivo_perdida';
    protected $primaryKey = 'id_motivo_perdida';
    public $timestamps = false;

    protected $fillable =[
        'motivo_perdida',

    ];

    protected $guarded =[
    ];
}
