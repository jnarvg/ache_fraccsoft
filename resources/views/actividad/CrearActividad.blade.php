@extends('layouts.admin')
@section('title')
Actividades
@endsection
@section('content')
<div class="content mt-6">
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-md-3 offset-md-6">
          <div class="form-group">
            <a href="{{ route('calendario') }}" ><button class="btn btn-grubsa btn-block"> <i class="menu-icon fa fa-calendar-alt"></i> Calendario</button></a>
          </div>
        </div>
      </div>
    </div>
  </div>
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
                                  <label for="nuevo_actividad_mdl">Titulo</label>
                                  <input type="text" name="nuevo_actividad_mdl" id="nuevo_actividad_mdl" value=""  class="letrasModal form-control" required="true" />
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="form-group">
                                  <label for="nuevo_tipo_mdl">Tipo de actividad</label>
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
                                  <label for="nuevo_fecha_mdl">Fecha</label>
                                  <input type="date" name="nuevo_fecha_mdl" id="nuevo_fecha_mdl" value="@php
                                    echo date('Y-m-d');
                                  @endphp"  class="letrasModal form-control" required="true" />
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="form-group">
                                  <label for="nuevo_hora_mdl">Hora</label>
                                  <input type="time" name="nuevo_hora_mdl" id="nuevo_hora_mdl" value="@php
                                    echo date('H:i:s');
                                  @endphp"  class="letrasModal form-control" required="true" />
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="form-group">
                                  <label for="nuevo_duracion_mdl">Duracion</label>
                                  <input type="number" name="nuevo_duracion_mdl" id="nuevo_duracion_mdl" step="0"   class="letrasModal form-control" required="true" />
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="form-group">
                                  <label for="nuevo_prospecto_mdl">Prospecto</label>
                                  <select name="nuevo_prospecto_mdl" id="nuevo_prospecto_mdl" class="letrasModal form-control">
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
                      <button type="submit" class="btn btn-grubsa">Confirmar</button>
                  </div>
              </div>
          </div>
        {{ Form::close()}}
    </div>
  </div>

@push('scripts')
@endpush 
@endsection