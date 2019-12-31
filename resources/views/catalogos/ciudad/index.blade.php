@extends('layouts.admin')
@section('title')
Ciudad
@endsection
@section('filter')
  <a href="#" data-toggle="modal" data-target="#modal-uso_propiedad"><button class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus"></i></button></a>
  <button href="#" data-toggle="collapse" data-target="#filtros" role="button" aria-expanded="false" aria-controls="filtros" class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-filter"></i></button>
@endsection
@section('content')
<div class="content mt-3">
  <div class="card show" id="filtros">
    <div class="card-body">
      <div class="row">
        <div class="col-lg-12">
        {!! Form::open( ['route'=>'ciudades', 'method'=>'get']) !!}
          <div class="input-group md-form form-sm form-4 pl-0">

            <input type="text" class="form-control" placeholder="Nombre" name="nombre_bs" id="nombre_bs" value="{{ $request->nombre_bs }}">
            <input type="text" class="form-control" placeholder="Pais" name="pais_bs" id="pais_bs" value="{{ $request->pais_bs }}">
            <input type="text" class="form-control" placeholder="Estado" name="estado_bs" id="estado_bs" value="{{ $request->estado_bs }}">
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
              Ciudad
            </th>
            <th class="center">
              Estado
            </th>
            <th class="center">
              Pais
            </th>
            <th class="center">
              Acciones
            </th>
          </thead>
          <tbody>
            @foreach ($ciudades as $ciud)                
            <tr>
              <td class="center">
                <a class="text-dark" href="{{URL::action('CiudadController@show', $ciud->id_ciudad)}}" >
                {{ $ciud->ciudad}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('CiudadController@show', $ciud->id_ciudad)}}" >
                {{ $ciud->estado}}</a> 
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('CiudadController@show', $ciud->id_ciudad)}}" >
                {{ $ciud->pais}}</a>
              </td>
              <td class="center-acciones">
                <a href="{{URL::action('CiudadController@show', $ciud->id_ciudad)}}" ><button class="btn-ico"><i class="fas fa-pencil-alt"></i></button></a>
                @if (auth()->user()->rol == 3)
                <a href="{{URL::action('CiudadController@destroy', $ciud->id_ciudad)}}" ><button class="btn-ico"><i class="fas fa-trash-alt"></i></button></a>               
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  {{$ciudades->appends(Request::only('nombre_bs','clave_bs','estado_bs'))->render()}}
</div>
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-uso_propiedad">
  {{ Form::open(array('action'=>array('CiudadController@store'),'method'=>'post')) }}
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title text-dark">Nueva ciudad</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="nueva_ciudad">*Nombre ciudad</label>
                          <input type="text" name="nueva_ciudad" id="nueva_ciudad" value=""  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="nueva_clave">*Clave</label>
                          <input type="text" name="nueva_clave" id="nueva_clave" value="MX" maxlength="5" class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                        <label>*Estado</label>
                        <select class="form-control" id="estado" name="estado">
                            @foreach ($estados as $estado)
                                <option selected="true" class="letrasModal" value="{{ $estado->id_estado }}">{{ $estado->estado }}</option>
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
@endpush 
@endsection