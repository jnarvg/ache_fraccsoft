@extends('layouts.admin')
@section('title')
Plazos de pago
@endsection
@section('filter')
  <a href="#" data-toggle="modal" data-target="#modal-plazo_pago"><button class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus"></i></button></a>
  {{ Form::open(array('action'=>array('PlazosPagoController@exportExcel'),'method'=>'get', 'class'=>'d-sm-inline-block')) }}
      <input type="hidden" class="form-control" placeholder="Nombre" name="fecha_min_excel" id="fecha_min_excel" value="{{ $request->fecha_min_bs }}">
      <input type="hidden" class="form-control" placeholder="Nombre" name="fecha_max_excel" id="fecha_max_excel" value="{{ $request->fecha_max_bs }}">
      <input type="hidden" class="form-control" placeholder="Correo" name="numero_excel" id="numero_excel" value="{{ $request->numero_bs }}">
      <input type="hidden" class="form-control" placeholder="Agente" name="prospecto_excel" id="prospecto_excel" value="{{ $request->prospecto_bs }}">
      <input type="hidden" class="form-control" placeholder="Estatus" name="estatus_excel" id="estatus_excel" value="{{ $request->estatus_bs }}">
      <input type="hidden" class="form-control" placeholder="Estatus" name="tipo_excel" id="tipo_excel" value="{{ $request->tipo_bs }}">
      <button class="btn btn-primary d-sm-inline-block btn-sm shadow-sm"><i class="fas fa-cloud-download-alt text-xl"></i></button>
  {!! Form::close() !!}
  <button href="#" data-toggle="collapse" data-target="#filtros" role="button" aria-expanded="false" aria-controls="filtros" class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-filter"></i></button>
@endsection
@section('content')
<div class="content mt-3">
  @php
    $procedencia = 'Menu';
  @endphp
  <div class="card collapse" id="filtros">
    <div class="card-body">
      {!! Form::open( ['route'=>'plazos_pago', 'method'=>'get']) !!}
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label for="numero_bs">Numero</label>
              <input type="text" class="form-control" placeholder="Numero" name="numero_bs" id="numero_bs" value="{{ $request->numero_bs }}">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="prospecto_bs">Cliente</label>
              <input type="text" class="form-control" placeholder="Prospecto" name="prospecto_bs" id="prospecto_bs" value="{{ $request->prospecto_bs }}">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="estatus_bs">Estatus</label>
              <select class="form-control" name="estatus_bs" id="estatus_bs">
                <option value=""></option>
                @foreach ($estatus as $rp)
                @if ($rp->estatus == $request->estatus_bs)
                <option selected value="{{ $rp->estatus }}">{{ $rp->estatus }}</option>
                @else
                <option value="{{ $rp->estatus }}">{{ $rp->estatus }}</option>
                @endif
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="tipo_bs">Tipo</label>
              <select class="form-control" name="tipo_bs" id="tipo_bs">
                <option value=""></option>
                @foreach ($tipos as $rp)
                @if ($rp->tipo == $request->tipo_bs)
                <option selected value="{{ $rp->tipo}}">{{ $rp->tipo}}</option>
                @else
                <option value="{{ $rp->tipo}}">{{ $rp->tipo}}</option>
                @endif
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Fecha</label><br>
              <label>De
                <input type="date" class="form-control" value="{{ $request->fecha_min_bs }}" name="fecha_min_bs" id="fecha_min_bs" >
                al
                <input type="date" class="form-control" value="{{ $request->fecha_max_bs }}" name="fecha_max_bs" id="fecha_max_bs" >
              </label>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="rows_per_page">Show</label>
              <select class="form-control" name="rows_per_page" id="rows_per_page">
                <option value=""></option>
                @foreach ($rows_pagina as $rp)
                @if ($rp == $request->rows_per_page)
                <option selected value="{{ $rp }}">{{ $rp }}</option>
                @else
                <option value="{{ $rp }}">{{ $rp }}</option>
                @endif
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <button type="submit" class="btn btn-info down" ><span class="glyphicon"><i class="fas fa-search"></i></span></button>
            </div>
          </div>
        </div>
      {!! Form::close() !!}
    </div>
  </div>
  <div class="card mt-3">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover table-withoutborder text-sm">
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
              Descripcion
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
              Prospecto
            </th>
            <th class="center">
             Acciones
            </th>
          </thead>
          <tbody >
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
                <a href="{{URL::action('PlazosPagoController@show', [$pp->id_plazo_pago, $procedencia])}}">
                  {{ $pp->num_plazo }}
                </a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('PlazosPagoController@show', [$pp->id_plazo_pago, $procedencia])}}">
                  {{ date('Y-m-d',strtotime($pp->fecha)) }}
                </a>
              </td>
              <td class="center">
                <a href="{{URL::action('PlazosPagoController@show', [$pp->id_plazo_pago, $procedencia])}}">
                    {{ $pp->tipo }}
                </a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('PlazosPagoController@show', [$pp->id_plazo_pago, $procedencia])}}">
                    {{ $pp->descripcion }}
                </a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('PlazosPagoController@show', [$pp->id_plazo_pago, $procedencia])}}">
                  {{ $pp->estatus }}
                </a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('PlazosPagoController@show', [$pp->id_plazo_pago, $procedencia])}}">
                  $ {{ number_format($pp->total , 2 , "." , ",") }}
                </a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('PlazosPagoController@show', [$pp->id_plazo_pago, $procedencia])}}">
                  $ {{ number_format($pp->saldo , 2 , "." , ",") }}
                </a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('PlazosPagoController@show', [$pp->id_plazo_pago, $procedencia])}}">
                  $ {{ number_format($pp->pagado , 2 , "." , ",") }}
                </a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('PlazosPagoController@show', [$pp->id_plazo_pago, $procedencia])}}">
                  {{ $pp->nombre }}
                </a>
              </td>
              <td class="center-acciones">
                <a href="{{URL::action('PlazosPagoController@show', [$pp->id_plazo_pago, $procedencia])}}"  style="width: 30%;"><button class="btn-ico" ><i class="fas fa-pencil-alt"></i></button></a>
                @if (auth()->user()->rol=='3')
                <a href="#" data-target="#modal-delete{{$pp->id_plazo_pago}}" data-toggle="modal"  style="width: 30%;"><button class="btn-ico" ><i class="fas fa-trash-alt"></i></button></a> 
                @endif
              </td>
            </tr>
            @include('plazos_pago.modal')
            @endforeach
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
                </th>
                <th class="center">
                </th>
              </tr>
            </tfoot>
           </tbody>
        </table>
      </div>
    </div>
  </div>
  {{$plazos_pago->appends(Request::only(['numero_bs','fecha_bs','prospecto_bs','estatus_bs','rows_per_page','fecha_max_bs','fecha_min_bs','tipo_bs']))->render()}}
</div>
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-plazo_pago">
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
                          <input type="text" step="any" name="nuevo_total" id="nuevo_total" value=""  class="mask form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="nuevo_interes">*Interes</label>
                          <input type="number" step="any" name="nuevo_interes" id="nuevo_interes" max="100" value="0.00"  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="nuevo_moneda">*Moneda</label>
                          <select name="nuevo_moneda" id="nuevo_moneda" class="letrasModal form-control" required="true">
                            @foreach ($monedas as $m)
                                <option value="{{ $m->id_moneda }}">{{ $m->siglas }}</option>
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
                            <option value="{{ $pros->id_prospecto }}">{{ $pros->nombre }}</option>
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
@push('scripts')
@endpush 
@endsection