@extends('layouts.admin')
@section('title')
Comparativo
@endsection
@section('filter')
<button href="#" data-toggle="collapse" data-target="#filtros" role="button" aria-expanded="false" aria-controls="filtros" class=" d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-filter"></i></button>
@endsection
@section('content')
<div class="content mt-3">
  <div class="card collapse" id="filtros">
    <div class="card-body">
      {!! Form::open(array('route'=>'reportes_comparativo', 'method'=>'get', 'autocomplete'=>'off')) !!}
        <div class="row">
          <div class="col-md-8">
            <div class="form-group">
              <label for="fecha_bs">Fecha</label>
                @if ($request->fecha_bs!=null)
                  <input type="date" class="form-control" id="fecha_bs" name="fecha_bs" value="{{ date('Y-m-d', strtotime($request->fecha_bs)) }}" />
                @else
                  <input type="date" class="form-control" id="fecha_bs" name="fecha_bs" value="{{ date('Y-m-d') }}" />
                @endif
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for=""></label>
              <button type="submit" class="btn btn-info btn-block" ><span class="glyphicon"><i class="fas fa-search"></i></span></button>
            </div>
          </div>
        </div>
      {!! Form::close() !!}
    </div>
  </div>
  <div class="card mt-3">
    <div class="card-body">
      @if (session()->has('msj'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
          <strong>{{ session('msj') }}</strong>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif
      <div class="row mt-3">
        <div class="col-md-12">
            <div class="form-group">
                <h3><em>Comparativo de ventas</em></h3>
                 <hr class="hr-titulo" width="100%" size="10">
            </div>
        </div>
        <div class="col-md-12">
          <div class="table-responsive">
            <table class="table text-sm table-hover">
              {!! $tabla !!}
              
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')

@endpush 
@endsection