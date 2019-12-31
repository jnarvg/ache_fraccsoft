@extends('layouts.admin')
@section('title')
Medio de contacto
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            {{ Form::open(array('action'=>array('MedioContactoController@update', $medio_contacto->id_medio_contacto),'method'=>'post')) }}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="medio_contacto">*Medio de contacto</label>
                        <input type="text" name="medio_contacto" id="medio_contacto" value="{{ $medio_contacto->medio_contacto }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                
                <div class="col-md-3 offset-md-3">
                    <div class="form-group">
                        <a href="{{ route('medio_contacto') }}" class="btn btn-dark btn-block">CANCELAR</a>
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