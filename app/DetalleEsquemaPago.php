<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleEsquemaPago extends Model
{
    protected $table = 'detalle_esquema_pago';
    protected $primaryKey = 'id_detalle_esquema_pago';
    public $timestamps = [
      'created_at',
      'updated_at',
    ];

    protected $fillable =[
      'alias',
      'tipo',
      'tipo_calculo',
      'porcentaje',
      'monto',
      'mensualidades',
      'esquema_pago_id',
    ];

    protected $guarded =[
    ];
}

