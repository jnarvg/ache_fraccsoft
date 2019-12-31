<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleCotizacionPropiedad extends Model
{
        //Agregado el  2 de agosto para el historal d ecotizaciones
    protected $table = 'detalle_cotizacion_propiedad';
    protected $primaryKey = 'id_detalle_cotizacion_propiedad';
    public $timestamps = [
      'created_at',
      'updated_at',
    ];

    protected $fillable =[
      'esquema_pago',
      'propiedad_id',
      'precio',
      'porcentaje_descuento',
      'monto_descuento',
      'precio_final ',
      'incluir',
      'folio_key',
      'cotizacion_id',
      'proyecto_id',
    ];

    protected $guarded =[
    ];
}
