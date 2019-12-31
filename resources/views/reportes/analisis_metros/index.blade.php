@extends('layouts.admin')
@section('title')
Analisis metros vendidos
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
      {!! Form::open(array('route'=>'reportes_analisis_metros', 'method'=>'get', 'autocomplete'=>'off')) !!}
        <div class="row">
          <div class="col-md-4">
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
          <div class="col-md-4">
            <div class="form-group">
              <label for="uso_propiedad_bs">Uso de propiedad</label>
              <select class="form-control"  name="uso_propiedad_bs" id="uso_propiedad_bs">
                <option value="Todos">Todos</option>
                @foreach ($usos_propiedad as $p)
                  @if ($p->uso_propiedad == $request->uso_propiedad_bs)
                    <option selected="true" value="{{ $p->uso_propiedad }}">{{ $p->uso_propiedad }}</option>
                  @else
                    <option value="{{ $p->uso_propiedad }}">{{ $p->uso_propiedad }}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label></label>
              <button type="submit" class="btn btn-info btn-block" ><span class="glyphicon"><i class="fas fa-search"></i></span></button>
            </div>
          </div>
        </div>
      {!! Form::close() !!}
      <div class="row mt-3">
        <div class="col-md-12">
            <h3><em>Proyecto {{ $proyecto_data->nombre_proyecto }}</em></h3>
            <h5><em>Uso de propiedad - {{ $request->uso_propiedad_bs }}</em></h5>
            <hr class="hr-titulo" width="100%" size="10">
        </div>
        <div class="col-md-6">
          <div class="table-responsive">
            <table class="table">
              <thead class="">
                <tr>
                  <th>Estatus</th>
                  <th class="center">Metros</th>
                  <th class="center">% </th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data_mts_total as $ele)
                <tr>
                  <td>{{ $ele[0]}}</td>
                  <td class="center">{{ $ele[1] }}</td>
                  <td class="center">{{ $ele[2] }}%</td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <td style="text-align: right;">Total </td>
                  <td class="center">{{ $total_mts_totales }}</td>
                  <td class="center">{{ $porcentaje_mts_total_total }}%</td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label>Total Metros Terreno</label>
            <input type="numeric" class="form-control" id="total_terreno" name="total_terreno" value="{{ $proyecto_data->metros_terreno }}">
          </div>
        </div>
        <div class="col-md-12">
          <canvas id="myPieEstatus" width="301" height="253" class="chartjs-render-monitor" style="display: block; width: 301px; height: 253px;"></canvas>
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
<script type="text/javascript">
  // Set new default font family and font color to mimic Bootstrap's default styling
  Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
  Chart.defaults.global.defaultFontColor = '#858796';

  function number_format(number, decimals, dec_point, thousands_sep) {
    // *     example: number_format(1234.56, 2, ',', ' ');
    // *     return: '1 234,56'
    number = (number + '').replace(',', '').replace(' ', '');
    var n = !isFinite(+number) ? 0 : +number,
      prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
      sep = (typeof  thousands_sep === 'undefined') ? ',' : thousands_sep,
      dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
      s = '',
      toFixedFix = function(n, prec) {
        var k = Math.pow(10, prec);
        return '' + Math.round(n * k) / k;
      };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
      s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
      s[1] = s[1] || '';
      s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
  }

  // PIE CHART Tipos de oportunidad
    var ctx = document.getElementById("myPieEstatus");
    var myPieEstatus = new Chart(ctx, {
      type: 'doughnut',
      data: { 
        labels:[ 
          @foreach ($data_mts_total as $r)
            '{{ $r[0] }}',
          @endforeach
          ],
        datasets: [{
            data: [ 
              @foreach ($data_mts_total as $r)
              {{ $r[1] }},
              @endforeach
            ],
            backgroundColor: [
              @foreach ($colores_A as $r)
              '{{ $r }}',
              @endforeach
            ],
        }],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        tooltips: {
          backgroundColor: "rgb(255,255,255)",
          bodyFontColor: "#858796",
          borderColor: '#dddfeb',
          borderWidth: 1,
          xPadding: 15,
          yPadding: 15,
          displayColors: true,
          caretPadding: 10,
        },
        legend: {
          display: true
        },
        cutoutPercentage: 80,
      },
    });
</script>
@endpush 
@endsection