@extends('layouts.admin')
@section('title')
Motivo de perdida
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            {{ Form::open(array('action'=>array('MotivoPerdidaController@update', $motivo_perdida->id_motivo_perdida),'method'=>'post')) }}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="motivo_perdida">*Motivo de perida</label>
                        <input type="text" name="motivo_perdida" id="motivo_perdida" value="{{ $motivo_perdida->motivo_perdida }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                
                <div class="col-md-3 offset-md-3">
                    <div class="form-group">
                        <a href="{{ route('motivo_perdida') }}" class="btn btn-dark btn-block">CANCELAR</a>
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