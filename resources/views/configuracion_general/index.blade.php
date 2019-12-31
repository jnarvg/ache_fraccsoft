@extends('layouts.admin')
@section('title')
Configuracion general
@endsection
@section('filter')
  @if ($id == '1')
    <a href="#" data-toggle="modal" data-target="#modal-configuracion"><button class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus"></i></button></a>
  @endif
  <button href="#" data-toggle="collapse" data-target="#filtros" role="button" aria-expanded="false" aria-controls="filtros" class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-filter"></i></button>
@endsection
@section('content')
<div class="content mt-3">
  <div class="card">
    <div class="card-body">
      <div class="table-responsive ">
        <table class="table table-hover table-withoutborder text-sm">
          <thead class="thead-grubsa ">
            <th class="center">
              Cliente
            </th>
            <th class="center">
              Empresa principal
            </th>
            <th class="center">
              Correo
            </th>
            <th class="center">
              Tasa interes
            </th>
            <th class="center">
              Usuarios
            </th>
            <th class="center">
              Accion
            </th>
          </thead>
          <tbody>
            @foreach ($configuracion_general as $cg)                
            <tr>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ConfiguracionGeneralController@show', $cg->id_configuracion_general)}}"  style="width: 30%;">
                {{ $cg->nombre_cliente}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ConfiguracionGeneralController@show', $cg->id_configuracion_general)}}"  style="width: 30%;">
                {{ $cg->nombre_comercial}}</a> 
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ConfiguracionGeneralController@show', $cg->id_configuracion_general)}}"  style="width: 30%;">
                {{ $cg->correo_contacto}}</a> 
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ConfiguracionGeneralController@show', $cg->id_configuracion_general)}}"  style="width: 30%;">
                {{ $cg->tasa_interes_mora}}</a> 
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ConfiguracionGeneralController@show', $cg->id_configuracion_general)}}"  style="width: 30%;">
                {{ $cg->limite_usuarios}}</a> 
              </td>
              <td class="center-acciones">
                <a href="{{URL::action('ConfiguracionGeneralController@show', $cg->id_configuracion_general)}}"  style="width: 30%;"><button class="btn-ico"><i class="fas fa-pencil-alt"></i></button></a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-configuracion">
  {{ Form::open(array('action'=>array('ConfiguracionGeneralController@store'),'method'=>'post')) }}
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title text-dark">Nueva configuracion inicial</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="nombre_cliente">*Cliente</label>
                          <input type="text" name="nombre_cliente" id="nombre_cliente" value=""  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="correo_contacto">Correo</label>
                          <input type="email" name="correo_contacto" id="correo_contacto" value=""  class="letrasModal form-control" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="limite_usuarios">Usuarios</label>
                          <input type="numeric" name="limite_usuarios" id="limite_usuarios" value="15"  class="letrasModal form-control" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="limite_propiedades">Propiedades</label>
                          <input type="text" name="limite_propiedades" id="limite_propiedades" value="1000"  class="letrasModal form-control"  />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="estatus">Estatus</label>
                          <input type="text" name="estatus" id="estatus" value="Activo"  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="empresa_principal">*Empresa</label>
                          <select name="empresa_principal" id="empresa_principal" class="letrasModal form-control" required="true">
                            @foreach ($empresas as $pros)
                              <option value="{{ $pros->id_empresa }}">{{ $pros->nombre_comercial }}</option>
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