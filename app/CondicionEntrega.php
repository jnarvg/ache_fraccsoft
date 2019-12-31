<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CondicionEntrega extends Model
{
    protected $table = 'condicion_entrega';
    protected $primaryKey = 'id_condicion_entrega';
    public $timestamps = false;

    protected $fillable =[
    'uso_propiedad_id',
    'proyecto_id',
    'titulo',
    'created_date',
    ];

    protected $guarded =[
    ];
}
