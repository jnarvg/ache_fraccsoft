@extends('layouts.admin')
@section('css')
    <link href="{{ asset('css/dropzone.css') }}" rel="stylesheet">
@endsection
@section('title')
Plazos de pago
@endsection
@section('filter')
    <a href="#" class="mb-0 d-sm-inline-block btn btn-primary " id="guardar" title="GUARDAR"><i class="fas fa-check"></i></a>
    @if ($procedencia == 'Menu')
        <a href="{{ route('plazos_pago') }}" class="mb-0 d-sm-inline-block btn btn-primary" title="CERRAR"><i class="fas fa-times"></i></a>
    @else
        <a href="{{ route('prospectos-show',[$plazo->prospecto_id, $procedencia]) }}" class="mb-0 d-sm-inline-block btn btn-primary" title="CERRAR"><i class="fas fa-times"></i></a>
    @endif
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            {{ Form::open(array('action'=>array('PlazosPagoController@update',$plazo->id_plazo_pago, $procedencia),'method'=>'post','files'=>'true', 'id'=>'guardar_form')) }}
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="prospecto">*Cliente</label>
                        <select name="prospecto" id="prospecto" class="letrasModal form-control">
                          @foreach ($prospectos as $pros)
                              @if ($plazo->prospecto_id == $pros->id_prospecto)
                                  <option selected="true" value="{{ $pros->id_prospecto }}">{{ $pros->nombre }}</option>
                              @else
                                  <option value="{{ $pros->id_prospecto }}">{{ $pros->nombre }}</option>
                              @endif
                          @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="num_plazo">Numero de plazo</label>
                        <input type="number" name="num_plazo" id="num_plazo" value="{{ $plazo->num_plazo }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="fecha">*Fecha</label>
                        <input type="date" name="fecha" id="fecha" value="{{
                            date('Y-m-d',strtotime($plazo->fecha)) }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="estatus">*Estatus</label>
                        <select name="estatus" id="estatus" class="letrasModal form-control">
                            <option selected="true" value="{{ $plazo->estatus }}">{{ $plazo->estatus }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="tipo">*Tipo</label>
                        <select name="tipo" id="tipo" class="letrasModal form-control">
                            <option selected="true" value="{{ $plazo->tipo }}">{{ $plazo->tipo }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="descripcion">*Descripcion</label>
                        <input type="text" name="descripcion" id="descripcion" value="{{ $plazo->descripcion }}"  class=" form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="total">*Total</label>
                        <input type="text" name="total" step="any" id="total" value="{{ $plazo->total }}"  class="mask form-control" required="true" readonly="true" />
                    </div>
                </div>
                <div class="col-md-12">
                    <h3><em>Saldos</em></h3>
                    <hr class="hr-titulo" width="100%" size="10">
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="saldo">*Saldo</label>
                        <input type="text" name="saldo" step="any" id="saldo" value="{{ $plazo->saldo }}"  class="mask form-control" required="true" readonly="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="pagado">*Pagado</label>
                        <input type="text" name="pagado" step="any" id="pagado" value="{{ $plazo->pagado }}"  class="mask form-control" required="true" readonly />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="capital_inicial">Capital inicial</label>
                        <input type="text" name="capital_inicial" step="any" id="capital_inicial" value="{{ $plazo->capital_inicial }}"  class="mask form-control" required="true" readonly="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="capital">Capital</label>
                        <input type="text" name="capital" step="any" id="capital" value="{{ $plazo->capital }}"  class="mask form-control" required="true" readonly="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="interes">Interes</label>
                        <input type="text" name="interes" step="any" id="interes" value="{{ $plazo->interes }}"  class="mask form-control" required="true" readonly="true" />
                    </div>
                </div>
                <div class="col-md-3 oculto">
                    <div class="form-group">
                        <label for="deuda">*Deuda</label>
                        <input type="text" name="deuda" step="any" id="deuda" value="{{ $plazo->deuda }}"  class="mask form-control" required="true" readonly="true" />
                    </div>
                </div>
                <div class="col-md-3 oculto">
                    <div class="form-group">
                        <label for="amortizacion">Amortizacion</label>
                        <input type="text" name="amortizacion" step="any" id="amortizacion" value="{{ $plazo->amortizacion }}"  class="mask form-control" required="true" readonly="true" />
                    </div>
                </div>
                <div class="col-md-3 oculto">
                    <div class="form-group">
                        <label for="interes_acumulado">Interes Acumulado</label>
                        <input type="text" name="interes_acumulado" step="any" id="interes_acumulado" value="{{ $plazo->interes_acumulado }}"  class="mask form-control" required="true" readonly="true" />
                    </div>
                </div>
                <div class="col-md-3 oculto">
                    <div class="form-group">
                        <label for="total_acumulado">Total acumulado</label>
                        <input type="text" name="total_acumulado" step="any" id="total_acumulado" value="{{ $plazo->total_acumulado }}"  class="mask form-control" required="true" readonly="true" />
                    </div>
                </div>
                <div class="col-md-3 oculto">
                    <div class="form-group">
                        <label for="capital_acumulado">Capital Acumulado</label>
                        <input type="text" name="capital_acumulado" step="any" id="capital_acumulado" value="{{ $plazo->capital_acumulado }}"  class="mask form-control" required="true" readonly="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="moneda">Moneda</label>
                        <select name="moneda" id="moneda" class="letrasModal form-control" required="true">
                          @foreach ($monedas as $m)
                            @if ($m->id_moneda == $plazo->moneda_id )
                                <option selected value="{{ $m->id_moneda }}">{{ $m->siglas }}</option>
                            @else
                                <option value="{{ $m->id_moneda }}">{{ $m->siglas }}</option>
                            @endif
                          @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <h3><em>Moras</em></h3>
                    <hr class="hr-titulo" width="100%" size="10">
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="dias_retraso">Dias de retraso</label>
                        <input type="number" name="dias_retraso" id="dias_retraso" value="{{ $plazo->dias_retraso }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="monto_mora">Monto mora</label>
                        <input type="text" step="any" name="monto_mora" id="monto_mora" value="{{ $plazo->monto_mora }}"  class="mask form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-12">   
                    <div class="form-group">
                        <label for="notas">Notas</label>
                        <textarea name="notas" id="notas" value=""  class="letrasModal form-control">{{ $plazo->notas }}</textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 offset-md-3">
                    @if ($procedencia == 'Menu')
                    <div class="form-group">
                        <a href="{{ route('plazos_pago') }}" class="btn btn-dark btn-block">REGRESAR</a>
                    </div>
                    @else
                    <div class="form-group">
                        <a href="{{ route('prospectos-show',[$plazo->prospecto_id, $procedencia]) }}" class="btn btn-dark btn-block">REGRESAR</a>
                    </div>
                    @endif
                </div> 
                @if ($plazo->estatus != 'Pagado' and $plazo->estatus != 'Inactivo')
                  <div class="col-md-3">
                      <div class="form-group">
                          <button type="submit" class="btn btn-info btn-block">GUARDAR</button>
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                          <a href="" id="btnpuluspago" data-toggle="modal" data-target="#modal-pago" class="btn btn-block btn-warning">PAGAR</a>    
                      </div>
                  </div>
                @endif
            </div>
            {{ Form::close()}}
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="row">
                        <div class="col-md-12">
                            Pagos
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive ">
                        <table class="table table-hover table-withoutborder text-sm">
                          <thead class="thead-grubsa ">
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
                              Estatus
                            </th>
                            <th class="center">
                             Acciones
                            </th>
                          </thead>
                          <tbody>
                            @foreach ($pagos_plazo as $pp)               
                            <tr>
                              <td class="center">
                                {{ date('Y-m-d',strtotime($pp->fecha)) }}
                              </td>
                              <td class="center">
                                $ {{ number_format($pp->monto , 2 , "." , ",") }}
                              </td>
                              <td class="center">
                                {{ $pp->forma_pago }}
                              </td>
                              <td class="center">
                                {{ $pp->estatus }}
                              </td>
                              <td class="center-acciones">
                                <a href="{{URL::action('PagosController@show', [$pp->id_pago, $procedencia])}}"  style="width: 30%;"><button class="btn-ico" style="margin-left: 2px; margin-right: 2px;"><i class="fas fa-pencil-alt"></i></button></a>
                                @if ($pp->estatus == 'Cancelado')
                                  <a href="{{URL::action('PagosController@destroy', [$pp->id_pago, $procedencia])}}"  style="width: 30%;"><button class="btn-ico" style="margin-left: 2px; margin-right: 2px;"><i class="fas fa-trash-alt"></i></button></a>
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
    </div>
</div>
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-pago">
  {{ Form::open(array('action'=>array('PlazosPagoController@pagar',$plazo->id_plazo_pago, $procedencia),'method'=>'post' )) }}
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h4 class="modal-title text-dark">Pagar plazo</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <div class="modal-body">
          <div class="row">
              <div class="col-md-6">
                  <div class="form-group">
                      <label for="nuevo_monto">*Monto de pago</label>
                      <input type="text" name="nuevo_monto" id="nuevo_monto" value="{{ $plazo->saldo }}" step="any" class="mask form-control" required="true" />
                  </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                    <label for="nuevo_forma_pago">*Forma de pago</label>
                    <select name="nuevo_forma_pago" id="nuevo_forma_pago" class="letrasModal form-control" required="true">
                      @foreach ($formas_pago as $pros)
                        <option value="{{ $pros->id_forma_pago }}">{{ $pros->forma_pago }}</option>
                      @endforeach
                    </select>
                </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      <label for="nuevo_fecha">*Fecha</label>
                      <input type="date" name="nuevo_fecha" id="nuevo_fecha" value="{{date("Y-m-d")}}" class="letrasModal form-control" required="true" />
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
<script type="text/javascript">
  jQuery(document).ready(function($)
  {   
    $( "#guardar" ).click(function() {
      $( "#guardar_form" ).submit();
    });
  });
</script>
@endpush 
@endsection