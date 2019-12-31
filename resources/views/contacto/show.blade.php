@extends('layouts.admin')
@section('title')
Contacto
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            {{ Form::open(array('action'=>array('ContactosController@update',$contacto->id_contacto, $procedencia),'method'=>'post','files'=>'true', 'id')) }}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nombre">*Nombre</label>
                        <input type="text" name="nombre" id="nombre" value="{{ $contacto->nombre }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="telefono">Telefono</label>
                        <input type="text" name="telefono" id="telefono" value="{{ $contacto->telefono }}"  class="letrasModal form-control" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="telefono_adcional">Telefono adicional</label>
                        <input type="text" name="telefono_adcional" id="telefono_adcional" value="{{ $contacto->telefono_adicional }}"  class="letrasModal form-control"  />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="correo">Correo</label>
                        <input type="email" name="correo" id="correo" value="{{ $contacto->correo }}"  class="letrasModal form-control" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="puesto">Puesto</label>
                        <input type="text" name="puesto" id="puesto" value="{{ $contacto->puesto }}"  class="letrasModal form-control"/>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="prospecto">Prospecto</label>
                        <select name="prospecto" id="prospecto" class="letrasModal form-control">
                          <option></option>
                          @foreach ($prospectos as $pros)
                              @if ($contacto->prospecto_id == $pros->id_prospecto)
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
                        <textarea name="notas" id="notas" value=""  class="letrasModal form-control">{{ $contacto->notas }}</textarea>
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