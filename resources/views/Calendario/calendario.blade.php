@extends('layouts.admin')
@section('title')
Calendario
@endsection
@section('content')

<div class="content mt-3">
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-md-3 offset-md-9">
          <div class="form-group">
            <a href="#" data-toggle="modal" data-target="#modal-uso_propiedad"><button class="btn btn-primary btn-block">Nuevo Evento</button></a>
          </div>
        </div>
        <div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-uso_propiedad">
          {{ Form::open(array('action'=>array('CalendarioController@store'),'method'=>'post','files'=>'true',)) }}
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h4 class="modal-title text-dark">Nuevo Evento</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      <div class="row">
                          <div class="col-md-6">
                              <div class="form-group">
                                  <label for="titulo">Titulo</label>
                                  <input type="text" name="titulo" id="titulo" value=""  class="letrasModal form-control" required="true" />
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="form-group">
                                <label for="notas">Fecha Inicial</label>
                                  <input type="date" name="fechaI" id="fechaI" value="" class="letrasModal form-control" required="true" />
                                  <input type="time" name="horaI" min="06:00:00" max="20:00:00" >
                              </div>
                          </div>               
                      </div>
                      <div class="row">
                          <div class="col-md-6">
                              <div class="form-group">
                                  <label for="fecha">Fecha Final</label>
                                  <input type="date" name="fechaF" id="fechaF" value="" class="letrasModal form-control" required="true" />
                                  <input type="time" name="horaF" min="06:00:00" max="20:00:00" >
                              </div>
                          </div>               
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
                      <button type="submit" class="btn btn-grubsa">Confirmar</button>
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
              Titulo
            </th>
            <th class="center">
              Fecha Inicial
            </th>
            <th class="center">
              Fecha Final
            </th>
          </thead>
          <tbody>
            @foreach ($calendarios as $calendar)                
            <tr>
              <td class="center">
                {{ $calendar->id}}
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('CalendarioController@show', $calendar->id)}}"  style="width: 30%;">
                {{ $calendar->title}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('CalendarioController@show', $calendar->id)}}"  style="width: 30%;">
                {{ $calendar->start_date}}</a> 
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('CalendarioController@show', $calendar->id)}}"  style="width: 30%;">
                {{ $calendar->end_date}}</a> 
              </td>
              <td class="center-acciones">
                <a href="{{URL::action('CalendarioController@show', $calendar->id)}}"  style="width: 30%;"><button class="btn-ico" style="margin-left: 2px; margin-right: 2px;"><i class="fas fa-pencil-alt"></i></button></a>
                @if (auth()->user()->rol == 3)
                <a href="{{URL::action('CalendarioController@destroy', $calendar->id)}}"  style="width: 30%;"><button class="btn-ico" style="margin-left: 2px; margin-right: 2px;"><i class="fas fa-trash-alt"></i></button></a>               
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  {{$calendarios->render()}}
</div>
@push('scripts')
@endpush 
@endsection