@extends('layouts.admin')
@section('title')
Plano
@endsection
@section('content')
<div class="content mt-3">    
    <div class="card">
        <div class="card-header bg-slider-black">
            <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
              <div class="carousel-inner" align="center">
                @php
                    $i = 0;
                @endphp
                @foreach ($imagenes_propiedad as $imagen)
                    @if ($i == 0)
                        <div class="carousel-item active">
                          <img class="d-block img-slider" src="{{ asset($imagen->imagen_path) }}" alt="@php echo substr($imagen->imagen_path, 55); @endphp">
                        </div>
                    @else
                        <div class="carousel-item">
                          <img class="d-block img-slider" src="{{ asset($imagen->imagen_path) }}" alt="@php echo substr($imagen->imagen_path, 55); @endphp">
                        </div>
                    @endif
                    @php
                        $i++;
                    @endphp
                @endforeach
              </div>
              <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
              </a>
              <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
              </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="card ">
                      <div class="card-header text-center card-header-grubsa">
                        {{ $propiedad->nombre }}
                      </div>
                      <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h2 class="card-title text-center">Caracteristicas de la propiedad</h2>
                                <hr class="hr-titulo" width="100%" size="10">
                            </div>
                            <div class="col-md-6">
                                <p class="card-text"><b>Tipo de propiedad: </b> {{ $propiedad->tipo_propiedad }}</p>
                                <p class="card-text oculto"><b>Uso de propiedad: </b> {{ $propiedad->uso_propiedad }}</p>
                                <p class="card-text"><b>Estatus: </b> {{ $propiedad->estatus_propiedad }}</p>
                                <p class="card-text"><b>Proyecto: </b> {{ $propiedad->proyecto }}</p>
                                <p class="card-text"><b>Precio: </b> $ {{ number_format($propiedad->precio , 2 , "." , ",").' '.$propiedad->siglas }}</p>
                                <p class="card-text oculto"><b>Baños: </b> {{ $propiedad->banos }}</p>
                                <p class="card-text oculto"><b>Recamaras: </b> {{ $propiedad->recamaras }}</p>
                                <p class="card-text"><b>Cajones: </b> {{ $propiedad->cajones_estacionamiento }}</p>
                                <p class="card-text oculto"><b>Vigilancia: </b> {{ $propiedad->vigilancia }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="card-text"><b>Descripcion: </b> {{ $propiedad->descripcion_corta }}</p>
                                <p class="card-text oculto"><b>Condicion: </b> {{ $propiedad->condicion }}</p>
                                <p class="card-text"><b>Construccion: </b> {{ $propiedad->construccion }}</p>
                                <p class="card-text"><b>Acabados: </b> {{ $propiedad->acabados }}</p>
                                <p class="card-text"><b>Estacionamiento: </b> {{ $propiedad->estacionamiento }}</p>
                                <p class="card-text"><b>Metros de terreno: </b> {{ $propiedad->terreno_metros }} mts</p>
                                <p class="card-text"><b>Metros de construccion: </b> {{ $propiedad->construccion_metros }} mts</p>
                                <p class="card-text oculto"><b>Area rentable: </b> {{ $propiedad->area_rentable_metros }}</p>
                            </div>
                            <div class="col-md-12">
                                <h2 class="card-title text-center">Ubicacion</h2>
                                <hr class="hr-titulo" width="100%" size="10">
                            </div>
                            <div class="col-md-12">
                                <p class="card-text"><b>Direccion: </b> {{ $propiedad->direccion }}</p>
                                <p class="card-text">{{ $propiedad->ciudad.', '.$propiedad->estado.', '.$propiedad->pais }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <br>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive ">
                                    <table class="table table-hover table-withoutborder">
                                      <thead class="thead-grubsa ">
                                        <th class="center">
                                          Amenidades
                                        </th>
                                      </thead>
                                      <tbody>
                                        @foreach ($amenidades_propiedad as $amenidad)                
                                        <tr>
                                          <td>
                                            {{ $amenidad->amenidad }}
                                          </td>
                                        </tr>
                                        @endforeach
                                      </tbody>
                                    </table>
                                </div>
                                {{$amenidades_propiedad->render()}}
                            </div>
                        </div>
                      </div>
                      <div class="card-footer text-muted">
                        <div class="row">
                            <div class="col-md-2 offset-md-3">
                                    <a href="{{ route('plano') }}" class="btn btn-dark btn-block">REGRESAR</a>
                            </div>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script >
    jQuery(document).ready(function($)
    {
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
@endpush 
@endsection