@extends('layouts.admin')
@section('title')
Ventas
@endsection
@section('filter')
<a href="#" data-toggle="modal" data-target="#modal_exportar"><button class="mb-0 d-sm-inline-block btn btn-dark btn-sm"><i class="fas fa-download"></i></button></a>
<button href="#" data-toggle="collapse" data-target="#filtros" role="button" aria-expanded="false" aria-controls="filtros" class=" d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-filter"></i></button>
@endsection
@section('content')
<div class="content mt-3">
  <div class="card collapse" id="filtros">
    <div class="card-body">
      {!! Form::open(array('route'=>'reportes_ventas', 'method'=>'get', 'autocomplete'=>'off')) !!}
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label for="year_bs">Año</label>
              <select class="form-control"  name="year_bs" id="year_bs">
                <option value=""></option>
                @foreach ($years as $p)
                  @if ($p == $request->year_bs)
                    <option selected="true" value="{{$p }}">{{ $p }}</option>
                  @else
                    @if ($p == date('Y'))
                      <option selected="true" value="{{$p }}">{{ $p }}</option>
                    @endif
                    <option value="{{$p }}">{{ $p }}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="mes_bs">Mes</label>
              <select class="form-control"  name="mes_bs" id="mes_bs">
                <option value=""></option>
                @foreach ($meses as $p)
                  @if ($p[0] == $request->mes_bs)
                    <option selected="true" value="{{$p[0] }}">{{ $p[1] }}</option>
                  @else
                    <option value="{{$p[0] }}">{{ $p[1] }}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="q_bs">Q</label>
              <select class="form-control"  name="q_bs" id="q_bs">
                <option value=""></option>
                @foreach ($ques as $p)
                  @if ($p[1] == $request->q_bs)
                    <option selected="true" value="{{$p[1] }}">{{ $p[0] }}</option>
                  @else
                    <option value="{{$p[1] }}">{{ $p[0] }}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label for="seccion_bs">Sección</label>
              <select class="form-control"  name="seccion_bs" id="seccion_bs">
                <option value=""></option>
                @foreach ($seccion as $p)
                  @if ($p->nivel == $request->seccion_bs)
                    <option selected="true" value="{{$p->nivel }}">{{ $p->nivel }}</option>
                  @else
                    <option value="{{$p->nivel }}">{{ $p->nivel }}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label></label>
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
                <h3><em>Reporte de Ventas</em></h3>
                 <hr class="hr-titulo" width="100%" size="10">
            </div>
        </div>
        <div class="col-md-12">
          <div class="table-responsive">
            <table class="table text-sm table-hover">
              <thead class="bg-gray-600 text-white">
                <tr>
                  <th>Fecha venta</th>
                  <th>Propiedad</th>
                  <th>Seccion</th>
                  <th width="30%">Cliente</th>
                  <th>$ Venta</th>
                </tr>
              </thead>
                {!! $body_tabla !!}
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" aria-hidden="true" role="dialog" tabindex="-1" id="modal_exportar">
    {{ Form::open(array('action'=>array('ReportesPDFController@exportar_ventas'),'method'=>'get')) }}
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Exportar reporte</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" class="form-control" value="{{ $request->year_bs }}" name="year_export" id="year_export">
                <input type="hidden" class="form-control" value="{{ $request->mes_bs }}" name="mes_export" id="mes_export">
                <input type="hidden" class="form-control" value="{{ $request->q_bs }}" name="q_export" id="q_export">
                <input type="hidden" class="form-control" value="{{ $request->manzana_bs }}" name="manzana_export" id="manzana_export">
                <input type="hidden" class="form-control" value="{{ $request->lote_bs }}" name="lote_export" id="lote_export">
                <input type="hidden" class="form-control" value="{{ $request->sublote_bs }}" name="sublote_export" id="subsublote_export">
                <input type="hidden" class="form-control" value="{{ $request->seccion_bs }}" name="seccion_export" id="seccion_export">
                <p>Desea exportar a PDF o EXCEL?</p>
                <select name="type_export" id="type_export" class="form-control">
                  <option value="EXCEL">EXCEL</option>
                  
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-info">Confirmar</button>
            </div>
        </div>
    </div>
    {{ Form::close()}}
    
</div>
@push('scripts')

@endpush 
@endsection