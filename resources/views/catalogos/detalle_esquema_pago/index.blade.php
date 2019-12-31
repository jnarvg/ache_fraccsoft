@extends('layouts.admin')
@section('title')
Detalle esquema de pago
@endsection
@section('filter')
  @if (auth()->user()->rol == 3)
  <a href="#" data-toggle="modal" data-target="#modal-new"><button class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus"></i></button></a>
  @endif
  <button href="#" data-toggle="collapse" data-target="#filtros" role="button" aria-expanded="false" aria-controls="filtros" class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-filter"></i></button>
@endsection
@section('content')
<div class="content mt-3">
  <div class="card collapse" id="filtros">
    <div class="card-body">
      {!! Form::open(array('route'=>'detalle_esquema_pago', 'method'=>'get', 'autocomplete'=>'off')) !!}
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="alias_bs">Alias</label>
            <input type="text" class="form-control" placeholder="Buscar..." name="alias_bs" id="alias_bs" value="{{ $request->alias_bs }}">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="tipo_bs">Tipo</label>
            <select class="form-control" id="tipo_bs" name="tipo_bs">
              <option value=""></option>
              @foreach ($tipo as $e)
                @if ($e == $request->tipo_bs)
                <option selected value="{{ $e }}">{{ $e }}</option>
                @else
                <option value="{{ $e }}">{{ $e }}</option>
                @endif
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="tipo_calculo_bs">Tipo de calculo</label>
            <select class="form-control" id="tipo_calculo_bs" name="tipo_calculo_bs">
              <option value=""></option>
              @foreach ($tipo_calculo as $e)
                @if ($e == $request->tipo_calculo_bs)
                <option selected value="{{ $e }}">{{ $e }}</option>
                @else
                <option value="{{ $e }}">{{ $e }}</option>
                @endif
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <button type="submit" class="btn btn-info" ><span class="glyphicon"><i class="fas fa-search"></i></span></button>
          </div>
        </div>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
  <div class="card">
    <div class="card-body">
      <div class="table-responsive ">
        <table class="table table-hover table-withoutborder text-sm" id="tabla">
          <thead class="thead-grubsa ">
            <th class="center">
              ID
            </th>
            <th class="center">
              Alias
            </th>
            <th class="center">
              Tipo
            </th>
            <th class="center">
              Tipo calculo
            </th>
            <th class="center">
              Porcentaje
            </th>
            <th class="center">
              Monto
            </th>
            <th class="center">
              Mensualidades
            </th>
            <th class="center">
              Fecha
            </th>
            <th class="center">
             Acciones
            </th>
          </thead>
          <tbody>
            @php
                $procedencia = 'Menu';
            @endphp
            @foreach ($resultados as $r)               
            <tr>
              <td>
                <a class="text-dark" href="{{URL::action('DetalleEsquemaPagoController@show', [$r->id_detalle_esquema_pago, $procedencia])}}">
                    {{ $r->id_detalle_esquema_pago }}
                </a>
              </td>
              <td>
                <a class="text-dark" href="{{URL::action('DetalleEsquemaPagoController@show', [$r->id_detalle_esquema_pago, $procedencia])}}">
                    {{ $r->alias }}
                </a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('DetalleEsquemaPagoController@show', [$r->id_detalle_esquema_pago, $procedencia])}}">
                    {{ $r->tipo }}
                </a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('DetalleEsquemaPagoController@show', [$r->id_detalle_esquema_pago, $procedencia])}}">
                    {{ $r->tipo_calculo }}
                </a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('DetalleEsquemaPagoController@show', [$r->id_detalle_esquema_pago, $procedencia])}}">
                    {{ number_format($r->porcentaje , 2 , "." , ",") }}%
                </a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('DetalleEsquemaPagoController@show', [$r->id_detalle_esquema_pago, $procedencia])}}">
                    ${{ number_format($r->monto , 2 , "." , ",") }}
                </a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('DetalleEsquemaPagoController@show', [$r->id_detalle_esquema_pago, $procedencia])}}">
                    {{ $r->mensualidades }}
                </a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('DetalleEsquemaPagoController@show', [$r->id_detalle_esquema_pago, $procedencia])}}">
                    {{ date('Y-M-d', strtotime($r->created_at)) }}
                </a>
              </td>
              <td class="center-acciones">
                <a href="{{URL::action('DetalleEsquemaPagoController@show', [$r->id_detalle_esquema_pago, $procedencia])}}"  style="width: 30%;"><button class="btn-ico"><i class="fas fa-pencil-alt"></i></button></a>
                <a href="#" data-target="#modal-delete{{$r->id_detalle_esquema_pago}}" data-toggle="modal" style="width: 30%;"><button class="btn-ico"><i class="fas fa-trash"></i></button></a>
              </td>
            </tr>
            @include('catalogos.detalle_esquema_pago.modal')
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-new">
  {{ Form::open(array('action'=>array('DetalleEsquemaPagoController@store'),'method'=>'post' )) }}
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title text-dark">Nuevo</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="alias">*Alias</label>
                          <input type="text" name="alias" id="alias" value=""  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="tipo">*Tipo</label>
                          <select name="tipo" id="tipo"  class="letrasModal form-control" required="true"> 
                              @foreach ($tipo as $e)
                                <option value="{{ $e }}">{{ $e }} </option>
                              @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="tipo_calculo">*Tipo calculo</label>
                          <select name="tipo_calculo" id="tipo_calculo"  class="letrasModal form-control" required="true"> 
                              @foreach ($tipo_calculo as $e)
                                <option value="{{ $e }}">{{ $e }} </option>
                              @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-md-6 oculto" id="porcentaje_ocultar">
                      <div class="form-group">
                          <label for="porcentaje">Porcentaje</label>
                          <input type="number" step="any" name="porcentaje" id="porcentaje" value=""  class=" form-control" />
                      </div>
                  </div>
                  <div class="col-md-6 oculto" id="monto_ocultar">
                      <div class="form-group">
                          <label for="monto">Monto</label>
                          <input type="text" name="monto" id="monto" value=""  class="mask form-control" />
                      </div>
                  </div>
                  <div class="col-md-6 oculto" id="porcentaje_ocultar">
                      <div class="form-group">
                          <label for="mensualidad">Mensualidades</label>
                          <input type="number" name="mensualidad" id="mensualidad" value="0"  class=" form-control" />
                      </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                        <label for="esquema_pago_id">*Esquema de pago</label>
                        <select name="esquema_pago_id" id="esquema_pago_id"  class="letrasModal form-control" required="true">
                            @foreach ($esquemas as $r)
                                <option value="{{ $r->id_esquema_pago }}">{{ $r->esquema_pago }}</option>
                            @endforeach
                        </select>
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
<script type="text/javascript">
  jQuery(document).ready(function($)
    {
        $('#tabla').DataTable();
            tipo_calculo = $('#tipo_calculo').val();
        if (tipo_calculo == "Porcentaje") {
            $('#porcentaje_ocultar').removeClass('oculto');
            $('#monto_ocultar').addClass('oculto');
        }else if(tipo_calculo == 'Monto'){
            $('#monto_ocultar').removeClass('oculto');
            $('#porcentaje_ocultar').addClass('oculto');
        }
        $("#tipo_calculo").on('change', function(){
            tipo_calculo = $('#tipo_calculo').val();
            if (tipo_calculo == "Porcentaje") {
                $('#porcentaje_ocultar').removeClass('oculto');
                $('#monto_ocultar').addClass('oculto');
            }else if(tipo_calculo == 'Monto'){
                $('#monto_ocultar').removeClass('oculto');
                $('#porcentaje_ocultar').addClass('oculto');
            }
        });
    });
</script>
@endpush 
@endsection