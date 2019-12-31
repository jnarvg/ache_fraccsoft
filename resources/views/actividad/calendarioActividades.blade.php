@extends('layouts.admin')
@section('css')
  <style type="text/css">
      #calendar {
        justify-content: center;
        align-items: center;
      }
      .fc-unthemed .fc-today{
        background: #e3e3e6;
      }
  </style>
    
    <link rel="stylesheet" href="{{ asset('//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css') }}"/>
@endsection
@section('title')
Calendario de Actividades
@endsection
@section('filter')
  <a href="#" data-toggle="modal" data-target="#modal-actividad"><button class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus"></i></button></a>
  <button href="#" data-toggle="collapse" data-target="#filtros" role="button" aria-expanded="false" aria-controls="filtros" class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-filter"></i></button>
@endsection
@section('content')
  <div class="container">
    <div class="card show" id="filtros">
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
                {{ Form::open(array('action'=>array('ActividadController@calendario'),'method'=>'get')) }}
                  <div class="input-group md-form form-sm form-2 pl-0">
                    <input class="form-control my-0 py-1 amber-border" type="text" placeholder="Titulo" aria-label="Titulo" id="titulo_bs" name="titulo_bs" value="{{ $request->titulo_bs }}">
                    <input class="form-control my-0 py-1 amber-border" type="text" placeholder="Prospecto" aria-label="Prospecto" id="prospecto_bs" name="prospecto_bs" value="{{ $request->prospecto_bs }}">
                    <input class="form-control my-0 py-1 amber-border" type="text" placeholder="Tipo de actividad" aria-label="Tipo de actividad" id="tipo_bs" name="tipo_bs" value="{{ $request->tipo_bs }}">
                    <input class="form-control my-0 py-1 amber-border" type="text" placeholder="Estatus" aria-label="Estatus" id="estatus_bs" name="estatus_bs" value="{{ $request->estatus_bs }}">
                    <span>
                      <button type="submit" class="btn btn-info"><i class="fas fa-search"></i></button>
                    </span>
                  </div>
                {{ Form::close()}}
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card mt-3">
      <div class="card-body">
        <div class="row" id="calendar">
          {!! $calendar->calendar() !!}
        </div>
      </div>
    </div>
  </div>
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-actividad">
  {{ Form::open(array('action'=>array('ActividadController@storec'),'method'=>'post')) }}
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-dark">Nueva actividad</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nuevo_actividad_mdl">*Titulo</label>
                            <input type="text" name="nuevo_actividad_mdl" id="nuevo_actividad_mdl" value=""  class="letrasModal form-control" required="true" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nuevo_tipo_mdl">*Tipo de actividad</label>
                            <select name="nuevo_tipo_mdl" id="nuevo_tipo_mdl" class="letrasModal form-control" required="true">
                              <option value="Tarea">Tarea</option>
                              <option value="Llamada">Llamada</option>
                              <option value="Cita">Cita</option>
                              <option value="Correo">Correo</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nuevo_fecha_mdl">*Fecha</label>
                            <input type="date" name="nuevo_fecha_mdl" id="nuevo_fecha_mdl" value="{{ date('Y-m-d') }}"  class="letrasModal form-control" required="true" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nuevo_hora_mdl">*Hora</label>
                            <input type="time" name="nuevo_hora_mdl" id="nuevo_hora_mdl" value="{{ date('H:i:s') }}"  class="letrasModal form-control" required="true" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nuevo_fecha_recordatorio_mdl">*Fecha recordatorio</label>
                            <input type="date" name="nuevo_fecha_recordatorio_mdl" id="nuevo_fecha_recordatorio_mdl" value="{{ date('Y-m-d') }}"  class="letrasModal form-control" required="true" />
                        </div>
                    </div>
                    <div class="col-md-6 oculto">
                        <div class="form-group">
                            <input type="hidden" name="nuevo_duracion_mdl" id="nuevo_duracion_mdl" step="any"  value="1" class="letrasModal form-control" required="true" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nuevo_prospecto_mdl">*Prospecto</label>
                            <select name="nuevo_prospecto_mdl" id="nuevo_prospecto_mdl" class="letrasModal form-control">
                              <option>Selecciona...</option>
                              @foreach ($prospectos as $pros)
                                <option value="{{ $pros->id_prospecto }}">{{ $pros->nombre }}</option>
                              @endforeach
                            </select>
                        </div>
                    </div> 
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="nuevo_desc_mdl">Descripcion</label>
                            <textarea name="nuevo_desc_mdl" id="nuevo_desc_mdl" value=""  class="letrasModal form-control"></textarea>
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
    <script src="{{ asset('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js') }}"></script>
    <script src="{{ asset('//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js') }}"></script>
    {!! $calendar->script() !!}
@endpush 
@endsection