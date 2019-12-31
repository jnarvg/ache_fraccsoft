<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegimenFiscal extends Model
{
    protected $table = 'regimen_fiscal';
    protected $primaryKey = 'id_regimen_fiscal';
    public $timestamps = false;

    protected $fillable =[
        'regimen_fiscal',
        'clave',
        'concatenado',

    ];

    protected $guarded =[
    ];
}
