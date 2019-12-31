@extends('layouts.admin')
@section('title')
Usuarios y agentes
@endsection
@section('filter')
  @if (auth()->user()->rol == 3)
  <a href="#" data-toggle="modal" data-target="#modal-agente"><button class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus"></i></button></a>
  @endif
  <button href="#" data-toggle="collapse" data-target="#filtros" role="button" aria-expanded="false" aria-controls="filtros" class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-filter"></i></button>
@endsection
@section('content')
<div class="content mt-3">
  <div class="card collapse" id="filtros">
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
        {!! Form::open(array('route'=>'usuarios', 'method'=>'get', 'autocomplete'=>'off')) !!}
          <div class="input-group md-form form-sm form-4 pl-0">

            <input type="text" class="form-control" placeholder="Nombre" name="search" id="search" value="{{ $request->search }}">
            <select class="form-control" id="rol_bs" name="rol_bs">
              <option value="">Rol...</option>
              @foreach ($roles as $e)
                @if ($e->rol == $request->rol_bs)
                <option selected value="{{ $e->rol }}">{{ $e->rol }}</option>
                @else
                <option value="{{ $e->rol }}">{{ $e->rol }}</option>
                @endif
              @endforeach
            </select>
            <select class="form-control" id="estatus_bs" name="estatus_bs">
              <option value="">Estatus...</option>
              @foreach ($estatus as $e)
                @if ($e == $request->estatus_bs)
                <option selected value="{{ $e }}">{{ $e }}</option>
                @else
                <option value="{{ $e }}">{{ $e }}</option>
                @endif
              @endforeach
            </select>
            <button type="submit" class="btn btn-info" ><span class="glyphicon"><i class="fas fa-search"></i></span></button>
          </div>
        {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-body">
      <div class="table-responsive ">
        <table class="table table-hover table-withoutborder text-sm" id="tabla">
          <thead class="thead-grubsa ">
            <th class="center">
              ID
            </th>
            <th class="center">
              Agente
            </th>
            <th class="center">
              Email
            </th>
            <th class="center">
              Rol
            </th>
            <th class="center">
              Estatus
            </th>
            <th class="center">
              Acciones
            </th>
          </thead>
          <tbody>
            @foreach ($usuarios as $us)                
            <tr>
              <td class="center">
                {{ $us->id }}
              </td>
              <td class="center">
                {{ $us->name }}
              </td>
              <td class="center">
                {{ $us->email}}
              </td>
              <td class="center"> 
                {{ $us->rol}}
              </td>
              <td class="center">                
                {{ $us->estatus}}
              </td>
              <td class="center-acciones">
                @if (auth()->user()->rol == 3 and $us->id != 1)
                  <a href="{{URL::action('AgenteController@show', $us->id)}}"><button class="btn-ico" ><i class="fas fa-pencil-alt"></i></button></a>
                  @if ($us->estatus == 'Activo')
                    <a href="{{URL::action('AgenteController@inactiva', $us->id)}}"><button class="btn-ico" ><i class="fas fa-user-times"></i></button></a>
                  @elseif($us->estatus == 'Inactivo')
                    <a href="{{URL::action('AgenteController@activa', $us->id)}}"><button class="btn-ico" ><i class="fas fa-user-check"></i></button></a>
                  @else
                    <a href="{{URL::action('AgenteController@activa', $us->id)}}"><button class="btn-ico" ><i class="fas fa-user-check"></i></button></a>
                  @endif
                @elseif(auth()->user()->rol == 3 and $us->id == 1 and auth()->user()->id == 1) 
                  <a href="{{URL::action('AgenteController@show', $us->id)}}"><button class="btn-ico" ><i class="fas fa-pencil-alt"></i></button></a>
                  @if ($us->estatus == 'Activo')
                    <a href="{{URL::action('AgenteController@inactiva', $us->id)}}"><button class="btn-ico" ><i class="fas fa-user-times"></i></button></a>
                  @elseif($us->estatus == 'Inactivo')
                    <a href="{{URL::action('AgenteController@activa', $us->id)}}"><button class="btn-ico" ><i class="fas fa-user-check"></i></button></a>
                  @else
                    <a href="{{URL::action('AgenteController@activa', $us->id)}}"><button class="btn-ico" ><i class="fas fa-user-check"></i></button></a>
                  @endif     
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  {{-- {{$usuarios->appends('search')->render()}} --}}
</div>
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-agente">
  {{ Form::open(array('action'=>array('AgenteController@store'),'method'=>'post')) }}
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title text-dark">Nuevo usuario agente</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="nuevo_name_mdl">*Nombre</label>
                          <input type="text" name="nuevo_name_mdl" id="nuevo_name_mdl" value=""  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="nuevo_email_mdl">*Email</label>
                          <input type="text" name="nuevo_email_mdl" id="nuevo_email_mdl" value=""  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="nuevo_pass_mdl">*Contraseña</label>
                          <input type="password" name="nuevo_pass_mdl" id="nuevo_pass_mdl" value=""  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="nuevo_rol_mdl">*Rol</label>
                          <select name="nuevo_rol_mdl" id="nuevo_rol_mdl" value=""  class="letrasModal form-control" required="true">
                            @foreach ($roles as $r)
                              <option value="{{ $r->id }}">{{ $r->rol }}</option>
                            @endforeach
                          </select>
                      </div>
                  </div>               
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-info">Confirmar</button>
          </div>
      </div>
  </div>
  {{ Form::close()}}    
</div>
@push('scripts')
<script type="text/javascript">
  jQuery(document).ready(function($)
  {
    $('#tabla').DataTable();
  });
</script>
@endpush 
@endsection