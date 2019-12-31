@extends('layouts.admin')
@section('title')
Contacto
@endsection
@section('filter')
  <a href="#" data-toggle="modal" data-target="#modal-contacto"><button class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus"></i></button></a>
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
        <div class="col-lg-12">
          {{ Form::open(array('action'=>array('ContactosController@index'),'method'=>'get')) }}
            <div class="input-group md-form form-sm form-2 pl-0">
              <input class="form-control my-0 py-1 amber-border" type="text" placeholder="Nombre" aria-label="Search" value="{{ $request->word_bs }}" id="word_bs" name="word_bs">
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
  <div class="card">
    <div class="card-body">
      <div class="table-responsive ">
        <table class="table table-hover table-withoutborder text-sm">
          <thead class="thead-grubsa ">
            <th class="center">
              Nombre
            </th>
            <th class="center">
              Telefono
            </th>
            <th class="center">
              Correo
            </th>
            <th class="center">
              Puesto
            </th>
            <th class="center">
              Adicional
            </th>
            <th class="center">
              Prospecto
            </th>
            <th class="center">
              accion
            </th>
          </thead>
          <tbody>
            @foreach ($contactos as $cc)                
            <tr>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ContactosController@show', [$cc->id_contacto, $procedencia])}}" >
                {{ $cc->nombre}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ContactosController@show', [$cc->id_contacto, $procedencia])}}" >
                {{ $cc->telefono}}</a> 
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ContactosController@show', [$cc->id_contacto, $procedencia])}}" >
                {{ $cc->correo}}</a> 
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ContactosController@show', [$cc->id_contacto, $procedencia])}}" >
                {{ $cc->puesto}}</a> 
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ContactosController@show', [$cc->id_contacto, $procedencia])}}" >
                {{ $cc->telefono_adicional}}</a> 
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ContactosController@show', [$cc->id_contacto, $procedencia])}}" >
                {{ $cc->nombre_prospecto}}</a> 
              </td>
              <td class="center-acciones">
                <a href="{{URL::action('ContactosController@show', [$cc->id_contacto, $procedencia])}}" ><button class="btn-ico"><i class="fas fa-pencil-alt"></i></button></a>
                @if (auth()->user()->rol == '3')
                <a href="#" data-target="#modal-delete{{$cc->id_contacto}}" data-toggle="modal"><button class="btn-ico"><i class="fas fa-trash"></i></button></a>               
                @endif
              </td>
            </tr>
            @include('contacto.modal')
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  {{$contactos->appends(Request::only('word_bs','rows_per_page'))->render()}}
</div>
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-contacto">
  {{ Form::open(array('action'=>array('ContactosController@store',$procedencia),'method'=>'post')) }}
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title text-dark">Nuevo contacto</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="nombre_contacto">*Nombre</label>
                          <input type="text" name="nombre_contacto" id="nombre_contacto" value=""  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="correo_contacto">Correo</label>
                          <input type="text" name="correo_contacto" id="correo_contacto" value=""  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="telefono_contacto">Telefono</label>
                          <input type="text" name="telefono_contacto" id="telefono_contacto" value=""  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="telefono_adicional_contacto">Telefono adicional</label>
                          <input type="text" name="telefono_adicional_contacto" id="telefono_adicional_contacto" value=""  class="letrasModal form-control"  />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="puesto_contacto">Puesto</label>
                          <input type="text" name="puesto_contacto" id="puesto_contacto" value=""  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="prospecto_contacto">*Prospecto</label>
                          <select name="prospecto_contacto" id="prospecto_contacto" class="letrasModal form-control" required="true">
                            @foreach ($prospectos as $pros)
                              <option value="{{ $pros->id_prospecto }}">{{ $pros->nombre }}</option>
                            @endforeach
                          </select>
                      </div>
                  </div> 
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="notas_contacto">Notas</label>
                          <textarea name="notas_contacto" id="notas_contacto" value=""  class="letrasModal form-control"></textarea>
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