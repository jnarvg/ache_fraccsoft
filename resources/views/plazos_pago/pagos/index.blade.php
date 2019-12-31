@extends('layouts.admin')
@section('title')
Pagos de plazo
@endsection
@section('content')
<div class="content mt-3">
  @php
    $procedencia = 'Menu';
  @endphp
  <div class="card">
    <div class="card-body">
      @if (session()->has('msj'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
          <strong>{{ session('msj') }}</strong>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif
      <div class="row">
        <div class="col-md-3 offset-md-9">
          <div class="form-group">
            <a href="#" data-toggle="modal" data-target="#modal-plazo_pago"><button class="btn btn-grubsa btn-block"><i class="fas fa-search"></i></button></a>
          </div>
        </div>
      </div>
      <div class="table-responsive ">
        <table class="table table-hover table-withoutborder text-sm">
          <thead class="thead-grubsa ">
            <th class="center">
              Fecha pago
            </th>
            <th class="center">
              Monto
            </th>
            <th class="center">
              Forma de pago
            </th>
            <th class="center">
              Estatus
            </th>
            <th class="center">
             Acciones
            </th>
          </thead>
          <tbody>
            @foreach ($pagos as $pp)               
              <tr>
                <td class="center">
                  {{ date('Y-m-d',strtotime($pp->fecha)) }}
                </td>
                <td class="center">
                  {{ $pp->monto }}
                </td>
                <td class="center">
                  {{ $pp->forma_pago }}
                </td>
                <td class="center">
                  {{ $pp->estatus }}
                </td>
                <td class="center-acciones">
                  <a href="{{URL::action('PagosController@show', [$pp->id_pago, $procedencia])}}"  style="width: 30%;"><button class="btn-ico" style="margin-left: 2px; margin-right: 2px;"><i class="fas fa-pencil-alt"></i></button></a>
                  @if ($pp->estatus == 'Cancelado')
                    <a href="{{URL::action('PagosController@destroy', [$pp->id_pago, $procedencia])}}"  style="width: 30%;"><button class="btn-ico" style="margin-left: 2px; margin-right: 2px;"><i class="fas fa-trash-alt"></i></button></a>
                  @endif
                </td>
              </tr>
            @endforeach
           </tbody>
        </table>
      </div>
    </div>
  </div>
  {{$pagos->render()}}
</div>

@push('scripts')
@endpush 
@endsection