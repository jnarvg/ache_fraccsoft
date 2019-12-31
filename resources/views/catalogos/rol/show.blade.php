@extends('layouts.admin')
@section('title')
Roles
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            {{ Form::open(array('action'=>array('RolController@update',$rol->id),'method'=>'post')) }}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="rol">*Rol</label>
                        <input type="text" name="rol" id="rol" value="{{ $rol->rol }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>            
                <div class="col-md-2 offset-md-3">
                    <div class="form-group">
                        <a href="{{ route('rol') }}" class="btn btn-dark btn-block">REGRESAR</a>
                    </div>
                </div> 
                <div class="col-md-2">
                    <div class="form-group">
                        <button type="submit" class="btn btn-info btn-block">GUARDAR</button>
                    </div>
                </div>
            </div>
            {{ Form::close()}}
            <div class="card">
              <div class="card-header bg-primary text-white">Usuarios en el rol</div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover table-stripered">
                      <thead>
                          <tr>
                          <th class="center">Id</th>
                          <th class="center">Nombre</th>
                          <th class="center">Email</th>
                          <th class="center">Acciones</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach ($users as $u)
                          <tr>
                              <td class="center">
                                <a href="{{URL::action('AgenteController@show', $u->id)}}"  style="width: 30%;">
                                {{ $u->id }}</a>
                              </td>
                              <td class="center">
                                <a href="{{URL::action('AgenteController@show', $u->id)}}"  style="width: 30%;">{{ $u->name}}</a>
                              </td>
                              <td class="center">
                                <a href="{{URL::action('AgenteController@show', $u->id)}}"  style="width: 30%;">{{ $u->email}}</a>
                              </td>
                              <td class="center-acciones">
                                <a href="{{URL::action('AgenteController@show', $u->id)}}"  style="width: 30%;"><button class="btn-ico" ><i class="fas fa-pencil-alt"></i></button></a>
                              </td>
                          </tr>
                          @endforeach
                      </tbody>
                  </table>
                </div>
              </div>
              {{$users->render()}}
            </div>       
        </div>
    </div>
</div>

@push('scripts')
@endpush 
@endsection