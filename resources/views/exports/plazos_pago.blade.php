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
                <td valign="middle" align="center">{{$r->id_plazo_pago }} </td>
                <td valign="middle" align="center">{{$r->prospecto_id }} </td>
                <td valign="middle" align="center">{{$r->fecha }} </td>
                <td valign="middle" align="center">{{$r->estatus }} </td>
                <td valign="middle" align="center">{{$r->num_plazo }} </td>
                <td valign="middle" align="center">{{$r->total }} </td>
                <td valign="middle" align="center">{{$r->saldo }} </td>
                <td valign="middle" align="center">{{$r->pagado }} </td>
                <td valign="middle" align="center">{{$r->dias_retraso }} </td>
                <td valign="middle" align="center">{{$r->monto_mora }} </td>
                <td valign="middle" align="center">{{$r->notas }} </td>
                <td valign="middle" align="center">{{$r->deuda }} </td>
                <td valign="middle" align="center">{{$r->amortizacion }} </td>
                <td valign="middle" align="center">{{$r->capital_inicial }} </td>
                <td valign="middle" align="center">{{$r->capital }} </td>
                <td valign="middle" align="center">{{$r->interes }} </td>
                <td valign="middle" align="center">{{$r->interes_acumulado }} </td>
                <td valign="middle" align="center">{{$r->capital_acumulado }} </td>
                <td valign="middle" align="center">{{$r->total_acumulado }} </td>
                <td valign="middle" align="center">{{$r->moneda_id }} </td>
                <td valign="middle" align="center">{{$r->descripcion }} </td>
                <td valign="middle" align="center">{{$r->tipo }} </td>
            </tr>
        @endforeach
    </tbody>
</table>