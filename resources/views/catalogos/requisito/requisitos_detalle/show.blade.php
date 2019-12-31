@extends('layouts.admin')
@section('title')
Requisito detalle
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            {{ Form::open(array('action'=>array('RequisitoDetalleController@update',$requisito_detalle->id_requisito_detalle),'method'=>'post')) }}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="requisito_detalle">*Requisito</label>
                        <input type="text" name="requisito_detalle" id="requisito_detalle" value="{{ $requisito_detalle->requisito }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>            
                <div class="col-md-3 offset-md-3">
                    <div class="form-group">
                        <a href="{{ route('requisito-show',["id"=>$requisito_detalle->requisito_id]) }}" class="btn btn-dark btn-block">CANCELAR</a>
                    </div>
                </div> 
                <div class="col-md-3">
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
@endpush 
@endsection