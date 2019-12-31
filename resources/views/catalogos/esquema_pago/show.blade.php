@extends('layouts.admin')
@section('title')
Esquema de pago
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            {{ Form::open(array('action'=>array('EsquemaPagoController@update',$resultado->id_esquema_pago),'method'=>'post')) }}
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="esquema_pago">*Esquema pago</label>
                        <input type="text" name="esquema_pago" id="esquema_pago" value="{{ $resultado->esquema_pago }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="created_date">*Fecha</label>
                        <input type="date" name="created_date" id="created_date" value="{{ date('Y-m-d',strtotime($resultado->created_at)) }}"  class="letrasModal form-control" readonly="true" required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="porcentaje_descuento">*% Descuento</label>
                        <input type="number" name="porcentaje_descuento" id="porcentaje_descuento" value="{{ $resultado->porcentaje_descuento }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="uso_propiedad_id">*Uso de propiedad</label>
                        <select name="uso_propiedad_id" id="uso_propiedad_id"  class="letrasModal form-control" required="true">
                            @foreach ($usos_propiedad as $r)
                                @if ($r->id_uso_propiedad == $resultado->uso_propiedad_id)
                                    <option selected="true" value="{{ $r->id_uso_propiedad }}">{{ $r->uso_propiedad }}</option>
                                @else
                                    <option value="{{ $r->id_uso_propiedad }}">{{ $r->uso_propiedad }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="proyecto_id">*Proyecto</label>
                        <select name="proyecto_id" id="proyecto_id"  class="letrasModal form-control" required="true">
                            @foreach ($proyectos as $r)
                                @if ($r->id_proyecto == $resultado->proyecto_id)
                                    <option selected="true" value="{{ $r->id_proyecto }}">{{ $r->nombre }}</option>
                                @else
                                    <option value="{{ $r->id_proyecto }}">{{ $r->nombre }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="grupo_esquema_id">*Grupo esquema</label>
                        <select name="grupo_esquema_id" id="grupo_esquema_id"  class="letrasModal form-control" required="true">
                            @foreach ($grupo_esquema as $r)
                                @if ($r->id_grupo_esquema == $resultado->grupo_esquema_id)
                                    <option selected="true" value="{{ $r->id_grupo_esquema }}">{{ $r->grupo_esquema }}</option>
                                @else
                                    <option value="{{ $r->id_grupo_esquema }}">{{ $r->grupo_esquema }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="incluir">*Incluir por default</label>
                        <div class="custom-control custom-switch">
                          @if ($resultado->incluir == 0)
                            <input type="checkbox" class="custom-control-input" id="incluir" name="incluir">
                          @elseif ($resultado->incluir == 1)
                            <input type="checkbox" class="custom-control-input" id="incluir" name="incluir" checked>
                          @else
                            <input type="checkbox" class="custom-control-input" id="incluir" name="incluir">
                          @endif
                          <label class="custom-control-label" for="incluir"> en porcentaje</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 offset-md-4">
                    <div class="form-group">
                      @if ($procedencia == 'Menu')
                        <a href="{{ route('esquema_pago') }}" class="btn btn-dark btn-block">CANCELAR</a>
                      @else
                        <a href="{{ route($procedencia, $resultado->grupo_esquema_id) }}" class="btn btn-dark btn-block">CANCELAR</a>
                      @endif
                    </div>
                </div> 
                <div class="col-md-2">
                    <div class="form-group">
                        <button type="submit" class="btn btn-info btn-block">GUARDAR</button>
                    </div>
                </div>
            </div>
            {{ Form::close()}}
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <div class="row justify-content-between">
                              <div class="col-md-8">Detalle esquemas de pago
                              </div>
                              <div class="col-md-4 mb-0 " align="right">
                                <a href="" id="btnplusactividad" data-toggle="modal" data-target="#modal-new" class="mb-0 d-sm-inline-block btn-ico-dark text-xl"><i class="fas fa-plus"></i></a>
                              </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive ">
                                <table class="table table-hover table-withoutborder text-sm" id="tabla_actividades">
                                  <thead class="thead-grubsa ">
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
                                    <th class="center oculto">
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
                                        $procedencia = 'esquema_pago-show';
                                        $sumaporcentaje = 0;
                                    @endphp
                                    @foreach ($resultados_detalle as $r)
                                    @php
                                        $sumaporcentaje = $sumaporcentaje + $r->porcentaje;
                                    @endphp             
                                    <tr>
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
                                      <td class="center oculto">
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
                                        <a href="{{URL::action('DetalleEsquemaPagoController@show', [$r->id_detalle_esquema_pago, $procedencia])}}" ><button class="btn-ico"><i class="fas fa-pencil-alt"></i></button></a>
                                        <a href="#" data-target="#modal-delete{{$r->id_detalle_esquema_pago}}" data-toggle="modal"><button class="btn-ico"><i class="fas fa-trash"></i></button></a>
                                      </td>
                                    </tr>
                                    @include('catalogos.detalle_esquema_pago.modal')
                                    @endforeach
                                  </tbody>
                                  <tfoot>
                                    <tr>
                                      <th>
                                      </th>
                                      <th>
                                      </th>
                                      <th>
                                      </th>
                                      @if ($sumaporcentaje == 100)
                                        <th class="center" style="background: #FFF; color: #3E3E3E;">
                                          {{ number_format($sumaporcentaje , 2 , "." , ",") }}%
                                        </th>
                                      @else
                                        <th class="center" style="background: #B41811; color: #FFF;">
                                          {{ number_format($sumaporcentaje , 2 , "." , ",") }}%
                                        </th>
                                      @endif
                                      <th class="oculto">
                                      </th>
                                      <th>
                                      </th>
                                      <th>
                                      </th>
                                      <th>
                                      </th>
                                    </tr>
                                  </tfoot>
                                </table>
                            </div>                           
                        </div>
                    </div>
                </div>
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
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="mensualidad">Mensualidades</label>
                          <input type="number" name="mensualidad" id="mensualidad" value="0"  class=" form-control" />
                      </div>
                  </div>   
                  <input type="hidden" name="esquema_pago_id" id="esquema_pago_id" value="{{ $resultado->id_esquema_pago}}" class="letrasModal form-control" required="true" />
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
        $('#tabla_actividades').DataTable();
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