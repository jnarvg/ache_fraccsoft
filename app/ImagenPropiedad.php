<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImagenPropiedad extends Model
{
    //
    protected $table = 'imagen_propiedad';
    protected $primaryKey = 'id_imagen';
    public $timestamps = false;

    protected $fillable =[
        'imagen_path',
        'propiedad_id',
        'titulo',

    ];

    protected $guarded =[
    ];
}
