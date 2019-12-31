@extends('layouts.admin')
@section('title')
Amenidad en la propiedad
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            {{ Form::open(array('action'=>array('AmenidadPropiedadController@update',$a->id_amenidad_propiedad),'method'=>'post')) }}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="amenidad_edit">*Amenidad</label>
                        <select class="form-control" id="amenidad_edit" name="amenidad_edit">
                            @foreach ($amenidades as $am)
                                @if ($am->id_amenidad == $a->amenidad_id)
                                    <option selected="true" class="letrasModal" value="{{ $am->id_amenidad }}">{{ $am->amenidad }}</option>
                                @else  
                                <option class="letrasModal" value="{{ $am->id_amenidad }}">{{ $am->amenidad }}</option>  
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-3 offset-md-3">
                    <div class="form-group">
                        <a href="{{ route('propiedades-show',[$a->propiedad_id,'propiedades','Menu']) }}" class="btn btn-dark btn-block">REGRESAR</a>
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