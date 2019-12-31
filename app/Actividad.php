<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    //
    protected $table = 'actividad';
    protected $primaryKey = 'id_actividad';
    public $timestamps = false;

    protected $fillable =[
        'titulo',
        'fecha',
        'hora',
        'duracion',
        'descripcion',
        'agente_id',
        'prospecto_id',
        'tipo_actividad', //Llamada Tarea Correo 
        'estatus', /// Pendiente Completada
        'proyecto_id',
        'fecha_recordatorio', //default la de la fecha
        

    ];

    protected $guarded =[
    ];
}
