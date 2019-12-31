@extends('layouts.admin')
@section('title')
Todos los clientes
@endsection
@section('content')
<div class="content mt-3">
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
      <div class="row">
        <div class="col-lg-12">
        {!! Form::open(array('route'=>'prospectos', 'method'=>'get', 'autocomplete'=>'off')) !!}
          <div class="input-group md-form form-sm form-4 pl-0">

            <input type="text" class="form-control" placeholder="Nombre" name="nombre_bs" id="nombre_bs" value="{{ $request->nombre_bs }}">

            <input type="text" class="form-control" placeholder="Correo" name="correo_bs" id="correo_bs" value="{{ $request->correo_bs }}">

            <input type="text" class="form-control" placeholder="Agente" name="agente_bs" id="agente_bs" value="{{ $request->agente_bs }}">
            <select class="form-control" placeholder="Estatus" name="estatus_bs" id="estatus_bs">
              <option value="Vacio">Estatus..</option>
              @foreach ($estatus_crm as $e)
                @if ($e->estatus_crm == $request->estatus_bs)
                <option selected value="{{ $e->estatus_crm }}">{{ $e->estatus_crm }}</option>
                @else
                <option value="{{ $e->estatus_crm }}">{{ $e->estatus_crm }}</option>
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
      
      <div class="row">
        <div class="col-md-3 offset-md-9">
          <div class="form-group">
            <a href="#" data-toggle="modal" data-target="#modal_prospecto"><button class="btn btn-primary btn-block">Nuevo prospecto</button></a>
          </div>
        </div>
        
      </div>
      <div class="table-responsive ">
        <table class="table table-hover table-withoutborder text-sm">
          <thead class="thead-grubsa ">
            <th class="center">
              Estatus
            </th>
            <th class="center">
              Propecto
            </th>
            <th class="center">
              Correo
            </th>
            <th class="center">
              Telefono
            </th>
            <th class="center">
              Fecha registro
            </th>
            <th class="center">
              Medio contacto
            </th>
            <th class="center">
              Agente
            </th>
            <th class="center">
              Propiedad
            </th>
            <th class="center">
              Acciones
            </th>
          </thead>
          <tbody>
            @foreach ($prospectos as $prospecto)                
            <tr>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ProspectosController@show', $prospecto->id_prospecto)}}"  style="width: 30%;">
                {{ $prospecto->estatus_prospecto}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ProspectosController@show', $prospecto->id_prospecto)}}"  style="width: 30%;">
                {{ $prospecto->nombre}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ProspectosController@show', $prospecto->id_prospecto)}}"  style="width: 30%;">
                {{ $prospecto->correo}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ProspectosController@show', $prospecto->id_prospecto)}}"  style="width: 30%;">
                {{ $prospecto->telefono}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ProspectosController@show', $prospecto->id_prospecto)}}"  style="width: 30%;">
                {{ date('Y-m-d',strtotime($prospecto->fecha_registro)) }}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ProspectosController@show', $prospecto->id_prospecto)}}"  style="width: 30%;">
                {{ $prospecto->medio_contacto}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ProspectosController@show', $prospecto->id_prospecto)}}"  style="width: 30%;">
                {{ $prospecto->nombre_agente}}</a>
              </td><td class="center">
                <a class="text-dark" href="{{URL::action('ProspectosController@show', $prospecto->id_prospecto)}}"  style="width: 30%;">
                {{ $prospecto->nombre_propiedad}}</a>
              </td>
              <td class="center-acciones">
                <a href="{{URL::action('ProspectosController@show', $prospecto->id_prospecto)}}"  style="width: 30%;"><button class="btn-ico" ><i class="fas fa-pencil-alt"></i></button></a>
                @if (auth()->user()->id == $prospecto->asesor_id)
                <a href="#" data-target="#modal-delete{{$prospecto->id_prospecto}}" data-toggle="modal" style="width: 30%;"><button class="btn-ico" ><i class="fas fa-trash-alt"></i></button></a>               
                @endif
              </td>
            </tr>
            @include('prospectos.modal')
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  {{$prospectos->appends(Request::only('nombre_bs','agente_bs','estatus_bs','correo_bs','rows_per_page'))->render()}}
</div>
@include('prospectos.modal_nuevo')
@push('scripts')
<script >
   jQuery(document).ready(function($)
    {
      $("#nuevo_proyecto_mdl").on('change', function(){
        desarrollo = $('#nuevo_proyecto_mdl').val();
        selectPropiedad = $('#nuevo_propiedad_mdl');
        selectPropiedad.empty().append('<option>Cargando las propiedades</option>');


        $.ajax({

        type: "GET",
        url: "/catalogo-propiedades-desarrollo/" + desarrollo,
        success: function(data) {
              var htmlOptions = [];
              if( data.length ){
                  html = '<option value="" disabled="true" selected="true">Selecciona una propiedad</option>';
                  htmlOptions[htmlOptions.length] = html;
                  for( item in data ) {
                    //en caso de ser un select
                    html = '<option value="' + data[item].id_propiedad + '">' + data[item].nombre + '</option>';
                    htmlOptions[htmlOptions.length] = html;
                  }

                  //en caso de ser un input
                  //$("#precio_propiedadctz").val(html);
                  
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