<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequisitosProspecto extends Model
{
    //
    protected $table = 'requisito_prospecto';
    protected $primaryKey = 'id_requisito_prospecto';
    public $timestamps = false;

    protected $fillable =[
        'requisito',
        'estatus', /// Completado Pendiente
        'comentario',
        'prospecto_id',

    ];

    protected $guarded =[
    ];
}
