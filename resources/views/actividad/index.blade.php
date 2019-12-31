@extends('layouts.admin')
@section('title')
Actividades
@endsection
@section('filter')
  <a href="#" data-toggle="modal" data-target="#modal-actividad"><button class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus"></i></button></a>
  <button href="#" data-toggle="collapse" data-target="#filtros" role="button" aria-expanded="false" aria-controls="filtros" class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-filter"></i></button>
@endsection
@section('content')
<div class="content mt-3">
  <div class="card show" id="filtros">
    <div class="card-body">
      <div class="row">
        <div class="col-lg-12">
          {{ Form::open(array('action'=>array('ActividadController@index'),'method'=>'get')) }}
            <div class="input-group md-form form-sm form-2 pl-0">
              <input class="form-control my-0 py-1 amber-border" type="text" placeholder="Titulo" aria-label="Search" value="{{ $request->titulo_bs }}" id="titulo_bs" name="titulo_bs">
              <input class="form-control my-0 py-1 amber-border" type="text" placeholder="Prospecto" aria-label="Search" value="{{ $request->prospecto_bs }}" id="prospecto_bs" name="prospecto_bs">
              <input class="form-control my-0 py-1 amber-border" type="date" placeholder="Fecha" aria-label="Search" value="{{ $request->fecha_bs }}" id="fecha_bs" name="fecha_bs">
              <select class="form-control" name="estatus_bs" id="estatus_bs">
                <option value="">Estatus...</option>
                @foreach ($estatus as $rp)
                @if ($rp == $request->estatus_bs)
                <option selected value="{{ $rp }}">{{ $rp }}</option>
                @else
                <option value="{{ $rp }}">{{ $rp }}</option>
                @endif
                @endforeach
              </select>
              <select class="form-control" name="tipo_bs" id="tipo_bs">
                <option value="">Tipos...</option>
                @foreach ($tipos as $rp)
                @if ($rp == $request->tipo_bs)
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
              <span>
                <button type="submit" class="btn btn-info"><i class="fas fa-search"></i></button>
              </span>
            </div>
          {{ Form::close()}}
        </div>
      </div>
    </div>
  </div>
  <div class="card mt-3">
    <div class="card-body">
      <div class="table-responsive ">
        <table class="table table-hover table-withoutborder text-sm">
          <thead class="thead-grubsa ">
            <th class="center">
              Estatus
            </th>
            <th class="center">
              Titulo
            </th>
            <th class="center">
              Fecha
            </th>
            <th class="center">
              Hora
            </th>
            <th class="center">
              Tipo de actividad
            </th>
            <th class="center">
              Prospecto
            </th>
            <th class="center">
              Responsable
            </th>
            <th class="center">
              Acciones
            </th>
            @php
              $procedencia = 'Menu'
            @endphp
          </thead>
          <tbody>
            @foreach ($actividades as $actividad)                
            <tr>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ActividadController@show', [$actividad->id_actividad, $procedencia])}}"  >
                {{ $actividad->estatus}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ActividadController@show', [$actividad->id_actividad, $procedencia])}}"  >
                {{ $actividad->titulo}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ActividadController@show', [$actividad->id_actividad, $procedencia])}}"  >
                {{ date('Y-M-d',strtotime($actividad->fecha)) }}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ActividadController@show', [$actividad->id_actividad, $procedencia])}}"  >
                {{ date('H:i A',strtotime($actividad->hora)) }}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ActividadController@show', [$actividad->id_actividad, $procedencia])}}"  >
                {{ $actividad->tipo_actividad}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ActividadController@show', [$actividad->id_actividad, $procedencia])}}"  >
                {{ $actividad->nombre_prospecto}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ActividadController@show', [$actividad->id_actividad, $procedencia])}}"  >
                {{ $actividad->nombre_user}}</a>
              </td>
              <td class="center-acciones">
                <a href="{{URL::action('ActividadController@show', [$actividad->id_actividad, $procedencia])}}"  ><button class="btn-ico" ><i class="fas fa-pencil-alt"></i></button></a>
                @if ($actividad->estatus == 'Completada')
                  <a href="{{URL::action('ActividadController@pendiente',[$actividad->id_actividad,$procedencia])}}"  ><button class="btn-ico" ><i class="fas fa-times"></i></button></a>
                @else
                  <a href="{{URL::action('ActividadController@completar', [$actividad->id_actividad,$procedencia])}}"  ><button class="btn-ico" ><i class="fas fa-check"></i></button></a>
                  <a href="{{URL::action('ActividadController@postergar', [$actividad->id_actividad,$procedencia])}}"  ><button class="btn-ico" ><i class="fas fa-history"></i></button></a>
                @endif
                <a href="#" data-target="#modal-delete{{$actividad->id_actividad}}" data-toggle="modal" ><button class="btn-ico"><i class="fas fa-trash"></i></button></a>
              </td>
            </tr>
            @include('actividad.modal')
            @endforeach 
          </tbody>
        </table>
      </div>
    </div>
  </div>
  {{$actividades->appends(Request::only('titulo_bs','estatus_bs','fecha_bs','tipo_bs','prospecto_bs','rows_per_page'))->render()}}
</div>
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-actividad">
@php
  $procedencia = 'Menu';
@endphp
{{ Form::open(array('action'=>array('ActividadController@store',$procedencia),'method'=>'post')) }}
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
                        <input type="date" name="nuevo_fecha_mdl" id="nuevo_fecha_mdl" value="{{date('Y-m-d')}}"  class="letrasModal form-control" required="true" />
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
                        <input type="date" name="nuevo_fecha_recordatorio_mdl" id="nuevo_fecha_recordatorio_mdl" value="{{date('Y-m-d')}}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-6 oculto">
                    <div class="form-group">
                        <input type="hidden" name="nuevo_duracion_mdl" id="nuevo_duracion_mdl" step="0" value="1"  class="letrasModal form-control" required="true" />
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
@endpush 
@endsection