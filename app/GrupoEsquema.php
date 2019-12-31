<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GrupoEsquema extends Model
{
    protected $table = 'grupo_esquema';
    protected $primaryKey = 'id_grupo_esquema';
    public $timestamps = [
      'created_at',
      'updated_at',
    ];

    protected $fillable =[
      'grupo_esquema',
      'uso_propiedad_id',
      'proyecto_id',
    ];

    protected $guarded =[
    ];
}
