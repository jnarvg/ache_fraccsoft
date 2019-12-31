@extends('layouts.admin')
@section('title')
Plazos de pago
@endsection
@section('filter')
<a href="#" data-toggle="modal" data-target="#modal_exportar"><button class="mb-0 d-sm-inline-block btn btn-dark btn-sm"><i class="fas fa-download"></i></button></a>
<button href="#" data-toggle="collapse" data-target="#filtros" role="button" aria-expanded="false" aria-controls="filtros" class=" d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-filter"></i></button>
@endsection
@section('content')
<div class="content mt-3">
  <div class="card collapse" id="filtros">
    <div class="card-body">
      {!! Form::open(array('route'=>'reportes_pagos', 'method'=>'get', 'autocomplete'=>'off')) !!}
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label for="year_bs">Año</label>
              <select class="form-control"  name="year_bs" id="year_bs">
                <option value=""></option>
                @foreach ($years as $p)
                  @if ($p->anio == $request->year_bs)
                    <option selected="true" value="{{$p->anio }}">{{ $p->anio }}</option>
                  @else
                    <option value="{{$p->anio }}">{{ $p->anio }}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="estatus_bs">Estatus</label><br>
              <select class="form-control" multiple="multiple"  name="estatus_bs[]" id="estatus_bs">
                <option value="all">Todos</option>
                @foreach ($estatus as $p)
                  @php $valida = 0; @endphp
                  @if ($request->estatus_bs)
                    @foreach ($request->estatus_bs as $e)
                      @if ($p == $e)
                        <option selected="true" value="{{$p }}">{{ $p }}</option>
                        @php $valida = 1;@endphp
                      @endif
                    @endforeach
                  @endif
                  @if ($valida == 0)
                    <option value="{{$p }}">{{ $p }}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="estatus_oportunidad_bs">Estatus oportunidad</label><br>
              <select class="form-control" multiple="multiple"  name="estatus_oportunidad_bs[]" id="estatus_oportunidad_bs">
                <option value="all">Todos</option>
                @foreach ($estatus_oportunidad as $p)
                  @php $valida = 0; @endphp
                  @if ($request->estatus_oportunidad_bs)
                    @foreach ($request->estatus_oportunidad_bs as $e)
                      @if ($p->id_estatus_crm == $e)
                        <option selected="true" value="{{$p->id_estatus_crm }}">{{ $p->estatus_oportunidad }}</option>
                        @php $valida = 1;@endphp
                      @endif
                    @endforeach
                  @endif
                  @if ($valida == 0)
                    <option value="{{$p->id_estatus_crm }}">{{ $p->estatus_oportunidad }}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="nombre_bs">Nombre</label><br>
              <select class="form-control" multiple="multiple" name="nombre_bs[]" id="nombre_bs">
                <option value="all">Todos</option>
                @foreach ($clientes as $p)
                  @php $valida = 0; @endphp
                  @if ($request->nombre_bs)
                    @foreach ($request->nombre_bs as $e)
                      @if ($p->id_prospecto == $e)
                        <option selected value="{{$p->id_prospecto }}">{{ $p->nombre_prospecto }}</option>
                        @php $valida = 1; @endphp
                      @endif
                    @endforeach
                  @endif
                  @if ($valida == 0)
                    <option value="{{$p->id_prospecto }}">{{ $p->nombre_prospecto }}</option>
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
                <h3><em>Clientes</em></h3>
                 <hr class="hr-titulo" width="100%" size="10">
            </div>
        </div>
        <div class="col-md-12">
          <div class="table-responsive">
            <table class="table text-sm table-hover">
              {!! $tabla  !!}
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" aria-hidden="true" role="dialog" tabindex="-1" id="modal_exportar">
    {{ Form::open(array('action'=>array('ReportesPDFController@exportar_pagos'),'method'=>'get')) }}
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
                <select class="oculto" class="form-control" multiple="multiple" name="estatus_export[]" id="estatus_export">
                  @foreach ($estatus as $p)
                    @if ($request->estatus_bs)
                    @foreach ($request->estatus_bs as $e)
                      @if ($p  == $e)
                        <option selected="true" value="{{$p }}">{{ $p }}</option>
                      @endif
                    @endforeach
                    @endif
                  @endforeach
                </select>
                <select class="oculto" class="form-control" multiple="multiple" name="estatus_oportunidad_export[]" id="estatus_oportunidad_export">
                  @foreach ($estatus_oportunidad as $p)
                    @if ($request->estatus_oportunidad_bs)
                    @foreach ($request->estatus_oportunidad_bs as $e)
                      @if ($p->id_estatus_crm == $e)
                        <option selected="true" value="{{$p->id_estatus_crm }}">{{ $p->estatus_oportunidad }}</option>
                      @endif
                    @endforeach
                    @endif
                  @endforeach
                </select>
                <select class="oculto" class="form-control" multiple="multiple" name="nombre_export[]" id="nombre_export">
                  @foreach ($clientes as $p)
                    @if ($request->subestatus_oportunidad_bs)
                    @foreach ($request->subestatus_oportunidad_bs as $e)
                      @if ($p->id_prospecto == $e)
                        <option selected="true" value="{{$p->id_prospecto }}">{{ $p->nombre_prospecto }}</option>
                      @endif
                    @endforeach
                    @endif
                  @endforeach
                </select>
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
<script type="text/javascript">
  $('#nombre_bs').multiselect('destroy');
  $('#nombre_bs').multiselect({
    enableFiltering: true,
    filterBehavior: 'text',
    includeSelectAllOption: true,
    allSelectedText: 'Todos ...',
    enableCaseInsensitiveFiltering: true,
    filterPlaceholder: 'Buscar...',
  });
  $('#estatus_bs').multiselect('destroy');
  $('#estatus_bs').multiselect({
    enableFiltering: true,
    filterBehavior: 'text',
    enableCaseInsensitiveFiltering: true,
    includeSelectAllOption: true,
    allSelectedText: 'Todos ...',
    filterPlaceholder: 'Buscar...',
  });
  $('#estatus_oportunidad_bs').multiselect('destroy');
  $('#estatus_oportunidad_bs').multiselect({
    enableFiltering: true,
    filterBehavior: 'text',
    enableCaseInsensitiveFiltering: true,
    includeSelectAllOption: true,
    allSelectedText: 'Todos ...',
    filterPlaceholder: 'Buscar...',
  });
  $('#subestatus_oportunidad_bs').multiselect('destroy');
  $('#subestatus_oportunidad_bs').multiselect({
    enableFiltering: true,
    filterBehavior: 'text',
    enableCaseInsensitiveFiltering: true,
    includeSelectAllOption: true,
    allSelectedText: 'Todos ...',
    filterPlaceholder: 'Buscar...',
  });
</script>
@endpush 
@endsection