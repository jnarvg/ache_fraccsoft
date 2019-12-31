@extends('layouts.admin')
@section('title')
Mensaje
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            {{ Form::open(array('action'=>array('MensajeController@update', $m->id_mensaje),'method'=>'post')) }}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="titulo">*Titulo</label>
                        <input type="text" name="titulo" id="titulo" value="{{ $m->titulo }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="fecha">*Fecha</label>
                        <input type="text" name="fecha" id="fecha" value="{{ $m->fecha }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                      <label for="estatus">*Estatus</label>
                      <input type="text" step="any" name="estatus" id="estatus" value="{{ $m->estatus }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                      <label for="creador">*Creador por</label>
                      <select name="creador" id="creador" class="letrasModal form-control" required="true" readonly>
                        @foreach ($usuarios as $u)
                            @if ($u->id == $m->creador_id )
                                <option selected value="{{ $u->id }}">{{ $u->name }}</option>
                            @else
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endif
                        @endforeach
                      </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="dirigido">*Dirigido a</label>
                        <select name="dirigido" id="dirigido" class="letrasModal form-control" required="true">
                            @foreach ($usuarios as $u)
                                @if ($u->id == $m->dirigido_id )
                                    <option selected value="{{ $u->id }}">{{ $u->name }}</option>
                                @else
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="nota">Notas</label>
                        <textarea name="nota" id="nota" rows="10" class="letrasModal form-control">{{ $m->nota }}</textarea>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="prospecto">*Prospecto</label>
                        <select name="prospecto" id="prospecto" class="letrasModal form-control">
                          @foreach ($prospectos as $pros)
                            @if ($pros->id_prospecto == $m->prospecto_id )
                                <option selected value="{{ $pros->id_prospecto }}">{{ $pros->nombre }}</option>
                            @else
                                <option value="{{ $pros->id_prospecto }}">{{ $pros->nombre }}</option>
                            @endif
                          @endforeach
                        </select>
                    </div>
                </div> 
                <div class="col-md-3 offset-md-3">
                    @if ($ruta == 'mensaje')
                    <div class="form-group">
                        <a href="{{ url($ruta) }}" class="btn btn-dark btn-block">SALIR</a>
                    </div>
                    @else
                    <div class="form-group">
                        <a href="{{ url('/prospectos/show/'.$m->prospecto_id.'/'.$ruta) }}" class="btn btn-dark btn-block">SALIR</a>
                    </div>
                    @endif
                </div> 
                <div class="col-md-3">
                    <div class="form-group">
                        <button type="submit" class="btn btn-info btn-block">GUARDAR</button>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a href="{{ route('mensaje-enviar', [$m->id_mensaje]) }}" class="btn btn-success btn-block">ENVIAR</a>
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