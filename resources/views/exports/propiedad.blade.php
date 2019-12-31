<table id="tblData">
    <thead>
        <tr>
            @foreach ($campos as $c)
                <th valign="middle" align="left" style="font-weight: bolder; background-color: #82C2F7">{{ strtoupper($c) }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($resultados as $r)
            <tr>
                <td valign="middle" align="left">{{ $r->nombre }}</td>
                <td valign="middle" align="left">{{ $r->tipo_propiedad_id }}</td>
                <td valign="middle" align="left">{{ $r->nivel_id }}</td>
                <td valign="middle" align="left">{{ $r->proyecto_id }}</td>
                <td valign="middle" align="left">{{ $r->uso_propiedad_id }}</td>
                <td valign="middle" align="left">{{ $r->estatus_propiedad_id }}</td>
                <td valign="middle" align="left">{{ $r->enganche }}</td>
                <td valign="middle" align="left">{{ $r->precio }}</td>
                <td valign="middle" align="left">{{ $r->moneda }}</td>
                <td valign="middle" align="left">{{ $r->pais_id }}</td>
                <td valign="middle" align="left">{{ $r->estado_id }}</td>
                <td valign="middle" align="left">{{ $r->ciudad_id }}</td>
                <td valign="middle" align="left">{{ $r->codigo_postal }}</td>
                <td valign="middle" align="left">{{ $r->recamaras }}</td>
                <td valign="middle" align="left">{{ $r->cajones_estacionamiento }}</td>
                <td valign="middle" align="left">{{ $r->fecha_registro }}</td>
                <td valign="middle" align="left">{{ $r->coordenadas }}</td>
                <td valign="middle" align="left">{{ $r->direccion }}</td>
                <td valign="middle" align="left">{{ $r->manzana }}</td>
                <td valign="middle" align="left">{{ $r->numero }}</td>
                <td valign="middle" align="left">{{ $r->tipo_modelo_id }}</td>
                <td valign="middle" align="left">{{ $r->precio_mts_interior }}</td>
                <td valign="middle" align="left">{{ $r->precio_mts_exterior }}</td>
                <td valign="middle" align="left">{{ $r->mts_interior }}</td>
                <td valign="middle" align="left">{{ $r->mts_exterior }}</td>
                <td valign="middle" align="left">{{ $r->mts_total }}</td>

            </tr>
        @endforeach
    </tbody>
</table>