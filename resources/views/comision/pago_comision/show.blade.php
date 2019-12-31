@extends('layouts.admin')
@section('title')
Pago Comision
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="rubro">*Estatus</label>
                        <input type="text" name="rubro" id="rubro" value="{{ $pago_comision->estatus }}"  class="letrasModal form-control" required="true" readonly="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="tipo">*Fecha de pago</label>
                        <input type="text" name="tipo" id="tipo" value="{{ $pago_comision->fecha_pago }}"  class="letrasModal form-control" required="true" readonly="true"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="estatus">*Monto</label>
                        <input type="text" name="estatus" id="estatus" value="{{ $pago_comision->monto }}"  class="mask form-control" required="true" readonly="true"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="estatus_pago">*Forma de pago</label>
                        <input type="text" name="estatus_pago" id="estatus_pago" value="{{ $pago_comision->forma_pago }}"  class="letrasModal form-control" required="true" readonly="true" />
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="total_venta">Descripcion</label>
                        <input type="text" name="total_venta" id="total_venta" value="{{ $pago_comision->descripcion }}"  class="letrasModal form-control" required="true" readonly="true"/>
                    </div>
                </div>
            </div>
            <div class="row">    
                <div class="col-md-3 offset-md-6">
                    <div class="form-group">
                        <a href="{{ route('comision_detalle-show',['id'=>$pago_comision->comision_detalle_id]) }}" class="btn btn-dark btn-block">REGRESAR</a>
                    </div>
                </div>
                @if ($pago_comision->estatus == 'Aplicado')
                    <div class="col-md-3">
                        <div class="form-group">
                            <a href="{{ route('cancelar_pago_comision',['id' => $pago_comision->id_pago_comision]) }}" id="btnVisita" class="btn btn-danger btn-block">CANCELAR</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
@endpush 
@endsection