@extends('layouts.admin')
@section('title')
Ciudad
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            {{ Form::open(array('action'=>array('CiudadController@update',$ciudad->id_ciudad),'method'=>'post')) }}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="ciudad">*Ciudad</label>
                        <input type="text" name="ciudad" id="ciudad" value="{{ $ciudad->ciudad }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="clave">*Clave</label>
                        <input type="text" name="clave" id="clave" value="{{ $ciudad->clave }}"  class="letrasModal form-control" maxlength="5" required="true" />
                    </div>
                </div>
                <div class="col-md-4">   
                    <div class="form-group">
                        <label for="ciudad">*Estado</label>
                        <select class="form-control" id="estado" name="estado">
                            @foreach ($estados as $estado)
                                @if ($estado->id_estado == $ciudad->estado_id)
                                    <option selected="true" class="letrasModal" value="{{ $estado->id_estado }}">{{ $estado->estado }}</option>
                                @else
                                    <option class="letrasModal" value="{{ $estado->id_estado }}">{{ $estado->estado }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-3 offset-md-3">
                    <div class="form-group">
                        <a href="{{ route('ciudades') }}" class="btn btn-dark btn-block">CANCELAR</a>
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