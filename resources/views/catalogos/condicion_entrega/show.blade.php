@extends('layouts.admin')
@section('title')
Condiciones de entrega
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            {{ Form::open(array('action'=>array('CondicionEntregaController@update',$resultado->id_condicion_entrega),'method'=>'post')) }}
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="titulo">*Titulo</label>
                        <input type="text" name="titulo" id="titulo" value="{{ $resultado->titulo }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="created_date">*Fecha</label>
                        <input type="date" name="created_date" id="created_date" value="{{ date('Y-m-d',strtotime($resultado->created_date)) }}"  class="letrasModal form-control" readonly="true" required="true" />
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
                
                <div class="col-md-2 offset-md-4">
                    <div class="form-group">
                        <a href="{{ route('condicion_entrega') }}" class="btn btn-dark btn-block">CANCELAR</a>
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
                              <div class="col-md-8">Detalle condiciones de entrega
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
                                      Condicion
                                    </th>
                                    <th class="center">
                                      Tipo
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
                                        $procedencia = 'condicion_entrega-show';
                                    @endphp
                                    @foreach ($condicion_entrega_detalle as $r)               
                                    <tr>
                                      <td>
                                        <a class="text-dark" href="{{URL::action('CondicionEntregaDetalleController@show', [$r->id_condicion_entrega_detalle, $procedencia])}}">
                                            {{ $r->condicion }}
                                        </a>
                                      </td>
                                      <td class="center">
                                        <a class="text-dark" href="{{URL::action('CondicionEntregaDetalleController@show', [$r->id_condicion_entrega_detalle, $procedencia])}}">
                                            {{ $r->tipo }}
                                        </a>
                                      </td>
                                      <td class="center">
                                        <a class="text-dark" href="{{URL::action('CondicionEntregaDetalleController@show', [$r->id_condicion_entrega_detalle, $procedencia])}}">
                                            {{ date('Y-M-d', strtotime($r->modified_date)) }}
                                        </a>
                                      </td>
                                      <td class="center-acciones">
                                        <a href="{{URL::action('CondicionEntregaDetalleController@show', [$r->id_condicion_entrega_detalle, $procedencia])}}"  style="width: 30%;"><button class="btn-ico"><i class="fas fa-pencil-alt"></i></button></a>
                                        <a href="#" data-target="#modal-delete{{$r->id_condicion_entrega_detalle}}" data-toggle="modal" style="width: 30%;"><button class="btn-ico"><i class="fas fa-trash"></i></button></a>
                                      </td>
                                    </tr>
                                    @include('catalogos.condicion_entrega_detalle.modal')
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
    {{ Form::open(array('action'=>array('CondicionEntregaDetalleController@store'),'method'=>'post' )) }}
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
                          <label for="condicion">*Condicion</label>
                          <input type="text" name="condicion" id="condicion" value=""  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="tipo">*Tipo</label>
                          <select name="tipo" id="tipo"  class="letrasModal form-control" required="true"> 
                              @foreach ($tipo_condicion as $e)
                                <option value="{{ $e }}">{{ $e }} </option>
                              @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-md-6 ocultar">
                      <div class="form-group">
                          <label for="subtitulo">Subtitulo</label>
                          <input type="text" name="subtitulo" id="subtitulo" value=""  class="letrasModal form-control" />
                      </div>
                  </div>
                  <input type="hidden" name="proyecto_id" id="proyecto_id" value="{{ $resultado->proyecto_id }}" class="letrasModal form-control" required="true" />
                  <input type="hidden" name="uso_propiedad_id" id="uso_propiedad_id" value="{{ $resultado->uso_propiedad_id}}" class="letrasModal form-control" required="true" />        
                  <input type="hidden" name="condicion_entrega_id" id="condicion_entrega_id" value="{{ $resultado->id_condicion_entrega}}" class="letrasModal form-control" required="true" />
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
            tipo = $('#tipo').val();
        if (tipo == 'Agregado') {
            $('.ocultar').removeClass('oculto');
        }else{
            $('.ocultar').addClass('oculto');
        }
        $("#tipo").on('change', function(){
            tipo = $('#tipo').val();
            if (tipo == 'Agregado') {
                $('.ocultar').removeClass('oculto');
            }else{
                $('.ocultar').addClass('oculto');
            }
        });
    });
</script>
@endpush 
@endsection