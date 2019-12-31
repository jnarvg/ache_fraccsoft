@extends('layouts.admin')
@section('title')
Estatus propiedad
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
        <table class="table table-hover table-withoutborder text-sm" id="tabla_estatus">
          <thead class="thead-grubsa ">
            <th class="center">
              Id
            </th>
            <th class="center">
              Estatus propiedad
            </th>
            <th class="center">
              Color
            </th>
            <th class="center">
              Representacion
            </th>
            <th class="center">
              Acciones
            </th>
          </thead>
          <tbody>
            @foreach ($estatus_propiedades as $estatus)                
            <tr>
              <td class="center">
                {{ $estatus->id_estatus_propiedad }}
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('EstatusPropiedadController@show', $estatus->id_estatus_propiedad)}}"  style="width: 30%;">
                {{ $estatus->estatus_propiedad}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('EstatusPropiedadController@show', $estatus->id_estatus_propiedad)}}"  style="width: 30%;">
                {{ $estatus->color}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('EstatusPropiedadController@show', $estatus->id_estatus_propiedad)}}"><div class="progress">
                      <div class="progress-bar" style="width:100%; background-color: {{ $estatus->codigo_hx }};"></div>
                    </div>
                </a>
              </td>
              <td class="center-acciones">
                <a href="{{URL::action('EstatusPropiedadController@show', $estatus->id_estatus_propiedad)}}"  style="width: 30%;"><button class="btn-ico" ><i class="fas fa-pencil-alt"></i></button></a>
                @if (\Auth()->user()->rol == 3)
                <a href="#" data-target="#modal-delete{{$estatus->id_estatus_propiedad}}" data-toggle="modal" style="width: 30%;"><button class="btn-ico"><i class="fas fa-trash"></i></button></a>             
                @endif
              </td>
            </tr>
            @include('catalogos.estatus_propiedad.modal')
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  {{-- {{$estatus_propiedades->render()}} --}}
</div>
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-uso_operacion">
  {{ Form::open(array('action'=>array('EstatusPropiedadController@store'),'method'=>'post')) }}
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title text-dark">Nuevo estatus propiedad</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="nuevo_estatus_mdl">*Estatus propiedad</label>
                          <input type="text" name="nuevo_estatus_mdl" id="nuevo_estatus_mdl" value=""  class="letrasModal form-control" required="true" />
                      </div>
                  </div>               
                  <div class="col-md-12">
                    <div class="form-group">
                        <label for="nuevo_color">*Color</label>
                        <select name="nuevo_color" id="nuevo_color" class="letrasModal form-control" required="true" >
                            @foreach ($colores as $color)
                                <option value="{{ $color->id_color }}">{{ $color->color }}</option>
                            @endforeach
                        </select>
                    </div>
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="nuevo_codigo">*Codigo color</label>
                          <input type="color" name="nuevo_codigo" id="nuevo_codigo"  class="letrasModal form-control" required="true" readonly="true" />
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
      $("#nuevo_color").on('change', function(){

        color = $('#nuevo_color').val();
        $.ajax({

        type: "GET",
        url: "/catalogo-colores/" + color,
        success: function(data) {
             var htmlOptions = [];
             if( data.length ){
                  for( item in data ) {
                    //en caso de ser un select
                    html = data[item].codigo_hexadecimal;
                  }

                  //en caso de ser un input
                  $("#nuevo_codigo").val(html);
                  
                  // se agregan las opciones del catalogo en caso de ser un select 
                  //selectInmueble.empty().append( htmlOptions.join('') );
               }
        },
          error: function(error) {
          alert("No se pudo cargar el catalogo de colores");
        }
        })

      });
    });
</script>
@endpush 
@endsection