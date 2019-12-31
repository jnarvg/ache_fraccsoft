@extends('layouts.admin')
@section('title')
Comisiones
@endsection
@section('filter')
  @if (auth()->user()->rol == 3)
    <a href="#" data-toggle="modal" data-target="#modal_nuevo"><button class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus"></i></button></a>
  @endif
  <button href="#" data-toggle="collapse" data-target="#filtros" role="button" aria-expanded="false" aria-controls="filtros" class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-filter"></i></button>
@endsection
@section('content')
<div class="content mt-3">
  <div class="card show" id="filtros">
    <div class="card-body">
      <div class="row">
        <div class="col-lg-12">
        {!! Form::open(array('route'=>'comision', 'method'=>'get', 'autocomplete'=>'off')) !!}
          <div class="input-group md-form form-sm form-4 pl-0">

            <input type="text" class="form-control" placeholder="Cliente" name="cliente_bs" id="cliente_bs" value="{{ $request->cliente_bs }}">

            <input type="text" class="form-control" placeholder="Propiedad" name="propiedad_bs" id="propiedad_bs" value="{{ $request->propiedad_bs
             }}">

            <select class="form-control" name="estatus_pago_bs" id="estatus_pago_bs">
              <option value="">Estatus...</option>
              @foreach ($estatus as $rp)
              @if ($rp == $request->estatus_pago_bs)
              <option selected value="{{ $rp }}">{{ $rp }}</option>
              @else
              <option value="{{ $rp }}">{{ $rp }}</option>
              @endif
              @endforeach
            </select>
            <select class="form-control" name="rows_per_page" id="rows_per_page">
              @foreach ($rows_pagina as $rp)
              @if ($rp == $request->rows_per_page)
              <option selected value="{{ $rp }}">{{ $rp }}</option>
              @else
              <option value="{{ $rp }}">{{ $rp }}</option>
              @endif
              @endforeach
            </select>
            <button type="submit" class="btn btn-info" ><span class="glyphicon"><i class="fas fa-search"></i></span></button>
          </div>
        {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
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
      <div class="table-responsive ">
        <table class="table table-hover table-withoutborder text-sm">
          <thead class="thead-grubsa ">
            <th class="center">
              Id
            </th>
            <th class="center">
              Cliente
            </th>
            <th class="center">
              Propiedad
            </th>
            <th class="center">
              Estatus pago
            </th>
            <th class="center">
              Fecha venta
            </th>
            <th class="center">
              Monto operacion
            </th>
            <th class="center">
              Comision
            </th>
            <th class="center">
              Saldo
            </th>
            <th class="center">
              Acciones
            </th>
          </thead>
          <tbody>
            @foreach ($comisiones as $comision)                
            <tr>
              <td class="center">
                {{ $comision->id_comision }}
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ComisionController@show', $comision->id_comision)}}" >
                {{ $comision->prospecto}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ComisionController@show', $comision->id_comision)}}" >
                {{ $comision->propiedad}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ComisionController@show', $comision->id_comision)}}" >
                {{ $comision->estatus_pago}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ComisionController@show', $comision->id_comision)}}" >
                {{ $comision->fecha_venta}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ComisionController@show', $comision->id_comision)}}" >
                ${{ number_format($comision->monto_operacion , 2 , "." , ",") }} </a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ComisionController@show', $comision->id_comision)}}" >
                ${{ number_format($comision->comision_total , 2 , "." , ",") }}</a>
              </td>
              <td class="center">
                <a class="text-dark text-sm" href="{{URL::action('ComisionController@show', $comision->id_comision)}}" >
                ${{ number_format($comision->saldo_comision , 2 , "." , ",") }}</a>
              </td>
              <td class="center-acciones">
                <a href="{{URL::action('ComisionController@show', $comision->id_comision)}}" ><button class="btn-ico"><i class="fas fa-pencil-alt"></i></button></a>
                @if (auth()->user()->rol == '3')
                  <a href="{{URL::action('ComisionController@destroy', $comision->id_comision)}}" ><button class="btn-ico"><i class="fas fa-times"></i></button></a>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  {{$comisiones->appends(Request::only('estatus_bs','estatus_pago_bs','cliente_bs','propiedad_bs','rows_per_page'))->render()}}
</div>
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal_nuevo">
  {{ Form::open(array('action'=>array('ComisionController@store'),'method'=>'post')) }}
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title text-dark">Nueva comision</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="nuevo_cliente">*Cliente</label>
                          <select class="form-control" id="nuevo_cliente" name="nuevo_cliente" required="true">
                            <option value="" disabled="true" selected="true">Seleccione...</option>
                            @foreach ($clientes as $cliente)
                              <option value="{{ $cliente->id_prospecto }}">{{ $cliente->nombre }}</option>
                            @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="nuevo_propiedad">*Propiedad</label>
                          <select class="form-control" id="nuevo_propiedad" name="nuevo_propiedad" required="true">
                            <option value="" disabled="true" selected="true">Seleccione...</option>
                            @foreach ($propiedades as $propiedad)
                              <option value="{{ $propiedad->id_propiedad }}">{{ $propiedad->nombre }}</option>
                            @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="esquema_comision">*Esquema de comision</label>
                          <select class="form-control" id="esquema_comision" name="esquema_comision" required="true">
                            <option value="" disabled="true" selected="true">Seleccione...</option>
                            @foreach ($esquema_comision as $esquema)
                              <option value="{{ $esquema->id_esquema_comision }}">{{ $esquema->esquema_comision }}</option>
                            @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="nuevo_monto">*Monto operacion</label>
                          <input type="text" name="nuevo_monto" id="nuevo_monto" value="" step="any" class="mask form-control" required="true"/>
                      </div>
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="nuevo_fecha_venta">*Fecha de venta</label>
                          <input type="date" name="nuevo_fecha_venta" id="nuevo_fecha_venta" value=""  class="letrasModal form-control" required="true"/>
                      </div>
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="nuevo_estatus">*Estatus</label>
                          <input type="text" name="nuevo_estatus" id="nuevo_estatus" value="En aprobacion"  class="letrasModal form-control" required="true" readonly="true"/>
                      </div>
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="nuevo_estatus_pago">*Estatus pago</label>
                          <input type="text" name="nuevo_estatus_pago" id="nuevo_estatus_pago" value="Pendiente pago"  class="letrasModal form-control" required="true" readonly="true" />
                      </div>
                  </div>              
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-info">Confirmar</button>
          </div>
      </div>
  </div>
  {{ Form::close()}}
      
</div>
@push('scripts')
<script >
   jQuery(document).ready(function($)
    {
      $("#nuevo_cliente").on('change', function(){
        cliente = $('#nuevo_cliente').val();
        selectPropiedad = $('#nuevo_propiedad');
        selectPropiedad.empty().append('<option>Cargando las propiedades</option>');


        $.ajax({

        type: "GET",
        url: "/catalogo-prospecto/" + cliente,
        success: function(data) {
              var htmlOptions = [];
              if( data.length ){
                  for( item in data ) {
                    //en caso de ser un select
                    html = '<option selected="true" value="' + data[item].propiedad_id + '">' + data[item].nombre_propiedad + '</option>';
                    htmlOptions[htmlOptions.length] = html;
                    fecha_venta = data[item].fecha_venta
                    monto = data[item].monto_venta
                  }

                  //en caso de ser un input
                  $("#nuevo_monto").val(monto);
                  $("#nuevo_fecha_venta").val(fecha_venta);
                  
                  // se agregan las opciones del catalogo en caso de ser un select 
                  selectPropiedad.empty().append( htmlOptions.join('') );
              }else{
                html = '<option value="" disabled="true" selected="true">No hay propiedades</option>';
                htmlOptions[htmlOptions.length] = html;
                selectPropiedad.empty().append( htmlOptions.join('') );
              }
        },
          error: function(error) {
          alert("No se pudo cargar el catalogo de propiedades");
        }
        })

      });
    });
</script>
@endpush 
@endsection