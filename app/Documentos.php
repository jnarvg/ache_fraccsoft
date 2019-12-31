<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Documentos extends Model
{
    //
    protected $table = 'documento';
    protected $primaryKey = 'id_documento';
    public $timestamps = false;

    protected $fillable =[
        'titulo',
        'notas',
        'fecha',
        'archivo',
        'prospecto_id',
        'agente_id',

    ];

    protected $guarded =[
    ];
}
