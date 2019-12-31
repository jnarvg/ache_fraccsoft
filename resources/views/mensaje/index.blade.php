@extends('layouts.admin')
@section('title')
Todos los mensajes
@endsection
@section('content')
<div class="content mt-3">
  
  <div class="card">
    <div class="card-body">
      <div class="table-responsive ">
        <table class="table table-hover table-withoutborder text-sm">
          <thead class="thead-grubsa ">
            <th class="center">
              Id
            </th>
            <th class="center">
              Fecha
            </th>
            <th class="center">
              Titulo
            </th>
            <th class="center">
              Creador
            </th>
            <th class="center">
              Dirigido
            </th>
            <th class="center">
              Estatus
            </th>
            <th class="center">
              Acciones
            </th>
          </thead>
          <tbody>
            @foreach ($mensajes as $m)                
            <tr>
              <td class="center">
                {{ $m->id_mensaje }}
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('MensajeController@show', [$m->id_mensaje, 'mensaje'])}}"  style="width: 30%;">
                {{ date('Y-m-d', strtotime($m->fecha)) }}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('MensajeController@show', [$m->id_mensaje, 'mensaje'])}}"  style="width: 30%;">
                {{ $m->titulo}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('MensajeController@show', [$m->id_mensaje, 'mensaje'])}}"  style="width: 30%;">
                {{ $m->remitente}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('MensajeController@show', [$m->id_mensaje, 'mensaje'])}}"  style="width: 30%;">
                {{ $m->destinatario}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('MensajeController@show', [$m->id_mensaje, 'mensaje'])}}"  style="width: 30%;">
                {{ $m->estatus}}</a>
              </td>
              <td class="center-acciones">
                <a href="{{URL::action('MensajeController@show', [$m->id_mensaje, 'mensaje'])}}"  style="width: 30%;"><button class="btn-ico" ><i class="fas fa-pencil-alt"></i></button></a>
                @if (auth()->user()->rol == 3)
                <a href="#" data-target="#modal-delete{{$m->id_mensaje}}" data-toggle="modal" style="width: 30%;"><button class="btn-ico"><i class="fas fa-trash"></i></button></a>             
                @endif
              </td>
            </tr>
            @include('mensaje.modal')
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  {{$mensajes->render()}}
</div>

@push('scripts')
@endpush 
@endsection