@extends('layouts.admin')
@section('title')
Paises
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            {{ Form::open(array('action'=>array('PaisController@update',$pais->id_pais),'method'=>'post')) }}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="pais">*Pais</label>
                        <input type="text" name="pais" id="pais" value="{{ $pais->pais }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="clave">*Clave</label>
                        <input type="text" name="clave" id="clave" value="{{ $pais->clave }}" maxlength="5" class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-3 offset-md-3">
                    <div class="form-group">
                        <a href="{{ route('pais') }}" class="btn btn-dark btn-block">CANCELAR</a>
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