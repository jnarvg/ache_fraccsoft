@extends('layouts.admin')
@section('title')
Proyectos
@endsection
@section('filter')
    <a href="#" class="mb-0 d-sm-inline-block btn btn-primary " id="guardar" title="GUARDAR"><i class="fas fa-check"></i></a>
    <a href="{{ route('proyectos') }}" class="mb-0 d-sm-inline-block btn btn-primary" title="CERRAR"><i class="fas fa-times"></i></a>
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
          {{ Form::open(array('action'=>array('ProyectosController@update',$proyecto->id_proyecto),'method'=>'post', 'id'=>'guardar_form','files'=>true)) }}
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="nombre">*Nombre</label>
                        <input type="text" name="nombre" id="nombre" value="{{ $proyecto->nombre }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                      <label for="file-input">*Plano</label>
                      <div class="image-upload">
                        <label for="file-input">
                            <img src="{{ asset('/images/iconos/camara-de-fotos.png') }}" alt ="Click aquí para subir tu foto" class="ico-cam" title ="Click aquí para subir tu foto" > 
                        </label> 
                        <input id="file-input" class="Images" name="file-input" type="file" accept="image/*"  />
                        <img id="blah-file-input" class="preview" src="{{ asset($proyecto->plano) }}" width="30%" alt="" title="" style="padding-left: 10%;" />
                      </div>
                  </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="metros_construccion">Metros de construccion</label>
                        <input type="number" step="any" name="metros_construccion" id="metros_construccion" value="{{ $proyecto->metros_construccion }}"  class="letrasModal form-control" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="metros_terreno">Metros de terreno</label>
                        <input type="number" step="any" name="metros_terreno" id="metros_terreno" value="{{ $proyecto->metros_terreno }}"  class="letrasModal form-control" />
                    </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                      <label for="plano_mapa">*Plano mapa</label>
                      <div class="image-upload">
                        <label for="plano_mapa">
                            <img src="{{ asset('/images/iconos/camara-de-fotos.png') }}" alt ="Click aquí para subir tu foto" class="ico-cam" title ="Click aquí para subir tu foto" > 
                        </label> 
                        <input id="plano_mapa" class="Images" name="plano_mapa" type="file" accept="image/*"  />
                        <img id="blah-plano_mapa" class="preview" src="{{ asset($proyecto->plano_mapa) }}" width="30%" alt="" title="" style="padding-left: 10%;" />
                      </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                      <label for="portada_cotizacion">*Portada cotizacion</label>
                      <div class="image-upload">
                        <label for="portada_cotizacion">
                            <img src="{{ asset('/images/iconos/camara-de-fotos.png') }}" alt ="Click aquí para subir tu foto" class="ico-cam" title ="Click aquí para subir tu foto" > 
                        </label> 
                        <input id="portada_cotizacion" class="Images" name="portada_cotizacion" type="file" accept="image/*"  />
                        <img id="blah-portada_cotizacion" class="preview" src="{{ asset($proyecto->portada_cotizacion) }}" width="30%" alt="" title="" style="padding-left: 10%;" />
                      </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                      <label for="logo_cotizacion">*Logo cotizacion</label>
                      <div class="image-upload">
                        <label for="logo_cotizacion">
                            <img src="{{ asset('/images/iconos/camara-de-fotos.png') }}" alt ="Click aquí para subir tu foto" class="ico-cam" title ="Click aquí para subir tu foto" > 
                        </label> 
                        <input id="logo_cotizacion" class="Images" name="logo_cotizacion" type="file" accept="image/*"  />
                        <img id="blah-logo_cotizacion" class="preview" src="{{ asset($proyecto->logo_cotizacion) }}" width="30%" alt="" title="" style="padding-left: 10%;" />
                      </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                      <label for="header_contrato">*Encabezado contrato</label>
                      <div class="image-upload">
                        <label for="header_contrato">
                            <img src="{{ asset('/images/iconos/camara-de-fotos.png') }}" alt ="Click aquí para subir tu foto" class="ico-cam" title ="Click aquí para subir tu foto" > 
                        </label> 
                        <input id="header_contrato" class="Images" name="header_contrato" type="file" accept="image/*"  />
                        <img id="blah-header_contrato" class="preview" src="{{ asset($proyecto->header_contrato) }}" width="30%" alt="" title="" style="padding-left: 10%;" />
                      </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                      <label for="footer_contrato">*Pie contrato</label>
                      <div class="image-upload">
                        <label for="footer_contrato">
                            <img src="{{ asset('/images/iconos/camara-de-fotos.png') }}" alt ="Click aquí para subir tu foto" class="ico-cam" title ="Click aquí para subir tu foto" > 
                        </label> 
                        <input id="footer_contrato" class="Images" name="footer_contrato" type="file" accept="image/*"  />
                        <img id="blah-footer_contrato" class="preview" src="{{ asset($proyecto->footer_contrato) }}" width="30%" alt="" title="" style="padding-left: 10%;" />
                      </div>
                  </div>
                </div>
                <div class="col-md-3 oculto">
                    <div class="form-group">
                        <label for="cuenta_catastral">Cuenta catastral</label>
                        <input type="text" name="cuenta_catastral" id="cuenta_catastral" value="{{ $proyecto->cuenta_catastral }}"  class="letrasModal form-control"/>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <h4>Datos Ubicación</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="calle">Calle</label>
                        <input type="text" name="calle" id="calle" value="{{ $proyecto->calle }}"  class="letrasModal form-control"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="num_exterior"># Exterior</label>
                        <input type="text" name="num_exterior" id="num_exterior" value="{{ $proyecto->num_exterior }}"  class="letrasModal form-control"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="num_interior"># Interior</label>
                        <input type="text" name="num_interior" id="num_interior" value="{{ $proyecto->num_interior }}"  class="letrasModal form-control"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="colonia">Colonia</label>
                        <input type="text" name="colonia" id="colonia" value="{{ $proyecto->colonia }}"  class="letrasModal form-control"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="codigo_postal_py">Codigo Postal</label>
                        <input type="text" name="codigo_postal_py" id="codigo_postal_py" value="{{ $proyecto->codigo_postal }}"  class="letrasModal form-control"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="pais_py">Pais</label>
                        <select class="form-control" id="pais_py" name="pais_py">
                          <option value="Vacio">Selecciona...</option>
                          @foreach ($paises as $p)
                            @if ($p->id_pais == $proyecto->pais_id)
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
                        <label for="estado_py">Estado</label>
                        <select class="form-control selectpicker" data-live-search="true" id="estado_py" name="estado_py">
                          <option value="Vacio"></option>
                          @foreach ($estados as $p)
                            @if ($p->id_estado == $proyecto->estado_id)
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
                        <label for="ciudad_py">Ciudad</label>
                        <select class="form-control selectpicker" data-live-search="true" id="ciudad_py" name="ciudad_py">
                          <option value="Vacio"></option>
                          @foreach ($ciudades as $p)
                            @if ($p->id_ciudad == $proyecto->ciudad_id)
                              <option selected="true" value="{{ $p->id_ciudad }}">{{ $p->ciudad }}</option>
                            @else
                              <option value="{{ $p->id_ciudad }}">{{ $p->ciudad }}</option>
                            @endif
                          @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="direccion">Dirección</label>
                        <input type="text" name="direccion" id="direccion" value="{{ $proyecto->direccion }}"  class="letrasModal form-control"  />
                    </div>
                </div>
            </div>
            <div class="row">    
                <div class="col-md-3 offset-md-3">
                    <div class="form-group">
                        <a href="{{ route('proyectos') }}" class="btn btn-dark btn-block">REGRESAR</a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <button type="submit" class="btn btn-info btn-block">GUARDAR</button>
                    </div>
                </div> 
            </div>
          {{ Form::close()}}
            <div class="card mt-3">
              <div class="card-header bg-primary text-white">
                <div class="row">
                    <div class="col-md-11">
                        Propiedades
                    </div>
                    <div class="col-md-1" align="right">
                        {{ Form::open(array('action'=>array('PropiedadController@exportExcel'),'method'=>'get')) }}
                            <a href="" id="btnplus" data-toggle="modal" data-target="#modal-add" class="mb-0 d-sm-inline-block btn-ico-dark text-xl" ><i class="fas fa-plus"></i></a>
                            <input type="hidden" class="form-control" placeholder="Id proyecto" name="id_proyecto_excel" id="id_proyecto_excel" value="{{ $proyecto->id_proyecto}}">
                            <button class="mb-0 d-sm-inline-block btn-ico-dark text-xl" ><i class="fas fa-file-excel"></i></button>
                        {!! Form::close() !!}
                    </div>
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-12">
                  {{ Form::open(array('action'=>array('ProyectosController@show',$proyecto->id_proyecto),'method'=>'get')) }}
                    <div class="input-group md-form form-sm form-4 pl-0">

                      <input type="text" class="form-control" placeholder="Buscar" name="word_bs" id="word_bs" value="{{ $request->word_bs }}">
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
                      <select class="form-control" name="rows_per_page" id="rows_per_page">
                        @foreach ($rows_pagina as $rp)
                        @if ($rp == $request->rows_per_page)
                        <option selected value="{{ $rp }}">{{ $rp }}</option>
                        @else
                        <option value="{{ $rp }}">{{ $rp }}</option>
                        @endif
                        @endforeach
                      </select>
                      <button type="submit" class="btn btn-info" ><span class="glyphicon"><i class="fas fa-search"></i></span></button>
                    </div>
                  {!! Form::close() !!}
                  </div>
                </div>
                <div class="table-responsive ">
                  <table class="table table-hover table-withoutborder">
                    <thead class="thead-grubsa ">
                      <th class="center">
                        Propiedad
                      </th>
                      <th class="center">
                        Cuenta catastral
                      </th>
                      <th class="center">
                        Tipo de propiedad
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
                    <tbody>
                      @foreach ($propiedades as $propiedad)                
                      <tr>
                        <td class="center">
                          <a class="text-dark" href="{{URL::action('PropiedadController@show', [$propiedad->id_propiedad, $procedencia, $padre=$proyecto->id_proyecto] )}}"  style="width: 30%;">
                          {{ $propiedad->nombre}}</a>
                        </td>
                        <td class="center">
                          <a class="text-dark" href="{{URL::action('PropiedadController@show', [$propiedad->id_propiedad, $procedencia, $padre=$proyecto->id_proyecto] )}}"  style="width: 30%;">
                          {{ $propiedad->cuenta_catastral}}</a>
                        </td>
                        <td class="center">
                          <a class="text-dark" href="{{URL::action('PropiedadController@show', [$propiedad->id_propiedad, $procedencia, $padre=$proyecto->id_proyecto] )}}"  style="width: 30%;">
                          {{ $propiedad->tipo_modelo}}</a>
                        </td>
                        <td class="center">
                          <a class="text-dark" href="{{URL::action('PropiedadController@show', [$propiedad->id_propiedad, $procedencia, $padre=$proyecto->id_proyecto] )}}"  style="width: 30%;">
                          $ {{ number_format($propiedad->precio , 2 , "." , ",") }} </a>
                        </td>
                        <td class="center">
                          <a class="text-dark" href="{{URL::action('PropiedadController@show', [$propiedad->id_propiedad, $procedencia, $padre=$proyecto->id_proyecto] )}}"  style="width: 30%;">
                          {{ $propiedad->estado}}</a>
                        </td class="center">
                        <td class="center">
                          <a class="text-dark" href="{{URL::action('PropiedadController@show', [$propiedad->id_propiedad, $procedencia, $padre=$proyecto->id_proyecto] )}}"  style="width: 30%;">
                          {{ $propiedad->ciudad}}</a>
                        </td>
                        <td class="center">
                          <a class="text-dark" href="{{URL::action('PropiedadController@show', [$propiedad->id_propiedad, $procedencia, $padre=$proyecto->id_proyecto] )}}"  style="width: 30%;">
                          {{ $propiedad->estatus_propiedad}}</a>
                        </td>
                        <td class="center-acciones">
                          <a href="{{URL::action('PropiedadController@show', [$propiedad->id_propiedad, $procedencia, $padre=$proyecto->id_proyecto] )}}"  style="width: 30%;"><button class="btn-ico" ><i class="fas fa-pencil-alt"></i></button></a>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
              {{$propiedades->appends(Request::only('word_bs','estatus_bs'))->render()}}
            </div>
            <div class="card mt-3">
              <div class="card-header bg-primary text-white">
                <div class="row">
                    <div class="col-md-11">
                        Niveles
                    </div>
                    <div class="col-md-1" align="right">
                        <a href="" id="btnplus" data-toggle="modal" data-target="#modal_nivel" class="mb-0 d-sm-inline-block btn-ico-dark text-xl" ><i class="fas fa-plus"></i></a>
                    </div>
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-12">
                  {{ Form::open(array('action'=>array('ProyectosController@show',$proyecto->id_proyecto),'method'=>'get')) }}
                    <div class="input-group md-form form-sm form-4 pl-0">

                      <input type="text" class="form-control" placeholder="Buscar" name="nivel_bs" id="nivel_bs" value="{{ $request->nivel_bs }}">
                      <button type="submit" class="btn btn-info" ><span class="glyphicon"><i class="fas fa-search"></i></span></button>
                    </div>
                  {!! Form::close() !!}
                  </div>
                </div>
                <div class="table-responsive ">
                  <table class="table table-hover table-withoutborder">
                    <thead class="thead-grubsa ">
                      <th class="center">
                        Nivel
                      </th>
                      <th class="center">
                        Orden
                      </th>
                      <th class="center">
                        Acciones
                      </th>
                    </thead>
                    <tbody>
                      @foreach ($niveles_proyecto as $nivel)                
                      <tr>
                        <td class="center">
                          <a class="text-dark" href="{{URL::action('NivelController@show', [$nivel->id_nivel, $procedencia])}}">
                          {{ $nivel->nivel}}</a>
                        </td>
                        <td class="center">
                          <a class="text-dark" href="{{URL::action('NivelController@show', [$nivel->id_nivel, $procedencia])}}">
                          {{ $nivel->orden}}</a>
                        </td>
                        <td class="center-acciones">
                          <a href="{{URL::action('NivelController@show', [$nivel->id_nivel, $procedencia])}}"><button class="btn-ico" ><i class="fas fa-pencil-alt"></i></button></a>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
              {{$niveles_proyecto->appends(Request::only('nivel_bs'))->render()}}
            </div>
        </div>
    </div>
</div>
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-preview">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header-img">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <div class="modal-body-img">
        <img id="preview-img" src="" class="modal-img">
      </div>
      <div class="modal-footer-img">
        <h3 id="preview-text"></h3>
      </div>
    </div>
  </div>
</div>
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal_nivel">
  {{ Form::open(array('action'=>array('NivelController@store'),'method'=>'post', 'files'=>true)) }}
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title text-dark">Nuevo nivel</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="nivel">*Nivel</label>
                          <input type="text" name="nivel" id="nivel" value="" minlength="2" class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="proyecto">*Proyecto</label>
                          <select class="form-control" id="proyecto" name="proyecto">
                              <option value="{{ $proyecto->id_proyecto }}">{{ $proyecto->nombre }}</option>
                          </select>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="orden">Orden</label>
                          <input type="number" name="orden" id="orden" value="0" class="letrasModal form-control" />
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
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-add">
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
                      <label for="nombre">Nombre</label>
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
                      <label for="tipo_modelo">*Tipo de propiedad </label>
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
                      <label for="proyecto">Proyecto</label>
                      <select class="form-control" id="proyecto" name="proyecto" required="true">
                        <option value="{{ $proyecto->id_proyecto }}">{{ $proyecto->nombre }}</option>
                      </select>
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="nivel">Nivel</label>
                      <select class="form-control" id="nivel" name="nivel" required="true">
                        <option value="Vacio">Selecciona...</option>
                        @foreach ($niveles as $p)
                          <option value="{{ $p->id_nivel }}">{{ $p->nivel }}</option>

                        @endforeach
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
                      <label for="estatus_propiedad">Estatus</label>
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
                      <label for="precio">Precio</label>
                      <input type="text" step="any" name="precio" id="precio" placeholder="0.00" class="mask form-control"  />
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="enganche">Enganche</label>
                      <input type="text" step="any" name="enganche" id="enganche" placeholder="0.00"  class="mask form-control"  />
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="moneda">Moneda</label>
                      <select class="form-control" id="moneda" name="moneda">
                        @foreach ($monedas as $m)
                        <option value="{{ $m->id_moneda }}">{{ $m->siglas }}</option>
                        @endforeach
                      </select>
                  </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                    <label for="fecha_registro">Fecha de registro</label>
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
                        @if ($proyecto->pais_id == null)
                          <option value="Vacio"></option>
                        @else
                          <option value="{{ $proyecto->pais_id }}">{{ $proyecto->pais }}</option>
                        @endif
                      </select>
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="estado">Estado</label>
                      <select class="form-control" id="estado" name="estado">
                        @if ($proyecto->estado_id == null)
                          <option value="Vacio"></option>
                        @else
                          <option value="{{ $proyecto->estado_id }}">{{ $proyecto->estado }}</option>
                        @endif
                      </select>
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="ciudad">Ciudad</label>
                      <select class="form-control" id="ciudad" name="ciudad">
                        @if ($proyecto->ciudad_id == null)
                          <option value="Vacio"></option>
                        @else
                          <option value="{{ $proyecto->ciudad_id }}">{{ $proyecto->ciudad }}</option>
                        @endif
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
                      <input type="text" name="direccion" id="direccion" value="{{ $proyecto->direccion }}"  class="letrasModal form-control" />
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
  jQuery(document).ready(function() {
    jQuery(".preview").click(function(){
        
      var srcimagen = jQuery(this).attr('src');
      var tituloimagen = jQuery(this).attr('title');
      jQuery("#preview-img").attr('src',srcimagen);
      jQuery("#preview-img").attr('alt',tituloimagen);
      jQuery("#preview-text").html(tituloimagen);
      jQuery("#modal-preview").modal();
    });
  });
</script>
<script>
  jQuery(document).ready(function($)
    {
      $( "#guardar" ).click(function() {
        $( "#guardar_form" ).submit();
      });
      $("#pais_py").on('change', function(){
        pais = $('#pais_py').val();
        selectEstado = $('#estado_py');
        selectEstado.empty().append('<option>Cargando paises</option>');
        construir_domicilio();

        $.ajax({

        type: "GET",
        url: "/catalogo-estados/" + pais,
        success: function(data) {
              var htmlOptions = [];
              if( data.length ){
                  html = '<option value="" disabled="true" selected="true">Selecciona...</option>';
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
                html = '<option value="" disabled="true" selected="true">No hay estados</option>';
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
      $("#estado_py").on('change', function(){
        pais = $('#estado_py').val();
        selectCiudad = $('#ciudad_py');
        selectCiudad.empty().append('<option>Cargando paises</option>');
        construir_domicilio();

        $.ajax({

        type: "GET",
        url: "/catalogo-ciudades/" + pais,
        success: function(data) {
              var htmlOptions = [];
              if( data.length ){
                  html = '<option value="" disabled="true" selected="true">Selecciona...</option>';
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
                html = '<option value="" disabled="true" selected="true">No hay ciudades</option>';
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
      $("#ciudad_py").on('change', function(){
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
      $('#codigo_postal_py').keyup(function() {
          construir_domicilio();
      });
      $('#colonia').keyup(function() {
          construir_domicilio();
      });
    });
</script>
  <script type="application/javascript">
      function construir_domicilio() {
          if ($('#num_interior').val() != '' ) {
              var num_interior = ' - ' + $('#num_interior').val();
          }else{
              var num_interior = '';
          }
          var domicilio = $('#calle').val() + ' #' + $('#num_exterior').val() + '' + num_interior + ', Col. ' + $('#colonia').val() + ', ' + $('select[name="ciudad_py"] option:selected').text() + ', ' + $('select[name="estado_py"] option:selected').text() + ', ' + $('select[name="pais_py"] option:selected').text() + '. ' + $('#codigo_postal_py').val();

          $('#direccion').val(domicilio);
      }
      jQuery('input[type=file]').change(function(){
       var filename = jQuery(this).val().split('\\').pop();
       var idname = jQuery(this).attr('id');
       jQuery('span.'+idname).next().find('span').html(filename);
      });
  </script>
  <script src="{{ asset('js/uploadfotos.js') }}"></script>
@endpush 
@endsection