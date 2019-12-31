@extends('layouts.admin')
@section('title')
Condiciones de entrega detalle
@endsection
@section('filter')
  @if (auth()->user()->rol == 3)
  <a href="#" data-toggle="modal" data-target="#modal-new"><button class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus"></i></button></a>
  @endif
  <button href="#" data-toggle="collapse" data-target="#filtros" role="button" aria-expanded="false" aria-controls="filtros" class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-filter"></i></button>
@endsection
@section('content')
<div class="content mt-3">
  <div class="card collapse" id="filtros">
    <div class="card-body">
      {!! Form::open(array('route'=>'condicion_entrega', 'method'=>'get', 'autocomplete'=>'off')) !!}
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="titulo_bs">Condicion</label>
            <input type="text" class="form-control" placeholder="Buscar..." name="titulo_bs" id="titulo_bs" value="{{ $request->titulo_bs }}">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="proyecto_bs">Proyecto</label>
            <select class="form-control" id="proyecto_bs" name="proyecto_bs">
              <option value=""></option>
              @foreach ($proyectos as $e)
                @if ($e->id_proyecto == $request->proyecto_bs)
                <option selected value="{{ $e->id_proyecto }}">{{ $e->nombre }}</option>
                @else
                <option value="{{ $e->id_proyecto }}">{{ $e->nombre }}</option>
                @endif
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="uso_propiedad_bs">Uso propiedad</label>
            <select class="form-control" id="uso_propiedad_bs" name="uso_propiedad_bs">
              <option value=""></option>
              @foreach ($usos_propiedad as $e)
                @if ($e->id_uso_propiedad == $request->uso_propiedad_bs)
                <option selected value="{{ $e->id_uso_propiedad }}">{{ $e->uso_propiedad }}</option>
                @else
                <option value="{{ $e->id_uso_propiedad }}">{{ $e->uso_propiedad }}</option>
                @endif
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <button type="submit" class="btn btn-info" ><span class="glyphicon"><i class="fas fa-search"></i></span></button>
          </div>
        </div>
      </div>
      {!! Form::close() !!}
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
              Condicion
            </th>
            <th class="center">
              Uso propiedad
            </th>
            <th class="center">
              Proyecto
            </th>
            <th class="center">
              Fecha
            </th>
            <th class="center">
              Acciones
            </th>
          </thead>
          <tbody>
            @foreach ($resultados as $r)                
            <tr>
              <td class="center">
                <a class="text-dark" href="{{URL::action('CondicionEntregaDetalleController@show', [$r->id_condicion_entrega_detalle, 'Menu'])}}">
                  {{ $r->id_condicion_entrega_detalle }}
                </a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('CondicionEntregaDetalleController@show', [$r->id_condicion_entrega_detalle, 'Menu'])}}">
                  {{ $r->condicion }}
                </a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('CondicionEntregaDetalleController@show', [$r->id_condicion_entrega_detalle, 'Menu'])}}">
                  {{ $r->uso_propiedad }}
                </a>
              </td>
              <td class="center"> 
                <a class="text-dark" href="{{URL::action('CondicionEntregaDetalleController@show', [$r->id_condicion_entrega_detalle, 'Menu'])}}">
                  {{ $r->proyecto }}
                </a>
              </td>
              <td class="center">                
                <a class="text-dark" href="{{URL::action('CondicionEntregaDetalleController@show', [$r->id_condicion_entrega_detalle, 'Menu'])}}">
                  {{ date('Y-M-d', strtotime($r->created_date)) }}
                </a>
              </td>
              <td class="center-acciones">
                <a href="{{URL::action('CondicionEntregaDetalleController@show', [$r->id_condicion_entrega_detalle, 'Menu'])}}"><button class="btn-ico" ><i class="fas fa-pencil-alt"></i></button></a>
                <a href="#" data-target="#modal-delete{{$r->id_condicion_entrega_detalle}}" data-toggle="modal"><button class="btn-ico"><i class="fas fa-trash"></i></button></a>
              </td>
            </tr>
            @include('catalogos.condicion_entrega_detalle.modal')
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  {{-- {{$usuarios->appends('search')->render()}} --}}
</div>
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-new">
  {{ Form::open(array('action'=>array('CondicionEntregaDetalleController@store'),'method'=>'post')) }}
  <div class="modal-dialog  modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title text-dark">Nuevo</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="condicion">*Condicion</label>
                          <input type="text" name="condicion" id="condicion" value=""  class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="tipo">*Tipo</label>
                          <select name="tipo" id="tipo"  class="letrasModal form-control" required="true"> 
                              @foreach ($tipo_condicion as $e)
                                <option value="{{ $e }}">{{ $e }} </option>
                              @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-md-6 ocultar">
                      <div class="form-group">
                          <label for="subtitulo">Subtitulo</label>
                          <input type="text" name="subtitulo" id="subtitulo" value=""  class="letrasModal form-control" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="proyecto_id">*Proyecto</label>
                          <select name="proyecto_id" id="proyecto_id" value=""  class="letrasModal form-control" required="true">
                            @foreach ($proyectos as $r)
                              <option value="{{ $r->id_proyecto }}">{{ $r->nombre }}</option>
                            @endforeach
                          </select>
                      </div>
                  </div> 
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="uso_propiedad_id">*Uso de propiedad</label>
                          <select name="uso_propiedad_id" id="uso_propiedad_id" value=""  class="letrasModal form-control" required="true">
                            @foreach ($usos_propiedad as $r)
                              <option value="{{ $r->id_uso_propiedad }}">{{ $r->uso_propiedad }}</option>
                            @endforeach
                          </select>
                      </div>
                  </div> 
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="condicion_entrega_id">*Condicion entrega</label>
                          <select name="condicion_entrega_id" id="condicion_entrega_id" value=""  class="letrasModal form-control" required="true">
                            @foreach ($condicion_entrega as $r)
                              <option value="{{ $r->id_condicion_entrega }}">{{ $r->titulo }}</option>
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
    tipo = $('#tipo').val();
    if (tipo == 'Agregado') {
        $('.ocultar').removeClass('oculto');
    }else{
        $('.ocultar').addClass('oculto');
    }
    $("#tipo").on('change', function(){
        tipo = $('#tipo').val();
        if (tipo == 'Agregado') {
            $('.ocultar').removeClass('oculto');
        }else{
            $('.ocultar').addClass('oculto');
        }
    });
  });
</script>
@endpush 
@endsection