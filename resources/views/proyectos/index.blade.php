@extends('layouts.admin')
@section('title')
Proyectos
@endsection
@section('filter')
  <a href="#" data-toggle="modal" data-target="#modal_nuevo"><button class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus"></i></button></a>
  <button href="#" data-toggle="collapse" data-target="#filtros" role="button" aria-expanded="false" aria-controls="filtros" class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-filter"></i></button>
@endsection
@section('content')
<div class="content mt-3">
  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover text-sm" id="dataTable_proyecto" width="100%" cellspacing="0">
          <thead class="thead-grubsa ">
            <th class="center">
              Proyecto
            </th>
            <th class="center">
              Metros contruccion
            </th>
            <th class="center">
              Metros terreno
            </th>
            <th class="center">
              País
            </th>
            <th class="center">
              Estado
            </th>
            <th class="center">
              Ciudad
            </th>
            <th class="center">
              Acciones
            </th>
          </thead>
          <tbody>
            @foreach ($proyectos as $proyecto)                
            <tr>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ProyectosController@show', $proyecto->id_proyecto)}}"  style="width: 30%;">
                {{ $proyecto->nombre}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ProyectosController@show', $proyecto->id_proyecto)}}"  style="width: 30%;">
                {{ $proyecto->metros_construccion}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ProyectosController@show', $proyecto->id_proyecto)}}"  style="width: 30%;">
                {{ $proyecto->metros_terreno}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ProyectosController@show', $proyecto->id_proyecto)}}"  style="width: 30%;">
                {{ $proyecto->pais}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ProyectosController@show', $proyecto->id_proyecto)}}"  style="width: 30%;">
                {{ $proyecto->estado}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('ProyectosController@show', $proyecto->id_proyecto)}}"  style="width: 30%;">
                {{ $proyecto->ciudad}}</a>
              </td>
              <td class="center-acciones">
                <a href="{{URL::action('ProyectosController@show', $proyecto->id_proyecto)}}"  style="width: 30%;"><button class="btn-ico"><i class="fas fa-pencil-alt"></i></button></a>
                <a href="#" data-target="#modal-delete{{$proyecto->id_proyecto}}" data-toggle="modal" style="width: 30%;"><button class="btn-ico"><i class="fas fa-trash"></i></button></a>
              </td>
            </tr>
            @include('proyectos.modal')
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal_nuevo">
  {{ Form::open(array('action'=>array('ProyectosController@store'),'method'=>'post', 'files'=>true)) }}
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title text-dark">Nuevo proyecto</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="nombre">*Nombre</label>
                          <input type="text" name="nombre" id="nombre" value="" minlength="2" class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-4 oculto">
                      <div class="form-group">
                          <label for="cuenta_catastral">Cuenta catastral</label>
                          <input type="text" name="cuenta_catastral" id="cuenta_catastral" value=""  class="letrasModal form-control" />
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="metros_construccion">Metros construccion</label>
                          <input type="number" step="any" name="metros_construccion" id="metros_construccion" value="0"  class="letrasModal form-control" />
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="metros_terreno">Metros terreno</label>
                          <input type="number" step="any" name="metros_terreno" id="metros_terreno" value="0"  class="letrasModal form-control" />
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                        <label for="nuevo_observacion_mdl">*Plano</label>
                        <div class="image-upload">
                          <label for="file-input">
                              <img src="{{ asset('/images/iconos/camara-de-fotos.png') }}" alt ="Click aquí para subir tu foto" class="ico-cam" title ="Click aquí para subir tu foto" > 
                          </label> 
                          <input id="file-input" class="Images" name="file-input" type="file" accept="image/*" capture />
                          <img id="blah-file-input" class="preview" src="" width="30%" alt="" title="" style="padding-left: 10%;" />
                        </div>
                      </div>
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                          <h4>Datos ubicación</h4>
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="calle">Calle</label>
                          <input type="text" name="calle" id="calle" value=""  class="letrasModal form-control" />
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="num_exterior"># Exterior</label>
                          <input type="text" name="num_exterior" id="num_exterior" value=""  class="letrasModal form-control" />
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="num_interior"># Interior</label>
                          <input type="text" name="num_interior" id="num_interior" value=""  class="letrasModal form-control" />
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="colonia">Colonia</label>
                          <input type="text" name="colonia" id="colonia" value=""  class="letrasModal form-control" />
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="codigo_postal">Codigo postal</label>
                          <input type="text" name="codigo_postal" id="codigo_postal" value=""  class="letrasModal form-control" />
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="pais">Pais</label>
                          <select class="form-control" id="pais" name="pais">
                            <option value="Vacio">Selecciona...</option>
                            @foreach ($paises as $p)
                              <option value="{{ $p->id_pais }}">{{ $p->pais }}</option>
                            @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="estado">Estado</label>
                          <select class="form-control selectpicker" data-live-search="true" id="estado" name="estado">
                            <option value="Vacio">Selecciona...</option>
                          </select>
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="ciudad">Ciudad</label>
                          <select class="form-control selectpicker" data-live-search="true" id="ciudad" name="ciudad">
                            <option value="Vacio">Selecciona...</option>
                          </select>
                      </div>
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="direccion">Direccion</label>
                          <input type="text" name="direccion" id="direccion" value=""  class="letrasModal form-control" />
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
  function construir_domicilio() {
      if ($('#num_interior').val() != '' ) {
          var num_interior = ' - ' + $('#num_interior').val();
      }else{
          var num_interior = '';
      }
      var domicilio = $('#calle').val() + ' #' + $('#num_exterior').val() + '' + num_interior + ', Col. ' + $('#colonia').val() + ', ' + $('select[name="ciudad"] option:selected').text() + ', ' + $('select[name="estado"] option:selected').text() + ', ' + $('select[name="pais"] option:selected').text() + '. ' + $('#codigo_postal').val();

      $('#direccion').val(domicilio);
  }
  jQuery(document).ready(function($)
    {
      $('#dataTable_proyecto').DataTable();
      $("#pais").on('change', function(){
        pais = $('#pais').val();
        selectEstado = $('#estado');
        selectEstado.empty().append('<option>Cargando paises</option>');
        construir_domicilio();

        $.ajax({

        type: "GET",
        url: "/catalogo-estados/" + pais,
        success: function(data) {
              var htmlOptions = [];
              if( data.length ){
                  html = '<option value="Vacio" disabled="true" selected="true">Selecciona...</option>';
                  htmlOptions[htmlOptions.length] = html;
                  for( item in data ) {
                    //en caso de ser un select
                    html = '<option value="' + data[item].id_estado + '">' + data[item].estado + '</option>';
                    htmlOptions[htmlOptions.length] = html;
                  }

                  //en caso de ser un input
                  //$("#precio_propiedadctz").val(html);
                  
                  // se agregan las opciones del catalogo en caso de ser un select 
                  selectEstado.empty().append( htmlOptions.join('') );
              }else{
                html = '<option value="Vacio" disabled="true" selected="true">No hay estados</option>';
                htmlOptions[htmlOptions.length] = html;
                selectEstado.empty().append( htmlOptions.join('') );
              }
              selectEstado.selectpicker('refresh');
        },
          error: function(error) {
          alert("No se pudo cargar el catalogo de estados");
        }
        })
      });
      $("#estado").on('change', function(){
        pais = $('#estado').val();
        selectCiudad = $('#ciudad');
        selectCiudad.empty().append('<option>Cargando paises</option>');
         construir_domicilio();

        $.ajax({

        type: "GET",
        url: "/catalogo-ciudades/" + pais,
        success: function(data) {
              var htmlOptions = [];
              if( data.length ){
                  html = '<option value="Vacio" disabled="true" selected="true">Selecciona...</option>';
                  htmlOptions[htmlOptions.length] = html;
                  for( item in data ) {
                    //en caso de ser un select
                    html = '<option value="' + data[item].id_ciudad + '">' + data[item].ciudad + '</option>';
                    htmlOptions[htmlOptions.length] = html;
                  }

                  //en caso de ser un input
                  //$("#precio_propiedadctz").val(html);
                  
                  // se agregan las opciones del catalogo en caso de ser un select 
                  selectCiudad.empty().append( htmlOptions.join('') );
              }else{
                html = '<option value="Vacio" disabled="true" selected="true">No hay ciudades</option>';
                htmlOptions[htmlOptions.length] = html;
                selectCiudad.empty().append( htmlOptions.join('') );
              }
              selectCiudad.selectpicker('refresh');
        },
          error: function(error) {
          alert("No se pudo cargar el catalogo de ciudades");
        }
        })
      });
      $("#ciudad").on('change', function(){
            
          construir_domicilio();
      });
      $('#calle').keyup(function() {
          construir_domicilio();
      });
      $('#num_exterior').keyup(function() {
          construir_domicilio();
      });

      $('#num_interior').keyup(function() {
          construir_domicilio();
      });
      $('#codigo_postal').keyup(function() {
          construir_domicilio();
      });
      $('#colonia').keyup(function() {
          construir_domicilio();
      });
    });
</script>
<script type="application/javascript">
    
    jQuery('input[type=file]').change(function(){
     var filename = jQuery(this).val().split('\\').pop();
     var idname = jQuery(this).attr('id');
     jQuery('span.'+idname).next().find('span').html(filename);
    });
</script>
<script src="{{ asset('js/uploadfotos.js') }}"></script>
@endpush 
@endsection