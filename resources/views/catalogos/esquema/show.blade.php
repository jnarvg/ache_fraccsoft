@extends('layouts.admin')
@section('title')
Esquemas de comision
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            {{ Form::open(array('action'=>array('EsquemaComisionController@update',$esquema_comision->id_esquema_comision),'method'=>'post')) }}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="esquema_comision">*Esquema de comision</label>
                        <input type="text" name="esquema_comision" id="esquema_comision" value="{{ $esquema_comision->esquema_comision }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
            </div>
            <div class="row">    
                <div class="col-md-3 offset-md-2">
                    <div class="form-group">
                        <a href="{{ route('esquema') }}" class="btn btn-dark btn-block">REGRESAR</a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <button type="submit" class="btn btn-info btn-block">ACTUALIZAR</button>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a href="#" data-toggle="modal" data-target="#modal_detalle_esquema"><button class="btn btn-primary btn-block">NUEVO</button></a>
                    </div>
                </div> 
            </div>
            {{ Form::close()}}
            <div class="row">
                <div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal_detalle_esquema">
                  {{ Form::open(array('action'=>array('DetalleEsquemaComisionController@store',$esquema_comision->id_esquema_comision),'method'=>'post')) }}
                  <div class="modal-dialog" role="document">
                      <div class="modal-content">
                          <div class="modal-header">
                              <h4 class="modal-title text-dark">Nuevo esquema detalle</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                          </div>
                          <div class="modal-body">
                              <div class="row">
                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <label for="nuevo_rubro_mdl">*Rubro</label>
                                          <input type="text" name="nuevo_rubro_mdl" id="nuevo_rubro_mdl" value=""  class="letrasModal form-control" required="true" />
                                      </div>
                                  </div>
                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <label for="nuevo_factor_mdl">*Factor</label>
                                          <input type="number" name="nuevo_factor_mdl" step="any" id="nuevo_factor_mdl" value=""  class="letrasModal form-control" required="true" />
                                      </div>
                                  </div>
                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <label for="nuevo_tipo_mdl">*Tipo</label>
                                          <select name="nuevo_tipo_mdl" id="nuevo_tipo_mdl" value=""  class="letrasModal form-control" required="true">
                                            <option value="Asesor">Asesor</option>
                                            <option value="Cerrador">Cerrador</option>
                                            <option value="Externo">Externo</option>
                                            <option value="Otro">Otro</option>
                                          </select>
                                      </div>
                                  </div>
                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <label for="nuevo_usuario_mdl">*Usuario</label>
                                          <select name="nuevo_usuario_mdl" id="nuevo_usuario_mdl" value=""  class="letrasModal form-control">
                                          <option value="sin" selected="true" disabled="true">Selecciona...</option>
                                          @foreach ($usuarios as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                          @endforeach
                    
                                          </select>
                                      </div>
                                  </div>
                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <label for="nuevo_persona_mdl">*Persona</label>
                                          <input type="text" name="nuevo_persona_mdl" id="nuevo_persona_mdl" value=""  class="letrasModal form-control" required="true" />
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
            </div>
            <div class="card">
              <div class="card-header bg-primary text-white">
                Detalle de esquema
              </div>
              <div class="card-body">
                <div class="table-responsive ">
                  <table class="table table-hover table-withoutborder text-sm">
                    <thead class="thead-grubsa ">
                      <th class="center">
                        Id
                      </th>
                      <th class="center">
                        Rubro
                      </th>
                      <th class="center">
                        Factor
                      </th>
                      <th class="center">
                        Tipo
                      </th>
                      <th class="center">
                        Persona
                      </th>
                      <th class="center">
                        Acciones
                      </th>
                    </thead>
                    <tbody>
                      @foreach ($detalles_comisiones as $detalle)                
                      <tr>
                        <td class="center">
                          {{ $detalle->id_detalle_esquema_comision }}
                        </td>
                        <td class="center">
                          <a class="text-dark" href="{{URL::action('DetalleEsquemaComisionController@show', $detalle->id_detalle_esquema_comision)}}" >
                          {{ $detalle->rubro}}</a>
                        </td>
                        <td class="center">
                          <a class="text-dark" href="{{URL::action('DetalleEsquemaComisionController@show', $detalle->id_detalle_esquema_comision)}}" >
                          {{ $detalle->factor}}</a>
                        </td>
                        <td class="center">
                          <a class="text-dark" href="{{URL::action('DetalleEsquemaComisionController@show', $detalle->id_detalle_esquema_comision)}}" >
                          {{ $detalle->tipo}}</a>
                        </td>
                        <td class="center">
                          <a class="text-dark" href="{{URL::action('DetalleEsquemaComisionController@show', $detalle->id_detalle_esquema_comision)}}" >
                          {{ $detalle->persona}}</a>
                        </td>
                        <td class="center-acciones">
                          <a href="{{URL::action('DetalleEsquemaComisionController@show', $detalle->id_detalle_esquema_comision)}}" ><button class="btn-ico" ><i class="fas fa-pencil-alt"></i></button></a>
                          @if (auth()->user()->rol == 3)
                            <a href="{{URL::action('DetalleEsquemaComisionController@destroy', $detalle->id_detalle_esquema_comision)}}" ><button class="btn-ico" ><i class="fas fa-times"></i></button></a>
                          @endif
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            
            {{$detalles_comisiones->render()}}
        </div>
    </div>
</div>

@push('scripts')
<script >
  jQuery(document).ready(function($)
    {

      $("#nuevo_usuario_mdl").on('change', function(){
          $("#nuevo_persona_mdl").val( $("#nuevo_usuario_mdl option:selected").text());

      });
  });
</script>
@endpush 
@endsection