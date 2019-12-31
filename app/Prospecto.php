<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prospecto extends Model
{
    //
    protected $table = 'prospectos';
    protected $primaryKey = 'id_prospecto';
    public $timestamps = false;

    protected $fillable =[
        'nombre',
        'rfc',
        'correo',
        'telefono',
        'telefono_adicional',
        'extension',
        'asesor_id',
        'propiedad_id',
        'proyecto_id',
        'folio',
        'estatus',
        'fecha_registro',
        'fecha_recontacto',
        'observaciones',
        'fecha_visita',
        'medio_contacto_id',
        'fecha_cotizacion',
        'fecha_apartado',
        'monto_apartado',
        'fecha_venta',
        'monto_venta',
        'fecha_enganche',
        'monto_enganche',
        'tipo_operacion_id',
        'motivo_perdida_id',
        'cerrador',
        'num_plazos',
        'fecha_ultimo_pago',
        'monto_ultimo_pago',
        'fecha_escrituracion',
        'esquema_comision_id',
        'comision_id',
        'domicilio',
        'interes',
        'mensualidad',
        'razon_social',
        'tipo', //Moral Fisico
        'capital',
        'pagado',
        'vigencia_contrato',
        'fecha_contrato',
        'num_contrato',
        'porcentaje_interes',
        'moneda_id',
        'cuenta_externa',
        'nacionalidad',
        'porcentaje_descuento',
        'porcentaje_enganche',
        'porcentaje_contraentrega',
        'oficina_broker',
        'fecha_entrega_propiedad',
        'nombre_broker',
        'esquema_pago',
        'foto_prospecto',
        'foto_prospecto_2',
        'adicional_d',
        'fecha_adicional',
        'dia_pago',
        'fecha_contraentrega',
        'pais_id',
        'estado_id',
        'ciudad_id',
        'calle',
        'num_exterior',
        'num_interior',
        'codigo_postal',
        'colonia',
        'apellido_materno',
        'apellido_paterno',
        'nombre_s',

    ];

    protected $guarded =[
    ];
}
