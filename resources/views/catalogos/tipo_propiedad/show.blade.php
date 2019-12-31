@extends('layouts.admin')
@section('title')
Tipo de propiedad sistema
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            {{ Form::open(array('action'=>array('TipoPropiedadController@update',$tipoPropiedad->id_tipo_propiedad),'method'=>'post')) }}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="tipo_propiedad">*Tipo de propiedad</label>
                        <input type="text" name="tipo_propiedad" id="tipo_propiedad" value="{{ $tipoPropiedad->tipo_propiedad }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                
                <div class="col-md-3 offset-md-3">
                    <div class="form-group">
                        <a href="{{ route('tipo_propiedad') }}" class="btn btn-dark btn-block">CANCELAR</a>
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