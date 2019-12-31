@extends('layouts.admin')
@section('title')
Prospectos perdidos
@endsection
@section('filter')
  <a href="#" data-toggle="modal" data-target="#modal_prospecto"><button class="mb-0 d-sm-inline-block btn btn-primary btn-sm"><i class="fas fa-plus text-xl"></i></button></a>
    {{ Form::open(array('action'=>array('ProspectosController@exportExcel'),'method'=>'get', 'class'=>'d-sm-inline-block')) }}
      <input type="hidden" class="form-control" placeholder="Nombre" name="nombre_excel" id="nombre_excel" value="{{ $request->nombre_bs }}">
      <input type="hidden" class="form-control"  name="fecha_venta_min_excel" id="fecha_venta_min_excel" value="{{ $request->fecha_venta_min_bs }}">
      <input type="hidden" class="form-control"  name="fecha_venta_max_excel" id="fecha_venta_max_excel" value="{{ $request->fecha_venta_max_bs }}">
      <input type="hidden" class="form-control"  name="fecha_registro_min_excel" id="fecha_registro_min_excel" value="{{ $request->fecha_registro_min_bs }}">
      <input type="hidden" class="form-control"  name="fecha_registro_max_excel" id="fecha_registro_max_excel" value="{{ $request->fecha_registro_max_bs }}">
      <select class="form-control oculto"  name="agente_excel[]" id="agente_excel"  multiple="multiple">
        @foreach ($users as $p)
          @if ($request->agente_bs)
            @foreach ($request->agente_bs as $e)
              @if ($p->id == $e)
                <option selected="true" value="{{$p->id }}">{{ $p->name }}</option>
                @php $valida = 1;@endphp
              @endif
            @endforeach
          @endif
        @endforeach
      </select>
      <select class="form-control oculto"  name="estatus_excel[]" id="estatus_excel"  multiple="multiple">
        @foreach ($estatus_crm as $p)
          @if ($request->estatus_bs)
            @foreach ($request->estatus_bs as $e)
              @if ($p->id_estatus_crm == $e)
                <option selected="true" value="{{$p->id_estatus_crm }}">{{ $p->estatus_crm }}</option>
                @php $valida = 1;@endphp
              @endif
            @endforeach
          @endif
        @endforeach
      </select>
      <select class="form-control oculto"  name="tipo_operacion_excel[]" id="tipo_operacion_excel"  multiple="multiple">
        @foreach ($medios_contacto as $p)
          @if ($request->medio_contacto_bs)
            @foreach ($request->medio_contacto_bs as $e)
              @if ($p->id_medio_contacto == $e)
                <option selected="true" value="{{$p->id_medio_contacto }}">{{ $p->medio_contacto }}</option>
                @php $valida = 1;@endphp
              @endif
            @endforeach
          @endif
        @endforeach
      </select>
      <button class="btn btn-primary d-sm-inline-block btn-sm "><i class="fas fa-cloud-download-alt text-xl"></i></button>
    {!! Form::close() !!}
  <button href="#" data-toggle="collapse" data-target="#filtros" role="button" aria-expanded="false" aria-controls="filtros" class="mb-0 d-sm-inline-block btn btn-primary btn-sm"><i class="fas fa-filter text-xl"></i></button>
@endsection
@section('content')
<div class="content mt-3">
  <div class="card collapse" id="filtros">
    <div class="card-body">
      {!! Form::open(array('route'=>'v_perdido', 'method'=>'get', 'autocomplete'=>'off')) !!}
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label>Prospecto</label>
              <input type="text" class="form-control" placeholder="Nombre" name="nombre_bs" id="nombre_bs" value="{{ $request->nombre_bs }}">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Estatus</label><br>
              <select class="form-control" placeholder="Estatus" name="estatus_bs[]" id="estatus_bs" multiple="multiple">
                <option selected="true" value="10">Perdido</option>
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Agente</label><br>
              <select class="form-control" placeholder="Estatus" name="agente_bs[]" id="agente_bs" multiple="multiple">
                @foreach ($users as $p)
                  @php $valida = 0; @endphp
                  @if ($request->agente_bs)
                    @foreach ($request->agente_bs as $e)
                      @if ($p->id == $e)
                        <option selected="true" value="{{$p->id }}">{{ $p->name }}</option>
                        @php $valida = 1;@endphp
                      @endif
                    @endforeach
                  @endif
                  @if ($valida == 0)
                    <option value="{{$p->id }}">{{ $p->name }}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Medio de contacto</label><br>
              <select class="form-control" placeholder="Estatus" name="medio_contacto_bs[]" id="medio_contacto_bs" multiple="multiple">
                @foreach ($medios_contacto as $p)
                  @php $valida = 0; @endphp
                  @if ($request->medio_contacto_bs)
                    @foreach ($request->medio_contacto_bs as $e)
                      @if ($p->id_medio_contacto == $e)
                        <option selected="true" value="{{$p->id_medio_contacto }}">{{ $p->medio_contacto }}</option>
                        @php $valida = 1;@endphp
                      @endif
                    @endforeach
                  @endif
                  @if ($valida == 0)
                    <option value="{{$p->id_medio_contacto }}">{{ $p->medio_contacto }}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Fecha registro</label><br>
              <label>De
                <input type="date" class="form-control" value="{{ $request->fecha_registro_min_bs }}" name="fecha_registro_min_bs" id="fecha_registro_min_bs" >
                al
                <input type="date" class="form-control" value="{{ $request->fecha_registro_max_bs }}" name="fecha_registro_max_bs" id="fecha_registro_max_bs" >
              </label>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Fecha venta</label><br>
              <label>De
                <input type="date" class="form-control" value="{{ $request->fecha_venta_min_bs }}" name="fecha_venta_min_bs" id="fecha_venta_min_bs" >
                al
                <input type="date" class="form-control" value="{{ $request->fecha_venta_max_bs }}" name="fecha_venta_max_bs" id="fecha_venta_max_bs" >
              </label>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Registros por pagina</label>
              <select class="form-control" name="rows_per_page" id="rows_per_page">
                @foreach ($rows_pagina as $rp)
                @if ($rp == $request->rows_per_page)
                <option selected value="{{ $rp }}">{{ $rp }}</option>
                @else
                <option value="{{ $rp }}">{{ $rp }}</option>
                @endif
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <button type="submit" class="btn btn-info btn-block down" ><span class="glyphicon"><i class="fas fa-search"></i></span></button>
            </div>
          </div>
        </div>
      {!! Form::close() !!}
    </div>
  </div>
  <div class="card">
    <div class="card-body">
      <div class="table-responsive ">
        <table class="table table-hover table-withoutborder text-sm">
          <thead class="thead-grubsa ">
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
              Motivo perdida
            </th>
            <th class="center">
              Agente
            </th>
            <th class="center">
              Acciones
            </th>
          </thead>
          <tbody>
            @foreach ($prospectos as $prospecto)                
            <tr>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ProspectosController@show', [ $prospecto->id_prospecto, 'v_perdido' ])}}" >
                {{ $prospecto->nombre}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ProspectosController@show', [ $prospecto->id_prospecto, 'v_perdido' ])}}" >
                {{ $prospecto->correo}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ProspectosController@show', [ $prospecto->id_prospecto, 'v_perdido' ])}}" >
                {{ $prospecto->telefono}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ProspectosController@show', [ $prospecto->id_prospecto, 'v_perdido' ])}}" >
                {{ date('Y-m-d',strtotime($prospecto->fecha_registro)) }}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ProspectosController@show', [ $prospecto->id_prospecto, 'v_perdido' ])}}" >
                {{ $prospecto->motivo_perdida}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ProspectosController@show', [ $prospecto->id_prospecto, 'v_perdido' ])}}" >
                {{ $prospecto->nombre_agente}}</a>
              </td>
              <td class="center-acciones">
                <a href="{{URL::action('ProspectosController@show', [ $prospecto->id_prospecto, 'v_perdido' ])}}" ><button class="btn-ico" ><i class="fas fa-pencil-alt"></i></button></a>
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
      $('#estatus_bs').multiselect('destroy');
      $('#estatus_bs').multiselect({
        enableFiltering: true,
        filterBehavior: 'text',
        enableCaseInsensitiveFiltering: true,
        includeSelectAllOption: true,
        allSelectedText: 'Todos ...',
        filterPlaceholder: 'Buscar...',
      });
      $('#agente_bs').multiselect('destroy');
      $('#agente_bs').multiselect({
        enableFiltering: true,
        filterBehavior: 'text',
        enableCaseInsensitiveFiltering: true,
        includeSelectAllOption: true,
        allSelectedText: 'Todos ...',
        filterPlaceholder: 'Buscar...',
      });
      $('#medio_contacto_bs').multiselect('destroy');
      $('#medio_contacto_bs').multiselect({
        enableFiltering: true,
        filterBehavior: 'text',
        enableCaseInsensitiveFiltering: true,
        includeSelectAllOption: true,
        allSelectedText: 'Todos ...',
        filterPlaceholder: 'Buscar...',
      });
    });
</script>
@endpush 
@endsection