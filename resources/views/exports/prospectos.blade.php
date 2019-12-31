<table id="tblData">
    <thead>
        <tr>
            @foreach ($campos as $c)
                <th valign="middle" align="center" style="font-weight: bolder; background-color: #82C2F7">{{ strtoupper($c) }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($resultados as $r)
            <tr>
                <td valign="middle" align="center">{{ $r->id_prospecto  }}</td>
                <td valign="middle" align="center">{{ $r->nombre  }}</td>
                <td valign="middle" align="center">{{ $r->rfc  }}</td>
                <td valign="middle" align="center">{{ $r->correo  }}</td>
                <td valign="middle" align="center">{{ $r->telefono  }}</td>
                <td valign="middle" align="center">{{ $r->telefono_adicional  }}</td>
                <td valign="middle" align="center">{{ $r->extension  }}</td>
                <td valign="middle" align="center">{{ $r->asesor_id  }}</td>
                <td valign="middle" align="center">{{ $r->razon_social  }}</td>
                <td valign="middle" align="center">{{ $r->domicilio  }}</td>
                <td valign="middle" align="center">{{ $r->tipo  }}</td>
                <td valign="middle" align="center">{{ $r->propiedad_id  }}</td>
                <td valign="middle" align="center">{{ $r->proyecto_id  }}</td>
                <td valign="middle" align="center">{{ $r->folio  }}</td>
                <td valign="middle" align="center">{{ $r->estatus  }}</td>
                <td valign="middle" align="center">{{ $r->fecha_registro  }}</td>
                <td valign="middle" align="center">{{ $r->fecha_recontacto  }}</td>
                <td valign="middle" align="center">{{ $r->observaciones  }}</td>
                <td valign="middle" align="center">{{ $r->fecha_visita  }}</td>
                <td valign="middle" align="center">{{ $r->medio_contacto_id  }}</td>
                <td valign="middle" align="center">{{ $r->fecha_cotizacion  }}</td>
                <td valign="middle" align="center">{{ $r->fecha_apartado  }}</td>
                <td valign="middle" align="center">{{ $r->monto_apartado  }}</td>
                <td valign="middle" align="center">{{ $r->fecha_venta  }}</td>
                <td valign="middle" align="center">{{ $r->monto_venta  }}</td>
                <td valign="middle" align="center">{{ $r->fecha_enganche  }}</td>
                <td valign="middle" align="center">{{ $r->monto_enganche  }}</td>
                <td valign="middle" align="center">{{ $r->tipo_operacion_id  }}</td>
                <td valign="middle" align="center">{{ $r->motivo_perdida_id  }}</td>
                <td valign="middle" align="center">{{ $r->cerrador  }}</td>
                <td valign="middle" align="center">{{ $r->num_plazos  }}</td>
                <td valign="middle" align="center">{{ $r->fecha_ultimo_pago  }}</td>
                <td valign="middle" align="center">{{ $r->monto_ultimo_pago  }}</td>
                <td valign="middle" align="center">{{ $r->fecha_escrituracion  }}</td>
                <td valign="middle" align="center">{{ $r->esquema_comision_id  }}</td>
                <td valign="middle" align="center">{{ $r->comision_id  }}</td>
                <td valign="middle" align="center">{{ $r->interes  }}</td>
                <td valign="middle" align="center">{{ $r->mensualidad  }}</td>
                <td valign="middle" align="center">{{ $r->capital  }}</td>
                <td valign="middle" align="center">{{ $r->pagado  }}</td>
                <td valign="middle" align="center">{{ $r->vigencia_contrato  }}</td>
                <td valign="middle" align="center">{{ $r->fecha_contrato  }}</td>
                <td valign="middle" align="center">{{ $r->num_contrato  }}</td>
                <td valign="middle" align="center">{{ $r->saldo  }}</td>
                <td valign="middle" align="center">{{ $r->porcentaje_interes  }}</td>
                <td valign="middle" align="center">{{ $r->moneda_id  }}</td>
                <td valign="middle" align="center">{{ $r->cuenta_externa  }}</td>
                <td valign="middle" align="center">{{ $r->nacionalidad  }}</td>
            </tr>
        @endforeach
    </tbody>
</table>