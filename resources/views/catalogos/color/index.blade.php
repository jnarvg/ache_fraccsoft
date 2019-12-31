@extends('layouts.admin')
@section('title')
Colores
@endsection
@section('filter')
  <a href="#" data-toggle="modal" data-target="#modal-uso_operacion"><button class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus"></i></button></a>
  <button href="#" data-toggle="collapse" data-target="#filtros" role="button" aria-expanded="false" aria-controls="filtros" class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-filter"></i></button>
@endsection
@section('content')
<div class="content mt-3">
  
  <div class="card">
    <div class="card-body">
      <div class="table-responsive ">
        <table class="table table-hover table-withoutborder text-sm" id="tabla">
          <thead class="thead-grubsa ">
            <th class="center">
              Color
            </th>
            <th class="center">
              Codigo
            </th>
            <th class="center">
              Color
            </th>
            <th class="center">
              Acciones
            </th>
          </thead>
          <tbody>
            @foreach ($colores as $color)                
            <tr>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ColoresController@show', $color->id_color)}}"  style="width: 30%;">
                {{ $color->color}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ColoresController@show', $color->id_color)}}"  style="width: 30%;">
                {{ $color->codigo_hexadecimal}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ColoresController@show', $color->id_color)}}"><div class="progress">
                      <div class="progress-bar" style="width:100%; background-color: {{ $color->codigo_hexadecimal }};"></div>
                    </div>
                </a>
              </td>
              <td class="center-acciones">
                <a href="{{URL::action('ColoresController@show', $color->id_color)}}"  style="width: 30%;"><button class="btn-ico"><i class="fas fa-pencil-alt"></i></button></a>
                @if (auth()->user()->rol == 3)
                <a href="{{URL::action('ColoresController@destroy', $color->id_color)}}"  style="width: 30%;"><button class="btn-ico"><i class="fas fa-trash-alt"></i></button></a>               
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  {{-- {{$colores->render()}} --}}
</div>
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-uso_operacion">
  {{ Form::open(array('action'=>array('ColoresController@store'),'method'=>'post')) }}
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title text-dark">Nuevo color</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="nuevo_color_mdl">*Color</label>
                          <input type="text" name="nuevo_color_mdl" id="nuevo_color_mdl" value=""  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="nuevo_codigo_mdl">*Codigo</label>
                          <input type="color" name="nuevo_codigo_mdl" id="nuevo_codigo_mdl" value=""  class="letrasModal form-control" required="true" />
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