@extends('layouts.admin')
@section('title')
Grupo esquema de pago
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            {{ Form::open(array('action'=>array('GrupoEsquemaController@update',$resultado->id_grupo_esquema),'method'=>'post')) }}
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="grupo_esquema">*Grupo esquema pago</label>
                        <input type="text" name="grupo_esquema" id="grupo_esquema" value="{{ $resultado->grupo_esquema }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="created_at">*Fecha</label>
                        <input type="date" name="created_at" id="created_at" value="{{ date('Y-m-d',strtotime($resultado->created_at)) }}"  class="letrasModal form-control" readonly="true" required="true" />
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
            </div>
            <div class="row">
                <div class="col-md-2 offset-md-4">
                    <div class="form-group">
                        <a href="{{ route('grupo_esquema') }}" class="btn btn-dark btn-block">CANCELAR</a>
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
                              <div class="col-md-8">Esquemas de pago
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
                                      Esquema pago
                                    </th>
                                    <th class="center">
                                      % Decuento
                                    </th>
                                    <th class="center">
                                      Incluir PDF
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
                                        $procedencia = 'grupo_esquema-show';
                                    @endphp
                                    @foreach ($resultados_detalle as $us)               
                                    <tr>
                                      <td class="center">
                                        <a class="text-dark" href="{{URL::action('EsquemaPagoController@show', [$us->id_esquema_pago, $procedencia])}}">
                                            {{ $us->esquema_pago }}
                                        </a>
                                      </td>
                                      <td class="center">
                                        <a class="text-dark" href="{{URL::action('EsquemaPagoController@show', [$us->id_esquema_pago, $procedencia])}}">
                                            {{ number_format($us->porcentaje_descuento , 2 , "." , ",") }}%
                                        </a>
                                      </td>
                                      <td class="center">
                                        <div class="form-check">
                                          @if ($us->incluir == 1)
                                            <input disabled class="form-check-input" type="checkbox" name="incluir" checked>
                                          @else
                                            <input disabled class="form-check-input" type="checkbox" name="incluir" >
                                          @endif
                                        </div>
                                      </td>
                                      <td class="center">
                                        <a class="text-dark" href="{{URL::action('EsquemaPagoController@show', [$us->id_esquema_pago, $procedencia])}}">
                                            {{ date('Y-M-d', strtotime($us->created_at)) }}
                                        </a>
                                      </td>
                                      <td class="center-acciones">
                                        <a href="{{URL::action('EsquemaPagoController@show', [$us->id_esquema_pago, $procedencia])}}"><button class="btn-ico"><i class="fas fa-pencil-alt"></i></button></a>
                                        <a href="#" data-target="#modal-delete{{$us->id_esquema_pago}}" data-toggle="modal" style="width: 30%;"><button class="btn-ico"><i class="fas fa-trash"></i></button></a>
                                      </td>
                                    </tr>
                                    @include('catalogos.esquema_pago.modal')
                                    @endforeach
                                   </tbody>
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
  {{ Form::open(array('action'=>array('EsquemaPagoController@store'),'method'=>'post' )) }}
    <div class="modal-dialog" role="document">
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
                          <label for="esquema_pago">*Esquema pago</label>
                          <input type="text" name="esquema_pago" id="esquema_pago" value=""  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="porcentaje_descuento">*% Descuento</label>
                          <input type="number" name="porcentaje_descuento" id="porcentaje_descuento" value="0"  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                        <label for="incluir">*Incluir por default</label>
                        <div class="custom-control custom-switch">
                          <input type="checkbox" class="custom-control-input" id="incluir" name="incluir" checked>
                          <label class="custom-control-label" for="incluir"> en porcentaje</label>
                        </div>
                      </div>
                  </div>
                  <input type="hidden" name="proyecto_id" id="proyecto_id" value="{{ $resultado->proyecto_id}}" class="letrasModal form-control" required="true" /> 
                  <input type="hidden" name="uso_propiedad_id" id="uso_propiedad_id" value="{{ $resultado->uso_propiedad_id}}" class="letrasModal form-control" required="true" />
                  <input type="hidden" name="grupo_esquema_id" id="grupo_esquema_id" value="{{ $resultado->id_grupo_esquema}}" class="letrasModal form-control" required="true" />
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
    });
</script>
@endpush 
@endsection