@extends('layouts.admin')
@section('title')
Requisitos
@endsection
@section('filter')
  <a href="#" data-toggle="modal" data-target="#modal-uso_propiedad"><button class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus"></i></button></a>
@endsection
@section('content')
<div class="content mt-3">
  
  <div class="card">
    <div class="card-body">
      <div class="table-responsive ">
        <table class="table table-hover table-withoutborder text-sm">
          <thead class="thead-grubsa ">
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
                <a class="text-dark" href="{{URL::action('RequisitosController@show', $requisito->id_requisito)}}"  style="width: 30%;">
                {{ $requisito->requisito}}</a>
              </td>
              <td class="center-acciones">
                <a href="{{URL::action('RequisitosController@show', $requisito->id_requisito)}}"  style="width: 30%;"><button class="btn-ico" style="margin-left: 2px; margin-right: 2px;"><i class="fas fa-pencil-alt"></i></button></a>
                @if (auth()->user()->rol == 3)
                <a href="{{URL::action('RequisitosController@destroy', $requisito->id_requisito)}}"  style="width: 30%;"><button class="btn-ico" style="margin-left: 2px; margin-right: 2px;"><i class="fas fa-trash-alt"></i></button></a>               
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
@push('scripts')
@endpush 
@endsection