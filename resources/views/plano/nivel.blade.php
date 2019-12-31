@extends('layouts.admin')
@section('title')
Plano por niveles
@endsection
@section('content')
<div class="content mt-3">
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-md-6 offset-md-6">
          {!! Form::open(array('route'=>'plano', 'method'=>'get', 'autocomplete'=>'off')) !!}
          <div class="input-group md-form form-sm form-4 pl-0">
            <select class="form-control"  name="proyecto_bs" id="proyecto_bs" >
              @foreach ($proyectos as $py)
                @if ($py->id_proyecto == $one_nivel->proyecto_id )
                  <option selected value="{{ $py->id_proyecto }}">{{ $py->nombre }}</option>
                @else
                  <option value="{{ $py->id_proyecto }}">{{ $py->nombre }}</option>
                @endif
              @endforeach
            </select>
            <button type="submit" class="btn btn-info" ><span class="glyphicon"><i class="fas fa-search"></i></span></button>
          </div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
  @if (!empty($one_nivel))
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div  class="col-lg-12" align="center">
          <h4>{{ $one_nivel->nombre.' - '.$one_nivel->nivel }}</h4>
          <hr>
        </div>
      </div>
      @if (!empty($niveles))
        <div class="row">
          @foreach ($niveles as $n)
            <div  class="col-lg-3" align="center">
              <div class="form-group">
                <a href="{{ route('plano-nivel',$n->id_nivel) }}" class="btn btn-outline-primary btn-block btn-sm">
                  {{ $n->nivel }}
                </a>
              </div>
            </div>
          @endforeach
        </div>
      @endif
      @if ($one_nivel->plano == null or $one_nivel->plano == '')
        <div class="row">
          <div  class="col-lg-12" align="center">
            <p>No hay plano cargado para este nivel</p>
          </div>
        </div>
      @else
        @if ($one_nivel->plano != '')
          <div class="row">
            <div  class="col-lg-12 scrolling">
              <hr>
              <img class="map" width="1200" height="800" src="{{ asset($one_nivel->plano) }}" usemap="#image-map" style="position: relative;">
              <map name="image-map">
                @foreach ($propiedades as $unidad)
                  <area data-maphilight='{"fillColor":"{{ substr($unidad->codigo_hx, 1) }}","shadow":true,"shadowBackground":"{{ substr($unidad->codigo_hx, 1) }}","alwaysOn":true}' href="#" target="" alt="{{ $unidad->nombre }}" title="{{ $unidad->nombre }}" data-toggle="modal" data-target="#modal{{ $unidad->id_propiedad }}" coords="{{ $unidad->coordenadas}}" shape="poly">
                  <!-- The Modal -->
                  <div class="modal" id="modal{{ $unidad->id_propiedad }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                      <div  class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                        
                          <!-- Modal Header -->
                          <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLongTitle">{{ $unidad->nombre}}</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                          </div>
                          
                          <!-- Modal body -->
                          <div class="modal-body">
                              <div class="container-fluid">
                                  <div class="row">
                                      <div class="col-md-12">
                                        <ul class="lista-char" style="list-style:none; text-align: center;">
                                          <li>Modelo: {{ $unidad->tipo_propiedad }}</li>
                                          <li>Estatus: {{ $unidad->estatus_propiedad }}</li>
                                          <li>Metros terreno: {{ $unidad->terreno_metros }}</li>
                                          <li>Metros construccion: {{ $unidad->construccion_metros }}</li>
                                          @if ($unidad->precio != null)
                                          <li>Precio: $ {{ number_format($unidad->precio , 2 , "." , ",") }}</li>
                                          @endif
                                        </ul>   
                                      </div>
                                  </div>
                              </div>
                          </div>
                          
                          <!-- Modal footer -->
                          <div class="modal-footer">
                              <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
                              @if ($unidad->estatus_propiedad == 'Disponible')
                              <a href="#" data-toggle="modal" data-target="#modal_prospecto{{ $unidad->id_propiedad }}"><button class="btn btn-info">Nuevo prospecto</button></a>
                              @endif
                              <a href="{{URL::action('PlanoController@show',$unidad->id_propiedad)}}"><button class="btn btn-success">Ver mas</button></a>
                          </div>
                          
                        </div>
                      </div>
                  </div>
                  @include('plano.modal_prospecto')
                @endforeach
              </map>
            </div>
          </div>
        @endif
      @endif
    </div>
  </div>
  @endif
</div>

@push('scripts')
  <script>
    jQuery(function() {
        jQuery.fn.maphilight.defaults = {
            fill: true,
            fillColor: '000000',
            fillOpacity: 0.2,
            stroke: true,
            strokeColor: '7D1612',
            strokeOpacity: 0.5,
            strokeWidth: 1,
            fade: true,
            alwaysOn: false,
            neverOn: false,
            groupBy: false,
            wrapClass: true,
            shadow: false,
            shadowX: 0,
            shadowY: 0,
            shadowRadius: 6,
            shadowColor: '000000',
            shadowOpacity: 0.8,
            shadowPosition: 'outside',
            shadowFrom: false
        }
        jQuery('.map').maphilight({
      
        });
       
    });
  </script>
@endpush 
@endsection