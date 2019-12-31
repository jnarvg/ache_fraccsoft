@extends('layouts.admin')
@section('title')
Amenidades
@endsection
@section('filter')
  <a href="#" data-toggle="modal" data-target="#modal-amenidad"><button class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus"></i></button></a>
@endsection
@section('content')
<div class="content mt-3">
  
  <div class="card">
    <div class="card-body">
      <div class="table-responsive ">
        <table class="table table-hover table-withoutborder text-sm" id="tabla">
          <thead class="thead-grubsa ">
            <th class="center">
              Amenidad
            </th>
            <th class="center">
              Acciones
            </th>
          </thead>
          <tbody>
            @foreach ($amenidades as $amenidad)                
            <tr>
              <td class="center">
                <a class="text-dark" href="{{URL::action('AmenidadController@show', $amenidad->id_amenidad)}}"  style="width: 30%;">
                {{ $amenidad->amenidad}}</a>
              </td>
              <td class="center-acciones">
                <a href="{{URL::action('AmenidadController@show', $amenidad->id_amenidad)}}"  style="width: 30%;"><button class="btn-ico" ><i class="fas fa-pencil-alt"></i></button></a>
                @if (auth()->user()->rol == 3)
                <a href="{{URL::action('AmenidadController@destroy', $amenidad->id_amenidad)}}"  style="width: 30%;"><button class="btn-ico" ><i class="fas fa-trash-alt"></i></button></a>               
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
 {{--  {{$amenidades->render()}} --}}
</div>
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-amenidad">
  {{ Form::open(array('action'=>array('AmenidadController@store'),'method'=>'post')) }}
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title text-dark">Nuevo amenidad</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="nuevo_amenidad_mdl">*Amenidad</label>
                          <input type="text" name="nuevo_amenidad_mdl" id="nuevo_amenidad_mdl" value=""  class="letrasModal form-control" required="true" />
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