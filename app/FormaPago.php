<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormaPago extends Model
{
    //
    protected $table = 'forma_pago';
    protected $primaryKey = 'id_forma_pago';
    public $timestamps = false;

    protected $fillable =[
        'forma_pago',

    ];

    protected $guarded =[
    ];
}
