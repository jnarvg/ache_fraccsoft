@extends('layouts.admin')
@section('title')
Requisitos en prospecto
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            {{ Form::open(array('action'=>array('RequisitosProspectoController@update',$requisito_prospecto->id_requisito_prospecto),'method'=>'post', 'files'=>true)) }}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="requisito">Requisito</label>
                        <input type="text" name="requisito" id="requisito" value="{{ $requisito_prospecto->requisito }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="estatus">Estatus</label>
                        @if ($requisito_prospecto->estatus == 'Pendiente')
                            <div class="progress">
                              <div class="progress-bar bg-danger" style="width:70%">{{ $requisito_prospecto->estatus }}</div>
                            </div>
                        @elseif($requisito_prospecto->estatus == 'Completado')
                            <div class="progress">
                              <div class="progress-bar bg-success" style="width:100%">{{ $requisito_prospecto->estatus }}</div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="archivo">Documento</label>
                        @if ($requisito_prospecto->archivo != null )
                            <a href="{{ url('storage',$requisito_prospecto->archivo) }}" target="_bank" class="btn-block archivo-link">{{ $requisito_prospecto->archivo }}</a>
                        @else
                            <input  class="archivo" name="archivo" type="file"/>
                        @endif
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="comentario">Comentario</label>
                        <textarea name="comentario" id="comentario" class="letrasModal form-control">{{ $requisito_prospecto->comentario }}</textarea>
                    </div>
                </div>
                <div class="col-md-4 offset-md-2">
                    <div class="form-group">
                        <a href="{{ url('prospectos/show',$requisito_prospecto->prospecto_id) }}" class="btn btn-dark btn-block">CANCELAR</a>
                    </div>
                </div> 
                <div class="col-md-4">
                    <div class="form-group">
                        <button type="submit" class="btn btn-info btn-block">GUARDAR</button>
                    </div>
                </div>
            </div>
            {{ Form::close()}}
            <div class="row">
                @if ($requisito_prospecto->estatus=='Pendiente')
                <div class="col-md-4 offset-md-4">
                    <div class="form-group">
                        <a href="{{URL::action('RequisitosProspectoController@completar', $requisito_prospecto->id_requisito_prospecto)}}"  style="width: 30%;"><button class="btn btn-primary btn-block">Completar</button></a>
                    </div>
                </div>
                @else
                <div class="col-md-4 offset-md-4">
                    <div class="form-group">
                        <a href="{{URL::action('RequisitosProspectoController@pendiente', $requisito_prospecto->id_requisito_prospecto)}}"  style="width: 30%;"><button class="btn btn-primary btn-block">Pendiente</button></a>
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