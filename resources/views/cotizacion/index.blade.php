@extends('layouts.admin')
@section('title')
Cotizacion
@endsection
@section('filter')
  <a href="{{ route('cotizacion-todas') }}"><button class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm">Todas las cotizaciones</button></a>
@endsection
@section('content')
<div class="content mt-6">
  <div class="card">
    <div class="card-body">
      {{ Form::open(array('action'=>array('CotizacionController@continuar', 'Menu'),'method'=>'post')) }}
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="agente">Agente</label>
            <input type="text" name="agente" id="agente" value="{{ $agente->name }}"  class="letrasModal form-control" required="true" />
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="cliente">*Nombre cliente</label>
            <input type="text" name="cliente" id="cliente" value=""  class="letrasModal form-control" required="true" />
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="telefono">Telefono</label>
            <input type="text" name="telefono" id="telefono" value=""  class="letrasModal form-control"  />
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="correo">Correo</label>
            <input type="email" name="correo" id="correo" value=""  class="letrasModal form-control"  />
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="moneda">Moneda</label>
            <select name="moneda" id="moneda" class="letrasModal form-control" required="true">
              @foreach ($monedas as $m)
                <option value="{{ $m->id_moneda }}">{{ $m->siglas }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="uso_propiedad_id">Uso propiedad</label>
            <select name="uso_propiedad_id" id="uso_propiedad_id" class="letrasModal form-control" required="true">
              <option value=""></option>
              @foreach ($usos_propiedad as $m)
                <option value="{{ $m->id_uso_propiedad }}">{{ $m->uso_propiedad }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="proyecto">*Proyecto</label>
            <select class="form-control" id="proyecto" name="proyecto" required="true">
              <option value="0" disabled="true" selected="true">Seleccione un proyecto</option>
              <option value="sin proyecto">Sin proyecto</option>
              @foreach ($proyectos as $proyecto)
                <option value="{{ $proyecto->id_proyecto }}" >{{ $proyecto->nombre }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
              <label for="propiedad">Propiedad</label><br>
              <select multiple="multiple" class="form-control selectpicker" data-live-search="true" required id="propiedad" name="propiedad[]">
                <option value="">Selecciona...</option>
              </select>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="grupo_esquema_id">*Grupo de esquemas</label>
            <select class="form-control selectpicker" data-live-search="true" id="grupo_esquema_id" name="grupo_esquema_id" required="true">
              <option value=""></option>
              @foreach ($grupo_esquema as $a)
                <option value="{{ $a->id_grupo_esquema }}" >{{ $a->grupo_esquema }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3 offset-md-3">
          <div class="form-group">
              <a href="{{ route('welcome') }}" class="btn btn-dark btn-block">CANCELAR</a>
          </div>
        </div> 

        <div class="col-md-3">
          <div class="form-group">
              <button type="submit" class="btn btn-info btn-block">CONTINUAR</button>
          </div>
        </div>
      </div>
      {{ Form::close()}}
    </div>
  </div>
</div>

@push('scripts')
  <script >
    jQuery(document).ready(function($)
    {
      $("#proyecto").on('change', function(){
        desarrollo = $('#proyecto').val();
        uso_propiedad = $('#uso_propiedad_id').val();
        selectPropiedad = $('#propiedad');
        selectPropiedad.empty().append('<option>Cargando...</option>');
        selectGrupo = $('#grupo_esquema_id');
        selectGrupo.empty().append('<option>Cargando...</option>');

        $.ajax({

          type: "GET",
          url: "/catalogo-propiedades-desarrollo/" + desarrollo,
          success: function(data) {
                var htmlOptions = [];
                if( data.length ){
                    html = '<option value="" disabled="true" >Selecciona una propiedad</option>';
                    htmlOptions[htmlOptions.length] = html;
                    for( item in data ) {
                      //en caso de ser un select
                      html = '<option value="' + data[item].id_propiedad + '">' + data[item].nombre + '</option>';
                      htmlOptions[htmlOptions.length] = html;
                    }

                    //en caso de ser un input
                    //$("#precio_propiedadctz").val(html);
                    
                    // se agregan las opciones del catalogo en caso de ser un select 
                    selectPropiedad.empty().append( htmlOptions.join('') );
                }else{
                  html = '<option value="" disabled="true">No hay propiedades</option>';
                  htmlOptions[htmlOptions.length] = html;
                  selectPropiedad.empty().append( htmlOptions.join('') );
                }
                selectPropiedad.selectpicker('refresh');
          },
            error: function(error) {
            alert("No se pudo cargar el catalogo de propiedades");
          }
        });

        /*POR LOS GRUPOS DE ESQUEMAS DISPONIBLES*/
        $.ajax({

          type: "GET",
          url: "/catalogo-grupo-esquema/" + desarrollo + "/" + uso_propiedad,
          success: function(data) {
                var htmlOptions = [];
                if( data.length ){
                    html = '<option value="" >Selecciona...</option>';
                    htmlOptions[htmlOptions.length] = html;
                    for( item in data ) {
                      //en caso de ser un select
                      html = '<option value="' + data[item].id_grupo_esquema + '">' + data[item].grupo_esquema + '</option>';
                      htmlOptions[htmlOptions.length] = html;
                    }
                    
                    // se agregan las opciones del catalogo en caso de ser un select 
                    selectGrupo.empty().append( htmlOptions.join('') );

                }else{
                  html = '<option value="" disabled="true" selected="true">No hay grupos</option>';
                  htmlOptions[htmlOptions.length] = html;
                  selectGrupo.empty().append( htmlOptions.join('') );
                }
                selectGrupo.selectpicker('refresh');
          },
            error: function(error) {
            alert("No se pudo cargar el catalogo");
          }
        });


      });

      $("#uso_propiedad_id").on('change', function(){
        desarrollo = $('#proyecto').val();
        uso_propiedad = $('#uso_propiedad_id').val();
        selectGrupo = $('#grupo_esquema_id');
        selectGrupo.empty().append('<option>Cargando...</option>');

        /*POR LOS GRUPOS DE ESQUEMAS DISPONIBLES*/
        $.ajax({

          type: "GET",
          url: "/catalogo-grupo-esquema/" + desarrollo + "/" + uso_propiedad,
          success: function(data) {
                var htmlOptions = [];
                if( data.length ){
                    html = '<option value="" >Selecciona...</option>';
                    htmlOptions[htmlOptions.length] = html;
                    for( item in data ) {
                      //en caso de ser un select
                      html = '<option value="' + data[item].id_grupo_esquema + '">' + data[item].grupo_esquema + '</option>';
                      htmlOptions[htmlOptions.length] = html;
                    }
                    
                    // se agregan las opciones del catalogo en caso de ser un select 
                    selectGrupo.empty().append( htmlOptions.join('') );

                }else{
                  html = '<option value="" disabled="true" selected="true">No hay grupos</option>';
                  htmlOptions[htmlOptions.length] = html;
                  selectGrupo.empty().append( htmlOptions.join('') );
                }
                selectGrupo.selectpicker('refresh');
          },
            error: function(error) {
            alert("No se pudo cargar el catalogo");
          }
        });

        
      });
    });

  </script>
@endpush 
@endsection