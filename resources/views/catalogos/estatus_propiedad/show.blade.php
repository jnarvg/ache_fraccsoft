@extends('layouts.admin')
@section('title')
Estatus propiedad
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            {{ Form::open(array('action'=>array('EstatusPropiedadController@update', $EstatusPropiedad->id_estatus_propiedad),'method'=>'post')) }}
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="estatus_propiedad">*Estatus propiedad</label>
                        <input type="text" name="estatus_propiedad" id="estatus_propiedad" value="{{ $EstatusPropiedad->estatus_propiedad }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="color_id">*Color</label>
                        <select name="color_id" id="color_id" class="letrasModal form-control" required="true" >
                            @foreach ($colores as $color)
                                @if( $color->id_color == $EstatusPropiedad->color_id)
                                    <option selected="true" value="{{ $color->id_color }}">{{ $color->color }}</option>
                                @else
                                    <option value="{{ $color->id_color }}">{{ $color->color }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="codigo_hx">*Codigo color</label>
                        <input type="color" name="codigo_hx" id="codigo_hx" value="{{ $EstatusPropiedad->codigo_hx }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                
                <div class="col-md-3 offset-md-3">
                    <div class="form-group">
                        <a href="{{ route('estatus_propiedad') }}" class="btn btn-dark btn-block">CANCELAR</a>
                    </div>
                </div> 
                <div class="col-md-3">
                    <div class="form-group">
                        <button type="submit" class="btn btn-info btn-block">GUARDAR</button>
                    </div>
                </div>
            </div>
            {{ Form::close()}}
        </div>
    </div>
</div>

@push('scripts')
<script>
  jQuery(document).ready(function($)
    {

      $("#color_id").on('change', function(){

        color = $('#color_id').val();

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
                  $("#codigo_hx").val(html);
                  
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