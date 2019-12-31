<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequisitoDetalle extends Model
{
    //
    protected $table = 'requisitos_detalle';
    protected $primaryKey = 'id_requisito_detalle';
    public $timestamps = false;

    protected $fillable =[
        'requisito',
        'requisito_id',
    ];

    protected $guarded =[
    ];
}
