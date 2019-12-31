@extends('layouts.admin')
@section('title')
Usuarios externos
@endsection
@section('filter')
  @if (auth()->user()->rol == 3)
  <a href="#" data-toggle="modal" data-target="#modal-agente"><button class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus"></i></button></a>
  @endif
@endsection
@section('content')
<div class="content mt-3">
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-md-6 offset-md-6">
        {!! Form::open(array('route'=>'usuarios', 'method'=>'get', 'autocomplete'=>'off')) !!}
          <div class="input-group md-form form-sm form-4 pl-0">

            <input type="text" class="form-control" placeholder="Search" name="search" id="search" value="{{ $request->search }}">
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
      @if (session()->has('msj'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
          <strong>{{ session('msj') }}</strong>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif
      <div class="table-responsive ">
        <table class="table table-hover table-withoutborder text-sm" id="tabla">
          <thead class="thead-grubsa ">
            <th class="center">
              ID
            </th>
            <th class="center">
              Nombre
            </th>
            <th class="center">
              Email
            </th>
            <th class="center">
              Prospecto
            </th>
            <th class="center">
              Estatus
            </th>
            <th class="center">
              Acciones
            </th>
          </thead>
          <tbody>
            @foreach ($usuarios as $us)                
            <tr>
              <td class="center">
                {{ $us->id }}
              </td>
              <td class="center">
                {{ $us->name }}
              </td>
              <td class="center">
                {{ $us->email}}
              </td>
              <td class="center"> 
                {{ $us->prospecto_id}}
              </td>
              <td class="center">                
                {{ $us->estatus}}
              </td>
              <td class="center-acciones">
                @if (auth()->user()->rol == 3 and $us->id != 1)
                  <a href="{{URL::action('UsuariosExternosController@show', $us->id)}}"  style="width: 30%;"><button class="btn-ico" ><i class="fas fa-pencil-alt"></i></button></a>
                  <a href="#" data-target="#modal-delete{{$us->id}}" data-toggle="modal" ><button class="btn-ico" ><i class="fas fa-trash"></i></button></a>     
                @endif
              </td>
            </tr>
            @include('catalogos.usuarios_externos.modal')
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  {{-- {{$usuarios->appends('search','rows_per_page')->render()}} --}}
</div>
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-agente">
  {{ Form::open(array('action'=>array('UsuariosExternosController@store'),'method'=>'post')) }}
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title text-dark">Nuevo usuario externo</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="prospecto">*Prospecto</label>
                          <select name="prospecto" id="prospecto" value=""  class="letrasModal form-control" required="true">
                            @foreach ($prospectos as $r)
                              <option value="{{ $r->id_prospecto }}">{{ $r->nombre }}</option>
                            @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="nuevo_rol_mdl">*Rol</label>
                          <select name="nuevo_rol_mdl" id="nuevo_rol_mdl" value=""  class="letrasModal form-control" required="true">
                            @foreach ($roles as $r)
                              <option value="{{ $r->id }}">{{ $r->rol }}</option>
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
<script type="text/javascript">
  jQuery(document).ready(function($)
  {
    $('#tabla').DataTable();
  });
</script>
@endpush 
@endsection