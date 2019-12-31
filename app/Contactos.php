<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contactos extends Model
{
    //
    protected $table = 'contacto';
    protected $primaryKey = 'id_contacto';
    public $timestamps = false;

    protected $fillable =[
        'nombre',
        'telefono',
        'correo',
        'telefono_adicional',
        'puesto',
        'notas',
        'prospecto_id',

    ];

    protected $guarded =[
    ];
}
