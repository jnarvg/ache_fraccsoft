<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleCotizacionPropiedadRubro extends Model
{
    protected $table = 'detalle_cotizacion_propiedad_rubro';
    protected $primaryKey = 'id_detalle_cotizacion_propiedad_rubro';
    public $timestamps = [
      'created_at',
      'updated_at',
    ];

    protected $fillable =[
      'alias',
      'fecha',
      'tipo',
      'tipo_calculo',
      'monto',
      'porcentaje',
      'mensualidades',
      'excluir_calculo',
      'excluir_descuento',
      'abono_aplica_a_id',
      'detalle_cotizacion_propiedad_id',
      'cotizacion_id',
    ];

    protected $guarded =[
    ];
}