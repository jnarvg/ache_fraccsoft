@extends('layouts.admin')
@section('title')
Documentos
@endsection
@section('filter')
  <a href="#" data-toggle="modal" data-target="#modal-uso_propiedad"><button class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus"></i></button></a>
  <button href="#" data-toggle="collapse" data-target="#filtros" role="button" aria-expanded="false" aria-controls="filtros" class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-filter"></i></button>
@endsection
@section('content')
<div class="content mt-3">
  @php
    $procedencia ='Menu';
  @endphp
  <div class="card show" id="filtros">
    <div class="card-body">
      <div class="row">
        <div class="col-lg-6 offset-md-6">
        {!! Form::open(array('route'=>'documentos', 'method'=>'get', 'autocomplete'=>'off')) !!}
          <div class="input-group md-form form-sm form-4 pl-0">

            <input type="text" class="form-control" placeholder="Titulo" name="titulo_bs" id="titulo_bs" value="{{ $request->titulo_bs }}">

            <input type="text" class="form-control" placeholder="Archivo" name="archivo_bs" id="archivo_bs" value="{{ $request->archivo_bs }}">
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
      <div class="table-responsive ">
        <table class="table table-hover table-withoutborder text-sm">
          <thead class="thead-grubsa ">
            <th class="center">
              Titulo
            </th>
            <th class="center">
              Nota
            </th>
            <th class="center">
              Fecha
            </th>
            <th class="center">
              archivo
            </th>
            <th class="center">
              accion
            </th>
          </thead>
          <tbody>
            @foreach ($documentos as $doc)                
            <tr>
              <td class="center">
                <a class="text-dark" href="{{URL::action('FileController@show', [$doc->id_documento, $procedencia])}}"  style="width: 30%;">
                {{ $doc->titulo}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('FileController@show', [$doc->id_documento, $procedencia])}}"  style="width: 30%;">
                {{ $doc->notas}}</a> 
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('FileController@show', [$doc->id_documento, $procedencia])}}"  style="width: 30%;">
                {{ date('Y-m-d',strtotime($doc->fecha)) }}</a> 
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('FileController@show', [$doc->id_documento, $procedencia])}}"  style="width: 30%;">
                {{ $doc->archivo}}</a> 
              </td>
              <td class="center-acciones">
                <a href="{{URL::action('FileController@show', [$doc->id_documento, $procedencia])}}"  style="width: 30%;"><button class="btn-ico" ><i class="fas fa-pencil-alt"></i></button></a>
                @if (auth()->user()->rol == '3')
                <a href="#" data-target="#modal-delete{{$doc->id_documento}}" data-toggle="modal" style="width: 30%;"><button class="btn-ico"><i class="fas fa-trash"></i></button></a>              
                @endif
              </td>
            </tr>
            @include('documentos.modal')
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  {{$documentos->appends(Request::only('titulo_bs','archivo_bs','rows_per_page'))->render()}}
</div>
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-uso_propiedad">
  
  {{ Form::open(array('action'=>array('FileController@store', $procedencia),'method'=>'post','files'=>'true', 'id' => 'my-dropzone' )) }}
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title text-dark">Nuevo Documento</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="titulo">*Titulo</label>
                          <input type="text" name="titulo" id="titulo" value=""  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="fecha">*Fecha</label>
                          <input type="date" name="fecha" id="fecha" value="{{date("Y-m-d")}}" class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                        <label for="nuevo_prospecto_mdl">*Prospecto</label>
                        <select name="nuevo_prospecto_mdl" id="nuevo_prospecto_mdl" class="letrasModal form-control">
                          <option selected="true" disabled="true">Seleccione..</option>
                          @foreach ($prospectos as $pros)
                            <option value="{{ $pros->id_prospecto }}">{{ $pros->nombre }}</option>
                          @endforeach
                        </select>
                    </div>
                  </div>               
              </div>
              <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                        <label for="notas">Notas</label>
                          <textarea name="notas" id="notas" value=""  class="letrasModal form-control" ></textarea>
                      </div>
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                          <td>
                                            
                              <input  class="archivo" onchange="ValidateSize(this)" name="archivo" type="file"/>
                             
                          </td>
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
<script>
function ValidateSize(file) {
        var FileSize = file.files[0].size / 1024 / 1024; // in MB
        if (FileSize > 2) {
            alert('No se puede insertar un archivo excedente de 2 megas');
           // $(file).val(''); //for clearing with Jquery
        }
    }
</script>
@endpush 
@endsection