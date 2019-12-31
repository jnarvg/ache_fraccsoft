@extends('layouts.admin')
@section('title')
Tipo de operacion
@endsection
@section('filter')
  <a href="#" data-toggle="modal" data-target="#modal-uso_operacion"><button class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus"></i></button></a>

@endsection
@section('content')
<div class="content mt-3">
  
  <div class="card">
    <div class="card-body">
      <div class="table-responsive ">
        <table class="table table-hover table-withoutborder text-sm" id="tabla">
          <thead class="thead-grubsa ">
            <th class="center">
              Tipo de operacion
            </th>
            <th class="center">
              Acciones
            </th>
          </thead>
          <tbody>
            @foreach ($TiposOperacion as $tipo)                
            <tr>
              <td class="center">
                <a class="text-dark" href="{{URL::action('TipoOperacionController@show', $tipo->id_tipo_operacion)}}" >
                {{ $tipo->tipo_operacion}}</a>
              </td>
              <td class="center-acciones">
                <a href="{{URL::action('TipoOperacionController@show', $tipo->id_tipo_operacion)}}" ><button class="btn-ico" ><i class="fas fa-pencil-alt"></i></button></a>
                @if (auth()->user()->rol == 3)
                <a href="{{URL::action('TipoOperacionController@destroy', $tipo->id_tipo_operacion)}}" ><button class="btn-ico" ><i class="fas fa-trash-alt"></i></button></a>               
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  {{-- {{$TiposOperacion->render()}} --}}
</div>
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-uso_operacion">
  {{ Form::open(array('action'=>array('TipoOperacionController@store'),'method'=>'post')) }}
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title text-dark">Nuevo tipo de operacion</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="nuevo_tipo_mdl">*Tipo de operacion</label>
                          <input type="text" name="nuevo_tipo_mdl" id="nuevo_tipo_mdl" value=""  class="letrasModal form-control" required="true" />
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