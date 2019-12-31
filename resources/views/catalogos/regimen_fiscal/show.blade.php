@extends('layouts.admin')
@section('title')
Regimen fiscal
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
          {{ Form::open(array('action'=>array('RegimenFiscalController@update',$regimen->id_regimen_fiscal),'method'=>'post','files'=>true)) }}
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="clave">*Clave</label>
                        <input type="text" name="clave" id="clave" value="{{ $regimen->clave }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="regimen_fiscal">*Regimen fisca</label>
                        <input type="text" name="regimen_fiscal" id="regimen_fiscal" value="{{ $regimen->regimen_fiscal }}"  class="letrasModal form-control"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="concatenado">*Clave - Regimen fiscal</label>
                        <input type="text" name="concatenado" id="concatenado" value="{{ $regimen->concatenado }}"  class="letrasModal form-control"  />
                    </div>
                </div>
            </div>
            <div class="row">    
                <div class="col-md-4">
                    <div class="form-group">
                        <a href="{{ route('regimen_fiscal') }}" class="btn btn-dark btn-block">REGRESAR</a>
                    </div>
                </div>
                <div class="col-md-4">
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