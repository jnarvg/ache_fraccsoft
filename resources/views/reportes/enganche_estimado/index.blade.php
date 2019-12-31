@extends('layouts.admin')
@section('title')
Engache a recibir
@endsection
@section('content')
<div class="content mt-3">
  <div class="card">
    <div class="card-body">
      @if (session()->has('msj'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
          <strong>{{ session('msj') }}</strong>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif
      {!! Form::open(array('route'=>'reportes_enganche_estimado', 'method'=>'get', 'autocomplete'=>'off')) !!}
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label for="proyecto_bs">Proyecto</label>
              <select class="form-control"  name="proyecto_bs" id="proyecto_bs">
                @foreach ($proyectos as $p)
                  @if ($p->id_proyecto == $request->proyecto_bs)
                    <option selected="true" value="{{ $p->id_proyecto }}">{{ $p->nombre }}</option>
                  @else
                    <option value="{{ $p->id_proyecto }}">{{ $p->nombre }}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>
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
              <label></label>
              <button type="submit" class="btn btn-info btn-block" ><span class="glyphicon"><i class="fas fa-search"></i></span></button>
            </div>
          </div>
        </div>
      {!! Form::close() !!}
      <div class="row mt-3">
        <div class="col-md-10">
            <h3><em>Proyecto {{ $proyecto_data->nombre_proyecto }}</em></h3>
        </div>
        <div class="col-md-2">
          <div class="input-group md-form form-sm form-4 pl-0">
          {{ Form::open(array('action'=>array('ReportesExcelController@exportExcelEnganches'),'method'=>'get')) }}
              <input type="hidden" class="form-control" placeholder="Proyecto" name="proyecto_excel" id="proyecto_excel" value="{{ $request->prospecto_bs}}">
              <input type="hidden" class="form-control" placeholder="Proyecto" name="fecha_minima_excel" id="fecha_minima_excel" value="{{ $request->fecha_minima_bs}}">
              <input type="hidden" class="form-control" placeholder="Proyecto" name="fecha_maxima_excel" id="fecha_maxima_excel" value="{{ $request->fecha_maxima_bs}}">
              <button class="btn-ico" style="font-size: 14pt;"><i class="fas fa-file-excel"></i></button>
          {!! Form::close() !!}
          {{ Form::open(array('action'=>array('ReportesPDFController@enganche_estimado_pdf'),'method'=>'get')) }}
              <input type="hidden" class="form-control" placeholder="Proyecto" name="proyecto_pdf" id="proyecto_pdf" value="{{ $request->prospecto_bs}}">
              <input type="hidden" class="form-control" placeholder="Proyecto" name="fecha_minima_pdf" id="fecha_minima_pdf" value="{{ $request->fecha_minima_bs}}">
              <input type="hidden" class="form-control" placeholder="Proyecto" name="fecha_maxima_pdf" id="fecha_maxima_pdf" value="{{ $request->fecha_maxima_bs}}">
              <button class="btn-ico" style="font-size: 14pt;"><i class="fas fa-file-pdf"></i></button>
          {!! Form::close() !!}
          </div>
        </div>
        <hr class="hr-titulo" width="100%" size="10">
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="table-responsive">
            <table class="table">
              <thead class="">
                <tr>
                  <th></th>
                  <th class="center">Total estimado</th>
                  <th class="center">Recibido</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Resumen</td>
                  <td class="center">$ {{ number_format($total_enganche , 2 , "." , ",") }}</td>
                  <td class="center">$ {{ number_format($total_pagado , 2 , "." , ",") }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="col-md-12">
          <div class="table-responsive">
            <table class="table">
              <thead class="">
                <tr>
                  <th>Prospecto</th>
                  <th class="center">Enganche estimado</th>
                  <th class="center">Recibido</th>
                  <th class="center">Estatus</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($resultados as $ele)
                <tr>
                  <td>{{ $ele->prospecto}}</td>
                  <td class="center">$ {{ number_format($ele->total , 2 , "." , ",") }}</td>
                  <td class="center">$ {{ number_format($ele->pagado , 2 , "." , ",") }}</td>
                  <td class="center">{{ $ele->estatus}}</td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <td style="text-align: right;">Total </td>
                  <td class="center">$ {{ number_format($total_enganche , 2 , "." , ",") }}</td>
                  <td class="center">$ {{ number_format($total_pagado , 2 , "." , ",") }}</td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
        <div class="col-md-6">
          <div id="donutEstatus" style="width: 500px; height: 300px;"></div>
        </div>
        <div class="col-md-6">
          <div id="donutEstatus_2" style="width: 500px; height: 300px;"></div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  $("#proyecto_bs").on('change', function(){
    desarrollo = $('#proyecto_bs').val();
    selectPropiedad = $('#propiedad_bs');
    selectPropiedad.empty().append('<option>Cargando las propiedades</option>');

    $.ajax({

    type: "GET",
    url: "/catalogo-propiedades-desarrollo-estatus/" + desarrollo,
    success: function(data) {
          var htmlOptions = [];
          if( data.length ){
              html = '<option value="Vacio"  selected="true">Selecciona una propiedad</option>';
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
    },
      error: function(error) {
      alert("No se pudo cargar el catalogo de propiedades");
    }
    })
  });
  $("#proyecto_bs").on('change', function(){
    variable = $("#proyecto_bs").val();
    $('#proyecto_pdf').val(variable);
  });
  $("#fecha_inicio").on('change', function(){
    variable = $("#fecha_inicio").val();
    $('#fecha_inicio_pdf').val(variable);
  });
  $("#fecha_final").on('change', function(){
    variable = $("#fecha_final").val();
    $('#fecha_final_pdf').val(variable);
  });
  $("#propiedad_bs").on('change', function(){
    variable = $("#propiedad_bs").val();
    $('#propiedad_pdf').val(variable);
  });
  function exportTableToExcel(tableID, filename = ''){
      var downloadLink;
      var dataType = 'application/vnd.ms-excel';
      var tableSelect = document.getElementById(tableID);
      var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
      
      // Specify file name
      filename = filename?filename+'.xls':'excel_data.xls';
      
      // Create download link element
      downloadLink = document.createElement("a");
      
      document.body.appendChild(downloadLink);
      
      if(navigator.msSaveOrOpenBlob){
          var blob = new Blob(['ufeff', tableHTML], {
              type: dataType
          });
          navigator.msSaveOrOpenBlob( blob, filename);
      }else{
          // Create a link to the file
          downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
      
          // Setting the file name
          downloadLink.download = filename;
          
          //triggering the function
          downloadLink.click();
      }
  }
</script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

@endpush 
@endsection