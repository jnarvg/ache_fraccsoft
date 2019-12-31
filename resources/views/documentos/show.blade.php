@extends('layouts.admin')
@section('css')
    <link href="{{ asset('css/dropzone.css') }}" rel="stylesheet">
@endsection
@section('title')
Documento
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            {{ Form::open(array('action'=>array('FileController@update',$documento->id_documento, $procedencia),'method'=>'post','files'=>'true', 'id')) }}
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="titulo">*Titulo</label>
                        <input type="text" name="titulo" id="titulo" value="{{ $documento->titulo }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="fecha">Fecha</label>
                        <input type="date" name="fecha" id="fecha" value="{{
                            date('Y-m-d',strtotime($documento->fecha)) }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="archivo">Documento</label>
                        @if ($documento->archivo != null )
                            <a href="{{ url('storage',$documento->archivo) }}" target="_bank" class="btn-block archivo-link">{{ $documento->archivo }}</a>
                        @else
                            <input  class="archivo" name="archivo" type="file"/>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="prospecto">*Prospecto</label>
                        <select name="prospecto" id="prospecto" class="letrasModal form-control">
                          @foreach ($prospectos as $pros)
                              @if ($documento->prospecto_id == $pros->id_prospecto)
                                  <option selected="true" value="{{ $pros->id_prospecto }}">{{ $pros->nombre }}</option>
                              @else
                                  <option value="{{ $pros->id_prospecto }}">{{ $pros->nombre }}</option>
                              @endif
                          @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-12">   
                    <div class="form-group">
                        <label for="notas">Notas</label>
                        <textarea name="notas" id="notas" value=""  class="letrasModal form-control">{{ $documento->notas }}</textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 offset-md-3">
                    <div class="form-group">
                        <a href="{{ URL::previous() }}" class="btn btn-dark btn-block">CANCELAR</a>
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