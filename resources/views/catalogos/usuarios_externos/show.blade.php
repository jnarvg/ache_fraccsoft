@extends('layouts.admin')
@section('title')
Usuarios y agentes
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            {{ Form::open(array('action'=>array('AgenteController@update',$usuario->id),'method'=>'post')) }}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nombre-user">*Nombre de usuario</label>
                        <input type="text" name="nombre-user" id="nombre-user" value="{{ $usuario->name }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nombre-email">*Email de usuario</label>
                        <input type="text" name="nombre-email" id="nombre-email" value="{{ $usuario->email }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nombre-pass">*Password</label>
                        <input type="password" name="nombre-pass" id="nombre-pass" value="{{ $passsinHash }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nombre-estatus">*Estatus</label>
                        <input type="text" name="nombre-estatus" id="nombre-estatus" value="{{ $usuario->estatus }}"  class="letrasModal form-control" readonly="true" required="true" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nombre-rol">*Rol</label>
                        <select name="nombre-rol" id="nombre-rol"  class="letrasModal form-control" required="true">
                            @foreach ($roles as $r)
                                @if ($r->id == $usuario->rol_id)
                                    <option selected="true" value="{{ $r->id }}">{{ $r->rol }}</option>
                                @else
                                    <option value="{{ $r->id }}">{{ $r->rol }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-3 offset-md-3">
                    <div class="form-group">
                        <a href="{{ route('usuarios_externos') }}" class="btn btn-dark btn-block">CANCELAR</a>
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