<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CondicionEntregaDetalle extends Model
{
    protected $table = 'condicion_entrega_detalle';
    protected $primaryKey = 'id_condicion_entrega_detalle';
    public $timestamps = false;

    protected $fillable =[
    'uso_propiedad_id',
    'proyecto_id',
    'condicion',
    'tipo',
    'condicion_entrega_id',
    'created_date',
    'modified_date',
    'subtitulo',
    ];

    protected $guarded =[
    ];
}
