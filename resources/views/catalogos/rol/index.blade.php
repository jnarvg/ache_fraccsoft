@extends('layouts.admin')
@section('title')
Roles
@endsection
@section('filter')
  <a href="#" data-toggle="modal" data-target="#modal-uso_propiedad"><button class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus"></i></button></a>
  <button href="#" data-toggle="collapse" data-target="#filtros" role="button" aria-expanded="false" aria-controls="filtros" class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-filter"></i></button>
@endsection
@section('content')
<div class="content mt-3">
  
  <div class="card collapse" id="filtros">
    <div class="card-body">
      <div class="row">
        <div class="col-lg-12">
        {!! Form::open(array('route'=>'rol', 'method'=>'get', 'autocomplete'=>'off')) !!}
          <div class="input-group md-form form-sm form-4 pl-0">

            <input type="text" class="form-control" placeholder="Searh" name="word_bs" id="word_bs" value="{{ $request->word_bs }}">
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
        <table class="table table-hover table-withoutborder text-sm" id="tabla">
          <thead class="thead-grubsa ">
            <th class="center">
              Id
            </th>
            <th class="center">
              Rol
            </th>
            <th class="center">
              Acciones
            </th>
          </thead>
          <tbody>
            @foreach ($roles as $r)                
            <tr>
              <td class="center">
                {{ $r->id }}
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('RolController@show', $r->id)}}"  style="width: 30%;">
                {{ $r->rol}}</a>
              </td>
              <td class="center-acciones">
                <a href="{{URL::action('RolController@show', $r->id)}}"  style="width: 30%;"><button class="btn-ico" style="margin-left: 2px; margin-right: 2px;"><i class="fas fa-pencil-alt"></i></button></a>
                @if (auth()->user()->rol == 3)
                <a href="#" data-target="#modal-delete{{$r->id}}" data-toggle="modal" ><button class="btn-ico" ><i class="fas fa-trash"></i></button></a>             
                @endif
              </td>
            </tr>
            @include('catalogos.rol.modal')
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  {{-- {{$roles->appends(Request::only('word_bs','rows_per_page'))->render()}} --}}
</div>
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-uso_propiedad">
  {{ Form::open(array('action'=>array('RolController@store'),'method'=>'post')) }}
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-dark">Nuevo rol</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="rol">*Rol</label>
                            <input type="text" name="rol" id="rol" value=""  class="letrasModal form-control" required="true" />
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
<script type="text/javascript">
  jQuery(document).ready(function($)
  {
    $('#tabla').DataTable();
  });
</script>
@endpush 
@endsection