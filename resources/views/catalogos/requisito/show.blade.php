@extends('layouts.admin')
@section('title')
Requisitos
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            {{ Form::open(array('action'=>array('RequisitosController@update',$requisito->id_requisito),'method'=>'post')) }}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="requisito">*Requisito</label>
                        <input type="text" name="requisito" id="requisito" value="{{ $requisito->requisito_padre }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>            
                <div class="col-md-2 offset-md-3">
                    <div class="form-group">
                        <a href="{{ route('requisito') }}" class="btn btn-dark btn-block">REGRESAR</a>
                    </div>
                </div> 
                <div class="col-md-2">
                    <div class="form-group">
                        <button type="submit" class="btn btn-info btn-block">GUARDAR</button>
                    </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <a href="#" data-toggle="modal" data-target="#modal_requisito_detalle"><button class="btn btn-primary btn-block">NUEVO</button></a>
                  </div>
                </div>
            </div>
            {{ Form::close()}}
            <div class="row">
                <div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal_requisito_detalle">
                  {{ Form::open(array('action'=>array('RequisitoDetalleController@store',$requisito->id_requisito),'method'=>'post')) }}
                  <div class="modal-dialog" role="document">
                      <div class="modal-content">
                          <div class="modal-header">
                              <h4 class="modal-title text-dark">Nuevo requisito</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                          </div>
                          <div class="modal-body">
                              <div class="row">
                                  <div class="col-md-12">
                                      <div class="form-group">
                                          <label for="nuevo_requisito_mdl">*Requisito</label>
                                          <input type="text" name="nuevo_requisito_mdl" id="nuevo_requisito_mdl" value=""  class="letrasModal form-control" required="true" />
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
            </div>
            <div class="card">
              <div class="card-header bg-primary text-white">Listado de requisitos</div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover table-stripered text-sm" id="tabla_requisito">
                      <thead>
                          <tr>
                          <th class="center">Id</th>
                          <th class="center">Requisito</th>
                          <th class="center">Acciones</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach ($requisitos_detalles as $requisito_detalle)
                          <tr>
                              <td class="center">
                                <a href="{{URL::action('RequisitoDetalleController@show', $requisito_detalle->id_requisito_detalle)}}"  >
                                {{ $requisito_detalle->id_requisito_detalle }}</a>
                              </td>
                              <td class="center">
                                <a href="{{URL::action('RequisitoDetalleController@show', $requisito_detalle->id_requisito_detalle)}}"  >{{ $requisito_detalle->requisito}}</a>
                              </td>
                              <td class="center-acciones">
                                <a href="{{URL::action('RequisitoDetalleController@show', $requisito_detalle->id_requisito_detalle)}}"  ><button class="btn-ico" ><i class="fas fa-pencil-alt"></i></button></a>
                                @if (auth()->user()->rol == 3)
                                <a href="{{URL::action('RequisitoDetalleController@destroy', $requisito_detalle->id_requisito_detalle)}}"  ><button class="btn-ico" ><i class="fas fa-trash-alt"></i></button></a>
                                @endif
                              </td>
                          </tr>
                          @endforeach
                      </tbody>
                  </table>
                </div>
              </div>
              {{-- {{$requisitos_detalles->render()}} --}}
            </div>       
        </div>
    </div>
</div>

@push('scripts')
<script>
  jQuery(document).ready(function($)
    {
      $('#tabla_requisito').DataTable();
    });
</script> 
@endpush 
@endsection