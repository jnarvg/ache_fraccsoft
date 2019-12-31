<table id="tblData">
@php
    $i = 1;
@endphp
@foreach ($areas_inspeccion as $area)
    <thead>
        <tr>
            <th valign="middle" align="center" style="font-weight: bolder; background-color: #82C2F7">No.</th>
            <th valign="middle" align="center" style="font-weight: bolder; background-color: #82C2F7" width="215">{{ strtoupper($area->area) }}</th>
            <th valign="middle" align="center" style="font-weight: bolder; background-color: #82C2F7"></th>
            <th valign="middle" align="center" style="font-weight: bolder; background-color: #82C2F7">{{ $area->puntos_obtenidos }}</th>
            <th valign="middle" align="center" style="font-weight: bolder; background-color: #82C2F7" width="150">{{ strtoupper($area->etiqueta_calificacion) }}</th>
            <th valign="middle" align="center" style="font-weight: bolder; background-color: #82C2F7" width="215">OBSERVACIONES</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($rubros_inspeccion as $rubro)
            @if ($rubro->area_id == $area->id_area_inspeccion)
                <tr>
                    <td style="font-size: 10pt; font-weight: bold; background-color: #D4D5D5;">{{ $i }}</td>
                    <td style="font-size: 10pt; font-weight: bold; background-color: #D4D5D5; width: 215px;" width="215">{{ $rubro->rubro }}</td>
                    <td style="font-size: 10pt; font-weight: bold; background-color: #D4D5D5;">{{ $rubro->porcentaje_obtenido }}</td>
                    <td style="font-size: 10pt; font-weight: bold; background-color: #D4D5D5;">{{ $rubro->ptos_rubro }}</td>
                    <td style="font-size: 10pt; font-weight: bold; background-color: #D4D5D5;">{{ strtoupper($rubro->etiqueta_rubro) }}</td>
                    <td style="font-size: 10pt; font-weight: bold; background-color: #D4D5D5; width: 215px;" width="215">{{ $rubro->observaciones }}</td>
                </tr>
                @php
                    $j = 1;
                @endphp
                @foreach ($subrubro_inspeccion as $subrubro)
                    @if ($rubro->id_rubro_inspeccion == $subrubro->rubro_id)
                        <tr>
                            <td valign="middle" align="center">{{ $i.'.0'.$j }}</td>
                            <td valign="middle" align="center">{{ $subrubro->subrubro }}</td>
                            <td valign="middle" align="center">{{ $subrubro->puntos_obtenidos }}</td>
                            <td valign="middle" align="center"></td>
                            <td valign="middle" align="center">{{ $subrubro->etiqueta_calificacion }}</td>
                            <td valign="middle" align="center"></td>
                        </tr>
                        @php
                            $j ++;
                        @endphp
                    @endif
                @endforeach
                @php
                    $i ++;
                @endphp
            @endif
        @endforeach
    </tbody>
@endforeach
<tfoot>
    <tr >
        <td valign="middle" align="center" style="font-size: 10pt; font-weight: bold; background-color: #C0C2C2;"></td>
        <td valign="middle" align="center" style="font-size: 10pt; font-weight: bold; background-color: #C0C2C2;">Resultado:</td>
        <td valign="middle" align="center" style="font-size: 10pt; font-weight: bold; background-color: #C0C2C2;">{{ $inspeccion->calificacion_final }}</td>
        <td valign="middle" align="center" style="font-size: 10pt; font-weight: bold; background-color: #C0C2C2;">{{ strtoupper($inspeccion->etiqueta_calificacion) }}</td>
        <td valign="middle" align="center" style="font-size: 10pt; font-weight: bold; background-color: #C0C2C2;"></td>
        <td valign="middle" align="center" style="font-size: 10pt; font-weight: bold; background-color: #C0C2C2;"></td>
    </tr>
</tfoot>
</table>
