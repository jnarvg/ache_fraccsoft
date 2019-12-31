 @extends('layouts.admin')
@section('title')
Dashboard
@endsection
@section('content')

<div class="row">
  @if (\Auth()->user()->rol == 6 /*Cobrazana*/)
    <div class=" col-md-2">
        <a href="{{ route('v_pagando') }}">
            <div class="social-box bg-grubsa text-primary">
                <i class="fas fa-users"></i>
                <span class="text-grubsa-clear">Pagando</span>
            </div>
        </a>
    </div>
    <div class=" col-md-2">
        <a href="{{ route('plazos_pago') }}">
            <div class="social-box bg-grubsa text-primary">
                <i class="fas fa-money-bill-wave"></i>
                <span class="text-grubsa-clear">Plazos de pago</span>
            </div>
        </a>
    </div>
    <div class=" col-md-2">
        <a href="{{ route('reportes') }}">
            <div class="social-box bg-grubsa text-primary">
                <i class="fas fa-chart-bar"></i>
                <span class="text-grubsa-clear">Reportes</span>
            </div>
        </a>
    </div>
  @else
    @if (auth()->user()->rol == 3)
        <div class=" col-md-2">
            <a href="{{ route('propiedades') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-home"></i>
                    <span class="text-grubsa-clear">Propiedades</span>
                </div>
            </a>
        </div>
    @endif
    <div class=" col-md-2">
        <a href="{{ route('prospectos') }}">
            <div class="social-box bg-grubsa text-primary">
                <i class="fas fa-users"></i>
                <span class="text-grubsa-clear">Todos</span>
            </div>
        </a>
    </div>
    <div class=" col-md-2">
        <a href="{{ route('plazos_pago') }}">
            <div class="social-box bg-grubsa text-primary">
                <i class="fas fa-money-bill-wave"></i>
                <span class="text-grubsa-clear">Plazos de pago</span>
            </div>
        </a>
    </div>
    @if (auth()->user()->rol == 3)
        <div class=" col-md-2">
            <a href="{{ route('comision') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-percentage"></i>
                    <span class="text-grubsa-clear">Comisiones</span>
                </div>
            </a>
        </div>
        <div class=" col-md-2">
            <a href="{{ route('mensaje') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-envelope-open-text"></i>
                    <span class="text-grubsa-clear">Mensajes</span>
                </div>
            </a>
        </div>
    @endif
    <div class=" col-md-2">
        <a href="{{ route('actividad') }}">
            <div class="social-box bg-grubsa text-primary">
                <i class="fas fa-phone"></i>
                <span class="text-grubsa-clear">Actividades</span>
            </div>
        </a>
    </div>
    <div class=" col-md-2">
        <a href="{{ route('calendario') }}">
            <div class="social-box bg-grubsa text-primary">
                <i class="fas fa-calendar-alt"></i>
                <span class="text-grubsa-clear">Calendario</span>
            </div>
        </a>
    </div>
    <div class=" col-md-2">
        <a href="{{ route('cotizacion') }}">
            <div class="social-box bg-grubsa text-primary">
                <i class="fas fa-calculator"></i>
                <span class="text-grubsa-clear">Cotizacion</span>
            </div>
        </a>
    </div>
    <!--/.col-->
    <div class=" col-md-2">
        <a href="{{ route('plano') }}">
            <div class="social-box bg-grubsa text-primary">
                <i class="fas fa-code-branch"></i>
                <span class="text-grubsa-clear">Plano</span>
            </div>
        </a>
    </div>
    <div class=" col-md-2">
        <a href="{{ route('documentos') }}">
            <div class="social-box bg-grubsa text-primary">
                <i class="fas fa-file-alt"></i>
                <span class="text-grubsa-clear">Documentos</span>
            </div>
        </a>
    </div>
    <div class=" col-md-2">
        <a href="{{ route('reportes') }}">
            <div class="social-box bg-grubsa text-primary">
                <i class="fas fa-chart-bar"></i>
                <span class="text-grubsa-clear">Reportes</span>
            </div>
        </a>
    </div>
    <div class=" col-md-2">
        <a href="{{ route('importar') }}">
            <div class="social-box bg-grubsa text-primary">
                <i class="fas fa-upload"></i>
                <span class="text-grubsa-clear">Importar</span>
            </div>
        </a>
    </div>
    <!--/.col-->
    <div class=" col-md-2">
        <a href="{{ route('catalogos') }}">
            <div class="social-box bg-grubsa text-primary">
                <i class="fas fa-server"></i>
                <span class="text-grubsa-clear">Catalogo</span>
            </div>
        </a>
    </div>
  @endif
</div>

{{-- tarjetas de prospectos --}}
@if (\Auth()->user()->rol != 6)
<div class="row">
  <div class="col-md-3">
    <div class="box aligncenter">
      <a href="{{ route('v_prospecto') }}">
        <div class="icon">
          <span class="badge badge-primary badge-circled">{{ $prospecto_prospecto->contador }}</span>
          <i class="fas fa-user-tie icon-4x"></i>
        </div>
      </a>
      <div class="text">
        <h4><strong>Prospecto</strong></h4>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="box aligncenter">
      <a href="{{ route('v_apartado') }}">
        <div class="icon">
          <span class="badge badge-primary badge-circled">{{ $prospecto_apartado->contador }}</span>
          <i class="fas fa-hands-helping icon-4x"></i>
        </div>
      </a>
      <div class="text">
        <h4><strong>Apartado</strong></h4>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="box aligncenter">
      <a href="{{ route('v_contrato') }}">
        <div class="icon">
          <span class="badge badge-primary badge-circled">{{ $prospecto_contrato->contador }}</span>
          <i class="fas fa-file-contract icon-4x"></i>
        </div>
      </a>
      <div class="text">
        <h4><strong>Contrato</strong></h4>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="box aligncenter">
      <a href="{{ route('v_pagando') }}">
        <div class="icon">
          <span class="badge badge-primary badge-circled">{{ $prospecto_pagando->contador }}</span>
          <i class="fas fa-dollar-sign icon-4x"></i>
        </div>
      </a>
      <div class="text">
        <h4><strong>Pagando</strong></h4>
      </div>
    </div>
  </div>
  @if (auth()->user()->rol == 3)
  <div class="col-md-3">
    <div class="box aligncenter">
      <a href="{{ route('v_escriturado') }}">
        <div class="icon">
          <span class="badge badge-primary badge-circled">{{ $prospecto_escriturado->contador }}</span>
          <i class="fas fa-file-signature icon-4x"></i>
        </div>
      </a>
      <div class="text">
        <h4><strong>Escriturado</strong></h4>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="box aligncenter">
      <a href="{{ route('v_no_escriturado') }}">
        <div class="icon">
          <span class="badge badge-primary badge-circled">{{ $prospecto_noescriturado->contador }}</span>
          <i class="fas fa-file-alt icon-4x"></i>
        </div>
      </a>
      <div class="text">
        <h4><strong>No escriturado</strong></h4>
      </div>
    </div>
  </div>
  @endif
  <div class="col-md-3">
    <div class="box aligncenter">
      <a href="{{ route('v_perdido') }}">
        <div class="icon">
          <span class="badge badge-primary badge-circled">{{ $prospecto_perdido->contador }}</span>
          <i class="fas fa-user-alt-slash icon-4x"></i>
        </div>
      </a>
      <div class="text">
        <h4><strong>Perdido</strong></h4>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="box aligncenter">
      <a href="{{ route('v_postergado') }}">
        <div class="icon">
          <span class="badge badge-primary badge-circled">{{ $prospecto_postergado->contador }}</span>
          <i class="fas fa-user-clock icon-4x"></i>
        </div>
      </a>
      <div class="text">
        <h4><strong>Postergado</strong></h4>
      </div>
    </div>
  </div>
</div>
<div class="row mt-3"> 
  <div class="col-md-12 mt-3">
    <hr>
    <h3 class="text-center">Prospectos dados de alta mensualmente</h3>
  </div>
  <div class="col-md-12">
    <canvas id="barLeadMensuales" width="301" height="400" class="chartjs-render-monitor" style="display: block; width: 301px; height: 400px;"></canvas>
  </div>
</div>
@endif
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
    var ctx = document.getElementById("barLeadMensuales");
    var barLeadMensuales = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: [
        @foreach ($resultados as $r)
          '{{ $r->label }}',
        @endforeach
        ],
        datasets: [{
          label: "Cantidad",
          backgroundColor: "#4e73df",
          data: [
          @foreach ($resultados as $r)
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
                barThickness: 20,
                maxBarThickness: 40,
                minBarLength: 10,
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
</script>
@endpush 
@endsection