@extends('layouts.admin')
@section('title')
Paises
@endsection
@section('filter')
  <a href="#" data-toggle="modal" data-target="#modal-pais"><button class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus"></i></button></a>
  <button href="#" data-toggle="collapse" data-target="#filtros" role="button" aria-expanded="false" aria-controls="filtros" class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-filter"></i></button>
@endsection
@section('content')
<div class="content mt-3">
  <div class="card show" id="filtros">
    <div class="card-body">
      <div class="row">
        <div class="col-lg-12">
        {!! Form::open( ['route'=>'pais', 'method'=>'get']) !!}
          <div class="input-group md-form form-sm form-4 pl-0">

            <input type="text" class="form-control" placeholder="Nombre" name="nombre_bs" id="nombre_bs" value="{{ $request->nombre_bs }}">
            <input type="text" class="form-control" placeholder="Clave" name="clave_bs" id="clave_bs" value="{{ $request->clave_bs }}">
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
              Clave
            </th>
            <th class="center">
              País
            </th>
            <th class="center">
              Acciones
            </th>
          </thead>
          <tbody>
            @foreach ($paises as $pais)                
            <tr>
              <td class="center">
                <a class="text-dark" href="{{URL::action('PaisController@show', $pais->id_pais)}}">
                {{ $pais->clave}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('PaisController@show', $pais->id_pais)}}">
                {{ $pais->pais}}</a>
              </td>
              <td class="center-acciones">
                <a href="{{URL::action('PaisController@show', $pais->id_pais)}}"><button class="btn-ico" ><i class="fas fa-pencil-alt"></i></button></a>
                @if (auth()->user()->rol == 3)
                <a href="{{URL::action('PaisController@destroy', $pais->id_pais)}}"><button class="btn-ico" ><i class="fas fa-trash-alt"></i></button></a>               
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  {{$paises->appends(Request::only('nombre_bs','clave_bs'))->render()}}
</div>
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-pais">
  {{ Form::open(array('action'=>array('PaisController@store'),'method'=>'post')) }}
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title text-dark">Nuevo país</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="nuevo_clave_mdl">*Clave</label>
                          <input type="text" name="nuevo_clave_mdl" id="nuevo_clave_mdl" value="" maxlength="5"  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="nuevo_pais_mdl">*País</label>
                          <input type="text" name="nuevo_pais_mdl" id="nuevo_pais_mdl" value=""  class="letrasModal form-control" required="true" />
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