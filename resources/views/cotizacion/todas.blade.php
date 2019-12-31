@extends('layouts.admin')
@section('title')
Todas las cotizaciones
@endsection
@section('filter')
  <a href="{{ route('cotizacion') }}"><button class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm">Nueva cotizacion</button></a>
  <button href="#" data-toggle="collapse" data-target="#filtros" role="button" aria-expanded="false" aria-controls="filtros" class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-filter"></i></button>
@endsection
@section('content')
<div class="content mt-3">
  <div class="card collapse" id="filtros">
    <div class="card-body">
      {!! Form::open(array('route'=>'cotizacion-todas', 'method'=>'get', 'autocomplete'=>'off')) !!}
        <div class="row">
          <div class="col-md-3">
            <div class="form-group ">
              <label>Cliente</label>
              <input type="text" class="form-control" placeholder="Nombre" name="cliente_bs" id="cliente_bs" value="{{ $request->cliente_bs }}">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group ">
              <label>Propiedad</label>
              <input type="text" class="form-control" placeholder="Propiedades" name="propiedad_bs" id="propiedad_bs" value="{{ $request->propiedad_bs }}">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group ">
              <label>Fecha</label>
              <input type="date" class="form-control" name="fecha_bs" id="fecha_bs" value="{{ $request->fecha_bs }}">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group ">
              <label>Estatus</label>
              <select class="form-control" name="estatus_bs" id="estatus_bs">
                <option value="Vacio">Todos</option>
                @foreach ($estatus as $rp)
                @if ($rp == $request->estatus_bs)
                <option selected value="{{ $rp }}">{{ $rp }}</option>
                @else
                <option value="{{ $rp }}">{{ $rp }}</option>
                @endif
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group ">
              <button type="submit" class="btn btn-info mt-4" ><span class="glyphicon"><i class="fas fa-search"></i></span></button>
            </div>
          </div>
        </div>
      {!! Form::close() !!}
    </div>
  </div>
  <div class="card">
      <div class="card-body" >
          <div class="table-responsive">
            <table class="table text-sm table-hover"  id="tabla_cotizaciones">
              <thead class="bg-gray-300">
                <tr>
                  <td class="text-center">Estatus</td>
                  <td class="text-center">Fecha</td>
                  <td class="text-center">Proyecto</td>
                  <td class="text-center">Propiedad</td>
                  <td class="text-center">Cliente</td>
                  <td class="text-center">Telefono</td>
                  <td class="text-center">Correo</td>
                  <td class="text-center">Moneda</td>
                  <td class="text-center">Acciones</td>
                </tr>
              </thead>
              <tbody>
                @foreach ($cotizaciones as $c)
                  <tr>
                    <td class="text-center">
                      <a class="text-dark" href="{{URL::action('CotizacionController@show', [ $c->id_cotizacion, 'Menu' ] )}}" >{{ $c->estatus }}</a></td>
                    <td class="text-center">
                      <a class="text-dark" href="{{URL::action('CotizacionController@show', [ $c->id_cotizacion, 'Menu' ] )}}" >{{ date('Y-M-d', strtotime($c->fecha_cotizacion)) }}
                      </a></td>
                    <td class="text-center">
                      <a class="text-dark" href="{{URL::action('CotizacionController@show', [ $c->id_cotizacion, 'Menu' ] )}}">{{ $c->proyecto }}</a></td>
                    <td class="text-center">
                      <a class="text-dark" href="{{URL::action('CotizacionController@show', [ $c->id_cotizacion, 'Menu' ] )}}">{{ $c->propiedad }}</a></td>
                    <td class="text-center">
                      <a class="text-dark" href="{{URL::action('CotizacionController@show', [ $c->id_cotizacion, 'Menu' ] )}}">{{ $c->cliente }}</a></td>
                    <td class="text-center">
                      <a class="text-dark" href="{{URL::action('CotizacionController@show', [ $c->id_cotizacion, 'Menu' ] )}}">{{ $c->telefono }}</a></td>
                    <td class="text-center">
                      <a class="text-dark" href="{{URL::action('CotizacionController@show', [ $c->id_cotizacion, 'Menu' ] )}}">{{ $c->correo }}</a></td>
                    <td class="text-center">
                      <a class="text-dark" href="{{URL::action('CotizacionController@show', [ $c->id_cotizacion, 'Menu' ] )}}">{{ $c->moneda }}</a></td>
                    <td class="center-acciones">
                      @if ($c->estatus == 'Cerrada')
                        <a href="{{URL::action('CotizacionController@abierta', $c->id_cotizacion)}}" ><button class="btn-ico" ><i class="fas fa-times"></i></button></a>
                      @else
                      <a href="{{URL::action('CotizacionController@cerrar', $c->id_cotizacion)}}" ><button class="btn-ico" ><i class="fas fa-check"></i></button></a>
                      @endif
                      @if (auth()->user()->rol == '3')
                      <a href="#" data-target="#modal-delete{{$c->id_cotizacion}}" data-toggle="modal"><button class="btn-ico"><i class="fas fa-trash"></i></button></a>
                      @endif
                    </td>
                  </tr>
                  @include('cotizacion.modal')
                @endforeach
              </tbody>
            </table>
          </div>
          {{-- {{$cotizaciones->appends(Request::only('cliente_bs','propiedad_bs','estatus_bs','fecha_bs','rows_per_page'))->render()}} --}}
      </div>
  </div>
  <div class="card mt-3">
      <div class="card-body" >
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                  <h3><em>Estatus de las cotizaciones</em></h3>
                   <hr class="hr-titulo" width="100%" size="10">
              </div>
            </div>
            <div class="col-md-6">
              <canvas id="barPorEstatus" width="301" height="253" class="chartjs-render-monitor" style="display: block; width: 301px; height: 253px;"></canvas>
            </div>
          </div>
      </div>
  </div>
</div>

@push('scripts')
<script >
    jQuery(document).ready(function($)
    {
      $('#tabla_cotizaciones').DataTable();
        $(".preview").click(function(){
          var srcimagen = $(this).attr('src');
          var tituloimagen = $(this).attr('title');
          $("#preview-img").attr('src',srcimagen);
          $("#preview-img").attr('alt',tituloimagen);
          $("#preview-text").html(tituloimagen);
          $("#modal-preview").modal();
        });

    });

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
    var ctx = document.getElementById("barPorEstatus");
    var barPorEstatus = new Chart(ctx, {
      type: 'horizontalBar',
      data: {
        labels: [
        @foreach ($resultados as $r)
          '{{ $r->label }}',
        @endforeach
        ],
        datasets: [{
          label: "Cantidad",
          backgroundColor: "#738D9F",
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
</script>
@endpush 
@endsection