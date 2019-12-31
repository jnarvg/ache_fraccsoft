@extends('layouts.admin')
@section('title')
Prospecto N. {{ $prospecto->id_prospecto }}
@endsection
@section('filter')
    <a href="#" class="mb-0 d-sm-inline-block btn btn-primary " id="guardar" title="GUARDAR"><i class="fas fa-check"></i></a>
    @if ($ruta == 'propiedades-show')
    <a href="{{ route($ruta, $prospecto->propiedad_id) }}" class="mb-0 d-sm-inline-block btn btn-primary" title="CERRAR"><i class="fas fa-times"></i></a>
    @else
    <a href="{{ route($ruta) }}" class="mb-0 d-sm-inline-block btn btn-primary" title="CERRAR"><i class="fas fa-times"></i></a>
    @endif
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            <div class="row" id="botones">
                @if (auth()->user()->id != null and auth()->user()->id == $prospecto->asesor_id and $prospecto->estatus_prospecto != 'Inactivo')
                    @if (\Auth()->user()->rol != 6/*Cobranza*/ )
                        @if ($prospecto->estatus_prospecto != 'Perdido' and $prospecto->estatus_prospecto != 'Postergado')
                            @if ($prospecto->nivel < 2 and $prospecto->limite >= 2  )
                            <div class="col-md-3">
                                <div class="form-group">    
                                    <svg>
                                        <a href="#" id="btnVisita" class=" btn btn-dark svgbutton" data-toggle="modal" data-target="#modal-visita"><path d="M 0 40 L 230 40 L 260 20 L 230 0 L 0 0 L 20 20 L 0 40  Z" />
                                            <text x="110"
                                                  y="20"
                                                  fill="#FFFFFF"
                                                  text-anchor="middle"
                                                  alignment-baseline="middle">
                                            Visita
                                            </text>
                                        </a>
                                    </svg>
                                </div>
                            </div>
                            @endif
                            @if($prospecto->nivel < 3 and $prospecto->limite >= 3)
                            <div class="col-md-3">
                                <div class="form-group">
                                    <svg>
                                        <a href="#" id="btncotizacion" class="btn btn-dark svgbutton" data-toggle="modal" data-target="#modal-cotizacion">
                                            <path d="M 0 40 L 230 40 L 260 20 L 230 0 L 0 0 L 20 20 L 0 40  Z" />
                                            <text x="110"
                                                  y="20"
                                                  fill="#FFFFFF"
                                                  text-anchor="middle"
                                                  alignment-baseline="middle">
                                            Cotización
                                            </text>
                                        </a>
                                    </svg>
                                </div>
                            </div>
                            @endif
                            @if($prospecto->nivel < 4 and $prospecto->limite >= 4)
                            <div class="col-md-3">
                                <div class="form-group"> 
                                    <svg>   
                                        <a href="#" id="btnApartado" class="btn btn-dark svgbutton" data-toggle="modal" data-target="#modal-apartar">
                                        <path d="M 0 40 L 230 40 L 260 20 L 230 0 L 0 0 L 20 20 L 0 40  Z" />
                                            <text x="110"
                                                  y="20"
                                                  fill="#FFFFFF"
                                                  text-anchor="middle"
                                                  alignment-baseline="middle">
                                            Apartado
                                            </text>
                                        </a>
                                    </svg>
                                </div>
                            </div>
                            @endif
                            @if($prospecto->nivel < 5 and $prospecto->limite >=5 )
                            <div class="col-md-3">
                                <div class="form-group">   
                                    <svg> 
                                        <a href="#" id="btnRecabandoDocumentacion" class="btn btn-dark svgbutton" data-toggle="modal" data-target="#modal-requisitos">
                                        <path d="M 0 40 L 230 40 L 260 20 L 230 0 L 0 0 L 20 20 L 0 40  Z" />
                                            <text x="130"
                                                  y="20"
                                                  fill="#FFFFFF"
                                                  text-anchor="middle"
                                                  alignment-baseline="middle">
                                            Recabando doc.
                                            </text>
                                        </a>
                                    </svg>
                                </div>
                            </div>
                            @endif
                            @if($prospecto->nivel < 6 and $prospecto->limite >= 6)
                            <div class="col-md-3">
                                <div class="form-group">  
                                    <svg>   
                                    <a href="#" id="btnContrato" class="btn btn-dark svgbutton" data-toggle="modal" data-target="#modal-contrato">
                                        <path d="M 0 40 L 230 40 L 260 20 L 230 0 L 0 0 L 20 20 L 0 40  Z" />
                                        <text x="120"
                                              y="20"
                                              fill="#FFFFFF"
                                              text-anchor="middle"
                                              alignment-baseline="middle">Contrato</text>
                                        </a>
                                    </svg>
                                </div>
                            </div>
                            @endif
                            @if($prospecto->nivel < 7 and $prospecto->limite >= 7)
                            <div class="col-md-3">
                                <div class="form-group">
                                    <svg >
                                      <a href="#" id="btnPagando" class="btn btn-dark svgbutton" data-toggle="modal" data-target="#modal-pagando">
                                        <path d="M 0 40 L 230 40 L 260 20 L 230 0 L 0 0 L 20 20 L 0 40  Z" />
                                        <text x="110"
                                              y="20"
                                              fill="#FFFFFF"
                                              text-anchor="middle"
                                              alignment-baseline="middle">
                                          Pagando
                                        </text>
                                      </a>
                                    </svg>
                                </div>
                            </div>
                            @endif
                            @if (\Auth()->user()->rol == 3)
                                @if($prospecto->nivel < 8 and $prospecto->limite >= 8)
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <svg >
                                            <a href="{{ url('no_escriturar',['id' => $prospecto->id_prospecto]) }}" id="btnContrato" class="btn btn-dark svgbutton">
                                                <path d="M 0 40 L 230 40 L 260 20 L 230 0 L 0 0 L 20 20 L 0 40  Z" />
                                                <text x="110"
                                                      y="20"
                                                      fill="#FFFFFF"
                                                      text-anchor="middle"
                                                      alignment-baseline="middle">Por escriturar
                                                </text> 
                                            </a>
                                        </svg>
                                    </div>
                                </div>
                                @endif
                                @if($prospecto->nivel < 9 and $prospecto->limite >= 9)
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <svg  align="center">
                                            <a href="{{ url('escriturar',['id' => $prospecto->id_prospecto]) }}" id="btnEscriturar" class="btn btn-dark svgbutton"><path d="M 0 40 L 230 40 L 260 20 L 230 0 L 0 0 L 20 20 L 0 40  Z"  align="center"/>
                                                <text x="110"
                                                      y="20"
                                                      align="center"
                                                      fill="#FFFFFF"
                                                      text-anchor="middle"
                                                      alignment-baseline="middle">Escriturado
                                                </text>
                                            </a>
                                        </svg>
                                    </div>
                                </div>
                                @endif              
                            @endif
                        @endif
                    @endif
                @endif
                
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            {{ Form::open(array('action'=>array('ProspectosController@update',$prospecto->id_prospecto),'method'=>'post','files'=>true, 'id'=>'guardar_form')) }}
            <div class="row">
                <div class="col-md-12">
                    <h3><a class="text-secondary" data-toggle="collapse" href="#infoprincipal" role="button" aria-expanded="false" aria-controls="infoprincipal"><em>Informacion principal</em></a></h3>
                    <hr class="hr-titulo" width="100%" size="10">
                </div>
            </div>
            <div class="row collapse show" id="infoprincipal">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="nombre_s">*Nombre(s)</label>
                        <input type="text" name="nombre_s" id="nombre_s" value="{{ $prospecto->nombre_s }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="apellido_paterno">*Apellido paterno</label>
                        <input type="text" name="apellido_paterno" id="apellido_paterno" value="{{ $prospecto->apellido_paterno }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="apellido_materno">*Apellido Materno</label>
                        <input type="text" name="apellido_materno" id="apellido_materno" value="{{ $prospecto->apellido_materno }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="nombre">*Nombre completo</label>
                        <input type="text" name="nombre" id="nombre" value="{{ $prospecto->nombre }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-3"> 
                    <div class="form-group">
                        <label for="tipo">*Tipo</label>
                        <select class="form-control" id="tipo" name="tipo">
                            @foreach ($tipos as $p)
                              @if ($p == $prospecto->tipo)
                                <option selected="true" value="{{ $p }}">{{ $p }}</option>
                              @else
                                <option value="{{ $p }}">{{ $p }}</option>
                              @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="razon_social">Razon social</label>
                        <input type="text" name="razon_social" id="razon_social" value="{{ $prospecto->razon_social }}"  class="letrasModal form-control" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="correo">*Correo</label>
                        <input type="email" name="correo" id="correo" value="{{ $prospecto->correo }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="telefono">Telefono</label>
                        <input type="text" name="telefono" id="telefono" value="{{ $prospecto->telefono }}"  class="letrasModal form-control"  />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="telefono_adicional">Telefono adicional</label>
                        <input type="text" name="telefono_adicional" id="telefono_adicional" value="{{ $prospecto->telefono_adicional }}"  class="letrasModal form-control" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="telefono_adicional">Extension</label>
                        <input type="text" name="telefono_adicional" id="telefono_adicional" value="{{ $prospecto->extension }}"  class="letrasModal form-control" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="rfc">RFC</label>
                        <input type="text" name="rfc" id="rfc" value="{{ $prospecto->rfc }}"  class="letrasModal form-control"  />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="estatus">*Estatus</label>
                        <select name="estatus" id="estatus" class="letrasModal form-control" required="true" readonly>
                            <option value="{{ $prospecto->estatus }}">{{ $prospecto->estatus_prospecto }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                      <label for="medio_contacto">Medio de contacto</label>
                      <select name="medio_contacto" id="medio_contacto"  class="letrasModal form-control">
                        @foreach ($medios_contacto as $medio)
                            @if ($medio->id_medio_contacto == $prospecto->medio_contacto_id)
                            <option selected value="{{ $medio->id_medio_contacto }}">{{ $medio->medio_contacto }}</option>
                            @else
                            <option value="{{ $medio->id_medio_contacto }}">{{ $medio->medio_contacto }}</option>
                            @endif
                        @endforeach
                      </select>
                    </div>
                </div>
                <div class="col-md-3 oculto oficina_broker">
                    <div class="form-group">
                        <label for="oficina_broker">Oficina broker</label>
                        <input type="text" name="oficina_broker" id="oficina_broker" value="{{ $prospecto->oficina_broker }}"  class="letrasModal form-control"  />
                    </div>
                </div>
                <div class="col-md-3 oculto oficina_broker">
                    <div class="form-group">
                        <label for="nombre_broker">Nombre broker</label>
                        <input type="text" name="nombre_broker" id="nombre_broker" value="{{ $prospecto->nombre_broker }}"  class="letrasModal form-control"  />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                      <label for="nacionalidad">Nacionalidad</label>
                      <select name="nacionalidad" id="nacionalidad" value=""  class="letrasModal form-control">
                        <option value=""></option>
                        @foreach ($nacionalidades as $n)
                            @if ($n == $prospecto->nacionalidad)
                            <option selected value="{{ $n }}">{{ $n }}</option>
                            @else
                            <option value="{{ $n }}">{{ $n }}</option>
                            @endif
                        @endforeach
                      </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="fecha_registro">*Fecha de registro</label>
                        <input type="date" name="fecha_registro" id="fecha_registro" value="{{ date('Y-m-d',strtotime($prospecto->fecha_registro)) }}"  class="letrasModal form-control" required="true" readonly="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="asesor_id">Agente</label>
                        <select name="asesor_id" id="asesor_id" class="letrasModal form-control" required="true" readonly>
                            <option value="{{ $prospecto->asesor_id }}">{{ $prospecto->nombre_agente }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="pais_id">*Pais</label>
                        <select name="pais_id" id="pais_id" class="letrasModal form-control">
                            <option value=""></option>
                          @foreach ($paises as $m)
                            @if ($m->id_pais == $prospecto->pais_id )
                                <option selected value="{{ $m->id_pais }}">{{ $m->pais }}</option>
                            @else
                                <option value="{{ $m->id_pais }}">{{ $m->pais }}</option>
                            @endif
                          @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="estado_id">*Estado</label>
                        <select name="estado_id" id="estado_id" class="letrasModal form-control selectpicker" data-live-search="true">
                            <option value=""></option>
                          @foreach ($estados as $m)
                            @if ($m->id_estado == $prospecto->estado_id )
                                <option selected value="{{ $m->id_estado }}">{{ $m->estado }}</option>
                            @else
                                <option value="{{ $m->id_estado }}">{{ $m->estado }}</option>
                            @endif
                          @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="ciudad_id">*Ciudad</label>
                        <select name="ciudad_id" id="ciudad_id" class="letrasModal form-control selectpicker" data-live-search="true">
                            <option value=""></option>
                          @foreach ($ciudades as $m)
                            @if ($m->id_ciudad == $prospecto->ciudad_id )
                                <option selected value="{{ $m->id_ciudad }}">{{ $m->ciudad }}</option>
                            @else
                                <option value="{{ $m->id_ciudad }}">{{ $m->ciudad }}</option>
                            @endif
                          @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="calle">Calle</label>
                        <input type="text" name="calle" id="calle" value="{{ $prospecto->calle }}"  class="letrasModal form-control"  />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="num_exterior">Num exterior</label>
                        <input type="text" name="num_exterior" id="num_exterior" value="{{ $prospecto->num_exterior }}"  class="letrasModal form-control"  />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="num_interior">Num interior</label>
                        <input type="text" name="num_interior" id="num_interior" value="{{ $prospecto->num_interior }}"  class="letrasModal form-control"  />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="colonia">Colonia</label>
                        <input type="text" name="colonia" id="colonia" value="{{ $prospecto->colonia }}"  class="letrasModal form-control"  />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="codigo_postal">Codigo postal</label>
                        <input type="text" name="codigo_postal" id="codigo_postal" value="{{ $prospecto->codigo_postal }}"  class="letrasModal form-control"  />
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="domicilio">Domicilio</label>
                        <input type="text" name="domicilio" id="domicilio" value="{{ $prospecto->domicilio }}"  class="letrasModal form-control"  />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="asesor_id">Foto anexo C</label>
                        <div class="image-upload">
                            <label for="foto_prospecto">
                                <img src="{{ asset('/images/iconos/camara-de-fotos.png') }}" alt ="Click aquí para subir tu foto" class="ico-cam" title ="Click aquí para subir tu foto" > 
                            </label> 
                            <input id="foto_prospecto" class="Images" name="foto_prospecto" type="file" accept="image/*"  />
                            <img id="blah-foto_prospecto" class="preview" src="{{ asset($prospecto->foto_prospecto) }}" width="30%" alt="" title="" style="padding-left: 10%;" />
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="asesor_id">Foto anexo C 2</label>
                        <div class="image-upload">
                            <label for="foto_prospecto_2">
                                <img src="{{ asset('/images/iconos/camara-de-fotos.png') }}" alt ="Click aquí para subir tu foto" class="ico-cam" title ="Click aquí para subir tu foto" > 
                            </label> 
                            <input id="foto_prospecto_2" class="Images" name="foto_prospecto_2" type="file" accept="image/*"  />
                            <img id="blah-foto_prospecto_2" class="preview" src="{{ asset($prospecto->foto_prospecto_2) }}" width="30%" alt="" title="" style="padding-left: 10%;" />
                        </div>
                    </div>
                </div>
                @if ($prospecto->estatus_prospecto == 'Postergado')                   
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="fecha_recontacto">Fecha de recontacto</label>
                        <input type="date" name="fecha_recontacto" id="fecha_recontacto" value="{{ date('Y-m-d',strtotime($prospecto->fecha_recontacto)) }}"  class="letrasModal form-control" required="true" readonly="true" />
                    </div>
                </div>
                @endif
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="observaciones">Observaciones</label>
                        <textarea rows="3" name="observaciones" id="observaciones" value=""  class="letrasModal form-control">{{ $prospecto->observaciones }}</textarea>
                    </div>
                </div>
            </div>
            @if ($prospecto->estatus_prospecto == 'Perdido')
            <div class="row" id="dvperdida">
                <div class="col-md-12">
                    <h3><em>Perdida</em></h3>
                    <hr class="hr-titulo" width="100%" size="10">
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="motivoperdida">Motivo de perdida</label>
                        <input type="text" name="motivoperdida" value="{{ $prospecto->motivo_perdida }}"  class="form-control">
                    </div>
                </div>
            </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <hr class="hr-titulo" width="100%" size="10">
                </div>
                @if (\Auth()->user()->rol != 6)
                    @if (\Auth()->user()->rol == 3)
                        <div class="col-md-3">
                            <div class="form-group">
                                <a href="#" id="btnCambiar" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-cambiar">Cambiar asesor</a>
                            </div>
                        </div>
                    @endif
                    @if ($prospecto->estatus_prospecto == 'Postergado' or $prospecto->estatus_prospecto == 'Perdido')
                        <div class="col-md-3">
                            <div class="form-group">
                                <a href="{{ url('reactivar',['id' => $prospecto->id_prospecto]) }}" id="btnReactivar" class="btn btn-primary btn-block">Reactivar<span class="arrow">❰</span></a>
                            </div>
                        </div>
                    @else
                        <div class="col-md-3">
                            <div class="form-group">
                                <a href="#" id="btnPerder" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-perder">Perder</a>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <a href="{{ url('regresar',['id' => $prospecto->id_prospecto]) }}" id="btnRegresar" class="btn btn-primary btn-block">Regresar</a>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <a href="#" id="btnPostergar" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-postergar">Postergar </a>
                            </div>
                        </div>
                        @if (auth()->user()->rol == 3)
                            <div class="col-md-3">
                                <div class="form-group">
                                    <a href="#" id="btnEntregarr" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-entregar_propiedad">Entregar propiedad </a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <a href="{{ url('cancelar',['id' => $prospecto->id_prospecto]) }}" id="btnContrato" class="btn btn-primary btn-block">Cancelar contrato</a>
                                </div>
                            </div>
                        @endif
                    @endif
                @endif
                @if ($id != null and $id == 1)
                    <div class="col-md-3">
                        <div class="form-group">
                            <a href="" id="btnadminestatus" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-adminestatus">
                                Cambiar estatus</a>
                        </div>
                    </div>
                @endif
                <div class="col-md-12">
                    <hr class="hr-titulo" width="100%" size="10">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h3><a class="text-secondary" data-toggle="collapse" href="#visita" role="button" aria-expanded="false" aria-controls="visita"><em>Visita</em></a></h3>
                    <hr class="hr-titulo" width="100%" size="10">
                </div>
            </div>
            <div class="row collapse show" id="visita">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="proyecto">Proyecto</label>
                        <select name="proyecto" id="proyecto" value=""  class="letrasModal form-control selectpicker">
                            <option value="sin proyecto">Sin proyecto</option>
                            @foreach ($proyectos as $py)
                                @if ($prospecto->proyecto_id == $py->id_proyecto)
                                    <option selected="true" value="{{ $py->id_proyecto }}">{{ $py->nombre }}</option>
                                @else
                                  <option value="{{ $py->id_proyecto }}">{{ $py->nombre }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="propiedad">Propiedad</label>
                        <select name="propiedad" id="propiedad" class="letrasModal form-control selectpicker">
                            <option value="{{ $prospecto->propiedad_id }}">{{ $prospecto->nombre_propiedad }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="fecha_visita">Fecha de visita</label>
                        @if ($prospecto->fecha_visita != null)
                            <input type="date" name="fecha_visita" id="fecha_visita" value="{{ date('Y-m-d',strtotime($prospecto->fecha_visita)) }}"  class="letrasModal form-control" required="true" readonly="true" />
                        @else
                            <input type="date" name="fecha_visita" id="fecha_visita" value=""  class="letrasModal form-control" required="true" readonly="true"/>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row" >
                <div class="col-md-12">
                    <h3><a class="text-secondary" data-toggle="collapse" href="#apartado" role="button" aria-expanded="false" aria-controls="apartado"><em>Apartado</em></a></h3>
                    <hr class="hr-titulo" width="100%" size="10">
                    <div class="row collapse show" id="apartado">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fecha_apartado">Fecha de apartado</label>
                                @if ($prospecto->fecha_apartado != null)
                                    <input type="date" name="fecha_apartado" id="fecha_apartado" value="{{ date('Y-m-d',strtotime($prospecto->fecha_apartado)) }}"  class="letrasModal form-control" required="true" />
                                @else
                                    <input type="date" name="fecha_apartado" id="fecha_apartado" value=""  class="letrasModal form-control" required="true"/>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="monto_apartado">*Monto apartado</label>
                                <input type="text" name="monto_apartado" id="monto_apartado" class="form-control mask" value="{{ $prospecto->monto_apartado }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" >
                <div class="col-md-12">
                    <h3><a class="text-secondary" data-toggle="collapse" href="#cotizacion" role="button" aria-expanded="false" aria-controls="cotizacion"><em>Cotizacion</em></a></h3>
                    <hr class="hr-titulo" width="100%" size="10">
                    <div class="row collapse show" id="cotizacion">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fecha_cotizacion">Fecha de cotizacion</label>
                                @if ($prospecto->fecha_cotizacion != null)
                                    <input type="date" name="fecha_cotizacion" id="fecha_cotizacion" value="{{ date('Y-m-d',strtotime($prospecto->fecha_cotizacion)) }}"  class="letrasModal form-control" required="true" readonly="true" />
                                @else
                                    <input type="date" name="fecha_cotizacion" id="fecha_cotizacion" value=""  class="letrasModal form-control" required="true" readonly="true"/>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cotizacion_id">*Cotizacion</label>
                                <select class="form-control" id="cotizacion_id" name="cotizacion_id" required="true">
                                    @foreach ($cotizaciones as $e)
                                        @if ($e->id_cotizacion == $prospecto->cotizacion_id)
                                        <option selected value="{{ $e->id_cotizacion }}">{{ $e->id_cotizacion }}</option>
                                        @else
                                        <option value="{{ $e->id_cotizacion }}">{{ $e->id_cotizacion }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="esquema_pago">*Esquema de pago</label>
                                <select class="form-control" id="esquema_pago" name="esquema_pago" required="true">
                                    @foreach ($esquemas_pago as $e)
                                        @if ($e == $prospecto->esquema_pago)
                                        <option selected value="{{ $e }}">{{ $e }}</option>
                                        @else
                                        <option value="{{ $e }}">{{ $e }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="esquema_pago_id">*Esquema de pago id</label>
                                <select class="form-control" id="esquema_pago_id" name="esquema_pago_id" required="true">
                                    @foreach ($detalles_esquema_cotizacion as $e)
                                        @if ($e->id_detalle_cotizacion_propiedad == $prospecto->esquema_pago_id)
                                        <option selected value="{{ $e->id_detalle_cotizacion_propiedad }}">{{ $e->esquema_pago }}</option>
                                        @else
                                        <option value="{{ $e->id_detalle_cotizacion_propiedad }}">{{ $e->esquema_pago }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-group">
                                    <a href="{{ url('/cotizacion/show',['id' => $prospecto->cotizacion_id, 'procedencia'=>$ruta]) }}" id="btnCrearContrato" class="btn btn-primary btn-block down" target="_bank">Ver Cotizacion</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if (auth()->user()->rol == 3 /*Gerente*/ or auth()->user()->rol == 6 or auth()->user()->rol == 1)
            <div class="row">
                <div class="col-md-12">
                    <h3><a class="text-secondary" data-toggle="collapse" href="#contrato" role="button" aria-expanded="false" aria-controls="contrato"><em>Contrato</em></a></h3>
                    <hr class="hr-titulo" width="100%" size="10">
                </div>
            </div>
            <div class="row collapse show" id="contrato">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="fecha_contrato">Fecha de contrato</label>
                        @if ($prospecto->fecha_contrato != null)
                            <input type="date" name="fecha_contrato" id="fecha_contrato" value="{{ date('Y-m-d',strtotime($prospecto->fecha_contrato)) }}"  class="letrasModal form-control" required="true" />
                        @else
                            <input type="date" name="fecha_contrato" id="fecha_contrato" value=""  class="letrasModal form-control" required="true"/>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="num_contrato">Numero de contrato</label>
                        <input type="number" step="any" name="num_contrato" id="num_contrato" value="{{ $prospecto->num_contrato }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                
                 <div class="col-md-3">
                    <div class="form-group">
                        <label for="vigencia_contrato">Vigencia contrato</label>
                        @if ($prospecto->vigencia_contrato != null)
                            <input type="date" name="vigencia_contrato" id="vigencia_contrato" value="{{ date('Y-m-d',strtotime($prospecto->vigencia_contrato)) }}"  class="letrasModal form-control" required="true" />
                        @else
                            <input type="date" name="vigencia_contrato" id="vigencia_contrato" value=""  class="letrasModal form-control" required="true"/>
                        @endif
                    </div>
                </div>
                
                @if ($prospecto->nivel > 3)
                <div class="col-md-3">
                    <div class="form-group">
                        <a href="{{ url('crearContrato',['id' => $prospecto->id_prospecto]) }}" id="btnCrearContrato" class="btn btn-primary btn-block" target="_bank" style="margin-top: 2rem;">Ver contrato</a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a href="{{ url('crearContratoEnglish',['id' => $prospecto->id_prospecto]) }}" id="btnCrearContrato" class="btn btn-primary btn-block" target="_bank" style="margin-top: 2rem;">Ver cédula</a>
                    </div>
                </div>
                @endif
            </div>
            @endif
            @if (auth()->user()->rol == 3 /*Gerente*/ or auth()->user()->rol == 6)
            <div class="row">
                <div class="col-md-12">
                    <h3><a class="text-secondary" data-toggle="collapse" href="#venta" role="button" aria-expanded="false" aria-controls="venta"><em>Venta</em></a></h3>
                    <hr class="hr-titulo" width="100%" size="10">
                </div>
            </div>
            <div class="row collapse show" id="venta">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="fecha_venta">Fecha de venta</label>
                        @if ($prospecto->fecha_venta != null)
                            <input type="date" name="fecha_venta" id="fecha_venta" value="{{ date('Y-m-d',strtotime($prospecto->fecha_venta)) }}"  class="letrasModal form-control" required="true" readonly="true" />
                        @else
                            <input type="date" name="fecha_venta" id="fecha_venta" value=""  class="letrasModal form-control" required="true" readonly="true"/>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="monto_venta">Monto de venta</label>
                        <input type="text" step="any" name="monto_venta" id="monto_venta" value="{{ $prospecto->monto_venta }}"  class="mask form-control" required="true" readonly="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="tipo_operacion">Tipo de operacion</label>
                        <select name="tipo_operacion" id="tipo_operacion" class="letrasModal form-control" readonly>
                            <option selected="true" disabled="true">Selecciona...</option>
                            @foreach ($tipos_operacion as $to)
                                @if ($prospecto->tipo_operacion_id == $to->id_tipo_operacion)
                                    <option selected="true" value="{{ $to->id_tipo_operacion }}">{{ $to->tipo_operacion }}</option>
                                @else
                                  <option value="{{ $to->id_tipo_operacion }}">{{ $to->tipo_operacion }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="cerrador">Cerrador</label>
                        <select name="cerrador" id="cerrador" class="letrasModal form-control" readonly>
                            <option selected="true" disabled="true">Selecciona...</option>
                            @foreach ($usuarios as $user)
                                @if ($prospecto->cerrador == $user->id)
                                    <option selected="true" value="{{ $user->id }}">{{ $user->name }}</option>
                                @else
                                  <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="esquema_comision_id">Esquema de comision</label>
                        <select name="esquema_comision_id" id="esquema_comision_id" class="letrasModal form-control" required="true" readonly>
                          <option value="Sin comisiones">Sin comisiones</option>
                          @foreach ($esquemas as $element)
                              @if ($element->id_esquema_comision == $prospecto->esquema_comision_id)
                                <option selected="true" value="{{ $element->id_esquema_comision }}">{{ $element->esquema_comision }}</option>
                              @else
                                <option value="{{ $element->id_esquema_comision }}">{{ $element->esquema_comision }}</option>
                              @endif
                          @endforeach
                        </select>
                    </div>
                </div>
                @if ($prospecto->comision_id != null)
                  <div class="col-md-3">
                      <div class="form-group">
                          <label for="comision_id">Comision relacionada</label>
                          <a href="{{ route('comision-show', $prospecto->comision_id) }}" target="_bank" class="btn-block archivo-link">Ver comision {{ $prospecto->comision_id }}</a>
                      </div>
                  </div>
                @endif
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h3><a class="text-secondary" data-toggle="collapse" href="#pagos" role="button" aria-expanded="false" aria-controls="pagos"><em>Pagos</em></a></h3>
                    <hr class="hr-titulo" width="100%" size="10">
                </div>
            </div>
            <div class="row collapse show" id="pagos">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="fecha_ultimo_pago">Fecha ultimo pago</label>
                        @if ($prospecto->fecha_ultimo_pago != null)
                            <input type="date" name="fecha_ultimo_pago" id="fecha_ultimo_pago" value="{{ date('Y-m-d',strtotime($prospecto->fecha_ultimo_pago)) }}"  class="letrasModal form-control" required="true" readonly="true" />
                        @else
                            <input type="date" name="fecha_ultimo_pago" id="fecha_ultimo_pago" value=""  class="letrasModal form-control" required="true" readonly="true"/>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="monto_ultimo_pago">Monto ultimo pago</label>
                        <input type="text" step="any" name="monto_ultimo_pago" id="monto_ultimo_pago" value="{{ $prospecto->monto_ultimo_pago }}"  class="mask form-control" required="true" readonly="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="mensualidad">Mensualidad</label>
                        <input type="text" step="any" name="mensualidad" id="mensualidad" value="{{ $prospecto->mensualidad }}"  class="mask form-control" readonly />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="porcentaje_interes">Porcentaje Interes</label>
                        <input type="number" max="100" step="any" name="porcentaje_interes" id="porcentaje_interes" value="{{ $prospecto->porcentaje_interes }}"  class="letrasModal form-control"  />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h3><a class="text-secondary" data-toggle="collapse" href="#saldos" role="button" aria-expanded="false" aria-controls="saldos"><em>Saldos</em></a></h3>
                    <hr class="hr-titulo" width="100%" size="10">
                </div>
            </div>
            <div class="row collapse show" id="saldos">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="interes">Interes</label>
                        <input type="text" step="any" name="interes" id="interes" value="{{ $prospecto->interes }}"  class="mask form-control"  readonly/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="capital">Capital</label>
                        <input type="text" step="any" name="capital" id="capital" value="{{ $prospecto->capital }}"  class="mask form-control" readonly />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="saldo">Saldo</label>
                        <input type="text" step="any" name="saldo" id="saldo" value="{{ $prospecto->saldo }}"  class="mask form-control" readonly />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="pagado">Pagado</label>
                        <input type="text" step="any" name="pagado" id="pagado" value="{{ $prospecto->pagado }}"  class="mask form-control" readonly />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="moneda">*Moneda</label>
                        <select name="moneda" id="moneda" class="letrasModal form-control">
                          @foreach ($monedas as $m)
                            @if ($m->id_moneda == $prospecto->moneda_id )
                                <option selected value="{{ $m->id_moneda }}">{{ $m->siglas }}</option>
                            @else
                                <option value="{{ $m->id_moneda }}">{{ $m->siglas }}</option>
                            @endif
                          @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h3><a class="text-secondary" data-toggle="collapse" href="#escrituracion" role="button" aria-expanded="false" aria-controls="escrituracion"><em>Escrituracion</em></a></h3>
                    <hr class="hr-titulo" width="100%" size="10">
                </div>
            </div>
            <div class="row collapse show" id="escrituracion">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="fecha_entrega_propiedad">Fecha de entrega propiedad</label>
                        @if ($prospecto->fecha_entrega_propiedad != null)
                            <input type="date" name="fecha_entrega_propiedad" id="fecha_entrega_propiedad" value="{{ date('Y-m-d',strtotime($prospecto->fecha_entrega_propiedad)) }}"  class="letrasModal form-control" required="true" readonly="true" />
                        @else
                            <input type="date" name="fecha_entrega_propiedad" id="fecha_entrega_propiedad" value=""  class="letrasModal form-control" required="true" readonly="true"/>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="fecha_escrituracion">Fecha de escrituracion</label>
                        @if ($prospecto->fecha_escrituracion != null)
                            <input type="date" name="fecha_escrituracion" id="fecha_escrituracion" value="{{ date('Y-m-d',strtotime($prospecto->fecha_escrituracion)) }}"  class="letrasModal form-control" required="true" readonly="true" />
                        @else
                            <input type="date" name="fecha_escrituracion" id="fecha_escrituracion" value=""  class="letrasModal form-control" required="true" readonly="true"/>
                        @endif
                    </div>
                </div>
            </div>
            @endif
            <div class="row">
                <div class="col-md-3 offset-md-3">
                    <div class="form-group">
                        @if ($ruta == 'propiedades-show')
                        <a href="{{ route($ruta, $prospecto->propiedad_id) }}" class="btn btn-dark btn-block">SALIR</a>
                        @else
                        <a href="{{ route($ruta) }}" class="btn btn-dark btn-block">SALIR</a>
                        @endif
                    </div>
                </div>
                @if (auth()->user()->id != null and auth()->user()->id == $prospecto->asesor_id and $prospecto->estatus_prospecto != 'Inactivo')
                    <div class="col-md-3">
                        <div class="form-group">
                            <button type="submit" class="btn btn-info btn-block">GUARDAR</button>
                        </div>
                    </div>
                @endif
            </div>
            {{ Form::close()}}
            @if (\Auth()->user()->rol != 6)
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <div class="row justify-content-between">
                                  <div class="col-md-8">Requisitos
                                  </div>
                                  <div class="col-md-4 mb-0 " align="right">
                                    <a href="#" id="btntodas" data-toggle="modal" data-target="#modal-completartodos" class="mb-0 d-sm-inline-block btn-ico-dark text-xl" title="Completar todas"><i class="fas fa-check"></i></a>
                                  </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive ">
                                    <table class="table table-hover table-withoutborder text-sm" id="tabla_requisitos">
                                      <thead class="thead-grubsa ">
                                        <th class="center">
                                          Requisito
                                        </th>
                                        <th class="center">
                                          Estatus
                                        </th>
                                        <th class="center">
                                         Acciones
                                        </th>
                                      </thead>
                                      <tbody>
                                        @foreach ($requisitos_prospecto as $rp)        
                                        <tr>
                                          <td>
                                            {{ $rp->requisito }}
                                          </td>
                                          <td class="center">
                                            {{ $rp->estatus }}
                                          </td>
                                          <td class="center-acciones">
                                            <a href="{{URL::action('RequisitosProspectoController@show', $rp->id_requisito_prospecto)}}"  style="width: 30%;"><button class="btn-ico"><i class="fas fa-pencil-alt"></i></button></a>
                                            @if ($rp->estatus == 'Pendiente')
                                            <a href="{{URL::action('RequisitosProspectoController@completar', $rp->id_requisito_prospecto)}}"  style="width: 30%;"><button class="btn-ico"><i class="fas fa-check"></i></button></a>
                                            @else
                                            <a href="{{URL::action('RequisitosProspectoController@pendiente', $rp->id_requisito_prospecto)}}"  style="width: 30%;"><button class="btn-ico"><i class="fas fa-times"></i></button></a>
                                            @endif
                                          </td>
                                        </tr>
                                        @endforeach
                                      </tbody>
                                    </table>
                                </div>                           
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <div class="row justify-content-between">
                                  <div class="col-md-8">Actividades
                                  </div>
                                  <div class="col-md-4 mb-0 " align="right">
                                    <a href="" id="btnplusactividad" data-toggle="modal" data-target="#modal-actividad" class="mb-0 d-sm-inline-block btn-ico-dark text-xl" title="NUEVO"><i class="fas fa-plus"></i></a>
                                  </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive ">
                                    <table class="table table-hover table-withoutborder text-sm" id="tabla_actividades">
                                      <thead class="thead-grubsa ">
                                        <th class="center">
                                          Actividad
                                        </th>
                                        <th class="center">
                                          Fecha
                                        </th>
                                        <th class="center">
                                          Estatus
                                        </th>
                                        <th class="center">
                                         Acciones
                                        </th>
                                      </thead>
                                      <tbody>
                                        @php
                                            $procedencia = 'Prospecto';
                                        @endphp
                                        @foreach ($actividades as $actividad)               
                                        <tr>
                                          <td>
                                            {{ $actividad->titulo }}
                                          </td>
                                          <td class="center">
                                            {{ $actividad->fecha }}
                                          </td>
                                          <td class="center">
                                            {{ $actividad->estatus }}
                                          </td>
                                          <td class="center-acciones">
                                            <a href="{{URL::action('ActividadController@show', [$actividad->id_actividad, $procedencia])}}"  style="width: 30%;"><button class="btn-ico"><i class="fas fa-pencil-alt"></i></button></a>
                                            @if ($actividad->estatus == 'Completada')
                                              <a href="{{URL::action('ActividadController@pendiente', [$actividad->id_actividad, $procedencia])}}"  style="width: 30%;"><button class="btn-ico"><i class="fas fa-times"></i></button></a>
                                            @else
                                              <a href="{{URL::action('ActividadController@completar', [$actividad->id_actividad, $procedencia])}}"  style="width: 30%;"><button class="btn-ico"><i class="fas fa-check"></i></button></a>
                                              <a href="{{URL::action('ActividadController@postergar', [$actividad->id_actividad, $procedencia])}}"  style="width: 30%;"><button class="btn-ico"><i class="fas fa-history"></i></button></a>
                                            @endif
                                            <a href="#" data-target="#modal-delete{{$actividad->id_actividad}}" data-toggle="modal" style="width: 30%;"><button class="btn-ico"><i class="fas fa-trash"></i></button></a>
                                          </td>
                                        </tr>
                                        @include('actividad.modal')
                                        @endforeach
                                       </tbody>
                                    </table>
                                </div>                           
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <div class="row justify-content-between">
                                  <div class="col-md-8">Cotizaciones
                                  </div>
                                  <div class="col-md-4 mb-0 " align="right">
                                    <a href="#" id="btnPlusCotizacion" data-toggle="modal" data-target="#modal-cotizacion" class="mb-0 d-sm-inline-block btn-ico-dark text-xl" title="NUEVO"><i class="fas fa-plus"></i></a>
                                  </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive ">
                                    <table class="table table-hover table-withoutborder text-sm" id="tabla_cotizaciones">
                                      <thead class="thead-grubsa ">
                                        <th class="center">
                                          No.
                                        </th>
                                        <th class="center">
                                          Fecha
                                        </th>
                                        <th class="center">
                                          Uso propiedad
                                        </th>
                                        <th class="center">
                                          Proyecto
                                        </th>
                                        <th class="center">
                                          Propiedades
                                        </th>
                                        <th class="center">
                                          Agente
                                        </th>
                                        <th class="center">
                                          Estatus
                                        </th>
                                        <th class="center">
                                          Fecha cierre
                                        </th>
                                        <th class="center">
                                         Acciones
                                        </th>
                                      </thead>
                                      <tbody>
                                        @php
                                            $procedencia = 'prospectos';
                                        @endphp
                                        @foreach ($cotizaciones as $c)               
                                        <tr>
                                          <td class="center">
                                            <a  href="{{URL::action('CotizacionController@show', [$c->id_cotizacion, $procedencia])}}">
                                                {{ $c->id_cotizacion }}
                                            </a>
                                          </td>
                                          <td class="center">
                                            <a class="text-dark" href="{{URL::action('CotizacionController@show', [$c->id_cotizacion, $procedencia])}}">
                                                {{ date('Y-M-d', strtotime($c->fecha_cotizacion)) }}
                                            </a>
                                          </td>
                                          <td class="center">
                                            <a class="text-dark" href="{{URL::action('CotizacionController@show', [$c->id_cotizacion, $procedencia])}}">
                                                {{ $c->uso_propiedad }}
                                            </a>
                                          </td>
                                          <td class="center">
                                            <a class="text-dark" href="{{URL::action('CotizacionController@show', [$c->id_cotizacion, $procedencia])}}">
                                                {{ $c->propiedad }}
                                            </a>
                                          </td>
                                          <td class="center">
                                            <a class="text-dark" href="{{URL::action('CotizacionController@show', [$c->id_cotizacion, $procedencia])}}">
                                                {{ $c->proyecto }}
                                            </a>
                                          </td>
                                          <td class="center">
                                            <a class="text-dark" href="{{URL::action('CotizacionController@show', [$c->id_cotizacion, $procedencia])}}">
                                                {{ $c->agente }}
                                            </a>
                                          </td>
                                          <td class="center">
                                            <a class="text-dark" href="{{URL::action('CotizacionController@show', [$c->id_cotizacion, $procedencia])}}">
                                                {{ $c->estatus }}
                                            </a>
                                          </td>
                                          <td class="center">
                                            @if ($c->fecha_cierre)
                                                <a class="text-dark" href="{{URL::action('CotizacionController@show', [$c->id_cotizacion, $procedencia])}}">
                                                    {{ date('Y-M-d', strtotime($c->fecha_cierre)) }}
                                                </a>
                                            @endif
                                          </td>
                                          <td class="center-acciones">
                                            <a href="{{URL::action('CotizacionController@show', [$c->id_cotizacion, $procedencia])}}"><button class="btn-ico"><i class="fas fa-pencil-alt"></i></button></a>
                                            <a href="#" data-target="#modal-delete{{$c->id_cotizacion}}" data-toggle="modal" style="width: 30%;"><button class="btn-ico"><i class="fas fa-trash"></i></button></a>
                                          </td>
                                        </tr>
                                        @include('cotizacion.modal')
                                        @endforeach
                                       </tbody>
                                    </table>
                                </div>                           
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <div class="row justify-content-between">
                                  <div class="col-md-8">Contactos
                                  </div>
                                  <div class="col-md-4 mb-0 " align="right">
                                    <a href="" id="btnplusactividad" data-toggle="modal" data-target="#modal-contacto" class="mb-0 d-sm-inline-block btn-ico-dark text-xl" title="NUEVO"><i class="fas fa-plus"></i></a>
                                  </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive ">
                                    <table class="table table-hover table-withoutborder text-sm" id="tabla_contactos">
                                      <thead class="thead-grubsa ">
                                        <th class="center">
                                          Nombre
                                        </th>
                                        <th class="center">
                                          Correo
                                        </th>
                                        <th class="center">
                                          Telefono
                                        </th>
                                        <th class="center">
                                         Acciones
                                        </th>
                                      </thead>
                                      <tbody>
                                        @foreach ($contactos as $cc)
                                        <tr>
                                          <td>
                                            {{ $cc->nombre }}
                                          </td>
                                          <td class="center">
                                            {{ $cc->correo }}
                                          </td>
                                          <td class="center">
                                            {{ $cc->telefono }}
                                          </td>
                                          <td class="center-acciones">
                                            <a href="{{URL::action('ContactosController@show', [$cc->id_contacto, $procedencia])}}"  style="width: 30%;"><button class="btn-ico"><i class="fas fa-pencil-alt"></i></button></a>
                                            <a href="#" data-target="#modal-delete{{$cc->id_contacto}}" data-toggle="modal" style="width: 30%;"><button class="btn-ico"><i class="fas fa-trash"></i></button></a>  
                                          </td>
                                        </tr>
                                        @include('contacto.modal')
                                        @endforeach
                                      </tbody>
                                    </table>
                                </div>                           
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <div class="row justify-content-between">
                                  <div class="col-md-8">Documentos
                                  </div>
                                  <div class="col-md-4 mb-0 " align="right">
                                    <a href="" class="mb-0 d-sm-inline-block btn-ico-dark text-xl" id="btnplusactividad" data-toggle="modal" data-target="#modal-documento" title="NUEVO"><i class="fas fa-plus"></i></a>
                                  </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive ">
                                    <table class="table table-hover table-withoutborder text-sm" id="tabla_documentos">
                                      <thead class="thead-grubsa ">
                                        <th class="center">
                                          Titulo
                                        </th>
                                        <th class="center">
                                          Fecha
                                        </th>
                                        <th class="center">
                                          Notas
                                        </th>
                                        <th class="center">
                                         Acciones
                                        </th>
                                      </thead>
                                      <tbody>
                                        @php
                                            $procedencia = 'Prospecto';
                                        @endphp
                                        @foreach ($documentos as $doc)               
                                        <tr>
                                          <td class="center">
                                            {{ $doc->titulo }}
                                          </td>
                                          <td class="center">
                                            {{ date('Y-m-d',strtotime($doc->fecha)) }}
                                          </td>
                                          <td class="center">
                                            {{ $doc->notas }}
                                          </td>
                                          <td class="center-acciones">
                                            <a href="{{URL::action('FileController@show', [$doc->id_documento, $procedencia])}}"  style="width: 30%;"><button class="btn-ico"><i class="fas fa-pencil-alt"></i></button></a>
                                            <a href="#" data-target="#modal-delete{{$doc->id_documento}}" data-toggle="modal" style="width: 30%;"><button class="btn-ico"><i class="fas fa-trash"></i></button></a>    
                                          </td>
                                        </tr>
                                        @include('documentos.modal')
                                        @endforeach
                                       </tbody>
                                    </table>
                                </div>                           
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if (auth()->user()->rol == '3' or auth()->user()->rol == 6)
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <div class="row justify-content-between">
                              <div class="col-md-10"> Plazos de pago
                              </div>
                              <div class="col-md-2 mb-0 " align="right">
                                {{ Form::open(array('action'=>array('PlazosPagoController@exportExcel'),'method'=>'get')) }}
 
                                    <a href="" id="btnplusmsj" data-toggle="modal" data-target="#modal-plazos" class="mb-0 d-sm-inline-block btn-ico-dark text-xl" title="NUEVO"><i class="fas fa-plus"></i></a>
                                    <input type="hidden" class="form-control" placeholder="Id prospecto" name="id_excel" id="id_excel" value="{{ $prospecto->id_prospecto}}">
                                    <button class="mb-0 d-sm-inline-block btn-ico-dark text-xl"><i class="fas fa-file-excel"></i></button>

                                {!! Form::close() !!}
                              </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive ">
                                <table class="table table-hover table-withoutborder text-sm" id="tabla_plazas_pago">
                                  <thead class="thead-grubsa ">
                                    <th class="center">
                                      Num
                                    </th>
                                    <th class="center">
                                      Fecha
                                    </th>
                                    <th class="center">
                                      Tipo
                                    </th>
                                    <th class="center">
                                      Concepto
                                    </th>
                                    <th class="center">
                                      Estatus
                                    </th>
                                    <th class="center">
                                      Total
                                    </th>
                                    <th class="center">
                                      Saldo
                                    </th>
                                    <th class="center">
                                      Pagado
                                    </th>
                                    <th class="center">
                                     Acciones
                                    </th>
                                  </thead>
                                  <tbody>
                                    @php
                                        $sumatotal = 0;
                                        $sumasaldo = 0;
                                        $sumapagado = 0;
                                    @endphp  
                                    @foreach ($plazos_pago as $pp)
                                    @php
                                        $sumatotal = $sumatotal + $pp->total;
                                        $sumasaldo = $sumasaldo + $pp->saldo;
                                        $sumapagado = $sumapagado + $pp->pagado;
                                    @endphp              
                                    <tr>
                                      <td class="center">
                                        <a class="text-dark" href="{{URL::action('PlazosPagoController@show', [$pp->id_plazo_pago, $ruta])}}">
                                            {{ $pp->num_plazo }}
                                        </a>
                                      </td>
                                      <td class="center">
                                        <a class="text-dark" href="{{URL::action('PlazosPagoController@show', [$pp->id_plazo_pago, $ruta])}}">
                                            {{ date('Y-m-d',strtotime($pp->fecha)) }}
                                        </a>
                                      </td>
                                      <td class="center">
                                        <a href="{{URL::action('PlazosPagoController@show', [$pp->id_plazo_pago, $ruta])}}">
                                            {{ $pp->tipo }}
                                        </a>
                                      </td>
                                      <td class="center">
                                        <a class="text-dark" href="{{URL::action('PlazosPagoController@show', [$pp->id_plazo_pago, $ruta])}}">
                                            {{ $pp->descripcion }}
                                        </a>
                                      </td>
                                      <td class="center">
                                        <a class="text-dark" href="{{URL::action('PlazosPagoController@show', [$pp->id_plazo_pago, $ruta])}}">
                                            {{ $pp->estatus }}
                                        </a>
                                      </td>
                                      <td class="center">
                                        <a class="text-dark" href="{{URL::action('PlazosPagoController@show', [$pp->id_plazo_pago, $ruta])}}">
                                            $ {{ number_format($pp->total , 2 , "." , ",") }}
                                        </a>
                                      </td>
                                      <td class="center">
                                        <a class="text-dark" href="{{URL::action('PlazosPagoController@show', [$pp->id_plazo_pago, $ruta])}}">
                                            $ {{ number_format($pp->saldo , 2 , "." , ",") }}
                                        </a>
                                      </td>
                                      <td class="center">
                                        <a class="text-error" href="{{URL::action('PlazosPagoController@show', [$pp->id_plazo_pago, $ruta])}}">
                                            $ {{ number_format($pp->pagado , 2 , "." , ",") }}
                                        </a>
                                      </td>
                                      <td class="center-acciones">
                                        <a href="{{URL::action('PlazosPagoController@show', [$pp->id_plazo_pago, $ruta])}}"  style="width: 30%;"><button class="btn-ico"><i class="fas fa-pencil-alt"></i></button></a>
                                        @if (auth()->user()->rol=='3')
                                        <a href="#" data-target="#modal-delete{{$pp->id_plazo_pago}}" data-toggle="modal" style="width: 30%;"><button class="btn-ico" ><i class="fas fa-trash-alt"></i></button></a> 
                                        @endif
                                      </td>
                                    </tr>
                                    @include('plazos_pago.modal')
                                    @endforeach
                                   </tbody>
                                   <tfoot>
                                       <tr>
                                            <th class="center">
                                            </th>
                                            <th class="center">
                                            </th>
                                            <th class="center">
                                            </th>
                                            <th class="center">
                                            </th>
                                            <th class="center">
                                            </th>
                                            <th class="center">
                                              $ {{ number_format($sumatotal , 2 , "." , ",") }}
                                            </th>
                                            <th class="center">
                                              $ {{ number_format($sumasaldo , 2 , "." , ",") }}
                                            </th>
                                            <th class="center">
                                              $ {{ number_format($sumapagado , 2 , "." , ",") }}
                                            </th>
                                            <th class="center">
                                             Acciones
                                            </th>
                                       </tr>
                                   </tfoot>
                                </table>
                            </div>
                            {{-- {{$plazos_pago->render()}}    --}}                     
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <div class="row justify-content-between">
                              <div class="col-md-8">Mensajes
                              </div>
                              <div class="col-md-4 mb-0 " align="right">
                                <a href="" class="mb-0 d-sm-inline-block btn-ico-dark text-xl"  id="btnplusmsj" data-toggle="modal" data-target="#modal-mensaje" title="NUEVO"><i class="fas fa-plus"></i></a>
                              </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive ">
                                <table class="table table-hover table-withoutborder text-sm" id="tabla_mensajes">
                                  <thead class="thead-grubsa ">
                                    <th class="center">
                                      Estatus
                                    </th>
                                    <th class="center">
                                      Fecha
                                    </th>
                                    <th class="center">
                                      Titulo
                                    </th>
                                    <th class="center">
                                      Creador
                                    </th>
                                    <th class="center">
                                      Dirigido
                                    </th>
                                    <th class="center">
                                     Acciones
                                    </th>
                                  </thead>
                                  <tbody>
                                    @foreach ($mensajes as $m)         
                                    <tr>
                                      <td class="center">
                                        <a href="{{URL::action('MensajeController@show', [$m->id_mensaje, $ruta])}}">
                                            {{ $m->estatus }}
                                        </a>
                                      </td>
                                      <td class="center">
                                        <a href="{{URL::action('MensajeController@show', [$m->id_mensaje, $ruta])}}">
                                            {{ date('Y-m-d',strtotime($m->fecha)) }}
                                        </a>
                                      </td>
                                      <td class="center">
                                        <a href="{{URL::action('MensajeController@show', [$m->id_mensaje, $ruta])}}">
                                            {{ $m->titulo }}
                                        </a>
                                      </td>
                                      <td class="center">
                                        <a href="{{URL::action('MensajeController@show', [$m->id_mensaje, $ruta])}}">
                                            {{ $m->creador }}
                                        </a>
                                      </td>
                                      <td class="center">
                                        <a href="{{URL::action('MensajeController@show', [$m->id_mensaje, $ruta])}}">
                                            {{ $m->dirigido }}
                                        </a>
                                      </td>
                                      <td class="center-acciones">
                                        <a href="{{URL::action('MensajeController@show', [$m->id_mensaje, $ruta])}}"  style="width: 30%;"><button class="btn-ico"><i class="fas fa-pencil-alt"></i></button></a>
                                        @if (auth()->user()->rol=='3')
                                        <a href="#" data-target="#modal-delete{{$m->id_mensaje}}" data-toggle="modal" style="width: 30%;"><button class="btn-ico" ><i class="fas fa-trash-alt"></i></button></a> 
                                        @endif
                                      </td>
                                    </tr>
                                    @include('mensaje.modal')
                                    @endforeach
                                   </tbody>
                                </table>
                            </div>
                            {{-- {{$mensajes->render()}}  --}}                       
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@php
    $procedencia = 'Prospecto';
@endphp
<!-- modal de visita-->
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-visita">
    {{ Form::open(array('action'=>array('ProspectosController@visita',$prospecto->id_prospecto),'method'=>'get')) }}
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Visitar propiedad</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="fecha_visita_mdl">*Fecha de visita</label>
                            <input type="date" name="fecha_visita_mdl" id="fecha_visita_mdl" value="{{ date('Y-m-d') }}" required="true" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="proyecto_mdl_visita">Proyecto</label>
                            <select name="proyecto_mdl_visita" id="proyecto_mdl_visita" class="form-control" >
                                <option value="sin proyecto">Sin proyecto</option>
                                @foreach ($proyectos as $pymdl) 
                                    @if ($prospecto->proyecto_id == $pymdl->id_proyecto)
                                        <option selected="selected" value="{{ $pymdl->id_proyecto  }}">{{ $pymdl->nombre }}</option>
                                    @else
                                        <option value="{{ $pymdl->id_proyecto  }}">{{ $pymdl->nombre }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="propiedad_mdl_visita">Propiedad</label>
                            <select name="propiedad_mdl_visita" id="propiedad_mdl_visita" class="form-control selectpicker" data-live-search="true">
                                <option selected="selected" value="{{ $prospecto->propiedad_id  }}">{{ $prospecto->nombre_propiedad }}</option>
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
<!-- modal de apartado-->
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-apartar">
    {{ Form::open(array('action'=>array('ProspectosController@apartar',$prospecto->id_prospecto),'method'=>'get')) }}
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Apartar propiedad</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="montoapartado_mdl">*Monto de apartado</label>
                            <input type="text" name="montoapartado_mdl" id="montoapartado_mdl" value="{{ $prospecto->enganche }}"  class="mask form-control">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="proyecto_mdl_apartado">*Proyecto</label>
                            <select name="proyecto_mdl_apartado" id="proyecto_mdl_apartado" class="form-control">
                                <option value="sin proyecto">Sin proyecto</option>
                                @foreach ($proyectos as $pymdl) 
                                    @if ($prospecto->proyecto_id == $pymdl->id_proyecto)
                                        <option selected="selected" value="{{ $pymdl->id_proyecto  }}">{{ $pymdl->nombre }}</option>
                                    @else
                                        <option value="{{ $pymdl->id_proyecto  }}">{{ $pymdl->nombre }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="propiedad_mdl">*Propiedad</label>
                            <select name="propiedad_mdl" id="propiedad_mdl" class="form-control">
                                <option selected="selected" value="{{ $prospecto->propiedad_id  }}">{{ $prospecto->nombre_propiedad }}</option>
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
<!-- modal de contrato-->
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-contrato">
    {{ Form::open(array('action'=>array('ProspectosController@contrato', $prospecto->id_prospecto),'method'=>'get')) }}
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nuevo contrato</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" name="id_prospecto_mdl_contrato" id="id_prospecto_mdl_contrato" value="{{ $prospecto->id_prospecto }}"  class="form-control" required>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="num_contrato_mdl">*Numero de contrato</label>
                            <input type="text" name="num_contrato_mdl" id="num_contrato_mdl" value=""  class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-4 oculto">
                        <div class="form-group">
                            <label for="porcentaje_interes_mdl">*Porcentaje Interes</label>
                            <input type="number" step="any" name="porcentaje_interes_mdl"  max="100" id="porcentaje_interes_mdl" value="0.00"  class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fecha_contrato_mdl">*Fecha de contrato</label>
                            <input type="date" name="fecha_contrato_mdl" id="fecha_contrato_mdl" value="{{ date('Y-m-d') }}" class="letrasModal form-control" required="true"/>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="proyecto_contrato_mdl">Proyecto</label>
                            <select name="proyecto_contrato_mdl" id="proyecto_contrato_mdl" value=""  class="letrasModal form-control">
                                <option value="sin proyecto">Sin proyecto</option>
                                @foreach ($proyectos as $py)
                                    @if ($prospecto->proyecto_id == $py->id_proyecto)
                                        <option selected="true" value="{{ $py->id_proyecto }}">{{ $py->nombre }}</option>
                                    @else
                                      <option value="{{ $py->id_proyecto }}">{{ $py->nombre }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="propiedad_contrato_mdl">Propiedad</label>
                            <select name="propiedad_contrato_mdl" id="propiedad_contrato_mdl" class="letrasModal form-control selectpicker" data-live-search="true">
                                <option value="{{ $prospecto->propiedad_id }}">{{ $prospecto->nombre_propiedad }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 oculto">
                        <div class="form-group">
                            <label for="monto_venta_mdl_contrato">*Monto venta</label>
                            <input type="text" step="any" name="monto_venta_mdl_contrato" id="monto_venta_mdl_contrato" value="{{ $prospecto->precio }}"  class="form-control mask">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <h5>Selecciona la cotizacion y esquema</h5>
                            <hr>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="cotizacion_id_mdl">*Cotizacion</label>
                            <select class="form-control selectpicker" id="cotizacion_id_mdl" name="cotizacion_id_mdl" required="true">
                                <option value=""></option>
                                @foreach ($cotizaciones as $e)
                                    @if ($e->id_cotizacion == $prospecto->cotizacion_id)
                                    <option selected value="{{ $e->id_cotizacion }}">{{ $e->id_cotizacion }}</option>
                                    @else
                                    <option value="{{ $e->id_cotizacion }}">{{ $e->id_cotizacion }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="esquema_pago_id_mdl">*Esquema de pago</label>
                            <select class="form-control selectpicker" id="esquema_pago_id_mdl" name="esquema_pago_id_mdl" required="true">
                                <option value=""></option>
                                @foreach ($detalles_esquema_cotizacion as $e)
                                    @if ($e->id_detalle_cotizacion_propiedad == $prospecto->esquema_pago_id)
                                    <option selected value="{{ $e->id_detalle_cotizacion_propiedad }}">{{ $e->esquema_pago }}</option>
                                    @else
                                    <option value="{{ $e->id_detalle_cotizacion_propiedad }}">{{ $e->esquema_pago }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 oculto">
                        <div class="form-group">
                            <label for="vigencia_contrato_mdl">Vigencia contrato</label>
                            <input type="date" name="vigencia_contrato_mdl" id="vigencia_contrato_mdl"  class="letrasModal form-control"/>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <h5>Datos del cliente</h5>
                            <hr>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="rfc_mdl">RFC</label>
                            <input type="text" name="rfc_mdl" id="rfc_mdl" value="{{ $prospecto->rfc }}" maxlength="15" class="letrasModal form-control" required="true"/>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="domicilio_mdl">Domicilio</label>
                            <input type="text" name="domicilio_mdl" id="domicilio_mdl" value="{{ $prospecto->domicilio }}" class="letrasModal form-control" required="true"/>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nacionalidad_mdl">Nacionalidad</label>
                            <select name="nacionalidad_mdl" id="nacionalidad_mdl"  class="letrasModal form-control" required="true">
                                @foreach ($nacionalidades as $n)
                                    <option value="{{ $n }}">{{ $n }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="crear_usuario">*Crear usuario?</label>
                            <select name="crear_usuario" id="crear_usuario" class="letrasModal form-control" required="true">
                              <option  value="1">Si</option>
                              <option selected="true" value="0">No</option>
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
<!-- modal de perdidad-->
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-perder">
    {{ Form::open(array('action'=>array('ProspectosController@perder',$prospecto->id_prospecto),'method'=>'get')) }}
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Perder prospecto</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">                      
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="motivo_perdida_mdl">*Motivo de perdida</label>
                            <select id="motivo_perdida_mdl" name="motivo_perdida_mdl" class="form-control">
                                @foreach ($motivos_perdida as $motivo)
                                    <option value="{{ $motivo->id_motivo_perdida }}">{{ $motivo->motivo_perdida }}</option>
                                @endforeach
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
<!-- modal de cambiar asesor-->
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-cambiar">
    {{ Form::open(array('action'=>array('ProspectosController@cambiar_asesor',$prospecto->id_prospecto),'method'=>'post')) }}
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cambiar asesor</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">                      
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="nuevo_asesor">*Nuevo asesor</label>
                            <select id="nuevo_asesor" name="nuevo_asesor" class="form-control">
                                @foreach ($usuarios as $nuser)
                                    <option value="{{ $nuser->id }}">{{ $nuser->name }}</option>
                                @endforeach
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
<!-- modal de carga de requisitos-->
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-requisitos">
    {{ Form::open(array('action'=>array('ProspectosController@cargar_requisitos',$prospecto->id_prospecto),'method'=>'post')) }}
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cargar requisitos</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">                      
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="requisitosmdl">*Tipo de requisitos</label>
                            <select id="requisitosmdl" name="requisitosmdl" class="form-control">
                                @foreach ($requisitos as $req)
                                    <option  value="{{ $req->id_requisito }}">{{ $req->requisito }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
                <button type="submit" id="btn_modal_requisitos" class="btn btn-info">Confirmar</button>
            </div>
        </div>
    </div>
    {{ Form::close()}}
</div>
<!-- modal de entrega propiead-->
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-entregar_propiedad">
    {{ Form::open(array('action'=>array('ProspectosController@entregar_propiedad',$prospecto->id_prospecto),'method'=>'post')) }}
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Entregar propiedad</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">                      
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="fecha_entrega_propiedad_mdl">*Fecha de entrega propiedad</label>
                            <input type="date" name="fecha_entrega_propiedad_mdl" id="fecha_entrega_propiedad_mdl"  class="letrasModal form-control" required="true"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
                <button type="submit" id="btn_modal_postergar" class="btn btn-info">Confirmar</button>
            </div>
        </div>
    </div>
    {{ Form::close()}}
</div>
<!-- modal de postergado-->
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-postergar">
    {{ Form::open(array('action'=>array('ProspectosController@postergar',$prospecto->id_prospecto),'method'=>'post')) }}
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Postergar</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">                      
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="fecha_recontacto_mdl">*Fecha de recontacto</label>
                            <input type="date" name="fecha_recontacto_mdl" id="fecha_recontacto_mdl"  class="letrasModal form-control" required="true"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
                <button type="submit" id="btn_modal_postergar" class="btn btn-info">Confirmar</button>
            </div>
        </div>
    </div>
    {{ Form::close()}}
</div>
<!-- modal de actividad-->
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-actividad">
    {{ Form::open(array('action'=>array('ActividadController@store',$procedencia),'method'=>'post')) }}
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title text-dark">Nueva actividad</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="nuevo_actividad_mdl">*Titulo</label>
                          <input type="text" name="nuevo_actividad_mdl" id="nuevo_actividad_mdl" value=""  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="nuevo_tipo_mdl">*Tipo de actividad</label>
                          <select name="nuevo_tipo_mdl" id="nuevo_tipo_mdl" class="letrasModal form-control" required="true">
                            <option value="Tarea">Tarea</option>
                            <option value="Llamada">Llamada</option>
                            <option value="Cita">Cita</option>
                            <option value="Correo">Correo</option>
                          </select>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="nuevo_fecha_mdl">*Fecha</label>
                          <input type="date" name="nuevo_fecha_mdl" id="nuevo_fecha_mdl" value="@php
                            echo date('Y-m-d');
                          @endphp"  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="nuevo_hora_mdl">*Hora</label>
                          <input type="time" name="nuevo_hora_mdl" id="nuevo_hora_mdl" value="@php
                            echo date('H:i:s');
                          @endphp"  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <input type="hidden" name="nuevo_duracion_mdl" id="nuevo_duracion_mdl" step="0" value="1"  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="nuevo_prospecto_mdl">*Prospecto</label>
                          <select name="nuevo_prospecto_mdl" id="nuevo_prospecto_mdl" class="letrasModal form-control">
                            @foreach ($prospectos as $pros)
                                @if ($prospecto->id_prospecto == $pros->id_prospecto)
                                    <option selected="true" value="{{ $pros->id_prospecto }}">{{ $pros->nombre }}</option>
                                @else
                                    <option value="{{ $pros->id_prospecto }}">{{ $pros->nombre }}</option>
                                @endif
                            @endforeach
                          </select>
                      </div>
                  </div> 
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="nuevo_desc_mdl">Descripcion</label>
                          <textarea name="nuevo_desc_mdl" id="nuevo_desc_mdl" value=""  class="letrasModal form-control"></textarea>
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
<!-- modal de documento-->
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-documento">
  {{ Form::open(array('action'=>array('FileController@store',$procedencia),'method'=>'post','files'=>'true', 'id' => 'my-dropzone' )) }}
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title text-dark">Nuevo Documento</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="titulo">*Titulo</label>
                          <input type="text" name="titulo" id="titulo" value=""  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="fecha">Fecha</label>
                          <input type="date" name="fecha" id="fecha" value="{{date("Y-m-d")}}" class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="nuevo_prospecto_mdl">Prospecto</label>
                          <select name="nuevo_prospecto_mdl" id="nuevo_prospecto_mdl" class="letrasModal form-control">
                            @foreach ($prospectos as $pros)
                                @if ($prospecto->id_prospecto == $pros->id_prospecto)
                                    <option selected="true" value="{{ $pros->id_prospecto }}">{{ $pros->nombre }}</option>
                                @else
                                    <option value="{{ $pros->id_prospecto }}">{{ $pros->nombre }}</option>
                                @endif
                            @endforeach
                          </select>
                      </div>
                  </div>   
                  <div class="col-md-12">
                      <div class="form-group">
                        <label for="notas">Notas</label>
                          <textarea name="notas" id="notas" value=""  class="letrasModal form-control"></textarea>
                      </div>
                  </div>               
              </div>
              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <td>           
                              <input  class="archivo" onchange="ValidateSize(this)" name="archivo" type="file" required="true" />
                          </td>
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
<!-- modal de contacto-->
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-contacto">
    {{ Form::open(array('action'=>array('ContactosController@store',$procedencia),'method'=>'post')) }}
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title text-dark">Nuevo contacto</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="nombre_contacto">*Nombre</label>
                          <input type="text" name="nombre_contacto" id="nombre_contacto" value=""  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="correo_contacto">Correo</label>
                          <input type="text" name="correo_contacto" id="correo_contacto" value=""  class="letrasModal form-control"  />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="telefono_contacto">Telefono</label>
                          <input type="text" name="telefono_contacto" id="telefono_contacto" value=""   class="letrasModal form-control" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="telefono_contacto">Telefono adicional</label>
                          <input type="text" name="telefono_contacto" id="telefono_contacto" value=""  class="letrasModal form-control" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="puesto_contacto">Puesto</label>
                          <input type="text" name="puesto_contacto" id="puesto_contacto" value=""  class="letrasModal form-control"  />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="prospecto_contacto">*Prospecto</label>
                          <select name="prospecto_contacto" id="prospecto_contacto" class="letrasModal form-control" >
                            @foreach ($prospectos as $pros)
                                @if ($prospecto->id_prospecto == $pros->id_prospecto)
                                    <option selected="true" value="{{ $pros->id_prospecto }}">{{ $pros->nombre }}</option>
                                @else
                                    <option value="{{ $pros->id_prospecto }}">{{ $pros->nombre }}</option>
                                @endif
                            @endforeach
                          </select>
                      </div>
                  </div> 
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="notas_contacto">Notas</label>
                          <textarea name="notas_contacto" id="notas_contacto" value=""  class="letrasModal form-control"></textarea>
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
<!-- modal de cotizacion-->
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-cotizacion">
    {{ Form::open(array('action'=>array('ProspectosController@cotizar', $prospecto->id_prospecto),'method'=>'get' )) }}
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title text-dark">Cotizar prospecto</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                <input type="hidden" name="procedencia_" id="procedencia_" value="{{ $ruta }}">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="proyecto_mdl_cotizar">*Proyecto</label>
                    <select class="form-control" id="proyecto_mdl_cotizar" name="proyecto_mdl_cotizar" required="true">
                        <option value="sin proyecto">Sin proyecto</option>
                        @foreach ($proyectos as $pymdl_c) 
                            @if ($prospecto->proyecto_id == $pymdl_c->id_proyecto)
                                <option selected="selected" value="{{ $pymdl_c->id_proyecto  }}">{{ $pymdl_c->nombre }}</option>
                            @else
                                <option value="{{ $pymdl_c->id_proyecto  }}">{{ $pymdl_c->nombre }}</option>
                            @endif
                        @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="propiedad_mdl_cotizar">*Propiedad</label><br>
                    <select class="form-control selectpicker" id="propiedad_mdl_cotizar" name="propiedad_mdl_cotizar[]" multiple="multiple" data-live-search="true" >
                        @foreach ($propiedades as $pymdl_c) 
                            @if ($prospecto->propiedad_id == $pymdl_c->id_propiedad)
                            <option selected="selected" value="{{ $pymdl_c->id_propiedad  }}">{{ $pymdl_c->nombre }}</option>
                            @else
                            <option value="{{ $pymdl_c->id_propiedad  }}">{{ $pymdl_c->nombre }}</option>
                            @endif
                        @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="moneda_cotizar">*Moneda</label>
                        <select name="moneda_cotizar" id="moneda_cotizar" class="letrasModal form-control" required="true">
                            @foreach ($monedas as $m)
                                @if ($m->id_moneda == $prospecto->moneda_id )
                                <option selected value="{{ $m->id_moneda }}">{{ $m->siglas }}</option>
                                @else
                                <option value="{{ $m->id_moneda }}">{{ $m->siglas }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="uso_propiedad_id_mdl_cotizar">*Uso de propiedad</label>
                        <select name="uso_propiedad_id_mdl_cotizar" id="uso_propiedad_id_mdl_cotizar" class="letrasModal form-control" required="true">
                            <option value=""></option>
                            @foreach ($usos_propiedad as $m)
                                <option value="{{ $m->id_uso_propiedad }}">{{ $m->uso_propiedad }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="grupo_esquema_mdl_cotizar">*Grupo esquemas</label>
                    <select id="grupo_esquema_mdl_cotizar" name="grupo_esquema_mdl_cotizar" class="form-control selectpicker"  data-live-search="true" >
                        @foreach ($grupo_esquema as $pymdl_c) 
                            <option value="{{ $pymdl_c->id_grupo_esquema  }}">{{ $pymdl_c->grupo_esquema }}</option>
                        @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="fecha_cotizacion_cotizar">*Fecha de cotizacion</label>
                    <input type="date" name="fecha_cotizacion_cotizar" id="fecha_cotizacion_cotizar" value="{{ date('Y-m-d') }}"  class="letrasModal form-control" required="true" />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="cliente_cotizar">*Nombre cliente</label>
                    <input type="text" name="cliente_cotizar" id="cliente_cotizar" value="{{ $prospecto->nombre }}"  class="letrasModal form-control" required="true" />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="telefono_cotizar">Telefono</label>
                    <input type="text" name="telefono_cotizar" id="telefono_cotizar" value="{{ $prospecto->telefono }}"  class="letrasModal form-control"  />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="correo">Correo</label>
                    <input type="email_cotizar" name="correo_cotizar" id="correo_cotizar" value="{{ $prospecto->correo }}"  class="letrasModal form-control"  />
                  </div>
                </div>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-info">Cotizar</button>
          </div>
      </div>
  </div>
  {{ Form::close()}}  
</div>
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-adminestatus">
    {{ Form::open(array('action'=>array('ProspectosController@admin_estatus', $prospecto->id_prospecto),'method'=>'post' )) }}
    <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title text-dark">Estatus prospecto</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="estatus_admin">Estatus</label>
                    <select class="form-control" id="estatus_admin" name="estatus_admin" required="true">
                        @foreach ($estatus_crm as $p) 
                            @if ($prospecto->estatus == $p->id_estatus_crm)
                                <option selected="selected" value="{{ $p->id_estatus_crm  }}">{{ $p->estatus_crm }}</option>
                            @else
                                <option value="{{ $p->id_estatus_crm  }}">{{ $p->estatus_crm }}</option>
                            @endif
                        @endforeach
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
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-plazos">
    {{ Form::open(array('action'=>array('PlazosPagoController@store', $procedencia),'method'=>'post' )) }}
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title text-dark">Nuevo plazo de pago</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="nuevo_num">*Numero de plazo</label>
                          <input type="number" name="nuevo_num" id="nuevo_num" value=""  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="nuevo_fecha">*Fecha</label>
                          <input type="date" name="nuevo_fecha" id="nuevo_fecha" value="{{date("Y-m-d")}}" class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="nuevo_total">*Total</label>
                          <input type="number" step="any" name="nuevo_total" id="nuevo_total" value=""  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="nuevo_interes">*Interes</label>
                          <input type="number"  max="100" step="any" name="nuevo_interes" id="nuevo_interes" value="{{ $prospecto->porcentaje_interes }}"  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="nuevo_moneda">*Moneda</label>
                          <select name="nuevo_moneda" id="nuevo_moneda" class="letrasModal form-control" required="true">
                            @foreach ($monedas as $m)
                                @if ($m->id_moneda == $prospecto->moneda_id )
                                    <option selected value="{{ $m->id_moneda }}">{{ $m->siglas }}</option>
                                @else
                                    <option value="{{ $m->id_moneda }}">{{ $m->siglas }}</option>
                                @endif
                            @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="nuevo_estatus">*Estatus</label>
                      <select name="nuevo_estatus" id="nuevo_estatus" class="letrasModal form-control">
                        <option selected="Programado">Programado</option>
                        <option value="Vencido">Vencido</option>
                        <option value="Pagado">Pagado</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                        <label for="nuevo_prospecto_mdl">*Prospecto</label>
                        <select name="nuevo_prospecto_mdl" id="nuevo_prospecto_mdl" class="letrasModal form-control">
                          @foreach ($prospectos as $pros)
                            @if ($pros->id_prospecto == $prospecto->id_prospecto )
                                <option selected value="{{ $pros->id_prospecto }}">{{ $pros->nombre }}</option>
                            @else
                                <option value="{{ $pros->id_prospecto }}">{{ $pros->nombre }}</option>
                            @endif
                          @endforeach
                        </select>
                    </div>
                  </div>               
              </div>
              <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                        <label for="nuevo_notas">Notas</label>
                          <textarea name="nuevo_notas" id="nuevo_notas" value=""  class="letrasModal form-control" ></textarea>
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
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-mensaje">
    {{ Form::open(array('action'=>array('MensajeController@store'),'method'=>'post' )) }}
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title text-dark">Nuevo mensaje</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="titulo">*Titulo</label>
                          <input type="text" name="titulo" id="titulo" value=""  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="fecha">*Fecha</label>
                          <input type="date" name="fecha" id="fecha" value="{{date("Y-m-d")}}" class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="estatus">*Estatus</label>
                          <input type="text" step="any" name="estatus" id="estatus" value="Borrador"  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="creador">*Creador por</label>
                          <select name="creador" id="creador" class="letrasModal form-control" required="true">
                            @foreach ($usuarios as $m)
                                @if ($m->id == auth()->user()->id )
                                    <option selected value="{{ $m->id }}">{{ $m->name }}</option>
                                @else
                                    <option value="{{ $m->id }}">{{ $m->name }}</option>
                                @endif
                            @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="dirigido">*Dirigido a</label>
                      <select name="dirigido" id="dirigido" class="letrasModal form-control" required="true">
                        @foreach ($usuarios as $m)
                            @if ($m->id == auth()->user()->id )
                                <option selected value="{{ $m->id }}">{{ $m->name }}</option>
                            @else
                                <option value="{{ $m->id }}">{{ $m->name }}</option>
                            @endif
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                        <label for="nota">Notas</label>
                          <textarea name="nota" id="nota" rows="6" value=""  class="letrasModal form-control" ></textarea>
                      </div>
                  </div>      
                  <div class="col-md-6">
                    <div class="form-group">
                        <label for="prospecto">*Prospecto</label>
                        <select name="prospecto" id="prospecto" class="letrasModal form-control">
                          @foreach ($prospectos as $pros)
                            @if ($pros->id_prospecto == $prospecto->id_prospecto )
                                <option selected value="{{ $pros->id_prospecto }}">{{ $pros->nombre }}</option>
                            @else
                                <option value="{{ $pros->id_prospecto }}">{{ $pros->nombre }}</option>
                            @endif
                          @endforeach
                        </select>
                    </div>
                  </div>               
              </div>
              <div class="row">
                  
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
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-pagando">
    {{ Form::open(array('action'=>array('ProspectosController@pagando',$prospecto->id_prospecto),'method'=>'post')) }}
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title text-dark">Nueva venta</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="fecha_venta_mdl">*Fecha de venta</label>
                          <input type="date" name="fecha_venta_mdl" id="fecha_venta_mdl" value="{{ date('Y-m-d') }}"  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                        <label for="cotizacion_id_mdl_pagando">*Cotizacion</label>
                        <select class="form-control" id="cotizacion_id_mdl_pagando" name="cotizacion_id_mdl_pagando" required="true">
                            <option value=""></option>
                            @foreach ($cotizaciones as $e)
                                @if ($e->id_cotizacion == $prospecto->cotizacion_id)
                                <option selected value="{{ $e->id_cotizacion }}">{{ $e->id_cotizacion }}</option>
                                @else
                                <option value="{{ $e->id_cotizacion }}">{{ $e->id_cotizacion }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                        <label for="esquema_pago_id_mdl_pagando">*Esquema de pago</label>
                        <select class="form-control" id="esquema_pago_id_mdl_pagando" name="esquema_pago_id_mdl_pagando" required="true">
                            <option value=""></option>
                            @foreach ($detalles_esquema_cotizacion as $e)
                                @if ($e->id_detalle_cotizacion_propiedad == $prospecto->esquema_pago_id)
                                <option selected value="{{ $e->id_detalle_cotizacion_propiedad }}">{{ $e->esquema_pago }}</option>
                                @else
                                <option value="{{ $e->id_detalle_cotizacion_propiedad }}">{{ $e->esquema_pago }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                        <label for="moneda_mdl">*Moneda</label>
                        <select name="moneda_mdl" id="moneda_mdl" class="letrasModal form-control">
                          @foreach ($monedas as $m)
                            @if ($m->id_moneda == $prospecto->moneda_id )
                                <option selected value="{{ $m->id_moneda }}">{{ $m->siglas }}</option>
                            @else
                                <option value="{{ $m->id_moneda }}">{{ $m->siglas }}</option>
                            @endif
                          @endforeach
                        </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="esquema_comision_mdl">*Esquema de comision</label>
                          <select name="esquema_comision_mdl" id="esquema_comision_mdl" class="letrasModal form-control" required="true">
                            <option value="Sin comisiones">Sin comisiones</option>
                            @foreach ($esquemas as $element)
                                <option value="{{ $element->id_esquema_comision }}">{{ $element->esquema_comision }}</option>
                            @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                        <label for="cerrador_mdl">*Cerrador</label>
                        <select name="cerrador_mdl" id="cerrador_mdl" class="letrasModal form-control" required="true">
                          <option selected="true" disabled="true">Selecciona...</option>
                          @foreach ($usuarios as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                          @endforeach
                        </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                        <label for="tipo_operacion_mdl">*Tipo de operacion</label>
                        <select name="tipo_operacion_mdl" id="tipo_operacion_mdl" class="letrasModal form-control" required="true">
                          <option selected="true" disabled="true">Selecciona...</option>
                          @foreach ($tipos_operacion as $tp)
                                <option value="{{ $tp->id_tipo_operacion }}">{{ $tp->tipo_operacion }}</option>
                          @endforeach
                        </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                        <label for="crear_plazos">*Crear Plazos?</label>
                        <select name="crear_plazos" id="crear_plazos" class="letrasModal form-control" required="true">
                          <option selected="true" value="si">Si</option>
                          <option value="no">No</option>
                        </select>
                    </div>
                  </div>
                  <div class="col-md-4 oculto">
                    <div class="form-group">
                        <label for="considerar_apartado">*Considerar apartado?</label>
                        <select name="considerar_apartado" id="considerar_apartado" class="letrasModal form-control" required="true">
                          <option  value="1">Si</option>
                          <option selected="true" value="0">No</option>
                        </select>
                    </div>
                  </div>
                  <div class="col-md-4 oculto">
                    <div class="form-group">
                        <label for="considerar_enganche">*Considerar pago inicial?</label>
                        <select name="considerar_enganche" id="considerar_enganche" class="letrasModal form-control" required="true">
                          <option selected="true" value="1">Si</option>
                          <option value="0">No</option>
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
<!-- modal de carga de requisitos-->
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-completartodos">
    {{ Form::open(array('action'=>array('RequisitosProspectoController@completar_todos',$prospecto->id_prospecto),'method'=>'post')) }}
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Completar todos los requisitos</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">                      
                    <div class="col-md-12">
                        <p>Esta seguro de completar todos los requisitos a este prospecto?</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
                <button type="submit" id="btn_modal_requisitos" class="btn btn-info">Confirmar</button>
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
<script src="{{ asset('js/uploadfotos.js') }}"></script>
<script >
    function construir_domicilio() {
        if ($('#num_interior').val() != '' ) {
            var num_interior = ' - ' + $('#num_interior').val();
        }else{
            var num_interior = '';
        }
        var domicilio = $('#calle').val() + ' #' + $('#num_exterior').val() + '' + num_interior + ', Col. ' + $('#colonia').val() + ', ' + $('select[name="ciudad_id"] option:selected').text() + ', ' + $('select[name="estado_id"] option:selected').text() + ', ' + $('select[name="pais_id"] option:selected').text() + '. ' + $('#codigo_postal').val();

        $('#domicilio').val(domicilio);
    }
   jQuery(document).ready(function($)
    {   
        construir_domicilio();
        $( "#guardar" ).click(function() {
          $( "#guardar_form" ).submit();
        });
        $('#tabla_contactos').DataTable();
        $('#tabla_actividades').DataTable();
        $('#tabla_plazas_pago').DataTable();
        $('#tabla_requisitos').DataTable();
        $('#tabla_documentos').DataTable();
        $('#tabla_mensajes').DataTable();
        $('#tabla_cotizaciones').DataTable();
        //*De que ya se cargo la pagina*/

        medio_contacto = $('select[name="medio_contacto"] option:selected').text();
        oficina_broker = $('.oficina_broker');
        if(medio_contacto == 'Broker' || medio_contacto == 10 || medio_contacto == 'Bróker'){
            oficina_broker.removeClass("oculto");
        }else{
            oficina_broker.addClass("oculto");
        }
        $("#nombre_s").keyup(function() {
            $('#nombre').val( $('#nombre_s').val() + " " + $('#apellido_paterno').val() + " " + $('#apellido_materno').val());
        });

        $("#apellido_paterno").keyup(function() {
            $('#nombre').val( $('#nombre_s').val() + " " + $('#apellido_paterno').val() + " " + $('#apellido_materno').val());
        });
        $("#apellido_materno").keyup(function() {
            $('#nombre').val( $('#nombre_s').val() + " " + $('#apellido_paterno').val() + " " + $('#apellido_materno').val());
        });
        $("#medio_contacto").on('change', function(){
            medio_contacto = $('select[name="medio_contacto"] option:selected').text();
            oficina_broker = $('.oficina_broker');
            if(medio_contacto == 'Broker' || medio_contacto == 10 || medio_contacto == 'Bróker'){
                oficina_broker.removeClass("oculto");
            }else{
                oficina_broker.addClass("oculto");
            }
        });
        //// para modla pagando
        $("#cotizacion_id_mdl_pagando").on('change', function(){
            desarrollo = $('#cotizacion_id_mdl_pagando').val();
            propiedad = $('#propiedad').val();
            selectPropiedad = $('#esquema_pago_id_mdl_pagando');
            selectPropiedad.empty().append('<option>Cargando...</option>');


            $.ajax({

            type: "GET",
            url: "/catalogo-esquema_detalle_cotizacion_propiedad/" + desarrollo + "/" + propiedad,
            success: function(data) {
                  var htmlOptions = [];
                  if( data.length ){
                      html = '<option value="" selected="true">Selecciona...</option>';
                      htmlOptions[htmlOptions.length] = html;
                      for( item in data ) {
                        //en caso de ser un select
                        html = '<option value="' + data[item].id_detalle_cotizacion_propiedad + '">' + data[item].esquema_pago + '</option>';
                        htmlOptions[htmlOptions.length] = html;
                      }
                      
                      // se agregan las opciones del catalogo en caso de ser un select 
                      selectPropiedad.empty().append( htmlOptions.join('') );
                      selectPropiedad.selectpicker('refresh');
                  }else{
                    html = '<option value="" disabled="true" selected="true">No hay datos</option>';
                    htmlOptions[htmlOptions.length] = html;
                    selectPropiedad.empty().append( htmlOptions.join('') );
                    selectPropiedad.selectpicker('refresh');
                  }
            },
              error: function(error) {
              alert("No se pudo cargar el catalogo");
            }
            })
        });
        ////PARA MODAL CONTRATO
        $("#cotizacion_id_mdl").on('change', function(){
            desarrollo = $('#cotizacion_id_mdl').val();
            propiedad = $('#propiedad_contrato_mdl').val();
            selectPropiedad = $('#esquema_pago_id_mdl');
            selectPropiedad.empty().append('<option>Cargando...</option>');


            $.ajax({

            type: "GET",
            url: "/catalogo-esquema_detalle_cotizacion_propiedad/" + desarrollo + "/" + propiedad,
            success: function(data) {
                  var htmlOptions = [];
                  if( data.length ){
                      html = '<option value="" selected="true">Selecciona...</option>';
                      htmlOptions[htmlOptions.length] = html;
                      for( item in data ) {
                        //en caso de ser un select
                        html = '<option value="' + data[item].id_detalle_cotizacion_propiedad + '">' + data[item].esquema_pago + '</option>';
                        htmlOptions[htmlOptions.length] = html;
                      }
                      
                      // se agregan las opciones del catalogo en caso de ser un select 
                      selectPropiedad.empty().append( htmlOptions.join('') );
                      selectPropiedad.selectpicker('refresh');
                  }else{
                    html = '<option value="" disabled="true" selected="true">No hay datos</option>';
                    htmlOptions[htmlOptions.length] = html;
                    selectPropiedad.empty().append( htmlOptions.join('') );
                    selectPropiedad.selectpicker('refresh');
                  }
            },
              error: function(error) {
              alert("No se pudo cargar el catalogo");
            }
            })
        });
        $("#propiedad_contrato_mdl").on('change', function(){
            desarrollo = $('#cotizacion_id_mdl').val();
            propiedad = $('#propiedad_contrato_mdl').val();
            selectPropiedad = $('#esquema_pago_id_mdl');
            selectPropiedad.empty().append('<option>Cargando...</option>');


            $.ajax({

            type: "GET",
            url: "/catalogo-esquema_detalle_cotizacion_propiedad/" + desarrollo + "/" + propiedad,
            success: function(data) {
                  var htmlOptions = [];
                  if( data.length ){
                      html = '<option value="" selected="true">Selecciona...</option>';
                      htmlOptions[htmlOptions.length] = html;
                      for( item in data ) {
                        //en caso de ser un select
                        html = '<option value="' + data[item].id_detalle_cotizacion_propiedad + '">' + data[item].esquema_pago + '</option>';
                        htmlOptions[htmlOptions.length] = html;
                      }
                      
                      // se agregan las opciones del catalogo en caso de ser un select 
                      selectPropiedad.empty().append( htmlOptions.join('') );
                      selectPropiedad.selectpicker('refresh');
                  }else{
                    html = '<option value="" disabled="true" selected="true">No hay datos</option>';
                    htmlOptions[htmlOptions.length] = html;
                    selectPropiedad.empty().append( htmlOptions.join('') );
                    selectPropiedad.selectpicker('refresh');
                  }
            },
              error: function(error) {
              alert("No se pudo cargar el catalogo");
            }
            })
            construir_domicilio();
        });
        $("#pais_id").on('change', function(){
            desarrollo = $('#pais_id').val();
            selectPropiedad = $('#estado_id');
            selectPropiedad.empty().append('<option>Cargando...</option>');


            $.ajax({

            type: "GET",
            url: "/catalogo-estados/" + desarrollo,
            success: function(data) {
                  var htmlOptions = [];
                  if( data.length ){
                      html = '<option value="" selected="true">Selecciona...</option>';
                      htmlOptions[htmlOptions.length] = html;
                      for( item in data ) {
                        //en caso de ser un select
                        html = '<option value="' + data[item].id_estado + '">' + data[item].estado + '</option>';
                        htmlOptions[htmlOptions.length] = html;
                      }
                      
                      // se agregan las opciones del catalogo en caso de ser un select 
                      selectPropiedad.empty().append( htmlOptions.join('') );
                      selectPropiedad.selectpicker('refresh');
                  }else{
                    html = '<option value="" disabled="true" selected="true">No hay datos</option>';
                    htmlOptions[htmlOptions.length] = html;
                    selectPropiedad.empty().append( htmlOptions.join('') );
                    selectPropiedad.selectpicker('refresh');
                  }
            },
              error: function(error) {
              alert("No se pudo cargar el catalogo");
            }
            })
            construir_domicilio();
        });
        $("#estado_id").on('change', function(){
            desarrollo = $('#estado_id').val();
            selectPropiedad = $('#ciudad_id');
            selectPropiedad.empty().append('<option>Cargando...</option>');

            $.ajax({

            type: "GET",
            url: "/catalogo-ciudades/" + desarrollo,
            success: function(data) {
                  var htmlOptions = [];
                  if( data.length ){
                      html = '<option value="" selected="true">Selecciona...</option>';
                      htmlOptions[htmlOptions.length] = html;
                      for( item in data ) {
                        //en caso de ser un select
                        html = '<option value="' + data[item].id_ciudad + '">' + data[item].ciudad + '</option>';
                        htmlOptions[htmlOptions.length] = html;
                      }
                      
                      // se agregan las opciones del catalogo en caso de ser un select 
                      selectPropiedad.empty().append( htmlOptions.join('') );
                      selectPropiedad.selectpicker('refresh');
                  }else{
                    html = '<option value="" disabled="true" selected="true">No hay datos</option>';
                    htmlOptions[htmlOptions.length] = html;
                    selectPropiedad.empty().append( htmlOptions.join('') );
                    selectPropiedad.selectpicker('refresh');
                  }
            },
              error: function(error) {
              alert("No se pudo cargar el catalogo");
            }
            })
            construir_domicilio();
        });
        $("#ciudad_id").on('change', function(){
            
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
        
        $("#proyecto").on('change', function(){
            desarrollo = $('#proyecto').val();
            selectPropiedad = $('#propiedad');
            selectPropiedad.empty().append('<option>Cargando las propiedades</option>');


            $.ajax({

            type: "GET",
            url: "/catalogo-propiedades-desarrollo-estatus/" + desarrollo,
            success: function(data) {
                  var htmlOptions = [];
                  if( data.length ){
                      html = '<option value="" disabled="true" selected="true">Selecciona una propiedad</option>';
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
                    html = '<option value="" disabled="true" selected="true">No hay propiedades</option>';
                    htmlOptions[htmlOptions.length] = html;
                    selectPropiedad.empty().append( htmlOptions.join('') );
                  }
                  selectPropiedad.selectpicker('refresh');
            },
              error: function(error) {
              alert("No se pudo cargar el catalogo de propiedades");
            }
            })
        });
        $("#proyecto_contrato_mdl").on('change', function(){
            desarrollo = $('#proyecto_contrato_mdl').val();
            selectPropiedad = $('#propiedad_contrato_mdl');
            selectPropiedad.empty().append('<option>Cargando las propiedades</option>');


            $.ajax({

            type: "GET",
            url: "/catalogo-propiedades-desarrollo-estatus/" + desarrollo,
            success: function(data) {
                  var htmlOptions = [];
                  if( data.length ){
                      html = '<option value="" disabled="true" selected="true">Selecciona una propiedad</option>';
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
                    html = '<option value="" disabled="true" selected="true">No hay propiedades</option>';
                    htmlOptions[htmlOptions.length] = html;
                    selectPropiedad.empty().append( htmlOptions.join('') );
                  }
                  selectPropiedad.selectpicker('refresh');
            },
              error: function(error) {
              alert("No se pudo cargar el catalogo de propiedades");
            }
            })
        });
        $("#proyecto_mdl_apartado").on('change', function(){
            desarrollo = $('#proyecto_mdl_apartado').val();
            selectPropiedad = $('#propiedad_mdl');
            selectPropiedad.empty().append('<option>Cargando las propiedades</option>');


            $.ajax({

            type: "GET",
            url: "/catalogo-propiedades-desarrollo/" + desarrollo,
            success: function(data) {
                  var htmlOptions = [];
                  if( data.length ){
                      html = '<option value="" disabled="true" selected="true">Selecciona una propiedad</option>';
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
                    html = '<option value="" disabled="true" selected="true">No hay propiedades</option>';
                    htmlOptions[htmlOptions.length] = html;
                    selectPropiedad.empty().append( htmlOptions.join('') );
                  }
                  selectPropiedad.selectpicker('refresh');
            },
              error: function(error) {
              alert("No se pudo cargar el catalogo de propiedades");
            }
            })
        });
        $("#proyecto_mdl_visita").on('change', function(){
            desarrollo = $('#proyecto_mdl_visita').val();
            selectPropiedad = $('#propiedad_mdl_visita');
            selectPropiedad.empty().append('<option>Cargando las propiedades</option>');


            $.ajax({

            type: "GET",
            url: "/catalogo-propiedades-desarrollo/" + desarrollo,
            success: function(data) {
                  var htmlOptions = [];
                  if( data.length ){
                      html = '<option value="" disabled="true" selected="true">Selecciona una propiedad</option>';
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
                    html = '<option value="" disabled="true" selected="true">No hay propiedades</option>';
                    htmlOptions[htmlOptions.length] = html;
                    selectPropiedad.empty().append( htmlOptions.join('') );
                  }
                  selectPropiedad.selectpicker('refresh');
            },
              error: function(error) {
              alert("No se pudo cargar el catalogo de propiedades");
            }
            })
        });
        /*$('#propiedad_mdl_cotizar').multiselect('destroy');
          $('#propiedad_mdl_cotizar').multiselect({
            enableFiltering: true,
            filterBehavior: 'text',
            enableCaseInsensitiveFiltering: true,
            filterPlaceholder: 'Buscar...',
          });*/
        $("#proyecto_mdl_cotizar").on('change', function(){
            desarrollo = $('#proyecto_mdl_cotizar').val();
            uso_propiedad = $('#uso_propiedad_id_mdl_cotizar').val();
            selectPropiedad = $('#propiedad_mdl_cotizar');
            selectPropiedad.empty().append('<option>Cargando...</option>');
            selectGrupo = $('#grupo_esquema_mdl_cotizar');
            selectGrupo.empty().append('<option>Cargando...</option>');

            $.ajax({

            type: "GET",
            url: "/catalogo-propiedades-desarrollo/" + desarrollo,
            success: function(data) {
                  var htmlOptions = [];
                  if( data.length ){
                      html = '<option value="" disabled="true" selected="true">Selecciona...</option>';
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
                    html = '<option value="" disabled="true" selected="true">No hay propiedades</option>';
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
        $("#uso_propiedad_id_mdl_cotizar").on('change', function(){
            desarrollo = $('#proyecto_mdl_cotizar').val();
            uso_propiedad = $('#uso_propiedad_id_mdl_cotizar').val();
            selectGrupo = $('#grupo_esquema_mdl_cotizar');
            selectGrupo.empty().append('<option>Cargando...</option>');

            /*POR LOS GRUPOS DE ESQUEMAS DISPONIBLES*/
            $.ajax({

            type: "GET",
            url: "/catalogo-grupo-esquema/" + desarrollo + "/" + uso_propiedad,
            success: function(data) {
                  var htmlOptions = [];
                  if( data.length ){
                      html = '<option value="">Selecciona...</option>';
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

        $("#monto_venta_mdl").on('change', function(){
            monto_venta = $("#monto_venta_mdl").val();
            $('#monto_enganche_mdl').attr('max',monto_venta);


        });
    });
</script>
<script>
function ValidateSize(file) {
        var FileSize = file.files[0].size / 1024 / 1024; // in MB
        if (FileSize > 2) {
            alert('No se puede insertar un archivo excedente de 2 megas');
           // $(file).val(''); //for clearing with Jquery
        }
    }
</script>
@endpush 
@endsection