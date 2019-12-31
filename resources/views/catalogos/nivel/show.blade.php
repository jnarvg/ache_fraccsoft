@extends('layouts.admin')
@section('title')
Niveles
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
          {{ Form::open(array('action'=>array('NivelController@update',$nivel->id_nivel),'method'=>'post','files'=>true)) }}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nivel">*Nombre</label>
                        <input type="text" name="nivel" id="nivel" value="{{ $nivel->nivel }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                      <label for="file-input">*Plano</label>
                      <div class="image-upload">
                        <label for="file-input">
                            <img src="{{ asset('/images/iconos/camara-de-fotos.png') }}" alt ="Click aquí para subir tu foto" class="ico-cam" title ="Click aquí para subir tu foto" > 
                        </label> 
                        <input id="file-input" class="Images" name="file-input" type="file" accept="image/*" capture />
                        <img id="blah-file-input" class="preview" src="{{ asset($nivel->plano) }}" width="30%" alt="" title="" style="padding-left: 10%;" />
                      </div>
                  </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="proyecto">*Proyecto</label>
                        <select class="form-control" id="proyecto" name="proyecto">
                          @foreach ($proyectos as $p)
                            @if ($p->id_proyecto == $nivel->proyecto_id)
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
                        <label for="orden">Orden</label>
                        <input type="number" name="orden" id="orden" value="{{ $nivel->orden }}"  class="letrasModal form-control"  />
                    </div>
                </div>
            </div>
            <div class="row" id="mapa">
                
            </div>
            <div class="row"> 
                <div class="col-md-4">
                    <div class="form-group">
                        @if ($procedencia == 'nivel')
                        <a href="{{ route($procedencia) }}" class="btn btn-dark btn-block">REGRESAR</a>
                        @else
                        <a href="{{ route($procedencia, $nivel->proyecto_id) }}" class="btn btn-dark btn-block">REGRESAR</a>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <button type="submit" class="btn btn-info btn-block">GUARDAR</button>
                    </div>
                </div> 
            </div>
          {{ Form::close()}}
            <div class="card">
              <div class="card-header bg-primary text-white">
                Propiedades
              </div>
              <div class="card-body">
                <div class="table-responsive ">
                  <table class="table table-hover table-withoutborder text-sm" id="tabla_propiedad">
                    <thead class="thead-grubsa ">
                      <th class="center">
                        Propiedad
                      </th>
                      <th class="center">
                        Proyecto
                      </th>
                      <th class="center">
                        Cuenta catastral
                      </th>
                      <th class="center">
                        Precio
                      </th>
                      <th class="center">
                        Enganche
                      </th>
                      <th class="center">
                        Estatus
                      </th>
                      <th class="center">
                        Acciones
                      </th>
                    </thead>
                    <tbody>
                      @foreach ($propiedades as $propiedad)                
                      <tr>
                        <td class="center">
                          <a class="text-dark" href="{{URL::action('PropiedadController@show', [$propiedad->id_propiedad, $procedencia = 'nivel-show', $padre = $nivel->id_nivel])}}"  style="width: 30%;">
                          {{ $propiedad->nombre}}</a>
                        </td>
                        <td class="center">
                          <a class="text-dark" href="{{URL::action('PropiedadController@show', [$propiedad->id_propiedad, $procedencia = 'nivel-show', $padre = $nivel->id_nivel])}}"  style="width: 30%;">
                          {{ $propiedad->proyecto}}</a>
                        </td>
                        <td class="center">
                          <a class="text-dark" href="{{URL::action('PropiedadController@show', [$propiedad->id_propiedad, $procedencia = 'nivel-show', $padre = $nivel->id_nivel])}}"  style="width: 30%;">
                          {{ $propiedad->cuenta_catastral}}</a>
                        </td>
                        <td class="center">
                          <a class="text-dark" href="{{URL::action('PropiedadController@show', [$propiedad->id_propiedad, $procedencia = 'nivel-show', $padre = $nivel->id_nivel])}}"  style="width: 30%;">
                          {{ $propiedad->precio}}</a>
                        </td>
                        <td class="center">
                          <a class="text-dark" href="{{URL::action('PropiedadController@show', [$propiedad->id_propiedad, $procedencia = 'nivel-show', $padre = $nivel->id_nivel])}}"  style="width: 30%;">
                          {{ $propiedad->enganche}}</a>
                        </td class="center">
                        <td class="center">
                          <a class="text-dark" href="{{URL::action('PropiedadController@show', [$propiedad->id_propiedad, $procedencia = 'nivel-show', $padre = $nivel->id_nivel])}}"  style="width: 30%;">
                          {{ $propiedad->estatus_propiedad}}</a>
                        </td>
                        <td class="center-acciones">
                          <a href="{{URL::action('PropiedadController@show', [$propiedad->id_propiedad, $procedencia = 'nivel-show', $padre = $nivel->id_nivel])}}"  style="width: 30%;"><button class="btn-ico" style="margin-left: 2px; margin-right: 2px;"><i class="fas fa-pencil-alt"></i></button></a>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            {{-- {{$propiedades->render()}} --}}
            </div>
            
        </div>
    </div>
</div>
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal-preview">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header-img">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <div class="modal-body-img">
        <img id="preview-img" src="" class="modal-img">
      </div>
      <div class="modal-footer-img">
        <h3 id="preview-text"></h3>
      </div>
    </div>
  </div>
</div>
@push('scripts')
  <script>
    jQuery(document).ready(function() {
      $('#tabla_propiedad').DataTable();
      jQuery(".preview").click(function(){
          
          var srcimagen = jQuery(this).attr('src');
          var tituloimagen = jQuery(this).attr('title');
          jQuery("#preview-img").attr('src',srcimagen);
          jQuery("#preview-img").attr('alt',tituloimagen);
          jQuery("#preview-text").html(tituloimagen);
          jQuery("#modal-preview").modal();
        });
      });
  </script>
  <script type="application/javascript">
      
      jQuery('input[type=file]').change(function(){
       var filename = jQuery(this).val().split('\\').pop();
       var idname = jQuery(this).attr('id');
       jQuery('span.'+idname).next().find('span').html(filename);
      });
  </script>
  <script src="{{ asset('js/uploadfotos.js') }}"></script>
@endpush 
@endsection