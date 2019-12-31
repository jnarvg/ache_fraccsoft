@extends('layouts.admin')
@section('title')
Clientes por asesor
@endsection
@section('filter')
<button href="#" data-toggle="collapse" data-target="#filtros" role="button" aria-expanded="false" aria-controls="filtros" class=" d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-filter"></i></button>
@endsection
@section('content')
<div class="content mt-3">
  <div class="card collapse" id="filtros">
    <div class="card-body">
      {!! Form::open(array('route'=>'reportes_clientes_asesor', 'method'=>'get', 'autocomplete'=>'off')) !!}
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label for="fecha_minima_bs">Fecha inicio</label>
              <input type="date" class="form-control" placeholder="Fecha incio" required name="fecha_minima_bs" id="fecha_minima_bs" value="{{ $request->fecha_minima_bs }}">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="fecha_maxima_bs">Fecha final</label>
              <input type="date" class="form-control" placeholder="Fecha final"  required name="fecha_maxima_bs" id="fecha_maxima_bs" value="{{ $request->fecha_maxima_bs }}">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="asesor_bs">Asesor</label>
              <select class="form-control"  name="asesor_bs" id="asesor_bs">
                <option value="Vacio">Selecciona</option>
                @foreach ($asesores as $p)
                  @if ($p->id == $request->asesor_bs)
                    <option selected="true" value="{{ $p->id }}">{{ $p->name }}</option>
                  @else
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                  @endif
                @endforeach
              </select>
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
                <h3><em>Clientes por asesor</em></h3>
                 <hr class="hr-titulo" width="100%" size="10">
            </div>
        </div>
        <div class="col-md-8">
          <div class="table-responsive">
            <table class="table text-sm table-hover">
              {!! $tabla !!}
            </table>
          </div>
        </div>
        <div class="col-md-4">
          <div class="table-responsive">
            <table class="table text-sm table-hover">
              <thead class="bg-gray-600 text-white">
                <tr><th>Asesor</th><th class="text-center">Total</th></tr>
              </thead>
              <tbody>
                @foreach ($por_asesor as $e)
                  <tr>
                    <td>{{ $e->label }}</td>
                    <td class="text-center">{{ $e->cantidad }}</td>
                  </tr>
                @endforeach
              </tbody>
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