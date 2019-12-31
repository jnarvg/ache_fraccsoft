@extends('layouts.admin')
@section('title')
Propiedad
@endsection
@section('filter')
    <a href="#" class="mb-0 d-sm-inline-block btn btn-primary " id="guardar" title="GUARDAR"><i class="fas fa-check"></i></a>
    @if ($procedencia == 'propiedades')
      <a href="{{ route($procedencia) }}" class="mb-0 d-sm-inline-block btn btn-primary" title="CERRAR"><i class="fas fa-times"></i></a>
    @elseif($procedencia == 'nivel-show') 
      <a href="{{ route($procedencia, [$padre, $procedencia =>'nivel'] ) }}" class="mb-0 d-sm-inline-block btn btn-primary" title="CERRAR"><i class="fas fa-times"></i></a>
    @else
      <a href="{{ route($procedencia, $padre) }}" class="mb-0 d-sm-inline-block btn btn-primary" title="CERRAR"><i class="fas fa-times"></i></a>
    @endif
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
          {{ Form::open(array('action'=>array('PropiedadController@update',$propiedad->id_propiedad),'method'=>'post', 'id'=>'guardar_form')) }}
            <div class="row">
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="nombre">*Nombre</label>
                      <input type="text" name="nombre" id="nombre" value="{{ $propiedad->nombre }}" minlength="2" class="letrasModal form-control" required="true" />
                  </div>
              </div>
              @if (auth()->user()->id == 1)
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="tipo_propiedad">*Tipo de propiedad sistema</label>
                        <select class="form-control" id="tipo_propiedad" name="tipo_propiedad" required="true">
                          <option value="Vacio">Selecciona...</option>
                          @foreach ($tipo_propiedad as $p)
                            @if ($p->id_tipo_propiedad == $propiedad->tipo_propiedad_id)
                              <option selected="true" value="{{ $p->id_tipo_propiedad }}">{{ $p->tipo_propiedad }}</option>
                            @else
                              <option value="{{ $p->id_tipo_propiedad }}">{{ $p->tipo_propiedad }}</option>
                            @endif
                          @endforeach
                        </select>
                    </div>
                </div>
              @else
                <div class="col-md-3 oculto">
                    <div class="form-group">
                        <label for="tipo_propiedad">*Tipo de propiedad sistema</label>
                        <select class="form-control" id="tipo_propiedad" name="tipo_propiedad" required="true">
                          <option value="Vacio">Selecciona...</option>
                          @foreach ($tipo_propiedad as $p)
                            @if ($p->id_tipo_propiedad == $propiedad->tipo_propiedad_id)
                              <option selected="true" value="{{ $p->id_tipo_propiedad }}">{{ $p->tipo_propiedad }}</option>
                            @else
                              <option value="{{ $p->id_tipo_propiedad }}">{{ $p->tipo_propiedad }}</option>
                            @endif
                          @endforeach
                        </select>
                    </div>
                </div>
              @endif
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="tipo_modelo">*Tipo</label>
                      <select class="form-control" id="tipo_modelo" name="tipo_modelo" required="true">
                        <option value="Vacio">Selecciona...</option>
                        @foreach ($tipo_modelo as $p)
                          @if ($p->id_tipo_modelo == $propiedad->tipo_modelo_id)
                            <option selected="true" value="{{ $p->id_tipo_modelo }}">{{ $p->tipo_modelo }}</option>
                          @else
                            <option value="{{ $p->id_tipo_modelo }}">{{ $p->tipo_modelo }}</option>
                          @endif
                        @endforeach
                      </select>
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="proyecto">*Proyecto</label>
                      <select class="form-control" id="proyecto" name="proyecto" required="true">
                        <option value="Vacio">Selecciona...</option>
                        @foreach ($proyectos as $p)
                          @if ($p->id_proyecto == $propiedad->proyecto_id)
                            <option selected value="{{ $p->id_proyecto }}">{{ $p->nombre }}</option>
                          @else
                            <option value="{{ $p->id_proyecto }}">{{ $p->nombre }}</option>
                          @endif
                        @endforeach
                      </select>
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="nivel">*Nivel</label>
                      <select class="form-control" id="nivel" name="nivel" required="true">
                        <option value="Vacio">Selecciona...</option>
                        @foreach ($niveles as $p)
                          @if ($p->id_nivel == $propiedad->nivel_id)
                            <option selected value="{{ $p->id_nivel }}">{{ $p->nivel }}</option>
                          @else
                            <option value="{{ $p->id_nivel }}">{{ $p->nivel }}</option>
                          @endif
                        @endforeach
                      </select>
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="manzana">Manzana</label>
                      <input type="text" name="manzana" id="manzana" value="{{ $propiedad->manzana }}"  class="letrasModal form-control" />
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="numero">Numero</label>
                      <input type="text" name="numero" id="numero" value="{{ $propiedad->numero }}"  class="letrasModal form-control" />
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="cuenta_catastral">Cuenta catastral</label>
                      <input type="text" name="cuenta_catastral" id="cuenta_catastral" value="{{ $propiedad->cuenta_catastral }}"  class="letrasModal form-control" />
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="uso_propiedad">Uso de la propiedad</label>
                      <select class="form-control" id="uso_propiedad" name="uso_propiedad" required="true">
                        <option value="Vacio">Selecciona...</option>
                        @foreach ($uso_propiedad as $p)
                          @if ($p->id_uso_propiedad == $propiedad->uso_propiedad_id)
                            <option selected="true" value="{{ $p->id_uso_propiedad }}">{{ $p->uso_propiedad }}</option>
                          @else
                            <option value="{{ $p->id_uso_propiedad }}">{{ $p->uso_propiedad }}</option>
                          @endif
                        @endforeach
                      </select>
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="estatus_propiedad">*Estatus</label>
                      <select class="form-control" id="estatus_propiedad" name="estatus_propiedad">
                        @foreach ($estatus_propiedad as $p)
                          @if ($p->id_estatus_propiedad == $propiedad->estatus_propiedad_id)
                            <option selected="true" value="{{ $p->id_estatus_propiedad }}">{{ $p->estatus_propiedad }}</option>
                          @else
                            <option value="{{ $p->id_estatus_propiedad }}">{{ $p->estatus_propiedad }}</option>
                          @endif
                        @endforeach
                      </select>
                  </div>
              </div>
              <div class="col-md-12">
                  <h3><em>Precio</em></h3>
                  <hr class="hr-titulo" width="100%" size="10">
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="precio">*Precio</label>
                      <input type="text" step="any" name="precio" id="precio" value="{{ $propiedad->precio }}"  class="mask form-control"  />
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="enganche">*Enganche</label>
                      <input type="text" step="any" name="enganche" id="enganche" value="{{ $propiedad->enganche }}"  class="mask form-control"  />
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="moneda">Moneda</label>
                      <select class="form-control" id="moneda" name="moneda">
                        <option value="Vacio">Seleccione...</option>
                        @foreach ($monedas as $m)
                        @if ($m->id_moneda == $propiedad->moneda)
                        <option selected value="{{ $m->id_moneda }}">{{ $m->siglas }}</option>
                        @else
                        <option value="{{ $m->id_moneda }}">{{ $m->siglas }}</option>
                        @endif
                        @endforeach
                      </select>
                  </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                    <label for="fecha_registro">*Fecha de registro</label>
                    <input type="text" name="fecha_registro" id="fecha_registro" value="{{ date('Y-m-d',strtotime($propiedad->fecha_registro)) }}"  class="letrasModal form-control" required="true" />
                </div>
              </div>
              <div class="col-md-12">
                  <h3><em>Ubicacion</em></h3>
                  <hr class="hr-titulo" width="100%" size="10">
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="pais">Pais</label>
                      <select class="form-control" id="pais" name="pais">
                        <option value="Vacio">Selecciona...</option>
                        @foreach ($paises as $p)
                          @if ($p->id_pais == $propiedad->pais_id)
                            <option selected="true" value="{{ $p->id_pais }}">{{ $p->pais }}</option>
                          @else
                            <option value="{{ $p->id_pais }}">{{ $p->pais }}</option>
                          @endif
                        @endforeach
                      </select>
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="estado">Estado</label>
                      <select class="form-control" id="estado" name="estado">
                        <option value="Vacio">Selecciona...</option>
                        @foreach ($estados as $p)
                          @if ($p->id_estado == $propiedad->estado_id)
                            <option selected="true" value="{{ $p->id_estado }}">{{ $p->estado }}</option>
                          @else
                            <option value="{{ $p->id_estado }}">{{ $p->estado }}</option>
                          @endif
                        @endforeach
                      </select>
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="ciudad">Ciudad</label>
                      <select class="form-control" id="ciudad" name="ciudad">
                        <option value="Vacio">Selecciona...</option>
                        @foreach ($ciudades as $p)
                          @if ($p->id_ciudad == $propiedad->ciudad_id)
                            <option selected="true" value="{{ $p->id_ciudad }}">{{ $p->ciudad }}</option>
                          @else
                            <option value="{{ $p->id_ciudad }}">{{ $p->ciudad }}</option>
                          @endif
                        @endforeach
                      </select>
                  </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                    <label for="codigo_postal">Codigo Postal</label>
                    <input type="text" name="codigo_postal" id="codigo_postal" value="{{ $propiedad->codigo_postal }}"  class="letrasModal form-control"  />
                </div>
              </div>
              <div class="col-md-12">
                  <div class="form-group">
                      <label for="direccion">Direccion</label>
                      <input type="text" name="direccion" id="direccion" value="{{ $propiedad->direccion }}"  class="letrasModal form-control" />
                  </div>
              </div>
              @if (auth()->user()->id == 1)
                <div class="col-md-12">
                  <div class="form-group">
                      <label for="coordenadas">Coordenadas</label>
                      <input type="text" name="coordenadas" id="coordenadas" value="{{ $propiedad->coordenadas }}"  class="letrasModal form-control"  />
                  </div>
                </div>
              @else
                <div class="col-md-12 oculto">
                  <div class="form-group">
                      <label for="coordenadas">Coordenadas</label>
                      <input type="text" name="coordenadas" id="coordenadas" value="{{ $propiedad->coordenadas }}"  class="letrasModal form-control"  />
                  </div>
                </div>
              @endif
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="descripcion_corta">Descripcion</label>
                        <textarea rows="5" name="descripcion_corta" class="form-control">{{ $propiedad->descripcion_corta }}</textarea>
                    </div>
                </div>
                <div class="col-md-6 oculto">
                    <div class="form-group">
                        <label for="condicion">Condicion</label>
                        <textarea rows="5" name="condicion" class="form-control">{{ $propiedad->condicion}}</textarea>
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
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="construccion_metros">Metros de construccion</label>
                      <input type="number" step="any" name="construccion_metros" id="construccion_metros" value="{{ $propiedad->construccion_metros }}"  class="letrasModal form-control"  />
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="terreno_metros">Metros de terreno</label>
                      <input type="number" step="any" name="terreno_metros" id="terreno_metros" value="{{ $propiedad->terreno_metros }}"  class="letrasModal form-control"  />
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="metros_contrato">Metros terreno contrato</label>
                      <input type="number" step="any" name="metros_contrato" id="metros_contrato" value="{{ $propiedad->metros_contrato }}"  class="letrasModal form-control"  />
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="metros_fondo">Metros fondo</label>
                      <input type="number" step="any" name="metros_fondo" id="metros_fondo" value="{{ $propiedad->metros_fondo }}"  class="letrasModal form-control"  />
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="metros_frente">Metros frente</label>
                      <input type="number" step="any" name="metros_frente" id="metros_frente" value="{{ $propiedad->metros_frente }}"  class="letrasModal form-control"  />
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="mts_interior">Metros interior</label>
                      <input type="number" step="any" name="mts_interior" id="mts_interior" value="{{ $propiedad->mts_interior }}"  class="letrasModal form-control"  />
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="mts_exterior">Metros exteriores</label>
                      <input type="number" step="any" name="mts_exterior" id="mts_exterior" value="{{ $propiedad->mts_exterior }}"  class="letrasModal form-control"  />
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="precio_mts_interior">$ Metros Interior</label>
                      <input type="number" step="any" name="precio_mts_interior" id="precio_mts_interior"  value="{{ $propiedad->precio_mts_interior }}" class="letrasModal form-control"  />
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="precio_mts_exterior">$ Metros Exterior</label>
                      <input type="number" step="any" name="precio_mts_exterior" id="precio_mts_exterior" value="{{ $propiedad->precio_mts_exterior }}" class="letrasModal form-control"  />
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="recamaras">Recamaras</label>
                      <input type="text" name="recamaras" id="recamaras" value="{{ $propiedad->recamaras }}"  class="letrasModal form-control"/>
                  </div>
              </div>
              <div class="col-md-3 oculto">
                  <div class="form-group">
                      <label for="banos">Baños</label>
                      <input type="text" name="banos" id="banos" value="{{ $propiedad->banos }}"  class="letrasModal form-control"  />
                  </div>
              </div>
              <div class="col-md-3 oculto">
                  <div class="form-group">
                      <label for="area_rentable_metros">Area rentable</label>
                      <input type="number" step="any" name="area_rentable_metros" id="area_rentable_metros" value="{{ $propiedad->area_rentable_metros }}"  class="letrasModal form-control" />
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="cajones_estacionamiento">Cajones de estacionamiento</label>
                      <input type="number" name="cajones_estacionamiento" id="cajones_estacionamiento" value="{{ $propiedad->cajones_estacionamiento }}"  class="letrasModal form-control" />
                  </div>
              </div>
              <div class="col-md-3 oculto">
                  <div class="form-group">
                      <label for="vigilancia">Vigilancia</label>
                      <input type="text" name="vigilancia" id="vigilancia" value="{{ $propiedad->vigilancia }}"  class="letrasModal form-control"/>
                  </div>
              </div>
              <div class="col-md-3 oculto">
                  <div class="form-group">
                      <label for="sala_tv">Sala de TV</label>
                      <select class="form-control" id="sala_tv" name="sala_tv">
                        @if ($propiedad->sala_tv == 1)
                            <option selected="true" value="1">Si</option>
                            <option value="0">No</option>
                        @else
                            <option selected="true" value="0">No</option>
                            <option value="1">Si</option>
                        @endif
                      </select>
                  </div>
              </div>
              <div class="col-md-3 oculto">
                  <div class="form-group">
                      <label for="cuarto_servicio">Cuarto de servicio</label>
                      <select class="form-control" id="cuarto_servicio" name="cuarto_servicio">
                        @if ($propiedad->cuarto_servicio == 1)
                            <option selected="true" value="1">Si</option>
                            <option value="0">No</option>
                        @else
                            <option selected="true" value="0">No</option>
                            <option value="1">Si</option>
                        @endif
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
                    <textarea rows="5" name="infraestructura" class="form-control">{{ $propiedad->infraestructura }}</textarea>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                    <label for="terreno">Terreno</label>
                    <textarea rows="5" name="terreno" class="form-control">{{ $propiedad->terreno }}</textarea>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                    <label for="construccion">Construccion</label>
                    <textarea rows="5" name="construccion" class="form-control">{{ $propiedad->construccion }}</textarea>
                </div>
              </div>
              <div class="col-md-3 oculto">
                <div class="form-group">
                    <label for="area_rentable">Area rentable</label>
                    <textarea rows="5" name="area_rentable" class="form-control">{{ $propiedad->area_rentable }}</textarea>
                </div>
              </div>
              <div class="col-md-3 oculto">
                <div class="form-group">
                    <label for="estacionamiento">Estacionamiento</label>
                    <textarea rows="5" name="estacionamiento" class="form-control">{{ $propiedad->estacionamiento}}</textarea>
                </div>
              </div>
              <div class="col-md-3 oculto">
                <div class="form-group">
                    <label for="acabados">Acabados</label>
                    <textarea rows="5" name="acabados" class="form-control">{{ $propiedad->acabados }}</textarea>
                </div>
              </div>
            </div>
            <div class="row">    
                <div class="col-md-3 offset-md-3">
                    <div class="form-group">
                      @if ($procedencia == 'propiedades')
                        <a href="{{ route($procedencia) }}" class="btn btn-dark btn-block">REGRESAR</a>
                      @elseif($procedencia == 'nivel-show') 
                        <a href="{{ route($procedencia, [$padre, $procedencia =>'nivel'] ) }}" class="btn btn-dark btn-block">REGRESAR</a>
                      @else
                        <a href="{{ route($procedencia, $padre) }}" class="btn btn-dark btn-block">REGRESAR</a>
                      @endif
                    </div>
                </div>
                @if (auth()->user()->rol == 3)
                <div class="col-md-3">
                    <div class="form-group">
                        <button type="submit" class="btn btn-info btn-block">GUARDAR</button>
                    </div>
                </div>
                @endif
            </div>
            {{ Form::close()}}
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <div class="row">
                                <div class="col-md-10">
                                    Amenidades
                                </div>
                                <div class="col-md-2" align="right">
                                    <a href="" id="btnplusamenidad" data-toggle="modal" data-target="#modal_amenidad" class="mb-0 d-sm-inline-block btn-ico-dark text-xl"><i class="fas fa-plus"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive ">
                                <table class="table table-hover table-withoutborder text-sm">
                                  <thead class="thead-grubsa ">
                                    <th class="center">
                                      Amenidad
                                    </th>
                                    <th class="center-acciones">
                                      -
                                    </th>
                                  </thead>
                                  <tbody>
                                    @foreach ($amenidades_propiedad as $amenidad)                
                                    <tr>
                                      <td class="center">
                                        {{ $amenidad->amenidad }}
                                      </td>
                                      <td class="center-acciones">
                                        <a href="{{URL::action('AmenidadPropiedadController@show', [$amenidad->id_amenidad_propiedad])}}"  style="width: 30%;"><button class="btn-ico" style="margin-left: 2px; margin-right: 2px;"><i class="fas fa-pencil-alt"></i></button></a>
                                        <a href="{{URL::action('AmenidadPropiedadController@destroy', [$amenidad->id_amenidad_propiedad])}}"  style="width: 30%;"><button class="btn-ico" style="margin-left: 2px; margin-right: 2px;"><i class="fas fa-trash-alt"></i></button></a>
                                      </td>
                                    </tr>
                                    @endforeach
                                  </tbody>
                                </table>
                            </div>
                            {{$amenidades_propiedad->render()}}                           
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <div class="row">
                                <div class="col-md-10">
                                    Galeria de imagenes
                                </div>
                                <div class="col-md-2" align="right">
                                    <a href="" id="btnplusimagen" data-toggle="modal" data-target="#modal_imagen" class="mb-0 d-sm-inline-block btn-ico-dark text-xl"><i class="fas fa-plus"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive ">
                                <table class="table table-hover table-withoutborder text-sm">
                                  <thead class="thead-grubsa ">
                                    <th class="center">
                                      Titulo
                                    </th>
                                    <th class="center">
                                      Imagen
                                    </th>
                                  </thead>
                                  <tbody>
                                    @foreach ($imagenes_propiedad as $imagen)                
                                    <tr>
                                      <td class="center">
                                        {{ $imagen->titulo }}
                                      </td>
                                      <td class="center">
                                        <img id="imagen-{{$imagen->id_imagen}}" class="preview" src="{{ asset($imagen->imagen_path) }}" height="10%" alt="@php
                                            echo substr($imagen->imagen_path, 55);
                                        @endphp" title="@php
                                            echo substr($imagen->imagen_path, 55);
                                        @endphp" />
                                      </td>
                                      <td class="center-acciones">
                                        <a href="{{URL::action('ImagenesPropiedadController@show', [$imagen->id_imagen])}}"  style="width: 20%;"><button class="btn-ico" style="margin-left: 2px; margin-right: 2px;"><i class="fas fa-pencil-alt"></i></button></a>
                                        <a href="{{URL::action('ImagenesPropiedadController@destroy', [$imagen->id_imagen])}}"  style="width: 20%;"><button class="btn-ico" style="margin-left: 2px; margin-right: 2px;"><i class="fas fa-trash-alt"></i></button></a>
                                      </td>
                                    </tr>
                                    @endforeach
                                  </tbody>
                                </table>
                            </div>
                            {{$imagenes_propiedad->render()}}
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-preview">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header-img">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true" style="color: #fff;">&times;</span>
                </button>
            </div>
            <div class="modal-body-img">
              <img id="preview-img" src="" class="modal-img">
            </div>
            <div class="modal-footer-img">
              <p id="preview-text"></p>
            </div>
          </div>
        </div>
    </div>
</div>
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal_amenidad">
  {{ Form::open(array('action'=>array('AmenidadPropiedadController@store'),'method'=>'post', 'files'=>true)) }}
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title text-dark">Nueva amenidad</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="amenidad_mdl">Amenidad</label>
                          <select class="form-control" id="amenidad_mdl" name="amenidad_mdl">
                            @foreach ($amenidades as $a)
                              <option value="{{ $a->id_amenidad }}">{{ $a->amenidad }}</option>
                            @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="propiedad_mdl_amenidad">Propiedad</label>
                          <select class="form-control" id="propiedad_mdl_amenidad" name="propiedad_mdl_amenidad">
                            <option value="{{ $propiedad->id_propiedad }}">{{ $propiedad->nombre }}</option>
                          </select>
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
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal_imagen">
  {{ Form::open(array('action'=>array('ImagenesPropiedadController@store'),'method'=>'post', 'files'=>true)) }}
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title text-dark">Nuevo imagen para galeria</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="titulo">Titulo</label>
                          <input type="text" name="titulo" id="titulo" value=""  class="letrasModal form-control" />
                      </div>
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="propiedad_mdl_imagen">Propiedad</label>
                          <select class="form-control" id="propiedad_mdl_imagen" name="propiedad_mdl_imagen">
                            <option value="{{ $propiedad->id_propiedad }}">{{ $propiedad->nombre }}</option>
                          </select>
                      </div>
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                        <label for="imagen">Imagen</label>
                        <div class="image-upload">
                          <label for="file-input">
                              <img src="{{ asset('/images/iconos/camara-de-fotos.png') }}" alt ="Click aquí para subir tu foto" class="ico-cam" title ="Click aquí para subir tu foto" > 
                          </label> 
                          <input id="file-input" class="Images" name="file-input" type="file" accept="image/*" capture />
                          <img id="blah-file-input" class="preview" src="" width="30%" alt="" title="" style="padding-left: 10%;" />
                        </div>
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
      $( "#guardar" ).click(function() {
        $( "#guardar_form" ).submit();
      });
      $("#pais").on('change', function(){
        pais = $('#pais').val();
        selectEstado = $('#estado');
        selectEstado.empty().append('<option>Cargando paises</option>');


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
        pais = $('#proyecto').val();
        selectNivel = $('#nivel');
        selectNivel.empty().append('<option>Cargando niveles</option>');
        $.ajax({

        type: "GET",
        url: "/catalogo-niveles/" + pais,
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
      });
    });
</script>
<script >
    jQuery(document).ready(function($)
    {
        $(".preview").click(function(){
          var srcimagen = $(this).attr('src');
          var tituloimagen = $(this).attr('title');
          $("#preview-img").attr('src',srcimagen);
          $("#preview-img").attr('alt',tituloimagen);
          $("#preview-text").html(tituloimagen);
          $("#modal-preview").modal();
        });
        $("#sala_tv").change(function(){
          
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