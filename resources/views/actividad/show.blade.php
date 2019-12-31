@extends('layouts.admin')
@section('title')
Actividades
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            {{ Form::open(array('action'=>array('ActividadController@update',$actividad->id_actividad, $procedencia),'method'=>'post')) }}
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="titulo">*Titulo</label>
                        <input type="text" name="titulo" id="titulo" value="{{ $actividad->titulo }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="tipo_actividad">*Tipo de actividad</label>
                        <input type="text" name="tipo_actividad" id="tipo_actividad" value="{{ $actividad->tipo_actividad }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="estatus">*Estatus</label>
                        @if ($actividad->estatus == 'Pendiente')
                            <div class="progress">
                              <div class="progress-bar bg-danger" style="width:70%">{{ $actividad->estatus }}</div>
                            </div>
                        @elseif($actividad->estatus == 'Postergada')
                            <div class="progress">
                              <div class="progress-bar bg-warning" style="width:100%">{{ $actividad->estatus }}</div>
                            </div>
                        @elseif($actividad->estatus == 'Completada')
                            <div class="progress">
                              <div class="progress-bar bg-success" style="width:100%">{{ $actividad->estatus }}</div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="fecha">*Fecha</label>
                        <input type="date" name="fecha" id="fecha" value="{{ date('Y-m-d',strtotime($actividad->fecha)) }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="hora">*Hora</label>
                        <input type="time" name="hora" id="hora" value="{{ date('H:i:s',strtotime($actividad->hora)) }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="fecha_recordatorio">*Fecha recordatorio</label>
                        <input type="date" name="fecha_recordatorio" id="fecha_recordatorio" value="{{ date('Y-m-d',strtotime($actividad->fecha_recordatorio)) }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-3 oculto">
                    <div class="form-group">
                        <input type="hidden" name="duracion" id="duracion" value="{{ $actividad->duracion }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="prospecto_id">Prospecto</label>
                        <select name="prospecto_id" id="prospecto_id" class="letrasModal form-control"  > 
                            <option></option>
                            @foreach ($prospectos as $prospecto)
                                @if ($prospecto->id_prospecto == $actividad->prospecto_id)
                                    <option selected value="{{ $prospecto->id_prospecto }}">{{ $prospecto->nombre }}</option>
                                @else
                                    <option value="{{ $prospecto->id_prospecto }}">{{ $prospecto->nombre }}</option>                 
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="agente_id">*Responsable</label>
                        <select name="agente_id" id="agente_id" class="letrasModal form-control" required="true" disabled> 
                            @foreach ($usuarios as $a)
                                @if ($a->id == $actividad->agente_id)
                                    <option selected value="{{ $a->id }}">{{ $a->name }}</option>
                                @else
                                    <option value="{{ $a->id }}">{{ $a->name }}</option>                 
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="descripcion">Descripcion</label>
                        <textarea name="descripcion" id="descripcion"  class="letrasModal form-control">{{ $actividad->descripcion }}</textarea>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a href="{{ URL::previous() }}" class="btn btn-dark btn-block">CANCELAR</a>
                    </div>
                </div> 
                <div class="col-md-3">
                    <div class="form-group">
                        <button type="submit" class="btn btn-info btn-block">ACTUALIZAR</button>
                    </div>
                </div>
                @if ($actividad->estatus == 'Completada')
                <div class="col-md-3">
                    <div class="form-group">
                        <a href="{{URL::action('ActividadController@pendiente', [$actividad->id_actividad, $procedencia])}}"><button type="button" class="btn btn-danger btn-block">PENDIENTE</button></a>
                    </div>
                </div>
                @else
                    <div class="col-md-3">
                        <div class="form-group">
                            <a href="{{URL::action('ActividadController@postergar', [$actividad->id_actividad,$procedencia])}}"><button type="button" class="btn btn-warning btn-block">POSTERGAR</button></a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <a href="{{URL::action('ActividadController@completar', [$actividad->id_actividad, $procedencia])}}"><button type="button" class="btn btn-success btn-block">COMPLETAR</button></a>
                        </div>
                    </div>
                @endif
                @if (auth()->user()->rol == 3)
                    <div class="col-md-3">
                        <div class="form-group">
                            <a href="#" id="btn_cmabiar" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-cambiar_agente">CAMBIAR ASESOR</a>
                        </div>
                    </div>
                @endif      
            </div>
            {{ Form::close()}}
        </div>
    </div>
</div>
<!-- modal de apartado-->
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-cambiar_agente">
    {{ Form::open(array('action'=>array('ActividadController@cambiar_agente',$actividad->id_actividad, $procedencia),'method'=>'get')) }}
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cambiar actividad de agente</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                        <label for="agente_cambiar">Nuevo agente</label>
                        <select name="agente_cambiar" id="agente_cambiar" class="letrasModal form-control">
                          @foreach ($usuarios as $user)
                              @if ($actividad->agente_id == $user->id)
                                  <option selected="true" value="{{ $user->id }}">{{ $user->name }}</option>
                              @else
                                  <option value="{{ $user->id }}">{{ $user->name }}</option>
                              @endif
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
@endpush 
@endsection