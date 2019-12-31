@extends('layouts.admin')
@section('title')
Estatus prospecto
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            {{ Form::open(array('action'=>array('EstatusProspectoController@update', $estatus_prospecto->id_estatus_crm),'method'=>'post')) }}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="estatus_prospecto">*Estatus prospecto</label>
                        <input type="text" name="estatus_prospecto" id="estatus_prospecto" value="{{ $estatus_prospecto->estatus_crm }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="nivel">*Nivel</label>
                        <input type="text" name="nivel" id="nivel" value="{{ $estatus_prospecto->nivel }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="limite">*Limite</label>
                        <input type="text" name="limite" id="limite" value="{{ $estatus_prospecto->limite }}"  class="letrasModal form-control" />
                    </div>
                </div>
                <div class="col-md-3 offset-md-3">
                    <div class="form-group">
                        <a href="{{ route('estatus_prospecto') }}" class="btn btn-dark btn-block">CANCELAR</a>
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