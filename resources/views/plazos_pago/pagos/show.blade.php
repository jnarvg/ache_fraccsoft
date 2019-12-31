@extends('layouts.admin')
@section('css')
    <link href="{{ asset('css/dropzone.css') }}" rel="stylesheet">
@endsection
@section('title')
Pagos de plazo
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
          @if (session()->has('msj'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>{{ session('msj') }}</strong>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          @endif
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="fecha">*Fecha</label>
                        <input type="date" name="fecha" id="fecha" value="{{
                            date('Y-m-d',strtotime($pago->fecha)) }}"  class="letrasModal form-control" required="true"  readonly="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="estatus">*Estatus</label>
                        <select name="estatus" id="estatus" class="letrasModal form-control" readonly="true" >
                            <option selected="true" value="{{ $pago->estatus }}">{{ $pago->estatus }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="monto">*Monto</label>
                        <input type="number" name="monto" step="any" id="monto" value="{{ $pago->monto }}"  class="letrasModal form-control" required="true" readonly="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="forma_pago">*Forma de pago</label>
                        <select name="forma_pago" id="forma_pago" class="letrasModal form-control" readonly = "true">
                            <option selected="true" value="{{ $pago->forma_pago_id }}">{{ $pago->forma_pago }}</option>
                        </select>
                    </div>
                </div>
                @if ($pago->estatus == 'Cancelado')
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_cancelacion">Fecha de cancelacion</label>
                            <input type="date" name="fecha_cancelacion" id="fecha_cancelacion" value="{{
                                date('Y-m-d',strtotime($pago->fecha_cancelacion)) }}"  class="letrasModal form-control" required="true" />
                        </div>
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="col-md-3 offset-md-3">
                    @if ($procedencia == 'Menu')
                        <div class="form-group">
                            <a href="{{ route('pagos') }}" class="btn btn-dark btn-block">REGRESAR</a>
                        </div>
                    @else
                        <div class="form-group">
                            <a href="{{ route('plazos_pago-show',[$pago->plazo_pago_id, $procedencia]) }}" class="btn btn-dark btn-block">REGRESAR</a>
                        </div>
                    @endif
                </div>
                @if ($pago->estatus == 'Aplicado')
                    <div class="col-md-3">
                        <div class="form-group">
                            <a href="{{ route('pagos-cancelar',['id' => $pago->id_pago, 'procedencia' => $procedencia]) }}" id="btncancelar" class="btn btn-danger btn-block">CANCELAR</a> 
                        </div>
                    </div>
                @endif
                <div class="col-md-3">
                    <div class="form-group">
                        <a href="{{ route('pagos-recibo',['id' => $pago->id_pago, 'procedencia' => $procedencia]) }}" id="btncancelar" class="btn btn-danger btn-block">RECIBO</a> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
@endpush 
@endsection