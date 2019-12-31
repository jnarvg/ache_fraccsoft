@extends('layouts.admin')
@section('title')
Estatus prospecto
@endsection
@section('filter')
  <a href="#" data-toggle="modal" data-target="#modal-uso_operacion"><button class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus"></i></button></a>
@endsection
@section('content')
<div class="content mt-3">
  
  <div class="card">
    <div class="card-body">
      <div class="table-responsive ">
        <table class="table table-hover table-withoutborder text-sm" id="tabla_estatus">
          <thead class="thead-grubsa ">
            <th class="center">
              Id
            </th>
            <th class="center">
              Estatus prospecto
            </th>
            <th class="center">
              Nivel
            </th>
            <th class="center">
              Limite
            </th>
            <th class="center">
              Acciones
            </th>
          </thead>
          <tbody>
            @foreach ($estatus_prospectos as $estatus)                
            <tr>
              <td class="center">
                {{ $estatus->id_estatus_crm }}
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('EstatusProspectoController@show', $estatus->id_estatus_crm)}}"  style="width: 30%;">
                {{ $estatus->estatus_crm}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('EstatusProspectoController@show', $estatus->id_estatus_crm)}}"  style="width: 30%;">
                {{ $estatus->nivel}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('EstatusProspectoController@show', $estatus->id_estatus_crm)}}"  style="width: 30%;">
                {{ $estatus->limite}}</a>
              </td>
              <td class="center-acciones">
                <a href="{{URL::action('EstatusProspectoController@show', $estatus->id_estatus_crm)}}"  style="width: 30%;"><button class="btn-ico" ><i class="fas fa-pencil-alt"></i></button></a>
                @if (auth()->user()->rol == 3)
                <a href="{{URL::action('EstatusProspectoController@destroy', $estatus->id_estatus_crm)}}"  style="width: 30%;"><button class="btn-ico" ><i class="fas fa-trash-alt"></i></button></a>               
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  {{-- {{$estatus_prospectos->render()}} --}}
</div>
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-uso_operacion">
  {{ Form::open(array('action'=>array('EstatusProspectoController@store'),'method'=>'post')) }}
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title text-dark">Nuevo estatus prospecto</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="nuevo_estatus_mdl">*Estatus prospecto</label>
                          <input type="text" name="nuevo_estatus_mdl" id="nuevo_estatus_mdl" value=""  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="nivel">*Nivel</label>
                          <input type="text" name="nivel" id="nivel" value="1"  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="limite">Limite</label>
                          <input type="text" name="limite" id="limite" value="1"  class="letrasModal form-control" />
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
      $('#tabla_estatus').DataTable();
    });
</script>  
@endpush 
@endsection