@extends('layouts.admin')
@section('title')
Forma de pago
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            {{ Form::open(array('action'=>array('FormaPagoController@update',$forma_pago->id_forma_pago),'method'=>'post')) }}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="forma_pago">*Forma de pago</label>
                        <input type="text" name="forma_pago" id="forma_pago" value="{{ $forma_pago->forma_pago }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-3 offset-md-3">
                    <div class="form-group">
                        <a href="{{ route('forma_pago') }}" class="btn btn-dark btn-block">CANCELAR</a>
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