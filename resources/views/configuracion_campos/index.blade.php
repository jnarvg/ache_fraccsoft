@extends('layouts.admin')
@section('title')
Configuracion campos
@endsection
@section('filter')
  @if ($id == '1')
    <a href="#" data-toggle="modal" data-target="#modal-configuracion"><button class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus"></i></button></a>
  @endif
@endsection
@section('content')
<div class="content mt-3">
  <div class="card">
    <div class="card-body">
      <div class="table-responsive ">
        <table class="table table-hover table-withoutborder text-sm" id="tabla">
          <thead class="thead-grubsa ">
            <th class="center">
              ID
            </th>
            <th class="center">
              Tabla
            </th>
            <th class="center">
              Campo
            </th>
            <th class="center">
              Label
            </th>
            <th class="center">
              Tipo
            </th>
            <th class="center">
              PK
            </th>
            <th class="center">
              UNI
            </th>
            <th class="center">
              HID
            </th>
            <th class="center">
              Import
            </th>
            <th class="center">
              Readonly
            </th>
            <th class="center">
              FK tabla
            </th>
            <th class="center">
              FK campo
            </th>
            <th class="center">
              Accion
            </th>
          </thead>
          <tbody>
            @foreach ($campos_configuracion as $cg)                
            <tr>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ConfiguracionCamposController@show', $cg->id_campos_configuracion)}}" >
                {{ $cg->id_campos_configuracion}}</a> 
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ConfiguracionCamposController@show', $cg->id_campos_configuracion)}}" >
                {{ $cg->tabla}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ConfiguracionCamposController@show', $cg->id_campos_configuracion)}}" >
                {{ $cg->campo}}</a> 
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ConfiguracionCamposController@show', $cg->id_campos_configuracion)}}" >
                {{ $cg->label}}</a> 
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ConfiguracionCamposController@show', $cg->id_campos_configuracion)}}" >
                {{ $cg->tipo_dato}}</a> 
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ConfiguracionCamposController@show', $cg->id_campos_configuracion)}}" >
                {{ $cg->pk}}</a> 
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ConfiguracionCamposController@show', $cg->id_campos_configuracion)}}" >
                {{ $cg->unique}}</a> 
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ConfiguracionCamposController@show', $cg->id_campos_configuracion)}}" >
                {{ $cg->hidden}}</a> 
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ConfiguracionCamposController@show', $cg->id_campos_configuracion)}}" >
                {{ $cg->importable}}</a> 
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ConfiguracionCamposController@show', $cg->id_campos_configuracion)}}" >
                {{ $cg->readonly}}</a> 
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ConfiguracionCamposController@show', $cg->id_campos_configuracion)}}" >
                {{ $cg->fk_tabla}}</a> 
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ConfiguracionCamposController@show', $cg->id_campos_configuracion)}}" >
                {{ $cg->fk_campo}}</a> 
              </td>
              <td class="center-acciones">
                <a href="{{URL::action('ConfiguracionCamposController@show', $cg->id_campos_configuracion)}}" ><button class="btn-ico"><i class="fas fa-pencil-alt"></i></button></a>
                <a href="#" data-target="#modal-delete{{$cg->id_campos_configuracion}}" data-toggle="modal"><button class="btn-ico"><i class="fas fa-trash"></i></button></a>
              </td>
            </tr>
            @include('configuracion_campos.modal')
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-configuracion">
  {{ Form::open(array('action'=>array('ConfiguracionCamposController@store'),'method'=>'post')) }}
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title text-dark">Nuevo campo</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="tabla">*Tabla</label>
                          <select name="tabla" id="tabla" class="letrasModal form-control" required="true">
                            @foreach ($tablas as $pros)
                              <option value="{{ $pros->Tables_in_db_ache }}">{{ $pros->Tables_in_db_ache }}</option>
                            @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="campo">Campo</label>
                          <input type="text" name="campo" id="campo" value=""  class="letrasModal form-control" />
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="limite_usuarios">Label</label>
                          <input type="text" name="limite_usuarios" id="limite_usuarios"  class="letrasModal form-control" />
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="tipo_dato">Tipo dato</label>
                          <select name="tipo_dato" id="tipo_dato" class="letrasModal form-control" required="true">
                            @foreach ($tipos as $s)
                              <option value="{{ $s }}">{{ $s }}</option>
                            @endforeach
                          </select>

                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="pk">PK</label>
                          <select name="pk" id="pk" class="letrasModal form-control" required="true">
                            @foreach ($siorno as $s)
                              <option value="{{ $s }}">{{ $s }}</option>
                            @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="unique">*Unique</label>
                          <select name="unique" id="unique" class="letrasModal form-control" required="true">
                            @foreach ($siorno as $s)
                              <option value="{{ $s }}">{{ $s }}</option>
                            @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="hidden">*Hidden</label>
                          <select name="hidden" id="hidden" class="letrasModal form-control" required="true">
                            @foreach ($siorno as $s)
                              <option value="{{ $s }}">{{ $s }}</option>
                            @endforeach
                          </select>
                      </div>
                  </div>  
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="readonly">*Readonly</label>
                          <select name="readonly" id="readonly" class="letrasModal form-control" required="true">
                            @foreach ($siorno as $s)
                              <option value="{{ $s }}">{{ $s }}</option>
                            @endforeach
                          </select>
                      </div>
                  </div> 
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="requerido">*Requerido</label>
                          <select name="requerido" id="requerido" class="letrasModal form-control" required="true">
                            @foreach ($siorno as $s)
                              <option value="{{ $s }}">{{ $s }}</option>
                            @endforeach
                          </select>
                      </div>
                  </div>  
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="importable">*Importable</label>
                          <select name="importable" id="importable" class="letrasModal form-control" required="true">
                            @foreach ($siorno as $s)
                              <option value="{{ $s }}">{{ $s }}</option>
                            @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="actualizable">*Actualizable</label>
                          <select name="actualizable" id="actualizable" class="letrasModal form-control" required="true">
                            @foreach ($siorno as $s)
                              <option value="{{ $s }}">{{ $s }}</option>
                            @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="fk_tabla">*FK Tabla</label>
                          <select name="fk_tabla" id="fk_tabla" class="letrasModal form-control" required="true">
                            <option value=""></option>
                            @foreach ($tablas as $pros)
                              <option value="{{ $pros->Tables_in_db_ache }}">{{ $pros->Tables_in_db_ache }}</option>
                            @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="fk_campo">*FK Campo</label>
                          <input type="text" name="fk_campo" id="fk_campo" class="letrasModal form-control">
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="fk_pk">*FK Id primaria</label>
                          <input type="text" name="fk_pk" id="fk_pk" class="letrasModal form-control">
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
  jQuery(document).ready(function($)
  {
    $('#tabla').DataTable();
  });
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