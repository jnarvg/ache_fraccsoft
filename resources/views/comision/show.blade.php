@extends('layouts.admin')
@section('title')
Comisiones
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="prospecto">*Cliente</label>
                        <input type="text" name="prospecto" id="prospecto" value="{{ $comision->prospecto }}"  class="letrasModal form-control" required="true" readonly="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="propiedad">*Propiedad</label>
                        <input type="text" name="propiedad" id="propiedad" value="{{ $comision->propiedad }}"  class="letrasModal form-control" required="true" readonly="true"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="estatus">*Estatus</label>
                        <input type="text" name="estatus" id="estatus" value="{{ $comision->estatus }}"  class="letrasModal form-control" required="true" readonly="true"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="estatus_pago">*Estatus pago</label>
                        <input type="text" name="estatus_pago" id="estatus_pago" value="{{ $comision->estatus_pago }}"  class="letrasModal form-control" required="true" readonly="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="monto_operacion">*Monto operacion</label>
                        <input type="text" name="monto_operacion" id="monto_operacion" value="{{ $comision->monto_operacion }}"  class="mask form-control" required="true" readonly="true"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="fecha_venta">*Fecha de vente</label>
                        @if ($comision->fecha_venta != null)
                          <input type="date" name="fecha_venta" id="fecha_venta" value="{{ $comision->fecha_venta }}"  class="letrasModal form-control" required="true" readonly="true"/>
                        @else
                          <input type="date" name="fecha_venta" id="fecha_venta" value=""  class="letrasModal form-control" required="true" readonly="true"/>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="comision">*Comision total</label>
                        <input type="text" step="any" name="comision" id="comision" value="{{ $comision->comision_total }}"  class="mask form-control" required="true" readonly="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="saldo_comision">*Saldo comision</label>
                        <input type="text" name="saldo_comision" id="saldo_comision" value="{{ $comision->saldo_comision }}"  class="mask form-control" required="true" readonly="true"/>
                    </div>
                </div>
            </div>
            <div class="row">    
                <div class="col-md-3 offset-md-6">
                    <div class="form-group">
                        <a href="{{ route('comision') }}" class="btn btn-dark btn-block">REGRESAR</a>
                    </div>
                </div> 
                <div class="col-md-3">
                    <div class="form-group">
                        <a href="{{ route('comision-aprobar',['id'=>$comision->id_comision]) }}" class="btn btn-success btn-block">APROBAR</a>
                    </div>
                </div>
                {{-- <div class="col-md-3">
                    <div class="form-group">
                        <a href="{{ route('comision-recalcular',['id'=>$comision->id_comision]) }}" class="btn btn-info btn-block">RECALCULAR</a>
                    </div>
                </div> --}}
            </div>
            <div class="card">
              <div class="card-header bg-primary text-white">
                Comisiones
              </div>
              <div class="card-body">
                <div class="table-responsive ">
                  <table class="table table-hover table-withoutborder text-sm">
                    <thead class="thead-grubsa ">
                      <th class="center">
                        Rubro
                      </th>
                      <th class="center">
                        Tipo
                      </th>
                      <th class="center">
                        Persona
                      </th>
                      <th class="center">
                        Factor
                      </th>
                      <th class="center">
                        Comision
                      </th>
                      <th class="center">
                        Saldo
                      </th>
                      <th class="center">
                        Acciones
                      </th>
                    </thead>
                    <tbody>
                      @foreach ($comisiones_detalle as $cd)                
                      <tr>
                        <td class="center">
                          <a class="text-dark" href="{{URL::action('ComisionDetalleController@show', $cd->id_comision_detalle)}}"  style="width: 30%;">
                          {{ $cd->rubro}}</a>
                        </td>
                        <td class="center">
                          <a class="text-dark" href="{{URL::action('ComisionDetalleController@show', $cd->id_comision_detalle)}}"  style="width: 30%;">
                          {{ $cd->tipo}}</a>
                        </td>
                        <td class="center">
                          <a class="text-dark" href="{{URL::action('ComisionDetalleController@show', $cd->id_comision_detalle)}}"  style="width: 30%;">
                          {{ $cd->persona}}</a>
                        </td>
                        <td class="center">
                          <a class="text-dark" href="{{URL::action('ComisionDetalleController@show', $cd->id_comision_detalle)}}"  style="width: 30%;">
                          {{ $cd->factor}}</a>
                        </td>
                        <td class="center">
                          <a class="text-dark" href="{{URL::action('ComisionDetalleController@show', $cd->id_comision_detalle)}}"  style="width: 30%;">
                          $ {{ number_format($cd->comision , 2 , "." , ",") }}
                          </a>
                        </td>
                        <td class="center">
                          <a class="text-dark" href="{{URL::action('ComisionDetalleController@show', $cd->id_comision_detalle)}}"  style="width: 30%;">
                          $ {{ number_format($cd->saldo_comision , 2 , "." , ",") }}</a>
                        </td>
                        <td class="center-acciones">
                          <a href="{{URL::action('ComisionDetalleController@show', $cd->id_comision_detalle)}}"  style="width: 30%;"><button class="btn-ico" ><i class="fas fa-pencil-alt"></i></button></a>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            
            {{$comisiones_detalle->render()}}
        </div>
    </div>
</div>

@push('scripts')
@endpush 
@endsection