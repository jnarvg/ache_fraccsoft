@extends('layouts.admin')
@section('title')
Oportunidades
@endsection
@section('filter')
<button href="#" data-toggle="collapse" data-target="#filtros" role="button" aria-expanded="false" aria-controls="filtros" class=" d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-filter"></i></button>
@endsection
@section('content')
<div class="content mt-3">
  <div class="card collapse" id="filtros">
    <div class="card-body">
      {!! Form::open(array('route'=>'reportes_oportunidades', 'method'=>'get', 'autocomplete'=>'off')) !!}
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
      <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <h3><em>Estatus de oportunidades</em></h3>
                 <hr class="hr-titulo" width="100%" size="10">
            </div>
        </div>
        <div class="col-md-12">
          <canvas id="barEstatus" width="301" height="250" class="chartjs-render-monitor" style="display: block; width: 301px; height: 250px;"></canvas>
        </div>
      </div> 
      <div class="row mt-3">
        <div class="col-md-12">
            <div class="form-group">
                <h3><em>Oportunidades por asesor</em></h3>
                 <hr class="hr-titulo" width="100%" size="10">
            </div>
        </div>
        <div class="col-md-12">
          <canvas id="barPorAsesor" width="301" height="253" class="chartjs-render-monitor" style="display: block; width: 301px; height: 253px;"></canvas>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-md-12">
            <div class="form-group">
                <h3><em>Oportunidades en Alta, Cotizacion y Apartado</em></h3>
                 <hr class="hr-titulo" width="100%" size="10">
            </div>
        </div>
        <div class="col-md-12">
          <canvas id="barAltaCotizacionApartados" width="301" height="253" class="chartjs-render-monitor" style="display: block; width: 301px; height: 253px;"></canvas>
        </div>
        <div class="col-md-12 mt-3">
            <div class="form-group">
                <h3><em>Ventas mensuales</em></h3>
                 <hr class="hr-titulo" width="100%" size="10">
            </div>
        </div>
        <div class="col-md-12">
          <canvas id="barVentasMensuales" width="301" height="400" class="chartjs-render-monitor" style="display: block; width: 301px; height: 400px;"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
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

  // Bar Vertical Chart Estatus
    var ctx = document.getElementById("barEstatus");
    var barEstatus = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: [
        @foreach ($estatus_prospecto as $r)
          '{{ $r->label }}',
        @endforeach
        ],
        datasets: [{
          label: "Cantidad",
          backgroundColor: "#4e73df",
          data: [
          @foreach ($estatus_prospecto as $r)
            {{ $r->cantidad }},
          @endforeach
          ],
        }],
      },
      options : {
        maintainAspectRatio: false,
         responsive: true,
        scales: {
            xAxes: [{
                ticks:{
                    stepSize : 10,

                },
                barThickness: 6,
                maxBarThickness: 8,
                minBarLength: 2,
                stacked: true,
                gridLines: {
                      lineWidth: 0,
                      color: "rgba(255,255,255,0)"
                }
            }],
            yAxes: [{

                stacked: true,
                 ticks: {
                    min: 0,
                    stepSize: 20,
                }

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
  // Bar Horizontal Chart Asesor
    var ctx = document.getElementById("barPorAsesor");
    var barPorAsesor = new Chart(ctx, {
      type: 'horizontalBar',
      data: {
        labels: [
        @foreach ($por_asesor as $r)
          '{{ $r->label }}',
        @endforeach
        ],
        datasets: [{
          label: "Cantidad",
          backgroundColor: "#44C6A5",
          data: [
          @foreach ($por_asesor as $r)
            {{ $r->cantidad }},
          @endforeach
          ],
        }],
      },
      options : {
        maintainAspectRatio: false,
         responsive: true,
        scales: {
            xAxes: [{
                ticks:{
                    stepSize : 5,

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
  // Bar Vertical Chart ALTA COTIZACION APARTADO
    var ctx = document.getElementById("barAltaCotizacionApartados");
    var barAltaCotizacionApartados = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['{{ $yearMes }}'],
        display: true,
        datasets: [
          {
            label: "Alta",
            backgroundColor: "#23c8b2",
            data: [
            @foreach ($oportunidad_alta as $r)
              {{ $r->cantidad }},
            @endforeach
            ],
          },
          {
            label: "Cotizada",
            backgroundColor: "#c3ecee",
            data: [
            @foreach ($oportunidad_cotizacion as $r)
              {{ $r->cantidad }},
            @endforeach
            ],
          },
          {
            label: "Apartadas",
            backgroundColor: "#bcefd0",
            data: [
            @foreach ($oportunidad_apartada as $r)
              {{ $r->cantidad }},
            @endforeach
            ],
          },
        ],
      },
      options : {
        maintainAspectRatio: false,
         responsive: true,
        scales: {
            xAxes: [{
                ticks:{
                    stepSize : 10,

                },
                barThickness: 6,
                maxBarThickness: 8,
                minBarLength: 2,
                stacked: false,
                gridLines: {
                      lineWidth: 0,
                      color: "rgba(255,255,255,0)"
                }
            }],
            yAxes: [{

                stacked: false,
                 ticks: {
                    min: 0,
                    stepSize: 10,
                }

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
  // Bar Vertical Chart Ventas mensuales
    var ctx = document.getElementById("barVentasMensuales");
    var barVentasMensuales = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['{{ $yearMes }}'],
        display: true,
        datasets: [
          {
            label: "Cantidad",
            backgroundColor: "#23c8b2",
            data: [
            @foreach ($ventas_mensuales as $r)
              {{ $r->cantidad }},
            @endforeach
            ],
          },
          {
            label: "Monto",
            backgroundColor: "#c3ecee",
            data: [
            @foreach ($ventas_mensuales as $r)
              {{ $r->suma_venta }},
            @endforeach
            ],
          },
        ],
      },
      options : {
        maintainAspectRatio: false,
         responsive: true,
        scales: {
            xAxes: [{
                ticks:{
                    stepSize : 1,

                },
                barThickness: 20,
                maxBarThickness: 30,
                minBarLength: 6,
                stacked: true,
                gridLines: {
                      lineWidth: 0,
                      color: "rgba(255,255,255,0)"
                }
            }],
            yAxes: [{
                maxTicksLimit: 5000000,
                stacked: true,
                 ticks: {
                    min: 0,
                    stepSize: 500000,
                }

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