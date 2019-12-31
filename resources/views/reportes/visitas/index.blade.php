@extends('layouts.admin')
@section('title')
Visitas
@endsection
@section('filter')
<a href="#" data-toggle="modal" data-target="#modal_exportar"><button class="mb-0 d-sm-inline-block btn btn-dark btn-sm"><i class="fas fa-download"></i></button></a>
<button href="#" data-toggle="collapse" data-target="#filtros" role="button" aria-expanded="false" aria-controls="filtros" class=" d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-filter"></i></button>
@endsection
@section('content')
<div class="content mt-3">
  <div class="card collapse" id="filtros">
    <div class="card-body">
      {!! Form::open(array('route'=>'reportes_visitas', 'method'=>'get', 'autocomplete'=>'off')) !!}
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
              <label for="proyecto_bs">Proyecto</label><br>
              <select class="form-control" multiple="multiple"  name="proyecto_bs[]" id="proyecto_bs">
                <option value="all">Todos</option>
                @foreach ($proyectos_filtro as $p)
                  @php $valida = 0; @endphp
                  @if ($request->proyecto_bs)
                    @foreach ($request->proyecto_bs as $e)
                      @if ($p->id_proyecto == $e)
                        <option selected value="{{$p->id_proyecto }}">{{ $p->nombre }}</option>
                        @php $valida = 1; @endphp
                      @endif
                    @endforeach
                  @endif
                  @if ($valida == 0)
                    <option value="{{$p->id_proyecto }}">{{ $p->nombre }}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="estatus_propiedad_bs">Estatus oportunidad</label><br>
              <select class="form-control" multiple="multiple"  name="estatus_propiedad_bs[]" id="estatus_propiedad_bs">
                <option value="all">Todos</option>
                @foreach ($estatus_propiedad as $p)
                  @php $valida = 0; @endphp
                  @if ($request->estatus_propiedad_bs)
                    @foreach ($request->estatus_propiedad_bs as $e)
                      @if ($p->id_estatus_propiedad == $e)
                        <option selected="true" value="{{$p->id_estatus_propiedad }}">{{ $p->estatus_propiedad }}</option>
                        @php $valida = 1;@endphp
                      @endif
                    @endforeach
                  @endif
                  @if ($valida == 0)
                    <option value="{{$p->id_estatus_propiedad }}">{{ $p->estatus_propiedad }}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="nombre_bs">Propiedad</label><br>
              <select class="form-control" multiple="multiple" name="nombre_bs[]" id="nombre_bs">
                <option value="all">Todos</option>
                @foreach ($propiedades_filtro as $p)
                  @php $valida = 0; @endphp
                  @if ($request->nombre_bs)
                    @foreach ($request->nombre_bs as $e)
                      @if ($p->id_propiedad == $e)
                        <option selected value="{{$p->id_propiedad }}">{{ $p->nombre }}</option>
                        @php $valida = 1; @endphp
                      @endif
                    @endforeach
                  @endif
                  @if ($valida == 0)
                    <option value="{{$p->id_propiedad }}">{{ $p->nombre }}</option>
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
                <h3><em>Visitas por propiedad</em></h3>
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
    {{ Form::open(array('action'=>array('ReportesPDFController@exportar_visitas'),'method'=>'get')) }}
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
                  @foreach ($proyectos_filtro as $p)
                    @if ($request->proyecto_bs)
                    @foreach ($request->proyecto_bs as $e)
                      @if ($p  == $e)
                        <option selected="true" value="{{$p }}">{{ $p }}</option>
                      @endif
                    @endforeach
                    @endif
                  @endforeach
                </select>
                <select class="oculto" class="form-control" multiple="multiple" name="estatus_propiedad_export[]" id="estatus_propiedad_export">
                  @foreach ($estatus_propiedad as $p)
                    @if ($request->estatus_propiedad_bs)
                    @foreach ($request->estatus_propiedad_bs as $e)
                      @if ($p->id_estatus_propiedad == $e)
                        <option selected="true" value="{{$p->id_estatus_propiedad }}">{{ $p->estatus_propiedad }}</option>
                      @endif
                    @endforeach
                    @endif
                  @endforeach
                </select>
                <select class="oculto" class="form-control" multiple="multiple" name="nombre_export[]" id="nombre_export">
                  @foreach ($propiedades_filtro as $p)
                    @if ($request->nombre_bs)
                    @foreach ($request->nombre_bs as $e)
                      @if ($p->id_propiedad == $e)
                        <option selected="true" value="{{$p->id_propiedad}}">{{ $p->nombre }}</option>
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
  $('#proyecto_bs').multiselect('destroy');
  $('#proyecto_bs').multiselect({
    enableFiltering: true,
    filterBehavior: 'text',
    enableCaseInsensitiveFiltering: true,
    includeSelectAllOption: true,
    allSelectedText: 'Todos ...',
    filterPlaceholder: 'Buscar...',
  });
  $('#estatus_propiedad_bs').multiselect('destroy');
  $('#estatus_propiedad_bs').multiselect({
    enableFiltering: true,
    filterBehavior: 'text',
    enableCaseInsensitiveFiltering: true,
    includeSelectAllOption: true,
    allSelectedText: 'Todos ...',
    filterPlaceholder: 'Buscar...',
  });
  $("#proyecto_bs").on('change', function(){
    desarrollo = $('#proyecto_bs').val();
    selectPropiedad = $('#nombre_bs');
    selectPropiedad.empty().append('<option>Cargando las propiedades</option>');

    $.ajax({

    type: "GET",
    url: "/catalogo-propiedades-desarrollo/" + desarrollo,
    success: function(data) {
          var htmlOptions = [];
          if( data.length ){
              //html = '<option value="" disabled="true" selected="true">Selecciona una propiedad</option>';
              htmlOptions[htmlOptions.length] = html;
              for( item in data ) {
                //en caso de ser un select
                html = '<option value="' + data[item].id_propiedad + '">' + data[item].nombre + '</option>';
                htmlOptions[htmlOptions.length] = html;
              }

              //en caso de ser un input
              //$("#precio_propiedadctz").val(html);
              
              // se agregan las opciones del catalogo en caso de ser un select 
              selectPropiedad.empty().append( htmlOptions.join('') );

          }else{
            html = '<option value="" disabled="true" selected="true">No hay propiedades</option>';
            htmlOptions[htmlOptions.length] = html;
            selectPropiedad.empty().append( htmlOptions.join('') );
          }
          selectPropiedad.multiselect('destroy');
          $('#nombre_bs').multiselect({
            enableFiltering: true,
            filterBehavior: 'text',
            includeSelectAllOption: true,
            allSelectedText: 'Todos ...',
            enableCaseInsensitiveFiltering: true,
            filterPlaceholder: 'Buscar...',
          });
    },
      error: function(error) {
      alert("No se pudo cargar el catalogo de propiedades");
    }
    })
});
</script>
@endpush 
@endsection