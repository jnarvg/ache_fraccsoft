@extends('layouts.admin')
@section('title')
Banco
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
          {{ Form::open(array('action'=>array('BancoController@update',$banco->id_banco),'method'=>'post','files'=>true)) }}
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="banco">*Banco</label>
                        <input type="text" name="banco" id="banco" value="{{ $banco->banco }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="razon_social">*Razón social</label>
                        <input type="text" name="razon_social" id="razon_social" value="{{ $banco->razon_social }}"  class="letrasModal form-control"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="rfc">RFC</label>
                        <input type="text" name="rfc" id="rfc" value="{{ $banco->rfc }}"  class="letrasModal form-control"  />
                    </div>
                </div>
            </div>
            <div class="row">    
                <div class="col-md-3 offset-md-3">
                    <div class="form-group">
                        <a href="{{ route('banco') }}" class="btn btn-dark btn-block">REGRESAR</a>
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