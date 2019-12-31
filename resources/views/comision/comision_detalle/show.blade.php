@extends('layouts.admin')
@section('title')
Comision detalle
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            @if (session()->has('msj'))
              <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>{{ session('msj') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            @endif
            {{ Form::open(array('action'=>array('ComisionDetalleController@update',$comision_detalle->id_comision_detalle),'method'=>'post')) }}
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="rubro">*Rubro</label>
                        <input type="text" name="rubro" id="rubro" value="{{ $comision_detalle->rubro }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="tipo">*Tipo</label>
                        <input type="text" name="tipo" id="tipo" value="{{ $comision_detalle->tipo }}"  class="letrasModal form-control" required="true"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="usuario">*Usuario</label>
                        <select name="usuario" id="usuario" value="{{ $comision_detalle->nombre_usuario }}"  class="letrasModal form-control" required="true">
                            @foreach ($usuarios as $u)
                                @if ($u->id == $comision_detalle->usuario_id)
                                    <option selected value="{{ $u->id }}">{{ $u->name }}</option>
                                @else
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="persona">Persona</label>
                        <input type="text" name="persona" id="persona" value="{{ $comision_detalle->persona }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="total_venta">*Total venta</label>
                        <input type="text" name="total_venta" id="total_venta" value="{{ $comision_detalle->total_venta }}"  class="mask form-control" required="true" readonly="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="factor">*Factor</label>
                        <input type="text" name="factor" id="factor" value="{{ $comision_detalle->factor }}"  class="letrasModal form-control" required="true" readonly="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="comision">*Comision</label>
                        <input type="text" step="any" name="comision" id="comision" value="{{ $comision_detalle->comision}}"  class="mask form-control" required="true" readonly="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="saldo_comision">*Saldo comision</label>
                        <input type="text" name="saldo_comision" id="saldo_comision" value="{{ $comision_detalle->saldo_comision }}"  class="mask form-control" required="true" readonly="true"/>
                    </div>
                </div>
            </div>
            <div class="row">    
                <div class="col-md-3 offset-md-3">
                    <div class="form-group">
                        <a href="{{ route('comision-show',['id'=>$comision_detalle->comision_id]) }}" class="btn btn-dark btn-block">REGRESAR</a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <button type="submit" class="btn btn-info btn-block">GUARDAR</button>
                    </div>
                </div>
                @if ($comision_detalle->saldo_comision > 0)
                    <div class="col-md-3">
                        <div class="form-group">
                            <a data-toggle="modal" data-target="#modal-pagar"><button type="button" class="btn btn-warning btn-block">PAGAR</button></a>
                        </div>
                    </div>
                @endif
            </div>
            {{ Form::close()}}
            <div class="card">
              <div class="card-header bg-primary text-white">
                Pagos
              </div>
              <div class="card-body">
                <div class="table-responsive ">
                  <table class="table table-hover table-withoutborder text-sm">
                    <thead class="thead-grubsa ">
                      <th class="center">
                        Estatus
                      </th>
                      <th class="center">
                        Fecha pago
                      </th>
                      <th class="center">
                        Monto
                      </th>
                      <th class="center">
                        Forma de pago
                      </th>
                      <th class="center">
                       Descripcion
                      </th>
                      <th class="center">
                        Acciones
                      </th>
                    </thead>
                    <tbody>
                      @foreach ($pagos_comision as $pc)                
                      <tr>
                        <td class="center">
                          <a class="text-dark" href="{{URL::action('PagoComisionController@show', $pc->id_pago_comision)}}"  style="width: 30%;">
                          {{ $pc->estatus}}</a>
                        </td>
                        <td class="center">
                          <a class="text-dark" href="{{URL::action('PagoComisionController@show', $pc->id_pago_comision)}}"  style="width: 30%;">
                          {{ $pc->fecha_pago}}</a>
                        </td>
                        <td class="center">
                          <a class="text-dark" href="{{URL::action('PagoComisionController@show', $pc->id_pago_comision)}}"  style="width: 30%;">
                          $ {{ number_format($pc->monto , 2 , "." , ",") }}</a>
                        </td>
                        <td class="center">
                          <a class="text-dark" href="{{URL::action('PagoComisionController@show', $pc->id_pago_comision)}}"  style="width: 30%;">
                          {{ $pc->forma_pago}}</a>
                        </td>
                        <td class="center">
                          <a class="text-dark" href="{{URL::action('PagoComisionController@show', $pc->id_pago_comision)}}"  style="width: 30%;">
                          {{ $pc->descripcion}}</a>
                        </td>
                        <td class="center-acciones">
                          <a href="{{URL::action('PagoComisionController@show', $pc->id_pago_comision)}}"  style="width: 30%;"><button class="btn-ico"><i class="fas fa-pencil-alt"></i></button></a>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            {{$pagos_comision->render()}}
        </div>
    </div>
</div>
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-pagar">
    {{ Form::open(array('action'=>array('ComisionDetalleController@pagar',$comision_detalle->id_comision_detalle),'method'=>'post')) }}
    <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title text-dark">Pagar comision</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="nuevo_fecha_pago">*Fecha de pago</label>
                          <input type="date" name="nuevo_fecha_pago" id="nuevo_fecha_pago" value=""  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="nuevo_monto">*Monto a pagar</label>
                          <input type="text" name="nuevo_monto" id="nuevo_monto" value="{{ $comision_detalle->saldo_comision }}"  class="mask form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="nuevo_forma_pago_id">*Forma de pago</label>
                          <select name="nuevo_forma_pago_id" id="nuevo_forma_pago_id" value=""  class="letrasModal form-control" required="true">
                            @foreach ($formas_pago as $forma)
                              <option value="{{ $forma->id_forma_pago }}">{{ $forma->forma_pago }}</option>
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
@push('scripts')
<script>
    jQuery(document).ready(function($)
    {

      $("#usuario").on('change', function(){
          $("#persona").val( $("#usuario option:selected").text());

      });
    });
</script>
@endpush 
@endsection