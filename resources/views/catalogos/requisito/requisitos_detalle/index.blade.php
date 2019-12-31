@extends('layouts.admin')
@section('title')
Requisitos detalle
@endsection
@section('content')
<div class="content mt-3">
  
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-md-3 offset-md-9">
          <div class="form-group">
            <a href="#" data-toggle="modal" data-target="#modal-uso_propiedad"><button class="btn btn-primary btn-block">Nuevo requisito</button></a>
          </div>
        </div>
        <div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-uso_propiedad">
          {{ Form::open(array('action'=>array('RequisitosController@store'),'method'=>'post')) }}
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
                                  <label for="nueva_requisito">*Requisito</label>
                                  <input type="text" name="nueva_requisito" id="nueva_requisito" value=""  class="letrasModal form-control" required="true" />
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
      <div class="table-responsive ">
        <table class="table table-hover table-withoutborder">
          <thead class="thead-grubsa ">
            <th class="center">
              Id
            </th>
            <th class="center">
              Requisito
            </th>
            <th class="center">
              Acciones
            </th>
          </thead>
          <tbody>
            @foreach ($requisitos as $requisito)                
            <tr>
              <td class="center">
                {{ $requisito->id_requisito }}
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('RequisitosController@show', $requisito->id_requisito)}}"  style="width: 30%;">
                {{ $requisito->requisito}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('RequisitosController@show', $requisito->id_requisito)}}"  style="width: 30%;">
                {{ $requisito->estado}}</a> 
              </td>
              <td class="center-acciones">
                <a href="{{URL::action('RequisitosController@show', $requisito->id_requisito)}}"  style="width: 30%;"><button class="btn-ico" ><i class="fas fa-pencil-alt"></i></button></a>
                @if (auth()->user()->rol == 3)
                <a href="{{URL::action('RequisitosController@destroy', $requisito->id_requisito)}}"  style="width: 30%;"><button class="btn-ico" ><i class="fas fa-trash-alt"></i></button></a>               
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  {{$requisitos->render()}}
</div>

@push('scripts')
@endpush 
@endsection