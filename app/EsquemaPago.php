<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EsquemaPago extends Model
{
    protected $table = 'esquema_pago';
    protected $primaryKey = 'id_esquema_pago';
    public $timestamps = [
      'created_at',
      'updated_at',
    ];

    protected $fillable =[
      'esquema_pago',
      'porcentaje_descuento',
      'uso_propiedad_id',
      'proyecto_id',
      'incluir',
      'tomar_encuenta_uso',
    ];

    protected $guarded =[
    ];
}
