<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EsquemaComision extends Model
{
    //
    protected $table = 'esquema_comision';
    protected $primaryKey = 'id_esquema_comision';
    public $timestamps = false;

    protected $fillable =[
        'esquema_comision',
    ];
    
    protected $guarded =[
    ];
}
