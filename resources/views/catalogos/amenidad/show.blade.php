@extends('layouts.admin')
@section('title')
Amenidades
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            {{ Form::open(array('action'=>array('AmenidadController@update',$amenidad->id_amenidad),'method'=>'post')) }}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="amenidad">*Amenidad</label>
                        <input type="text" name="amenidad" id="amenidad" value="{{ $amenidad->amenidad }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-3 offset-md-3">
                    <div class="form-group">
                        <a href="{{ route('amenidades') }}" class="btn btn-dark btn-block">CANCELAR</a>
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