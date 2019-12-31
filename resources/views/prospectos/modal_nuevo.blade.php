<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal_prospecto">
  {{ Form::open(array('action'=>array('ProspectosController@store'),'method'=>'post')) }}
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title text-dark">Nuevo prospecto</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="nombre_s">*Nombre (s)</label>
                          <input type="text" name="nombre_s" id="nombre_s" value=""  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                          <label for="apellido_paterno">*Apellido paterno</label>
                          <input type="text" name="apellido_paterno" id="apellido_paterno" value=""  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                          <label for="apellido_materno">*Apellido materno</label>
                          <input type="text" name="apellido_materno" id="apellido_materno" value=""  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="razon_social">Razón social</label>
                          <input type="text" name="razon_social" id="razon_social" value=""  class="letrasModal form-control" />
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                          <label for="tipo">*Tipo</label>
                          <select class="form-control" id="tipo" name="tipo">
                              @foreach ($tipos as $p)
                                <option value="{{ $p }}">{{ $p }}</option>
                              @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                          <label for="nuevo_rfc_mdl">RFC</label>
                          <input type="text" name="nuevo_rfc_mdl" id="nuevo_rfc_mdl" value="" class="letrasModal form-control"  />
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                          <label for="nuevo_correo_mdl">*Correo</label>
                          <input type="email" name="nuevo_correo_mdl" id="nuevo_correo_mdl" value=""  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                          <label for="nuevo_telefono_mdl">Telefono</label>
                          <input type="text" name="nuevo_telefono_mdl" id="nuevo_telefono_mdl" value=""  class="letrasModal form-control" />
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                          <label for="nuevo_telefono_adicional_mdl">Telefono adicional</label>
                          <input type="text" name="nuevo_telefono_adicional_mdl" id="nuevo_telefono_adicional_mdl" value=""  class="letrasModal form-control" />
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                          <label for="nuevo_extension_mdl">Extension</label>
                          <input type="text" name="nuevo_extension_mdl" id="nuevo_extension_mdl" value=""  class="letrasModal form-control" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="nuevo_medio_contacto_mdl">Medio de contacto</label>
                          <select name="nuevo_medio_contacto_mdl" id="nuevo_medio_contacto_mdl" value=""  class="letrasModal form-control selectpicker" data-live-search="true">
                            @foreach ($medios_contacto as $medio)
                              <option value="{{ $medio->id_medio_contacto }}">{{ $medio->medio_contacto }}</option>
                            @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="moneda">*Moneda</label>
                      <select name="moneda" id="moneda" class="letrasModal form-control">
                        @foreach ($monedas as $m)
                          <option value="{{ $m->id_moneda }}">{{ $m->siglas }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="domicilio">Domicilio</label>
                          <input type="text" name="domicilio" id="domicilio" value=""  class="letrasModal form-control"  />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="nuevo_proyecto_mdl">Proyecto</label>
                          <select name="nuevo_proyecto_mdl" id="nuevo_proyecto_mdl" value=""  class="letrasModal form-control">
                            <option selected="true" disabled="true">Selecciona un proyecto</option>
                            <option value="sin proyecto">Sin proyecto</option>
                            @foreach ($proyectos as $py)
                              <option value="{{ $py->id_proyecto }}">{{ $py->nombre }}</option>
                            @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="nuevo_propiedad_mdl">Propiedad</label>
                          <select name="nuevo_propiedad_mdl" id="nuevo_propiedad_mdl" class="letrasModal form-control selectpicker" data-live-search="true">
                            <option selected="true" disabled="true" >Selecciona una propiedad</option>
                          </select>
                      </div>
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="nuevo_observacion_mdl">Observaciones</label>
                          <textarea rows="5" name="nuevo_observacion_mdl" id="nuevo_observacion_mdl" value=""  class="letrasModal form-control"></textarea>
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