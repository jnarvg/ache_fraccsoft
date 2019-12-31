@extends('layouts.admin')
@section('title')
Propiedades
@endsection
@section('filter')
  <a href="#" data-toggle="modal" data-target="#modal_nuevo"><button class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus"></i></button></a>
  {{ Form::open(array('action'=>array('PropiedadController@exportExcel'),'method'=>'get', 'class'=>'d-sm-inline-block')) }}
      <input type="hidden" class="form-control" placeholder="Nombre" name="nombre_excel" id="nombre_excel" value="{{ $request->nombre_bs }}">
      <input type="hidden" class="form-control" placeholder="Proyecto" name="proyecto_excel" id="proyecto_excel" value="{{ $request->proyecto_bs }}">
      <input type="hidden" class="form-control" placeholder="Estatus" name="estatus_excel" id="estatus_excel" value="{{ $request->estatus_bs }}">
      <input type="hidden" class="form-control" placeholder="Estatus" name="uso_propiedad_excel" id="uso_propiedad_excel" value="{{ $request->uso_propiedad_bs }}">
      <button class="btn btn-primary mb-0 d-sm-inline-block btn-sm shadow-sm"><i class="fas fa-cloud-download-alt text-xl"></i></button>
  {!! Form::close() !!}
  <button href="#" data-toggle="collapse" data-target="#filtros" role="button" aria-expanded="false" aria-controls="filtros" class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-filter"></i></button>
@endsection
@section('content')
<div class="content mt-3">
  <div class="card collapse" id="filtros">
    <div class="card-body">
      {!! Form::open(array('route'=>'propiedades', 'method'=>'get', 'autocomplete'=>'off')) !!}
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label for="nombre_bs">Nombre</label>
              <input type="text" class="form-control" placeholder="Nombre propiedad" name="nombre_bs" id="nombre_bs" value="{{ $request->nombre_bs }}">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="proyecto_bs">Proyecto</label>
              <select class="form-control" placeholder="Proyecto" name="proyecto_bs" id="proyecto_bs">
                <option value="Vacio"></option>
                @foreach ($proyectos as $e)
                  @if ($e->id_proyecto == $request->proyecto_bs)
                  <option selected value="{{ $e->id_proyecto }}">{{ $e->nombre }}</option>
                  @else
                  <option value="{{ $e->id_proyecto }}">{{ $e->nombre }}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="estatus_bs">Estatus propiedad</label>
              <select class="form-control" placeholder="Estatus" name="estatus_bs" id="estatus_bs">
                <option value="Vacio">Estatus..</option>
                @foreach ($estatus_propiedad as $e)
                  @if ($e->estatus_propiedad == $request->estatus_bs)
                  <option selected value="{{ $e->estatus_propiedad }}">{{ $e->estatus_propiedad }}</option>
                  @else
                  <option value="{{ $e->estatus_propiedad }}">{{ $e->estatus_propiedad }}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="uso_propiedad_bs">Uso propiedad</label>
              <select class="form-control" placeholder="Estatus" name="uso_propiedad_bs" id="uso_propiedad_bs">
                <option value="Vacio"></option>
                @foreach ($uso_propiedad as $e)
                  @if ($e->id_uso_propiedad == $request->uso_propiedad_bs)
                  <option selected value="{{ $e->id_uso_propiedad }}">{{ $e->uso_propiedad }}</option>
                  @else
                  <option value="{{ $e->id_uso_propiedad }}">{{ $e->uso_propiedad }}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <button type="submit" class="btn btn-info down" ><span class="glyphicon"><i class="fas fa-search"></i></span></button>
            </div>
          </div>
        </div>
      {!! Form::close() !!}
    </div>
  </div>
  <div class="card">
    <div class="card-body">
      <div class="table-responsive ">
        <table class="table table-hover table-withoutborder text-sm" id="tabla">
          <thead class="thead-grubsa ">
            <th class="center">
              Propiedad
            </th>
            <th class="center">
              Proyecto
            </th>
            <th class="center">
              Uso
            </th>
            <th class="center">
              Tipo
            </th>
            <th class="center">
              Precio
            </th>
            <th class="center">
              Estado
            </th>
            <th class="center">
              Ciudad
            </th>
            <th class="center">
              Estatus
            </th>
            <th class="center">
              Acciones
            </th>
          </thead>
          <tbody >
            @foreach ($propiedades as $propiedad)                
            <tr>
              <td class="center">
                <a class="text-dark" href="{{URL::action('PropiedadController@show', [$propiedad->id_propiedad, $procedencia, $padre = 'Menu'] )}}"  >
                {{ $propiedad->nombre}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('PropiedadController@show', [$propiedad->id_propiedad, $procedencia, $padre = 'Menu'] )}}"  >
                {{ $propiedad->proyecto}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('PropiedadController@show', [$propiedad->id_propiedad, $procedencia, $padre = 'Menu'] )}}"  >
                {{ $propiedad->uso_propiedad}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('PropiedadController@show', [$propiedad->id_propiedad, $procedencia, $padre = 'Menu'] )}}"  >
                {{ $propiedad->tipo_modelo}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('PropiedadController@show', [$propiedad->id_propiedad, $procedencia, $padre = 'Menu'] )}}"  >
                  $ {{ number_format($propiedad->precio , 2 , "." , ",") }}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('PropiedadController@show', [$propiedad->id_propiedad, $procedencia, $padre = 'Menu'] )}}"  >
                {{ $propiedad->estado}}</a>
              </td class="center">
              <td class="center">
                <a class="text-dark" href="{{URL::action('PropiedadController@show', [$propiedad->id_propiedad, $procedencia, $padre = 'Menu'] )}}"  >
                {{ $propiedad->ciudad}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('PropiedadController@show', [$propiedad->id_propiedad, $procedencia, $padre = 'Menu'] )}}"  >
                {{ $propiedad->estatus_propiedad}}</a>
              </td>
              <td class="center-acciones text-xs">
                <a href="{{URL::action('PropiedadController@show', [$propiedad->id_propiedad, $procedencia, $padre = 'Menu'] )}}"  ><button class="btn-ico" ><i class="fas fa-pencil-alt"></i></button></a>
                <a href="#" data-target="#modal-delete{{$propiedad->id_propiedad}}" data-toggle="modal" ><button class="btn-ico" ><i class="fas fa-trash"></i></button></a>
              </td>
            </tr>
            @include('propiedades.modal')
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  {{-- {{$propiedades->appends(Request::only('nombre_bs','estatus_bs','estado_bs','ciudad_bs','id_bs','proyecto_bs','rows_per_page'))->render()}} --}}
</div>
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal_nuevo">
  {{ Form::open(array('action'=>array('PropiedadController@store'),'method'=>'post', 'files'=>true)) }}
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title text-dark">Nueva propiedad</h4>
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
              @if (auth()->user()->id == 1)
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="tipo_propiedad">*Tipo de propiedad sistema</label>
                        <select class="form-control" id="tipo_propiedad" name="tipo_propiedad" required="true">
                          <option value="Vacio">Selecciona...</option>
                          @foreach ($tipo_propiedad as $p)
                            @if ($p->tipo_propiedad == 'Tipo unidad')
                              <option selected value="{{ $p->id_tipo_propiedad }}">{{ $p->tipo_propiedad }}</option>
                            @else
                            <option value="{{ $p->id_tipo_propiedad }}">{{ $p->tipo_propiedad }}</option>
                            @endif
                          @endforeach
                        </select>
                    </div>
                </div>
              @else
                <div class="col-md-4 oculto">
                    <div class="form-group">
                        <label for="tipo_propiedad">*Tipo de propiedad sistema</label>
                        <select class="form-control" id="tipo_propiedad" name="tipo_propiedad" required="true">
                          <option value="Vacio">Selecciona...</option>
                          @foreach ($tipo_propiedad as $p)
                            @if ($p->tipo_propiedad == 'Tipo unidad')
                              <option selected value="{{ $p->id_tipo_propiedad }}">{{ $p->tipo_propiedad }}</option>
                            @else
                            <option value="{{ $p->id_tipo_propiedad }}">{{ $p->tipo_propiedad }}</option>
                            @endif
                          @endforeach
                        </select>
                    </div>
                </div>
              @endif
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="tipo_modelo">*Tipo </label>
                      <select class="form-control" id="tipo_modelo" name="tipo_modelo" required="true">
                        <option value="Vacio">Selecciona...</option>
                        @foreach ($tipo_modelo as $p)
                          <option value="{{ $p->id_tipo_modelo }}">{{ $p->tipo_modelo }}</option>
                        @endforeach
                      </select>
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="proyecto">*Proyecto</label>
                      <select class="form-control" id="proyecto" name="proyecto" required="true">
                        <option value="Vacio">Selecciona...</option>
                        @foreach ($proyectos as $p)
                          <option value="{{ $p->id_proyecto }}">{{ $p->nombre }}</option>
                        @endforeach
                      </select>
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="nivel">*Nivel</label>
                      <select class="form-control" id="nivel" name="nivel" required="true">
                        <option value="Vacio">Selecciona...</option>
                      </select>
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="manzana">Manzana</label>
                      <input type="text" name="manzana" id="manzana"  class="letrasModal form-control" />
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="numero">Numero</label>
                      <input type="text" name="numero" id="numero" class="letrasModal form-control" />
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="cuenta_catastral">Cuenta catastral</label>
                      <input type="text" name="cuenta_catastral" id="cuenta_catastral" value=""  class="letrasModal form-control" />
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="uso_propiedad">Uso de la propiedad</label>
                      <select class="form-control" id="uso_propiedad" name="uso_propiedad" required="true">
                        <option value="Vacio">Selecciona...</option>
                        @foreach ($uso_propiedad as $p)
                          <option value="{{ $p->id_uso_propiedad }}">{{ $p->uso_propiedad }}</option>
                        @endforeach
                      </select>
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="estatus_propiedad">*Estatus</label>
                      <select class="form-control" id="estatus_propiedad" name="estatus_propiedad">
                        @foreach ($estatus_propiedad as $p)
                          <option value="{{ $p->id_estatus_propiedad }}">{{ $p->estatus_propiedad }}</option>
                        @endforeach
                      </select>
                  </div>
              </div>
              <div class="col-md-12">
                  <h3><em>Precios</em></h3>
                  <hr class="hr-titulo" width="100%" size="10">
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="precio">*Precio</label>
                      <input type="text" step="any" name="precio" id="precio" placeholder="0.00" class="mask form-control"  />
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="enganche">*Enganche</label>
                      <input type="text" step="any" name="enganche" id="enganche" placeholder="0.00"  class="mask form-control"  />
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="moneda">Moneda</label>
                      <select class="form-control" id="moneda" name="moneda">
                        <option value="Vacio">Seleccione...</option>
                        @foreach ($monedas as $m)
                        <option value="{{ $m->id_moneda }}">{{ $m->siglas }}</option>
                        @endforeach
                      </select>
                  </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                    <label for="fecha_registro">*Fecha de registro</label>
                    <input type="date" name="fecha_registro" id="fecha_registro" value="{{ date('Y-m-d') }}"  class="letrasModal form-control" required="true" />
                </div>
              </div>
              <div class="col-md-12">
                  <h3><em>Ubicacion</em></h3>
                  <hr class="hr-titulo" width="100%" size="10">
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
                      <select class="form-control" id="estado" name="estado">
                        <option value="Vacio">Selecciona...</option>
                      </select>
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="ciudad">Ciudad</label>
                      <select class="form-control" id="ciudad" name="ciudad">
                        <option value="Vacio">Selecciona...</option>
                      </select>
                  </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                    <label for="codigo_postal">Codigo Postal</label>
                    <input type="text" name="codigo_postal" id="codigo_postal" value=""  class="letrasModal form-control"  />
                </div>
              </div>
              <div class="col-md-8">
                  <div class="form-group">
                      <label for="direccion">Direccion</label>
                      <input type="text" name="direccion" id="direccion" value=""  class="letrasModal form-control" />
                  </div>
              </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="descripcion_corta">Descripcion</label>
                        <textarea rows="5" name="descripcion_corta" class="form-control"></textarea>
                    </div>
                </div>
                <div class="col-md-6 oculto">
                    <div class="form-group">
                        <label for="condicion">Condicion</label>
                        <textarea rows="5" name="condicion" class="form-control"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                  <h3><em>Caracteristicas de la propiedad</em></h3>
                  <hr class="hr-titulo" width="100%" size="10">
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="construccion_metros">Metros de construccion</label>
                      <input type="number" step="any" name="construccion_metros" id="construccion_metros" value=""  class="letrasModal form-control"  />
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="terreno_metros">Metros de terreno</label>
                      <input type="number" step="any" name="terreno_metros" id="terreno_metros" value=""  class="letrasModal form-control"  />
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="metros_contrato">Metros terreno contrato</label>
                      <input type="number" step="any" name="metros_contrato" id="metros_contrato"  class="letrasModal form-control"  />
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="metros_fondo">Metros fondo</label>
                      <input type="number" step="any" name="metros_fondo" id="metros_fondo"   class="letrasModal form-control"  />
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="metros_frente">Metros frente</label>
                      <input type="number" step="any" name="metros_frente" id="metros_frente"   class="letrasModal form-control"  />
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="mts_interior">Metros interior</label>
                      <input type="number" step="any" name="mts_interior" id="mts_interior" class="letrasModal form-control"  />
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="mts_exterior">Metros exteriores</label>
                      <input type="number" step="any" name="mts_exterior" id="mts_exterior" class="letrasModal form-control"  />
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="precio_mts_interior">$ Metros Interior</label>
                      <input type="number" step="any" name="precio_mts_interior" id="precio_mts_interior"   class="letrasModal form-control"  />
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="precio_mts_exterior">$ Metros Exterior</label>
                      <input type="number" step="any" name="precio_mts_exterior" id="precio_mts_exterior"   class="letrasModal form-control"  />
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="recamaras">Recamaras</label>
                      <input type="text" name="recamaras" id="recamaras" value=""  class="letrasModal form-control"/>
                  </div>
              </div>
              <div class="col-md-4 oculto">
                  <div class="form-group">
                      <label for="banos">Ba&ntilde;os</label>
                      <input type="text" name="banos" id="banos" value=""  class="letrasModal form-control"  />
                  </div>
              </div>
              <div class="col-md-4 oculto">
                  <div class="form-group">
                      <label for="area_rentable_metros">Area rentable</label>
                      <input type="number" step="any" name="area_rentable_metros" id="area_rentable_metros" value=""  class="letrasModal form-control" />
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="cajones_estacionamiento">Cajones de estacionamiento</label>
                      <input type="number" name="cajones_estacionamiento" id="cajones_estacionamiento" value=""  class="letrasModal form-control" />
                  </div>
              </div>
              <div class="col-md-4 oculto">
                  <div class="form-group">
                      <label for="vigilancia">Vigilancia</label>
                      <input type="text" name="vigilancia" id="vigilancia" value=""  class="letrasModal form-control"/>
                  </div>
              </div>
              <div class="col-md-4 oculto">
                  <div class="form-group">
                      <label for="sala_tv">Sala de TV</label>
                      <select class="form-control" id="sala_tv" name="sala_tv">
                        <option value="1">Si</option>
                        <option value="0">No</option>
                      </select>
                  </div>
              </div>
              <div class="col-md-4 oculto">
                  <div class="form-group">
                      <label for="cuarto_servicio">Cuarto de servicio</label>
                      <select class="form-control" id="cuarto_servicio" name="cuarto_servicio">
                        <option value="1">Si</option>
                        <option value="0">No</option>
                      </select>
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <h3><em>Descripciones de la propiedad</em></h3>
                <hr class="hr-titulo" width="100%" size="10">
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                    <label for="infraestructura">Infraestructura</label>
                    <textarea rows="5" name="infraestructura" class="form-control"></textarea>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                    <label for="terreno">Terreno</label>
                    <textarea rows="5" name="terreno" class="form-control"></textarea>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                    <label for="construccion">Construccion</label>
                    <textarea rows="5" name="construccion" class="form-control"></textarea>
                </div>
              </div>
              <div class="col-md-4 oculto">
                <div class="form-group">
                    <label for="area_rentable">Area rentable</label>
                    <textarea rows="5" name="area_rentable" class="form-control"></textarea>
                </div>
              </div>
              <div class="col-md-4 oculto">
                <div class="form-group">
                    <label for="estacionamiento">Estacionamiento</label>
                    <textarea rows="5" name="estacionamiento" class="form-control"></textarea>
                </div>
              </div>
              <div class="col-md-4 oculto">
                <div class="form-group">
                    <label for="acabados">Acabados</label>
                    <textarea rows="5" name="acabados" class="form-control"></textarea>
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
      $('#tabla').DataTable();
      $("#pais").on('change', function(){
        pais = $('#pais').val();
        selectEstado = $('#estado');
        selectEstado.empty().append('<option>Cargando estados</option>');

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
                  // se agregan las opciones del catalogo en caso de ser un select 
                  selectEstado.empty().append( htmlOptions.join('') );
              }else{
                html = '<option value="Vacio" disabled="true" selected="true">No hay estados</option>';
                htmlOptions[htmlOptions.length] = html;
                selectEstado.empty().append( htmlOptions.join('') );
              }
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
        },
          error: function(error) {
          alert("No se pudo cargar el catalogo de ciudades");
        }
        })
      });
      $("#proyecto").on('change', function(){
        proyecto = $('#proyecto').val();
        selectNivel = $('#nivel');
        selectPais = $('#pais');
        selectEstado = $('#estado');
        selectCiudad = $('#ciudad');
        selectNivel.empty().append('<option>Cargando niveles</option>');
        
        $.ajax({

        type: "GET",
        url: "/catalogo-niveles/" + proyecto,
        success: function(data) {
              var htmlOptions = [];
              if( data.length ){
                  html = '<option value="Vacio" selected="true">Selecciona...</option>';
                  htmlOptions[htmlOptions.length] = html;
                  for( item in data ) {
                    //en caso de ser un select
                    html = '<option value="' + data[item].id_nivel + '">' + data[item].nivel + '</option>';
                    
                    htmlOptions[htmlOptions.length] = html;
                  }
                  
                  selectNivel.empty().append( htmlOptions.join('') );

              }else{
                html = '<option value="Vacio" selected="true">No hay niveles</option>';
                htmlOptions[htmlOptions.length] = html;
                selectNivel.empty().append( htmlOptions.join('') );
              }
        },
          error: function(error) {
          alert("No se pudo cargar el catalogo");
        }
        })

        $.ajax({

        type: "GET",
        url: "/catalogo-proyectos/" + proyecto,
        success: function(data) {
              var htmlOptions_pais = [];
              var htmlOptions_estado = [];
              var htmlOptions_ciudad = [];
              if( data.length ){
                  for( item in data ) {
                    //en caso de ser un select
                    html_pais = '<option value="' + data[item].pais_id + '">' + data[item].pais + '</option>';
                    html_estado = '<option value="' + data[item].estado_id + '">' + data[item].estado + '</option>';
                    html_ciudad = '<option value="' + data[item].ciudad_id + '">' + data[item].ciudad + '</option>';

                    direccion = data[item].direccion;
                    
                    htmlOptions_pais[htmlOptions_pais.length] = html_pais;
                    htmlOptions_estado[htmlOptions_estado.length] = html_estado;
                    htmlOptions_ciudad[htmlOptions_ciudad.length] = html_ciudad;
                  }
                  if( htmlOptions_pais != '<option value="null">null</option>'){
                    selectPais.empty().append( htmlOptions_pais.join('') );
                    selectEstado.empty().append( htmlOptions_estado.join('') );
                    selectCiudad.empty().append( htmlOptions_ciudad.join('') );
                  }
                  $('#direccion').val(direccion);
              }else{
                html = '<option value="Vacio" selected="true">No hay niveles</option>';
                htmlOptions[htmlOptions.length] = html;
                selectNivel.empty().append( htmlOptions.join('') );
              }
        },
          error: function(error) {
          alert("No se pudo cargar el catalogo");
        }
        })
      });
      $("#sala_tv_check").change(function(){
        var sala =  $("#sala_tv_check");
      });
    });
</script>
@endpush 
@endsection