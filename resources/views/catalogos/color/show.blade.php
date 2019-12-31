@extends('layouts.admin')
@section('title')
Colores
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            {{ Form::open(array('action'=>array('ColoresController@update', $color->id_color),'method'=>'post')) }}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="color">*Color</label>
                        <input type="text" name="color" id="color" value="{{ $color->color }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="codigo_hexadecimal">*Codigo</label>
                        <input type="color" name="codigo_hexadecimal" id="codigo_hexadecimal" value="{{ $color->codigo_hexadecimal }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                
                <div class="col-md-3 offset-md-3">
                    <div class="form-group">
                        <a href="{{ route('color') }}" class="btn btn-dark btn-block">CANCELAR</a>
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