@extends('layouts.admin')
@section('title')
Banco
@endsection
@section('filter')
  <a href="#" data-toggle="modal" data-target="#modal_nuevo"><button class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus"></i></button></a>
  <button href="#" data-toggle="collapse" data-target="#filtros" role="button" aria-expanded="false" aria-controls="filtros" class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-filter"></i></button>
@endsection
@section('content')
<div class="content mt-3">
  <div class="card show" id="filtros">
    <div class="card-body">
      @if (session()->has('msj'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
          <strong>{{ session('msj') }}</strong>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif
      <div class="row">
        <div class="col-lg-12">
        {!! Form::open(array('route'=>'banco', 'method'=>'get', 'autocomplete'=>'off')) !!}
          <div class="input-group md-form form-sm form-4 pl-0">

            <input type="text" class="form-control" placeholder="Search" name="nombre_bs" id="nombre_bs" value="{{ $request->nombre_bs }}">

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
              Banco
            </th>
            <th class="center">
              Razon social
            </th>
            <th class="center">
              RFC
            </th>
            <th class="center">
              Acciones
            </th>
          </thead>
          <tbody>
            @foreach ($bancos as $b)                
            <tr>
              <td class="center">
                <a class="text-dark" href="{{URL::action('BancoController@show', $b->id_banco)}}"  style="width: 30%;">
                {{ $b->banco}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('BancoController@show', $b->id_banco)}}"  style="width: 30%;">
                {{ $b->razon_social}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('BancoController@show', $b->id_banco)}}"  style="width: 30%;">
                {{ $b->rfc}}</a>
              </td>
              
              <td class="center-acciones">
                <a href="{{URL::action('BancoController@show', $b->id_banco)}}"  style="width: 30%;"><button class="btn-ico"><i class="fas fa-pencil-alt"></i></button></a>
                <a href="#" data-target="#modal-delete{{$b->id_banco}}" data-toggle="modal" style="width: 30%;"><button class="btn-ico"><i class="fas fa-trash"></i></button></a>
              </td>
            </tr>
            @include('catalogos.banco.modal')
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  {{-- {{$bancos->appends(Request::only('nombre_bs'))->render()}} --}}
</div>
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal_nuevo">
  {{ Form::open(array('action'=>array('BancoController@store'),'method'=>'post', 'files'=>true)) }}
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title text-dark">Nuevo</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="razon_social">*Razon social</label>
                          <input type="text" name="razon_social" id="razon_social" value="" minlength="2" class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="banco">*Banco</label>
                          <input type="text" name="banco" id="banco" value=""  class="letrasModal form-control" required="true"/>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="rfc">RFC</label>
                          <input type="text"  name="rfc" id="rfc"   class="letrasModal form-control" />
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