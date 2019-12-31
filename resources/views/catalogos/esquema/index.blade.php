@extends('layouts.admin')
@section('title')
Esquemas de comision
@endsection
@section('filter')
  <a href="#" data-toggle="modal" data-target="#modal-esquema"><button class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus"></i></button></a>
@endsection
@section('content')
<div class="content mt-3">
  
  <div class="card">
    <div class="card-body">
      <div class="table-responsive ">
        <table class="table table-hover table-withoutborder text-sm" id="tabla">
          <thead class="thead-grubsa ">
            <th class="center">
              Esquema
            </th>
            <th class="center">
              Acciones
            </th>
          </thead>
          <tbody>
            @foreach ($esquemas_comision as $esquema)                
            <tr>
              <td class="center">
                <a class="text-dark" href="{{URL::action('EsquemaComisionController@show', $esquema->id_esquema_comision)}}"  style="width: 30%;">
                {{ $esquema->esquema_comision}}</a>
              </td>
              <td class="center-acciones">
                <a href="{{URL::action('EsquemaComisionController@show', $esquema->id_esquema_comision)}}"  style="width: 30%;"><button class="btn-ico" ><i class="fas fa-pencil-alt"></i></button></a>
                @if (auth()->user()->rol == 3)
                  <a href="{{URL::action('EsquemaComisionController@destroy', $esquema->id_esquema_comision)}}"  style="width: 30%;"><button class="btn-ico" ><i class="fas fa-times"></i></button></a>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  {{-- {{$esquemas_comision->render()}} --}}
</div>
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-esquema">
  {{ Form::open(array('action'=>array('EsquemaComisionController@store'),'method'=>'post')) }}
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title text-dark">Nuevo esquema de comision</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="esquema_comision_mdl">*Esquema de comision</label>
                          <input type="text" name="esquema_comision_mdl" id="esquema_comision_mdl" value=""  class="letrasModal form-control" required="true" />
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