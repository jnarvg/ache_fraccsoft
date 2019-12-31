@extends('layouts.admin')
@section('title')
Plazos de pago
@endsection
@section('content')
<div class="content mt-3">
  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover table-withoutborder text-sm">
          <thead class="thead-grubsa ">
            <th class="center">
              Num
            </th>
            <th class="center">
              Fecha
            </th>
            <th class="center">
              Estatus
            </th>
            <th class="center">
              Total
            </th>
            <th class="center">
              Saldo
            </th>
            <th class="center">
              Pagado
            </th>
          </thead>
          <tbody >
            @foreach ($plazos_pago as $pp)               
            <tr>
              <td class="center">
                <a href="{{URL::action('ExternosController@showplazo', [$pp->id_plazo_pago])}}">
                    {{ $pp->num_plazo }}
                </a>
              </td>
              <td class="center">
                <a href="{{URL::action('ExternosController@showplazo', [$pp->id_plazo_pago])}}">
                    {{ date('Y-m-d',strtotime($pp->fecha)) }}
                </a>
              </td>
              <td class="center">
                <a href="{{URL::action('ExternosController@showplazo', [$pp->id_plazo_pago])}}">
                    {{ $pp->estatus }}
                </a>
              </td>
              <td class="center">
                <a href="{{URL::action('ExternosController@showplazo', [$pp->id_plazo_pago])}}">
                    $ {{ number_format($pp->total , 2 , "." , ",") }}
                </a>
              </td>
              <td class="center">
                <a href="{{URL::action('ExternosController@showplazo', [$pp->id_plazo_pago])}}">
                    $ {{ number_format($pp->saldo , 2 , "." , ",") }}
                </a>
              </td>
              <td class="center">
                <a href="{{URL::action('ExternosController@showplazo', [$pp->id_plazo_pago])}}">
                    $ {{ number_format($pp->pagado , 2 , "." , ",") }}
                </a>
              </td>
            </tr>
            @endforeach
           </tbody>
        </table>
      </div>
    </div>
  </div>
  {{$plazos_pago->render()}}
</div>

@push('scripts')
@endpush 
@endsection