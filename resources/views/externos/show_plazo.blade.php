@extends('layouts.admin')
@section('css')
    <link href="{{ asset('css/dropzone.css') }}" rel="stylesheet">
@endsection
@section('title')
Plazos de pago
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
          <div class="row">
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="num_plazo">Numero de plazo</label>
                      <input type="number" name="num_plazo" id="num_plazo" value="{{ $plazo->num_plazo }}"  class="letrasModal form-control" required="true" />
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="fecha">*Fecha</label>
                      <input type="date" name="fecha" id="fecha" value="{{
                          date('Y-m-d',strtotime($plazo->fecha)) }}"  class="letrasModal form-control" required="true" />
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="estatus">*Estatus</label>
                      <select name="estatus" id="estatus" class="letrasModal form-control">
                          <option selected="true" value="{{ $plazo->estatus }}">{{ $plazo->estatus }}</option>
                      </select>
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="total">*Total</label>
                      <input type="text" name="total" step="any" id="total" value="{{ $plazo->total }}"  class="mask form-control" required="true" readonly="true" />
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="saldo">*Saldo</label>
                      <input type="text" name="saldo" step="any" id="saldo" value="{{ $plazo->saldo }}"  class="mask form-control" required="true" readonly="true" />
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="pagado">*Pagado</label>
                      <input type="text" name="pagado" step="any" id="pagado" value="{{ $plazo->pagado }}"  class="mask form-control" required="true" readonly="true" />
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="capital_inicial">Capital inicial</label>
                      <input type="text" name="capital_inicial" step="any" id="capital_inicial" value="{{ $plazo->capital_inicial }}"  class="mask form-control" required="true" readonly="true" />
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="capital">Capital</label>
                      <input type="text" name="capital" step="any" id="capital" value="{{ $plazo->capital }}"  class="mask form-control" required="true" readonly="true" />
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="interes">Interes</label>
                      <input type="text" name="interes" step="any" id="interes" value="{{ $plazo->interes }}"  class="mask form-control" required="true" readonly="true" />
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="deuda">*Deuda</label>
                      <input type="text" name="deuda" step="any" id="deuda" value="{{ $plazo->deuda }}"  class="mask form-control" required="true" readonly="true" />
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="amortizacion">Amortizacion</label>
                      <input type="text" name="amortizacion" step="any" id="amortizacion" value="{{ $plazo->amortizacion }}"  class="mask form-control" required="true" readonly="true" />
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="interes_acumulado">Interes Acumulado</label>
                      <input type="text" name="interes_acumulado" step="any" id="interes_acumulado" value="{{ $plazo->interes_acumulado }}"  class="mask form-control" required="true" readonly="true" />
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="total_acumulado">Total acumulado</label>
                      <input type="text" name="total_acumulado" step="any" id="total_acumulado" value="{{ $plazo->total_acumulado }}"  class="mask form-control" required="true" readonly="true" />
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="capital_acumulado">Capital Acumulado</label>
                      <input type="text" name="capital_acumulado" step="any" id="capital_acumulado" value="{{ $plazo->capital_acumulado }}"  class="mask form-control" required="true" readonly="true" />
                  </div>
              </div>
              <div class="col-md-4">
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
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="prospecto">*Prospecto</label>
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
              {{-- <div class="col-md-4">
                  <div class="form-group">
                      <label for="dias_retraso">Dias de retraso</label>
                      <input type="hidden" name="dias_retraso" id="dias_retraso" value="{{ $plazo->dias_retraso }}"  class="letrasModal form-control" required="true" />
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="monto_mora">Monto mora</label>
                      <input type="hidden" step="any" name="monto_mora" id="monto_mora" value="{{ $plazo->monto_mora }}"  class="letrasModal form-control" required="true" />
                  </div>
              </div> --}}
              <div class="col-md-12">   
                  <div class="form-group">
                      <label for="notas">Notas</label>
                      <textarea name="notas" id="notas" value=""  class="letrasModal form-control">{{ $plazo->notas }}</textarea>
                  </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <a href="{{ route('welcome') }}" class="btn btn-dark btn-block">REGRESAR</a>
                </div>
              </div>
          </div>
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
                        </thead>
                        <tbody>
                          @php
                              $procedencia = 'Prospecto';
                          @endphp
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

@push('scripts')
@endpush 
@endsection