@extends('layouts.admin')
@section('title')
Cuenta por cobrar
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
      {!! Form::open(array('route'=>'cuenta_cobrar', 'method'=>'get', 'autocomplete'=>'off')) !!}
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label for="fecha_inicio">Fecha inicio</label>
              <input type="date" class="form-control" placeholder="Fecha incio" required name="fecha_inicio" id="fecha_inicio" value="{{ $request->fecha_inicio }}">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="fecha_final">Fecha final</label>
              <input type="date" class="form-control" placeholder="Fecha final"  required name="fecha_final" id="fecha_final" value="{{ $request->fecha_final }}">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="proyecto_bs">Proyecto</label>
              <select class="form-control"  name="proyecto_bs" id="proyecto_bs">
                <option value="Vacio">Selecciona</option>
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
              <label for="propiedad_bs">Propiedad</label>
              <select class="form-control selected"  name="propiedad_bs" id="propiedad_bs">
                <option value="Vacio">Selecciona</option>
                @foreach ($propiedades as $p)
                  @if ($p->id_propiedad == $request->propiedad_bs)
                    <option selected="true" value="{{ $p->id_propiedad }}">{{ $p->nombre }}</option>
                  @else
                    <option value="{{ $p->id_propiedad }}">{{ $p->nombre }}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-6 offset-md-3">
            <div class="form-group">
              <button type="submit" class="btn btn-info btn-block" ><span class="glyphicon"><i class="fas fa-search"></i></span></button>
            </div>
          </div>
        </div>
      {!! Form::close() !!}
      <div class="row">
        <div class="col-md-1 offset-md-8">
          <div class="form-group">
            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseGrafico" aria-expanded="false" aria-controls="collapseGrafico">Grafico</button>
          </div>
        </div>
        <div class="col-md-1">
          <div class="form-group">
            <a href="#page-bottom" class="btn btn-info"><i class="fas fa-angle-down"></i></a>
          </div>
        </div>
        <div class="col-md-1">
          <div class="form-group">
            {!! Form::open(array('route'=>'cuenta_cobrar_pdf', 'method'=>'get', 'autocomplete'=>'off')) !!}
            <input type="hidden" value="{{ $request->fecha_inicio}}" name="fecha_inicio_pdf" id="fecha_inicio_pdf">
            <input type="hidden" value="{{ $request->fecha_final }}" name="fecha_final_pdf" id="fecha_final_pdf">
            <input type="hidden" value="{{ $request->proyecto_bs }}" name="proyecto_pdf" id="proyecto_pdf">
            <input type="hidden" value="{{ $request->propiedad_bs }}" name="propiedad_pdf" id="propiedad_pdf">
            <button class="btn btn-block btn-danger"><i class="fas fa-file-pdf"></i></button>
            {!! Form::close() !!}
          </div>
        </div>
        <div class="col-md-1">
          <div class="form-group">
            <button class="btn  btn-block btn-success" onclick="exportTableToExcel('dataExport', 'cuenta_por_cobrar')"><i class="fas fa-file-excel"></i></button>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <canvas id="BarCuenta" width="301" height="253" class="chartjs-render-monitor" style="display: block; width: 301px; height: 253px;"></canvas>
        </div>
      </div>
      <div class="table-responsive ">
        <table class="table table-hover table-withoutborder" id="dataExport">
          <thead class="thead-grubsa ">
            <th class="center">
              Proyecto
            </th>
            <th class="center">
              Propiedad
            </th>
            <th class="center">
              Cliente
            </th>
            <th class="center">
              Mes
            </th>
            <th class="center">
              Pagado
            </th>
            <th class="center">
              Fecha pago
            </th>
            <th class="center">
              Saldo
            </th>
          </thead>
          <tbody>
            @foreach ($resultados as $r)
            @php
              if ($r->mes=='01') {$mesLetra='Ene';}
              if ($r->mes=='02') {$mesLetra='Feb';}
              if ($r->mes=='03') {$mesLetra='Mar';}
              if ($r->mes=='04') {$mesLetra='Abr';}
              if ($r->mes=='05') {$mesLetra='May';}
              if ($r->mes=='06') {$mesLetra='Jun';}
              if ($r->mes=='07') {$mesLetra='Jul';}
              if ($r->mes=='08') {$mesLetra='Ago';}
              if ($r->mes=='09') {$mesLetra='Sep';}
              if ($r->mes=='10') {$mesLetra='Oct';}
              if ($r->mes=='11') {$mesLetra='Nov';}
              if ($r->mes=='12') {$mesLetra='Dic';}
            @endphp
            <tr style="font-size: 10pt;">
              <td class="center">
                {{ $r->proyecto }}
              </td>
              <td class="center">
                {{ $r->propiedad }}
              </td>
              <td class="center">
                {{ $r->cliente }}
              </td>
              <td class="center">
                {{ $mesLetra }}
              </td>
              <td class="center">
                ${{ number_format($r->pagado , 2 , "." , ",") }}
              </td>
              <td class="center">
                @if ($r->fecha_pago != null)
                  {{ date('Y-m-d',strtotime($r->fecha_pago)) }}
                @endif
              </td>
              <td class="center">
                ${{ number_format($r->saldo , 2 , "." , ",") }}
              </td>
            </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <th class="center">
              </th>
              <th class="center">
              </th>
              <th class="center">
              </th>
              <th class="center">
              </th>
              <th class="center">
               ${{ number_format($sumapagado , 2 , "." , ",") }}
              </th>
              <th class="center">
              </th>
              <th class="center">
               ${{ number_format($sumasaldo , 2 , "." , ",") }}
              </th>
            </tr>
          </tfoot>
        </table>
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

  // Bar Horizontal Chart Estatus
    var ctx = document.getElementById("BarCuenta");
    var BarCuenta = new Chart(ctx, {
      type: 'horizontalBar',
      data: {
        labels: [
        @foreach ($resultados_g as $r)
          '{{ $r->cliente }}',
        @endforeach
        ],
        datasets: [
          {
            label: "Pagado",
            backgroundColor: "#4e73df",
            data: [
            @foreach ($resultados_g as $r)
              {{ $r->pagado_group }},
            @endforeach
            ],
          },
          {
            label: "Saldo",
            backgroundColor: "#c3ecee",
            data: [
            @foreach ($resultados_g as $r)
              {{ $r->saldo_group }},
            @endforeach
            ],
          }
        ],
      },
      options : {
        maintainAspectRatio: false,
         responsive: true,
        scales: {
            xAxes: [{
                ticks:{
                    stepSize : 100000,

                },
                stacked: true,
                gridLines: {
                      lineWidth: 1,
                      color: "rgba(255,255,255,0)"
                }
            }],
            yAxes: [{
                barThickness: 6,
                maxBarThickness: 8,
                minBarLength: 4,
                stacked: true,
                 ticks: {
                    min: 0,
                    stepSize: 1,
                },
            }]
        },
        tooltips: {
          titleMarginBottom: 10,
          titleFontColor: '#6e707e',
          titleFontSize: 14,
          backgroundColor: "rgb(255,255,255)",
          bodyFontColor: "#858796",
          borderColor: '#dddfeb',
          borderWidth: 1,
          xPadding: 15,
          yPadding: 15,
          displayColors: false,
          caretPadding: 10,
        },
      }
    });
</script>

@endpush 
@endsection