@extends('layouts.admin')
@section('title')
Detalle esquema de pago
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            {{ Form::open(array('action'=>array('DetalleEsquemaPagoController@update',$resultado->id_detalle_esquema_pago),'method'=>'post')) }}
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="alias">*Alias</label>
                        <input type="text" name="alias" id="alias" value="{{ $resultado->alias }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="created_at">*Fecha</label>
                        <input type="date" name="created_at" id="created_at" value="{{ date('Y-m-d',strtotime($resultado->created_at)) }}"  class="letrasModal form-control" readonly="true" required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                      <div class="form-group">
                          <label for="tipo">*Tipo</label>
                          <select name="tipo" id="tipo"  class="letrasModal form-control" required="true"> 
                              @foreach ($tipo as $e)
                                @if ($resultado->tipo == $e)
                                <option selected value="{{ $e }}">{{ $e }} </option>
                                @else
                                <option value="{{ $e }}">{{ $e }} </option>
                                @endif
                              @endforeach
                          </select>
                      </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="tipo_calculo">*Tipo calculo</label>
                        <select name="tipo_calculo" id="tipo_calculo"  class="letrasModal form-control" required="true"> 
                            @foreach ($tipo_calculo as $e)
                              @if ($resultado->tipo_calculo == $e)
                              <option selected value="{{ $e }}">{{ $e }} </option>
                              @else
                              <option value="{{ $e }}">{{ $e }} </option>
                              @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3" id="porcentaje_ocultar">
                  <div class="form-group">
                      <label for="porcentaje">Porcentaje</label>
                      <input type="number" step="any" name="porcentaje" id="porcentaje" value="{{ $resultado->porcentaje }}"  class=" form-control" />
                  </div>
                </div>
                <div class="col-md-3" id="monto_ocultar">
                  <div class="form-group">
                      <label for="monto">Monto</label>
                      <input type="text" name="monto" id="monto" value="{{ $resultado->monto }}"  class="mask form-control" />
                  </div>
                </div>
                <div class="col-md-3" id="porcentaje_ocultar">
                  <div class="form-group">
                      <label for="mensualidades">Mensualidades</label>
                      <input type="number" step="any" name="mensualidades" id="mensualidades" value="{{ $resultado->mensualidades }}"  class=" form-control" />
                  </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="esquema_pago_id">*Esquema de pago</label>
                        <select name="esquema_pago_id" id="esquema_pago_id"  class="letrasModal form-control" required="true">
                            @foreach ($esquemas as $r)
                                @if ($r->id_esquema_pago == $resultado->esquema_pago_id)
                                    <option selected="true" value="{{ $r->id_esquema_pago }}">{{ $r->esquema_pago }}</option>
                                @else
                                    <option value="{{ $r->id_esquema_pago }}">{{ $r->esquema_pago }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2 offset-md-5">
                    <div class="form-group">
                        @if ($procedencia == 'Menu')
                        <a href="{{ route('detalle_esquema_pago') }}" class="btn btn-dark btn-block">CANCELAR</a>
                        @else
                        <a href="{{ route($procedencia, $resultado->esquema_pago_id) }}" class="btn btn-dark btn-block">CANCELAR</a>
                        @endif
                    </div>
                </div> 
                <div class="col-md-2">
                    <div class="form-group">
                        <button type="submit" class="btn btn-info btn-block">GUARDAR</button>
                    </div>
                </div>
            </div>
            {{ Form::close()}}
        </div>
    </div>
</div>

@push('scripts')
<script >
   jQuery(document).ready(function($)
    {
        tipo_calculo = $('#tipo_calculo').val();
        if (tipo_calculo == "Porcentaje") {
            $('#porcentaje_ocultar').removeClass('oculto');
            $('#monto_ocultar').addClass('oculto');
        }else if(tipo_calculo == 'Monto'){
            $('#monto_ocultar').removeClass('oculto');
            $('#porcentaje_ocultar').addClass('oculto');
        }
        $("#tipo_calculo").on('change', function(){
            tipo_calculo = $('#tipo_calculo').val();
            if (tipo_calculo == "Porcentaje") {
                $('#porcentaje_ocultar').removeClass('oculto');
                $('#monto_ocultar').addClass('oculto');
            }else if(tipo_calculo == 'Monto'){
                $('#monto_ocultar').removeClass('oculto');
                $('#porcentaje_ocultar').addClass('oculto');
            }
        });
    });
</script>
@endpush 
@endsection