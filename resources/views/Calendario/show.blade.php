@extends('layouts.admin')
@section('css')
    <link href="{{ asset('css/dropzone.css') }}" rel="stylesheet">
@endsection
@section('title')
Calendario
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            {{ Form::open(array('action'=>array('CalendarioController@update',$calendario->id),'method'=>'post','files'=>'true', 'id')) }}
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="titulo">Titulo</label>
                        <input type="text" name="titulo" id="titulo" value="{{ $calendario->title }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                
                </div>
                <div class="row">
                    <div class="col-md-6">   
                    <div class="form-group">
                        <label for="notas">Fecha Inicial</label>
                        <input type="date" name="fechaI" id="fechaI" value="{{ $calendario->start_date }}" class="letrasModal form-control" required="true" />
                        <input type="time" name="horaI" min="06:00:00" max="20:00:00" >   
                    </div>
                </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fecha">Fecha Final</label>
                          <input type="date" name="fechaF" id="fechaF" value="{{ $calendario->end_date }}" class="letrasModal form-control" required="true" />
                          <input type="time" name="horaF" min="06:00:00" max="20:00:00" >
                        </div>
                    </div>
                </div>
                <div class="row">
                <div class="col-md-3 offset-md-3">
                    <div class="form-group">
                        <a href="{{ route('calendarios') }}" class="btn btn-grubsa-danger btn-block">CANCELAR</a>
                    </div>
                </div> 
                <div class="col-md-3">
                    <div class="form-group">
                        <button type="submit" id="submit" class="btn btn-grubsa btn-block">GUARDAR</button>
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