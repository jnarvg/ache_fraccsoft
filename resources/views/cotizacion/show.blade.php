@extends('layouts.admin')
@section('title')
Cotizacion N. {{ $resultado->id_cotizacion }}
@endsection
@section('filter')
  <a href="{{ route('cotizacion-todas') }}"><button class="mb-0 d-sm-inline-block btn  btn-primary" title="Ver todas">Todas</button></a>
  <a href="#" class="mb-0 d-sm-inline-block btn btn-primary" id="guardar" title="GUARDAR"><i class="fas fa-check"></i></a>
  @if ($procedencia == 'Menu')
  <a href="{{ route('cotizacion-todas') }}" class="mb-0 d-sm-inline-block btn btn-primary " title="CERRAR"><i class="fas fa-times"></i></a>
  @else
  <a href="{{ route('prospectos-show', [$resultado->prospecto_id, $procedencia]) }}" class="mb-0 d-sm-inline-block btn btn-primary" title="CERRAR"><i class="fas fa-times"></i></a>
  @endif
  @if ($resultado->proyecto_id == '1')
  <a class="mb-0 d-sm-inline-block btn btn-primary" target="_blank" href="{{ route('cotizacion-generarpdf', $resultado->id_cotizacion) }}" title="PDF">PDF</a>
  @elseif($resultado->proyecto_id == '2')
  <a class="mb-0 d-sm-inline-block btn btn-primary" target="_blank" href="{{ route('cotizacion-generarpdf_ixuh', $resultado->id_cotizacion) }}" title="PDF">PDF IXUH</a>
  @endif
@endsection
@section('content')
<div class="content mt-6">
  <div class="card">
    <div class="card-body">
      {{ Form::open(array('action'=>array('CotizacionController@update',$resultado->id_cotizacion),'method'=>'post','id'=>'guardar_form')) }}
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label for="uso_propiedad_id">Uso de propiedad</label>
              <select name="uso_propiedad_id" id="uso_propiedad_id" class="letrasModal form-control" required="true">
                <option value=""></option>
                @foreach ($usos_propiedad as $m)
                  @if ($m->id_uso_propiedad == $resultado->uso_propiedad_id)
                  <option selected value="{{ $m->id_uso_propiedad }}">{{ $m->uso_propiedad }}</option>
                  @else
                  <option value="{{ $m->id_uso_propiedad }}">{{ $m->uso_propiedad }}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="agente">Agente</label>
              <input type="text" name="agente" id="agente" value="{{ $resultado->asesor_nombre }}"  class="letrasModal form-control-plaintext" readonly required="true" />
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="cliente">*Nombre cliente</label>
              <input type="text" name="cliente" id="cliente" value="{{ $resultado->cliente }}"  class="letrasModal form-control" required="true" />
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="telefono">Telefono</label>
              <input type="text" name="telefono" id="telefono" value="{{ $resultado->telefono }}"  class="letrasModal form-control"  />
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="correo">Correo</label>
              <input type="email" name="correo" id="correo" value="{{ $resultado->correo }}"  class="letrasModal form-control"  />
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="moneda">Moneda</label>
              <select name="moneda" id="moneda" class="letrasModal form-control" required="true">
                @foreach ($monedas as $m)
                  @if ($m->id_moneda == $resultado->moneda_id)
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
              <label for="proyecto">*Proyecto</label>
              <input type="text" name="proyecto" id="proyecto" class="form-control-plaintext" value="{{ $resultado->proyecto }}">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="propiedad">*Propiedades</label>
              <input type="text" name="propiedad" id="propiedad" class="form-control-plaintext" value="{{ $resultado->propiedad }}">
            </div>
          </div>
        </div>
        <div class="row mt-3">  
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-8"><h3><em>ESQUEMAS Y PROPIEDADES</em></h3>
              </div>
              <div class="col-md-4 h3 mb-0" align="right">
                <a href="#" data-toggle="modal" data-target="#modal_new" ><button class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm">Agregar propiedad</button></a>
                <a href="#" data-toggle="modal" data-target="#modal_delete" ><button class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm">Eliminar propiedad</button></a>
              </div>
              <hr class="hr-titulo" width="100%" size="10">
            </div>
          </div>
        </div>
      {{ Form::close()}}
        <div class="row" id="botones">
          @php
            $i = 1;
          @endphp
          @foreach ($listbtn as $e)
            @if ($i == 1)
              <div class="col-md-2">
                <a id="btn_propiedad_{{ $e->propiedad_id }}" class="btn btn-danger btn-block btn_propiedad btn-sm" data-toggle="collapse" href="#rowpropiedad_{{ $e->propiedad_id }}" role="button" aria-expanded="false" aria-controls="rowpropiedad_{{ $e->propiedad_id }}">{{ $e->nombre_propiedad }}</a>
              </div>
            @else
              <div class="col-md-2">
                <a id="btn_propiedad_{{ $e->propiedad_id }}" class="btn btn-primary btn-block btn_propiedad btn-sm" data-toggle="collapse" href="#rowpropiedad_{{ $e->propiedad_id }}" role="button" aria-expanded="false" aria-controls="rowpropiedad_{{ $e->propiedad_id }}">{{ $e->nombre_propiedad }}</a>
              </div>
            @endif
            @php
              $i = $i + 1;
            @endphp
          @endforeach
          <hr>
        </div>
        @php
          $i = 1;
        @endphp
        {{-- Dibujar esuemas --}}
        @foreach ($listbtn as $h)
          @if ($i == 1)
          <div class="row show" id="rowpropiedad_{{ $h->propiedad_id }}">
          @else
          <div class="row collapse" id="rowpropiedad_{{ $h->propiedad_id }}">
          @endif
            <div class="col-md-12 mt-3">
              <h4>Propiedad {{ $h->nombre_propiedad }}</h4>
              <hr>
            </div>
            @foreach ($detalle_propiedad as $e)
              <div class="col-md-12">
                @if ($h->id_propiedad == $e->propiedad_id)
                  {{ Form::open(array('action'=>array('DetalleCotizacionPropiedadController@update',$e->id_detalle_cotizacion_propiedad),'method'=>'post')) }}
                    <div class="row">
                      <div class="col-md-12">
                        <div class="row justify-content-between">
                          <div class="col-md-8">
                          </div>
                          <div class="col-md-4 mb-0 " align="right">
                            {{-- Calcular --}}
                            <button type="submit" id="btn_calcular_{{ $e->id_detalle_cotizacion_propiedad }}" class="mb-1 d-sm-inline-block btn btn-success btn-sm text-xl" title="Calcular">Calcular</button>
                            {{-- Nuevo rubro --}}
                            <a class="mb-1 d-sm-inline-block btn btn-info btn-sm text-xl" href="#" data-toggle="modal" data-target="#modal_new_rubro_{{ $e->id_detalle_cotizacion_propiedad }}" ><i class="fas fa-plus"></i></a>
                            {{-- Eliminar rubro --}}
                            <a class="mb-1 d-sm-inline-block btn btn-warning btn-sm text-xl" href="#" data-toggle="modal" data-target="#modal_delete_rubro_{{ $e->id_detalle_cotizacion_propiedad }}" ><i class="fas fa-trash"></i></a>
                            {{-- Ocultar mostrar --}}
                            <a class="mb-1 d-sm-inline-block btn btn-dark btn-sm text-xl" title="Esconder / Mostrar" data-toggle="collapse" href="#detallerubro_{{ $e->id_detalle_cotizacion_propiedad }}" role="button" aria-expanded="false" aria-controls="detallerubro_{{ $e->id_detalle_cotizacion_propiedad }}"><i class="fas fa-minus"></i></a>
                            <br>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label for="esquema_pago" class="bold">Esquema</label>
                          <input type="text" name="esquema_pago" id="esquema_pago_{{ $e->id_detalle_cotizacion_propiedad }}" value="{{ $e->esquema_pago }}" class="form-control form-control-sm">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label for="precio">Precio</label>
                          <input type="text" name="precio" id="precio_{{ $e->id_detalle_cotizacion_propiedad }}" value="{{ $e->precio }}" class="mask form-control form-control-sm precio">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label for="porcentaje_descuento">% Descuento</label>
                          <input type="number" name="porcentaje_descuento" id="porcentaje_descuento_{{ $e->id_detalle_cotizacion_propiedad }}" value="{{ $e->porcentaje_descuento }}" class="porcentaje_descuento form-control form-control-sm" step="any">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label for="monto_descuento">Descuento</label>
                          <input type="text" name="monto_descuento" id="monto_descuento_{{ $e->id_detalle_cotizacion_propiedad }}" value="{{ $e->monto_descuento }}" class="mask form-control-plaintext form-control-sm" readonly>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label for="precio_final">Precio final</label>
                          <input type="text" name="precio_final" id="precio_final_{{ $e->id_detalle_cotizacion_propiedad }}" value="{{ $e->precio_final }}" class="mask form-control-plaintext form-control-sm" readonly>
                        </div>
                      </div>
                      <div class="col-md-2">
                          <div class="form-group">
                            <label for="incluir_{{ $e->id_detalle_cotizacion_propiedad }}">*Incluir</label>
                            <div class="custom-control custom-switch">
                              @if ($e->incluir == 0)
                                <input type="checkbox" class="custom-control-input" id="incluir_{{ $e->id_detalle_cotizacion_propiedad }}" name="incluir">
                              @elseif ($e->incluir == 1)
                                <input type="checkbox" class="custom-control-input" id="incluir_{{ $e->id_detalle_cotizacion_propiedad }}" name="incluir" checked>
                              @else
                                <input type="checkbox" class="custom-control-input" id="incluir_{{ $e->id_detalle_cotizacion_propiedad }}" name="incluir">
                              @endif
                              <label class="custom-control-label" for="incluir_{{ $e->id_detalle_cotizacion_propiedad }}"> en pdf</label>
                            </div>
                          </div>
                      </div>
                      <div class="col-md-12 show" id="detallerubro_{{ $e->id_detalle_cotizacion_propiedad }}">
                        <div class="table-responsive" >
                          <table class="rubros table table-sm">
                            <thead class="bg-gray-300 text-sm">
                              <tr>
                                <th>Alias</th>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Tipo calculo</th>
                                <th>Monto</th>
                                <th>Porcentaje</th>
                                <th>Mensualidades</th>
                                <th class="oculto">Descuento</th>
                                <th>Abona</th>
                              </tr>
                            </thead>
                            <tbody>
                              @php
                                $suma = 0;
                                $suma_porcentaje = 0;
                              @endphp
                              @foreach ($detalles_rubro as $a)
                                @if ($a->detalle_cotizacion_propiedad_id == $e->id_detalle_cotizacion_propiedad )
                                  @php
                                    $suma = $suma + $a->monto;
                                    $suma_porcentaje = $suma_porcentaje + $a->porcentaje;
                                  @endphp
                                  <tr>
                                    <td>
                                      <input type="hidden" id="id_rubro_{{ $a->id_detalle_cotizacion_propiedad_rubro }}" name="id_rubro_[]" class="form-control form-control-sm" value="{{ $a->id_detalle_cotizacion_propiedad_rubro }}" required>
                                      <input type="hidden" id="id_detalle_padre_{{ $a->id_detalle_cotizacion_propiedad_rubro }}" name="id_detalle_padre_[]" class="form-control form-control-sm" value="{{ $a->detalle_cotizacion_propiedad_id }}" required>
                                      <input type="text" id="alias_{{ $a->id_detalle_cotizacion_propiedad_rubro }}" name="alias_[]" class="form-control form-control-sm" value="{{ $a->alias }}" required></td>
                                    <td><input type="date" id="fecha_{{ $a->id_detalle_cotizacion_propiedad_rubro }}" name="fecha_[]" class="form-control form-control-sm" value="{{ $a->fecha }}"></td>
                                    <td>
                                      <select name="tipo_[]" id="tipo_{{ $a->id_detalle_cotizacion_propiedad_rubro }}"  class="letrasModal form-control form-control-sm tipo" required="true">
                                        <option value=""></option>
                                        @foreach ($tipo as $t)
                                          @if ($a->tipo == $t)
                                          <option selected value="{{ $t }}">{{ $t }} </option>
                                          @else
                                          <option value="{{ $t }}">{{ $t }} </option>
                                          @endif
                                        @endforeach
                                      </select>
                                    </td>
                                    <td>
                                      <select name="tipo_calculo_[]" id="tipo_calculo_{{ $a->id_detalle_cotizacion_propiedad_rubro }}"  class="letrasModal form-control form-control-sm" required="true">
                                        <option value=""></option>
                                        @foreach ($tipo_calculo as $t)
                                          @if ($a->tipo_calculo == $t)
                                          <option selected value="{{ $t }}">{{ $t }} </option>
                                          @else
                                          <option value="{{ $t }}">{{ $t }} </option>
                                          @endif
                                        @endforeach
                                      </select>
                                    </td>
                                    <td><input type="text" id="monto_{{ $a->id_detalle_cotizacion_propiedad_rubro }}" name="monto_[]" class="mask monto_tabla form-control form-control-sm" value="{{ $a->monto }}"></td>
                                    <td><input type="number" id="porcentaje_{{ $a->id_detalle_cotizacion_propiedad_rubro }}" name="porcentaje_[]" step="any" class="porcentaje_tabla form-control form-control-sm" value="{{ $a->porcentaje }}"></td>
                                    <td><input type="number" id="mensualidades_{{ $a->id_detalle_cotizacion_propiedad_rubro }}" name="mensualidades_[]" class="form-control form-control-sm" value="{{ $a->mensualidades }}"></td>
                                    <td align="center" class="oculto">
                                      <div class="form-check">
                                        @if ($a->excluir_descuento == 1)
                                          <input class="form-check-input" type="checkbox" name="excluir_descuento[]" checked>
                                        @else
                                          <input class="form-check-input" type="checkbox" name="excluir_descuento[]" >
                                        @endif
                                      </div>
                                    </td>
                                    <td>
                                      @if ($a->tipo == 'Abono a capital')
                                        <select name="abono_aplica_a_id_[]" id="abono_aplica_a_id_{{ $a->id_detalle_cotizacion_propiedad_rubro }}"  class="letrasModal form-control form-control-sm" >
                                      @else
                                        <select name="abono_aplica_a_id_[]" id="abono_aplica_a_id_{{ $a->id_detalle_cotizacion_propiedad_rubro }}" readonly class="letrasModal form-control form-control-sm" >
                                      @endif
                                        <option value=""></option>
                                        @foreach ($detalles_rubro as $t)
                                          @if ($t->detalle_cotizacion_propiedad_id == $e->id_detalle_cotizacion_propiedad )
                                            @if ($a->abono_aplica_a_id == $t->id_detalle_cotizacion_propiedad_rubro)
                                            <option selected value="{{ $t->id_detalle_cotizacion_propiedad_rubro }}">{{ $t->alias }} </option>
                                            @else
                                            <option value="{{ $t->id_detalle_cotizacion_propiedad_rubro }}">{{ $t->alias }} </option>
                                            @endif
                                          @endif
                                        @endforeach
                                      </select>
                                    </td>
                                  </tr>
                                @endif
                              @endforeach
                            </tbody>
                            <tfoot class="bg-gray-100 text-sm">
                              <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th><input type="text" disabled name="suma_monto_tabla" id="suma_monto_tabla{{ $e->id_detalle_cotizacion_propiedad }}" class="form-control-plaintext form-control-sm mask" value="{{ $suma }}"></th>
                                <th ><input type="number" disabled name="suma_porcentaje_tabla" id="suma_porcentaje_tabla{{ $e->id_detalle_cotizacion_propiedad }}" class="form-control-plaintext form-control-sm" value="{{ $suma_porcentaje }}"></th>
                                <th></th>
                                <th></th>
                                <th></th>
                              </tr>
                            </tfoot>
                          </table>
                        </div>  
                      </div>
                    </div> 
                  {{ Form::close() }}
                  @include('cotizacion.modal_new_rubro')
                  @include('cotizacion.modal_delete_rubro')
                @endif
              </div>
            @endforeach
          </div>
          @php
            $i = $i + 1;
          @endphp
        @endforeach
    </div>
  </div>
</div>
<div class="modal fade" aria-hidden="true" role="dialog" tabindex="-1" id="modal_new">
    {{ Form::open(array('action'=>array('DetalleCotizacionPropiedadController@store'),'method'=>'post')) }}
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar propiedad</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="cotizacion_id_new" id="cotizacion_id_new" value="{{ $resultado->id_cotizacion }}">
                <p>Que propiedad desea agregar?</p>
                <select name="propiedad_id_new" data-live-search="true" id="propiedad_id_new" class="form-control selectpicker show-tick">
                  @foreach ($propiedades as $e)
                    <option value="{{ $e->id_propiedad }}">{{ $e->nombre }}</option>
                  @endforeach
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-info">Confirmar</button>
            </div>
        </div>
    </div>
    {{ Form::close()}}
</div>
<div class="modal fade" aria-hidden="true" role="dialog" tabindex="-1" id="modal_delete">
    {{ Form::open(array('action'=>array('DetalleCotizacionPropiedadController@destroy', $resultado->id_cotizacion),'method'=>'post')) }}
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Eliminar propiedad</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="cotizacion_id_destroy" id="cotizacion_id_destroy" value="{{ $resultado->id_cotizacion }}">
                <p>Que propiedad desea eliminar?</p>
                <select name="propiedad_id_delete" id="propiedad_id_delete" class="form-control selectpicker show-tick">
                  @foreach ($listbtn as $e)
                    <option value="{{ $e->id_propiedad }}">{{ $e->nombre_propiedad }}</option>
                  @endforeach
                </select>
            </div>
            <div class="modal-footer">
              
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-info">Confirmar</button>
            </div>
        </div>
    </div>
    {{ Form::close()}}
</div>

@push('scripts')
  <script >
    jQuery(document).ready(function($)
    {
      $( "#guardar" ).click(function() {
        $( "#guardar_form" ).submit();
      });

      $( ".btn_propiedad" ).click(function() {
        var str = $(this).attr('id');
        var id = str.substring(14);
        $(".btn_propiedad").each(function(){
            $(this).removeClass('btn-danger');
            $(this).addClass('btn-primary');
            var str_ = $(this).attr('id');
            var id_ = str_.substring(14);
            $('#rowpropiedad_'+id_).collapse('hide')
        });
        $(this).removeClass('btn-primary');
        $(this).addClass('btn-danger');
        $('#rowpropiedad_'+id).collapse('show')

      });

      $('.precio').on('change', function(){
        var id_input = $(this).attr('id');
        var id = id_input.substring(7);
        var porcentaje_descuento = $('#porcentaje_descuento_'+id).val();
        var precio = $('#precio_'+id).val();
        if (precio == 0 || precio == '0' || precio == '') {
          var precio_final = '0.00';
        }else{
          $('#monto_descuento_'+id).val( parseFloat(precio.replace(/,/g, '')) * (parseFloat(porcentaje_descuento.replace(/,/g, '')) / 100 ));
          var descuento = $('#monto_descuento_'+id).val();
          var precio_final = parseFloat(precio.replace(/,/g, '')) - parseFloat(descuento.replace(/,/g, ''));
        }
        $('#precio_final_'+id).val( parseFloat(precio_final) );

      });

      $('.porcentaje_descuento').on('change', function(){
        var id_input = $(this).attr('id');
        var id = id_input.substring(21);
        var porcentaje_descuento = $('#porcentaje_descuento_'+id).val();
        if (porcentaje_descuento == 0 || porcentaje_descuento == '0' || porcentaje_descuento == '') {
          var precio_final = '0.00';
          $('#monto_descuento_'+id).val('0');
          $(".mask").inputmask({ 'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'placeholder': '0.00'}); 
        }else{
          var precio = $('#precio_'+id).val();

          $('#monto_descuento_'+id).val( parseFloat(precio.replace(/,/g, '')) * (parseFloat(porcentaje_descuento.replace(/,/g, '')) / 100 ));
          var descuento = $('#monto_descuento_'+id).val();
          var precio_final = parseFloat(precio.replace(/,/g, '')) - parseFloat(descuento.replace(/,/g, ''));
        }
        $('#precio_final_'+id).val( parseFloat(precio_final) );
        $(".mask").inputmask({ 'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'placeholder': '0.00'}); 

      });

      $('.tipo').on('change', function(){
        var id_input = $(this).attr('id');
        var id = id_input.substring(5);
        var tipo = $(this).val();
        if(tipo == 'Abono a capital'){
          $('#abono_aplica_a_id_'+id).removeAttr("disabled");
        }else{
          $('#abono_aplica_a_id_'+id).attr("disabled","disabled");
        }
      });

      $('.tipo_new').on('change', function(){
        var id_input = $(this).attr('id');
        var id = id_input.substring(9);
        var tipo = $(this).val();
        if(tipo == 'Abono a capital'){
          $('#abono_aplica_a_id_new_'+id).removeAttr("disabled");
        }else{
          $('#abono_aplica_a_id_new_'+id).attr("disabled","disabled");
        }
      });

      $('.porcentaje_tabla').on('change', function(){
        var id_input = $(this).attr('id');
        var id = id_input.substring(11);
        var porcentaje = $(this).val();
        var id_detalle_padre = $('#id_detalle_padre_'+id).val();
        if (porcentaje == 0 || porcentaje == '0' || porcentaje == '') {
          var monto = '0.00';
        }else{
          var precio_final = $('#precio_final_'+id_detalle_padre).val();
          precio_final = parseFloat(precio_final.replace(/,/g, ''));
          var monto = parseFloat(precio_final) *  (parseFloat(porcentaje)/100);
        }
        $('#monto_'+id).val( parseFloat(monto) ) ;
        sumar(id_detalle_padre);
      });

      $('.monto_tabla').on('change', function(){
        var id_input = $(this).attr('id');
        var id = id_input.substring(6);
        var monto = $(this).val();
        var monto = monto.replace(/,/g, '');
        var id_detalle_padre = $('#id_detalle_padre_'+id).val();
        if (monto == 0 || monto == '0' || monto == '') {
          var porcentaje = '0';
        }else{
          var precio_final = $('#precio_final_'+id_detalle_padre).val();
          precio_final = parseFloat(precio_final.replace(/,/g, ''));
          var porcentaje = ( parseFloat(monto) * 100) / ( parseFloat(precio_final) );
        }
        $('#porcentaje_'+id).val( parseFloat(porcentaje) );
        sumar(id_detalle_padre);
      });

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
              selectPropiedad.multiselect('destroy');
              selectPropiedad.multiselect({
                enableFiltering: true,
                filterBehavior: 'text',
                enableCaseInsensitiveFiltering: true,
                filterPlaceholder: 'Buscar propiedad...',
              });
        },
          error: function(error) {
          alert("No se pudo cargar el catalogo de propiedades");
        }
        })
      });

    });
    function roundNumber(num, scale) {
      if(!("" + num).includes("e")) {
        return +(Math.round(num + "e+" + scale)  + "e-" + scale);
      } else {
        var arr = ("" + num).split("e");
        var sig = ""
        if(+arr[1] + scale > 0) {
          sig = "+";
        }
        return +(Math.round(+arr[0] + "e" + sig + (+arr[1] + scale)) + "e-" + scale);
      }
    }
    function sumar(id_padre){
      var sum=0;
      
      $('.monto_tabla').each(function() {
          var monto = $(this).val();
          var monto = monto.replace(/,/g, '');
          var id_input = $(this).attr('id');
          var id = id_input.substring(6);
          var id_detalle_padre = $('#id_detalle_padre_'+id).val();
          if (id_padre == id_detalle_padre) {
            sum += Number(monto);
          }
      });
      $('#suma_monto_tabla'+id_padre).val(sum);

      var sum_p=0;
      $('.porcentaje_tabla').each(function() {
        var id_input = $(this).attr('id');
        var id = id_input.substring(11);
        var id_detalle_padre = $('#id_detalle_padre_'+id).val();
        if (id_padre == id_detalle_padre) {
          sum_p += Number($(this).val());
        }
      });
      $('#suma_porcentaje_tabla'+id_padre).val(sum_p);
      if (sum_p > 100) {
        $('#btn_calcular_'+id_padre).attr("disabled","disabled");
        $("#suma_porcentaje_tabla"+id_padre).css("background-color", "#B41811");
        $("#suma_porcentaje_tabla"+id_padre).css("color", "#FFF");
        $("#suma_monto_tabla"+id_padre).css("background-color", "#B41811");
        $("#suma_monto_tabla"+id_padre).css("color", "#FFF");
      }else if( sum_p < 100 ){
        $('#btn_calcular_'+id_padre).attr("disabled","disabled");
        $("#suma_porcentaje_tabla"+id_padre).css("background-color", "#B41811");
        $("#suma_porcentaje_tabla"+id_padre).css("color", "#FFF");
        $("#suma_monto_tabla"+id_padre).css("background-color", "#B41811");
        $("#suma_monto_tabla"+id_padre).css("color", "#FFF");
      }else if( sum_p == 100 ){
        $('#btn_calcular_'+id_padre).removeAttr("disabled");
        $("#suma_porcentaje_tabla"+id_padre).css("background-color", "#f8f9fc");
        $("#suma_porcentaje_tabla"+id_padre).css("color", "#3E3E3E");
        $("#suma_monto_tabla"+id_padre).css("background-color", "#f8f9fc");
        $("#suma_monto_tabla"+id_padre).css("color", "#3E3E3E");
      }
    }
  </script>
@endpush 
@endsection