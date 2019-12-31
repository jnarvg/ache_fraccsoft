@extends('layouts.admin')
@section('title')
Estados
@endsection
@section('filter')
  <a href="#" data-toggle="modal" data-target="#modal-estado"><button class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus"></i></button></a>
  <button href="#" data-toggle="collapse" data-target="#filtros" role="button" aria-expanded="false" aria-controls="filtros" class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-filter"></i></button>
@endsection
@section('content')
<div class="content mt-3">
  <div class="card collapse" id="filtros">
    <div class="card-body">
      <div class="row">
        <div class="col-lg-12">
        {!! Form::open( ['route'=>'estado', 'method'=>'get']) !!}
          <div class="input-group md-form form-sm form-4 pl-0">
            <input type="text" class="form-control" placeholder="Nombre" name="nombre_bs" id="nombre_bs" value="{{ $request->nombre_bs }}">
            <input type="text" class="form-control" placeholder="Pais" name="pais_bs" id="pais_bs" value="{{ $request->pais_bs }}">
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
              Estado
            </th>
            <th class="center">
              País
            </th>
            <th class="center">
              Acciones
            </th>
          </thead>
          <tbody>
            @foreach ($estados as $estado)                
            <tr>
              <td class="center">
                <a class="text-dark" href="{{URL::action('EstadoController@show', $estado->id_estado)}}"  style="width: 30%;">
                {{ $estado->estado}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('EstadoController@show', $estado->id_estado)}}"  style="width: 30%;">
                {{ $estado->pais}}</a>
              </td>
              <td class="center-acciones">
                <a href="{{URL::action('EstadoController@show', $estado->id_estado)}}"  style="width: 30%;"><button class="btn-ico" ><i class="fas fa-pencil-alt"></i></button></a>
                @if (auth()->user()->rol == 3)
                <a href="{{URL::action('EstadoController@destroy', $estado->id_estado)}}"  style="width: 30%;"><button class="btn-ico" ><i class="fas fa-trash-alt"></i></button></a>               
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  {{-- {{$estados->appends(Request::only('nombre_bs','clave_bs','pais_bs'))->render()}} --}}
</div>
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-estado">
{{ Form::open(array('action'=>array('EstadoController@store'),'method'=>'post')) }}
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title text-dark">Nuevo estado</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="nuevo_estado_mdl">*Estado</label>
                        <input type="text" name="nuevo_estado_mdl" id="nuevo_estado_mdl" value=""  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="nuevo_clave_mdl">*Clave</label>
                        <input type="text" name="nuevo_clave_mdl" id="nuevo_clave_mdl" maxlength="5" value="MX"  class="letrasModal form-control" required="true" />
                    </div>
                </div>   
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="nuevo_pais_mdl">*Pais</label>
                        <select  name="nuevo_pais_mdl" id="nuevo_pais_mdl" class="letrasModal form-control" required="true"> 
                          @foreach ($paises as $pais)
                              <option class="letrasModal" value="{{ $pais->id_pais }}">{{ $pais->pais }}</option>  
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