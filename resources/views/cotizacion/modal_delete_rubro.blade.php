<div class="modal fade" aria-hidden="true" role="dialog" tabindex="-1" id="modal_delete_rubro_{{ $e->id_detalle_cotizacion_propiedad }}">
  {{ Form::open(array('action'=>array('DetalleCotizacionPropiedadRubroController@destroy', $e->id_detalle_cotizacion_propiedad),'method'=>'post')) }}
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title">Eliminar rubro {{ $e->id_detalle_cotizacion_propiedad }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="cotizacion_id_delete_{{ $e->id_detalle_cotizacion_propiedad }}" id="cotizacion_id_delete_{{ $e->id_detalle_cotizacion_propiedad }}" value="{{ $resultado->id_cotizacion }}">
                <input type="hidden" name="id_detalle_cotizacion_propiedad_delete_{{ $e->id_detalle_cotizacion_propiedad }}" id="id_detalle_cotizacion_propiedad_delete_{{ $e->id_detalle_cotizacion_propiedad }}" value="{{ $e->id_detalle_cotizacion_propiedad }}">
                <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <label for="rubro_eliminar{{ $e->id_detalle_cotizacion_propiedad }}">Selecciona el rubro a eliminar</label>
                      <select name="rubro_eliminar{{ $e->id_detalle_cotizacion_propiedad }}" id="rubro_eliminar{{ $e->id_detalle_cotizacion_propiedad }}"  class="letrasModal form-control form-control-sm" required="true">
                        <option value=""></option>
                        @foreach ($detalles_rubro as $t)
                          @if ($t->detalle_cotizacion_propiedad_id == $e->id_detalle_cotizacion_propiedad )
                            @if ($a->abono_aplica_a_id == $t->id_detalle_cotizacion_propiedad_rubro)
                            <option selected value="{{ $t->id_detalle_cotizacion_propiedad_rubro }}">{{ $t->alias }} </option>
                            @else
                            <option value="{{ $t->id_detalle_cotizacion_propiedad_rubro }}">{{ $t->alias }} - {{ $t->porcentaje }}</option>
                            @endif
                          @endif
                        @endforeach
                      </select>
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