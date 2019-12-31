<div class="modal fade" aria-hidden="true" role="dialog" tabindex="-1" id="modal_new_rubro_{{ $e->id_detalle_cotizacion_propiedad }}">
  {{ Form::open(array('action'=>array('DetalleCotizacionPropiedadRubroController@store', $e->id_detalle_cotizacion_propiedad),'method'=>'post')) }}
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title">Agregar rubro</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="cotizacion_id_new_{{ $e->id_detalle_cotizacion_propiedad }}" id="cotizacion_id_new_{{ $e->id_detalle_cotizacion_propiedad }}" value="{{ $resultado->id_cotizacion }}">
                <input type="hidden" name="id_detalle_cotizacion_propiedad_new_{{ $e->id_detalle_cotizacion_propiedad }}" id="id_detalle_cotizacion_propiedad_{{ $e->id_detalle_cotizacion_propiedad }}" value="{{ $e->id_detalle_cotizacion_propiedad }}">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>*Alias</label>
                      <input type="text" id="alias_new_{{ $e->id_detalle_cotizacion_propiedad }}" name="alias_new_{{ $e->id_detalle_cotizacion_propiedad }}" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>*Fecha</label>
                      <input type="date" id="fecha_new_{{ $e->id_detalle_cotizacion_propiedad }}" name="fecha_new_{{ $e->id_detalle_cotizacion_propiedad }}" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>*Tipo</label>
                      <select name="tipo_new_{{ $e->id_detalle_cotizacion_propiedad }}" id="tipo_new_{{ $e->id_detalle_cotizacion_propiedad }}"  class="letrasModal form-control tipo_new" required="true"> 
                          @foreach ($tipo as $p)
                            <option value="{{ $p }}">{{ $p }} </option>
                          @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>*Tipo calculo</label>
                      <select name="tipo_calculo_new_{{ $e->id_detalle_cotizacion_propiedad }}" id="tipo_calculo_new_{{ $e->id_detalle_cotizacion_propiedad }}"  class="letrasModal form-control " required="true"> 
                          @foreach ($tipo_calculo as $p)
                            <option value="{{ $p }}">{{ $p }} </option>
                          @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Porcentaje</label>
                      <input type="number" id="porcentaje_new_{{ $e->id_detalle_cotizacion_propiedad }}" name="porcentaje_new_{{ $e->id_detalle_cotizacion_propiedad }}" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Monto</label>
                      <input type="text" name="monto_new_{{ $e->id_detalle_cotizacion_propiedad }}" id="monto_new_{{ $e->id_detalle_cotizacion_propiedad }}" class="mask form-control">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Mensualidades</label>
                      <input type="number" name="mensualidades_new_{{ $e->id_detalle_cotizacion_propiedad }}" id="mensualidades_new_{{ $e->id_detalle_cotizacion_propiedad }}" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Abona a</label>
                      <select name="abono_aplica_a_id_new_{{ $e->id_detalle_cotizacion_propiedad }}" id="abono_aplica_a_id_new_{{ $e->id_detalle_cotizacion_propiedad }}"  class="letrasModal form-control" disabled >
                        <option value=""></option>
                        @foreach ($detalles_rubro as $p)
                          @if ($p->detalle_cotizacion_propiedad_id == $e->id_detalle_cotizacion_propiedad )
                            <option value="{{ $p->id_detalle_cotizacion_propiedad_rubro }}">{{ $p->alias }} </option>
                          @endif
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4 oculto">
                      <div class="form-group">
                        <label for="excluir_descuento_new_{{ $e->id_detalle_cotizacion_propiedad }}">*Excluir</label>
                        <div class="custom-control custom-switch">
                          <input type="checkbox" class="custom-control-input" id="excluir_descuento_new_{{ $e->id_detalle_cotizacion_propiedad }}" name="excluir_descuento_new_{{ $e->id_detalle_cotizacion_propiedad }}">
                          <label class="custom-control-label" for="excluir_descuento_new_{{ $e->id_detalle_cotizacion_propiedad }}"> de descuento</label>
                        </div>
                      </div>
                  </div>
                  <div class="col-md-4 oculto">
                      <div class="form-group">
                        <label for="excluir_calculo_new_{{ $e->id_detalle_cotizacion_propiedad }}">*Excluir</label>
                        <div class="custom-control custom-switch">
                          <input type="checkbox" class="custom-control-input" id="excluir_calculo_new_{{ $e->id_detalle_cotizacion_propiedad }}" name="excluir_calculo_new_{{ $e->id_detalle_cotizacion_propiedad }}">
                          <label class="custom-control-label" for="excluir_calculo_new_{{ $e->id_detalle_cotizacion_propiedad }}"> de calculo</label>
                        </div>
                      </div>
                  </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-info">Confirmar</button>
            </div>
        </div>
    </div>
  {{ Form::close()}}
</div>