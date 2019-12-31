@extends('layouts.admin')
@section('title')
Cliente
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h3><a class="text-secondary" data-toggle="collapse" href="#infoprincipal" role="button" aria-expanded="false" aria-controls="infoprincipal"><em>Informacion principal</em></a></h3>
                    <hr class="hr-titulo" width="100%" size="10">
                </div>
            </div>
            <div class="row collapse show" id="infoprincipal">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="nombre">*Nombre</label>
                        <input type="text" name="nombre" id="nombre" value="{{ $prospecto->nombre }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-3"> 
                    <div class="form-group">
                        <label for="tipo">*Tipo</label>
                        <select class="form-control" id="tipo" name="tipo">
                            <option value="{{ $prospecto->tipo }}">{{ $prospecto->tipo }}</option>
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
                        <label for="estatus">*Estatus</label>
                        <select name="estatus" id="estatus" class="letrasModal form-control" required="true" readonly>
                            <option value="{{ $prospecto->estatus }}">{{ $prospecto->estatus_prospecto }}</option>
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
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="domicilio">Domicilio</label>
                        <input type="text" name="domicilio" id="domicilio" value="{{ $prospecto->domicilio }}"  class="letrasModal form-control"  />
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
                    <h3><a class="text-secondary" data-toggle="collapse" href="#visita" role="button" aria-expanded="false" aria-controls="visita"><em>Visita</em></a></h3>
                    <hr class="hr-titulo" width="100%" size="10">
                </div>
            </div>
            <div class="row collapse show" id="visita">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="proyecto">Proyecto</label>
                        <select name="proyecto" id="proyecto" value=""  class="letrasModal form-control">
                            <option value="{{ $prospecto->proyecto_id }}">{{ $prospecto->nombre_proyecto }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="propiedad">Propiedad</label>
                        <select name="propiedad" id="propiedad" class="letrasModal form-control">
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
            <div class="row">
                <div class="col-md-6">
                    <h3><a class="text-secondary" data-toggle="collapse" href="#cotizacion" role="button" aria-expanded="false" aria-controls="cotizacion"><em>Cotizacion</em></a></h3>
                    <hr class="hr-titulo" width="100%" size="10">
                    <div class="row collapse show" id="cotizacion">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha_cotizacion">Fecha de cotizacion</label>
                                @if ($prospecto->fecha_cotizacion != null)
                                    <input type="date" name="fecha_cotizacion" id="fecha_cotizacion" value="{{ date('Y-m-d',strtotime($prospecto->fecha_cotizacion)) }}"  class="letrasModal form-control" required="true" readonly="true" />
                                @else
                                    <input type="date" name="fecha_cotizacion" id="fecha_cotizacion" value=""  class="letrasModal form-control" required="true" readonly="true"/>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-6">
                    <h3><a class="text-secondary" data-toggle="collapse" href="#apartado" role="button" aria-expanded="false" aria-controls="apartado"><em>Apartado</em></a></h3>
                    <hr class="hr-titulo" width="100%" size="10">
                    <div class="row collapse show" id="apartado">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha_apartado">Fecha de apartado</label>
                                @if ($prospecto->fecha_apartado != null)
                                    <input type="date" name="fecha_apartado" id="fecha_apartado" value="{{ date('Y-m-d',strtotime($prospecto->fecha_apartado)) }}"  class="letrasModal form-control" required="true" readonly="true" />
                                @else
                                    <input type="date" name="fecha_apartado" id="fecha_apartado" value=""  class="letrasModal form-control" required="true" readonly="true"/>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="monto_apartado">Monto de apartado</label>
                                <input type="text" name="monto_apartado" id="monto_apartado" value="{{ $prospecto->monto_apartado }}"  class="mask form-control" required="true" readonly="true" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                        <label for="fecha_enganche">Fecha de enganche</label>
                        @if ($prospecto->fecha_enganche != null)
                            <input type="date" name="fecha_enganche" id="fecha_enganche" value="{{ date('Y-m-d',strtotime($prospecto->fecha_enganche)) }}"  class="letrasModal form-control" required="true" readonly="true" />
                        @else
                            <input type="date" name="fecha_enganche" id="fecha_enganche" value=""  class="letrasModal form-control" required="true" readonly="true"/>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="monto_enganche">Monto de enganche</label>
                        <input type="text" step="any" name="monto_enganche" id="monto_enganche" value="{{ $prospecto->monto_enganche }}"  class="mask form-control" required="true" readonly="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="tipo_operacion">Tipo de operacion</label>
                        <select name="tipo_operacion" id="tipo_operacion" class="letrasModal form-control" readonly>
                            @foreach ($tipos_operacion as $to)
                                @if ($prospecto->tipo_operacion_id == $to->id_tipo_operacion)
                                    <option selected="true" value="{{ $to->id_tipo_operacion }}">{{ $to->tipo_operacion }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                
            </div>
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
                            <input type="date" name="fecha_contrato" id="fecha_contrato" value="{{ date('Y-m-d',strtotime($prospecto->fecha_contrato)) }}"  class="letrasModal form-control" required="true" readonly="true" />
                        @else
                            <input type="date" name="fecha_contrato" id="fecha_contrato" value=""  class="letrasModal form-control" required="true" readonly="true"/>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="num_contrato">Numero de contrato</label>
                        <input type="number" step="any" name="num_contrato" id="num_contrato" value="{{ $prospecto->num_contrato }}"  class="letrasModal form-control" required="true" readonly="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="vigencia_contrato">Vigencia contrato</label>
                        @if ($prospecto->vigencia_contrato != null)
                            <input type="date" name="vigencia_contrato" id="vigencia_contrato" value="{{ date('Y-m-d',strtotime($prospecto->vigencia_contrato)) }}"  class="letrasModal form-control" required="true" readonly="true" />
                        @else
                            <input type="date" name="vigencia_contrato" id="vigencia_contrato" value=""  class="letrasModal form-control" required="true" readonly="true"/>
                        @endif
                    </div>
                </div>
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
                        <label for="n_plazos"># de plazos</label>
                        <input type="number" name="n_plazos" id="n_plazos" value="{{ $prospecto->num_plazos }}"  class="letrasModal form-control" required="true" readonly="true" />
                    </div>
                </div>
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
                        <input type="text" step="any" name="mensualidad" id="mensualidad" value="{{ $prospecto->mensualidad }}"  class="mask form-control"/>
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
                        <input type="text" step="any" name="interes" id="interes" value="{{ $prospecto->interes }}"  class="mask form-control"  />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="capital">Capital</label>
                        <input type="text" step="any" name="capital" id="capital" value="{{ $prospecto->capital }}"  class="mask form-control" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="saldo">Saldo</label>
                        <input type="text" step="any" name="saldo" id="saldo" value="{{ $prospecto->saldo }}"  class="mask form-control" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="pagado">Pagado</label>
                        <input type="text" step="any" name="pagado" id="pagado" value="{{ $prospecto->pagado }}"  class="mask form-control" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="moneda">*Moneda</label>
                        <input type="text" name="moneda" id="moneda" class="form-control" value="{{ $prospecto->siglas }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <div class="row">
                                <div class="col-md-10">
                                    Documentos
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
                                     Archivo
                                    </th>
                                    <th class="center">
                                      Fecha
                                    </th>
                                    <th class="center">
                                      Notas
                                    </th>
                                  </thead>
                                  <tbody>
                                    @php
                                        $procedencia = 'Prospecto';
                                    @endphp
                                    @foreach ($documentos as $doc)  
                                    <tr>
                                      <td class="center">
                                        {{ $doc->titulo}}
                                      </td>
                                      <td class="center">
                                        <a class="" href="{{ url('storage',$doc->archivo) }}" target="_bank"  style="width: 30%;">
                                        {{ $doc->archivo}}</a> 
                                      </td>
                                      <td class="center">
                                        {{ date('Y-m-d',strtotime($doc->fecha)) }}
                                      </td>
                                      <td class="center">
                                        {{ $doc->notas}}
                                      </td>
                                    </tr>
                                    @endforeach
                                   </tbody>
                                </table>
                            </div>                           
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <div class="row">
                                <div class="col-md-11">
                                    Plazos de pago
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive ">
                                <table class="table table-hover table-withoutborder text-sm">
                                  <thead class="thead-grubsa ">
                                    <th class="center">
                                      Num
                                    </th>
                                    <th class="center">
                                      Fecha
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
                                  </thead>
                                  <tbody>
                                    @foreach ($plazos_pago as $pp)               
                                    <tr>
                                      <td class="center">
                                        <a href="{{URL::action('ExternosController@showplazo', [$pp->id_plazo_pago])}}">
                                            {{ $pp->num_plazo }}
                                        </a>
                                      </td>
                                      <td class="center">
                                        <a href="{{URL::action('ExternosController@showplazo', [$pp->id_plazo_pago])}}">
                                            {{ date('Y-m-d',strtotime($pp->fecha)) }}
                                        </a>
                                      </td>
                                      <td class="center">
                                        <a href="{{URL::action('ExternosController@showplazo', [$pp->id_plazo_pago])}}">
                                            {{ $pp->estatus }}
                                        </a>
                                      </td>
                                      <td class="center">
                                        <a href="{{URL::action('ExternosController@showplazo', [$pp->id_plazo_pago])}}">
                                            $ {{ number_format($pp->total , 2 , "." , ",") }}
                                        </a>
                                      </td>
                                      <td class="center">
                                        <a href="{{URL::action('ExternosController@showplazo', [$pp->id_plazo_pago])}}">
                                            $ {{ number_format($pp->saldo , 2 , "." , ",") }}
                                        </a>
                                      </td>
                                      <td class="center">
                                        <a href="{{URL::action('ExternosController@showplazo', [$pp->id_plazo_pago])}}">
                                            $ {{ number_format($pp->pagado , 2 , "." , ",") }}
                                        </a>
                                      </td>
                                    </tr>
                                    @endforeach
                                   </tbody>
                                </table>
                            </div>
                            {{$plazos_pago->render()}}                        
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <canvas id="barPlazos" width="301" height="400" class="chartjs-render-monitor" style="display: block; width: 301px; height: 400px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script >
   jQuery(document).ready(function($)
    {
      $("#proyecto").on('change', function(){
        desarrollo = $('#proyecto').val();
        selectPropiedad = $('#propiedad');
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
        },
          error: function(error) {
          alert("No se pudo cargar el catalogo de propiedades");
        }
        })
      });
      $("#proyecto_mdl_cotizar").on('change', function(){
        desarrollo = $('#proyecto_mdl_cotizar').val();
        selectPropiedad = $('#propiedad_mdl_cotizar');
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
        },
          error: function(error) {
          alert("No se pudo cargar el catalogo de propiedades");
        }
        })
      });

      $("#propiedad_mdl_cotizar").on('change', function(){

        propiedad = $('#propiedad_mdl_cotizar').val();
        selectInmueble = $('#moneda_cotizar');
        $.ajax({

        type: "GET",
        url: "/catalogo-propiedades/" + propiedad,
        success: function(data) {
             var htmlOptions = [];
             if( data.length ){
                  html = '<option value="" disabled="true">Selecciona una propiedad</option>';
                  htmlOptions[htmlOptions.length] = html;
                  for( item in data ) {
                    //en caso de ser un select
                    html_moneda = '<option selected value="' + data[item].moneda + '">' + data[item].siglas + '</option>';
                    html = data[item].precio;
                    html_enganche = data[item].enganche;
                    htmlOptions[htmlOptions.length] = html_moneda;
                  }

                  //en caso de ser un input
                  $("#precio_mdl_cotizar").val(html);
                  $("#enganche_cotizar").val(html_enganche);
                  
                  // se agregan las opciones del catalogo en caso de ser un select 
                  selectInmueble.empty().append( htmlOptions.join('') );
               }
        },
          error: function(error) {
          alert("No se pudo cargar el catalogo de propiedad");
        }
        })

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
<script type="text/javascript">
  // Set new default font family and font color to mimic Bootstrap's default styling
  Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
  Chart.defaults.global.defaultFontColor = '#858796';

  function number_format(number, decimals, dec_point, thousands_sep) {
    // *     example: number_format(1234.56, 2, ',', ' ');
    // *     return: '1 234,56'
    number = (number + '').replace(',', '').replace(' ', '');
    var n = !isFinite(+number) ? 0 : +number,
      prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
      sep = (typeof  thousands_sep === 'undefined') ? ',' : thousands_sep,
      dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
      s = '',
      toFixedFix = function(n, prec) {
        var k = Math.pow(10, prec);
        return '' + Math.round(n * k) / k;
      };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
      s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
      s[1] = s[1] || '';
      s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
  }

  // PIE CHART Tipos de oportunidad
    var ctx = document.getElementById("barPlazos");
    var barPlazos = new Chart(ctx, {
      type: 'doughnut',
      data: { 
        labels:[ 
          @foreach ($resultados as $r)
            '{{ $r->label }}',
          @endforeach
          ],
        datasets: [{
            data: [ 
              @foreach ($resultados as $r)
              {{ $r->cantidad }},
              @endforeach
            ],
            backgroundColor: [
              @foreach ($colores_A as $r)
              '{{ $r }}',
              @endforeach
            ],
        }],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        tooltips: {
          backgroundColor: "rgb(255,255,255)",
          bodyFontColor: "#858796",
          borderColor: '#dddfeb',
          borderWidth: 1,
          xPadding: 15,
          yPadding: 15,
          displayColors: true,
          caretPadding: 10,
        },
        legend: {
          display: true
        },
        cutoutPercentage: 80,
      },
    });
</script>
@endpush 
@endsection