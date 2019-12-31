@extends('layouts.admin')
@section('title')
Estados
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            {{ Form::open(array('action'=>array('EstadoController@update',$estado->id_estado),'method'=>'post')) }}
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="estado">*Estado</label>
                        <input type="text" name="estado" id="estado" value="{{ $estado->estado }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="clave">*Clave</label>
                        <input type="text" name="clave" id="clave" value="{{ $estado->clave }}"  class="letrasModal form-control" maxlength="5" required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="pais">*Pais</label>
                        <select  name="pais" id="pais" class="letrasModal form-control" required="true"> 
                            @foreach ($paises as $pais)
                                @if ($pais->id_pais == $estado->pais_id)
                                    <option selected="true" class="letrasModal" value="{{ $pais->id_pais }}">{{ $pais->pais }}</option>
                                @else  
                                <option class="letrasModal" value="{{ $pais->id_pais }}">{{ $pais->pais }}</option>  
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div> 
                
                <div class="col-md-3 offset-md-3">
                    <div class="form-group">
                        <a href="{{ route('estado') }}" class="btn btn-dark btn-block">CANCELAR</a>
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