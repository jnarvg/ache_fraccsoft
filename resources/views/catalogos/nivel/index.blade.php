@extends('layouts.admin')
@section('title')
Niveles
@endsection
@section('filter')
  <a href="#" data-toggle="modal" data-target="#modal_nuevo"><button class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus"></i></button></a>
  <button href="#" data-toggle="collapse" data-target="#filtros" role="button" aria-expanded="false" aria-controls="filtros" class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-filter"></i></button>
@endsection
@section('content')
<div class="content mt-3">
  <div class="card collapse" id="filtros">
    <div class="card-body">
      <div class="row">
        <div class="col-lg-12">
        {!! Form::open(array('route'=>'nivel', 'method'=>'get', 'autocomplete'=>'off')) !!}
          <div class="input-group md-form form-sm form-4 pl-0">

            <input type="text" class="form-control" placeholder="Nivel" name="nombre_bs" id="nombre_bs" value="{{ $request->nombre_bs }}">

            <input type="text" class="form-control" placeholder="Proyecto" name="proyecto_bs" id="proyecto_bs" value="{{ $request->proyecto_bs }}">

            <button type="submit" class="btn btn-info" ><span class="glyphicon"><i class="fas fa-search"></i></span></button>
          </div>
        {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-body">
      <div class="table-responsive ">
        <table class="table table-hover table-withoutborder text-sm" id="tabla_nivel">
          <thead class="thead-grubsa ">
            <th class="center">
              Nivel
            </th>
            <th class="center">
              Proyecto
            </th>
            <th class="center">
              Orden
            </th>
            <th class="center">
              Acciones
            </th>
          </thead>
          <tbody>
            @foreach ($niveles as $nivel)                
            <tr>
              <td class="center">
                <a class="text-dark" href="{{URL::action('NivelController@show', [$nivel->id_nivel, $procedencia])}}" >
                {{ $nivel->nivel}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('NivelController@show', [$nivel->id_nivel, $procedencia])}}" >
                {{ $nivel->proyecto}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('NivelController@show', [$nivel->id_nivel, $procedencia])}}" >
                {{ $nivel->orden}}</a>
              </td>
              <td class="center-acciones">
                <a href="{{URL::action('NivelController@show', [$nivel->id_nivel, $procedencia])}}" ><button class="btn-ico" ><i class="fas fa-pencil-alt"></i></button></a>
                <a href="#" data-target="#modal-delete{{$nivel->id_nivel}}" data-toggle="modal"><button class="btn-ico" ><i class="fas fa-trash"></i></button></a>
              </td>
            </tr>
            @include('catalogos.nivel.modal')
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  {{-- {{$niveles->appends(Request::only('nombre_bs','proyecto_bs'))->render()}} --}}
</div>
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal_nuevo">
  {{ Form::open(array('action'=>array('NivelController@store'),'method'=>'post', 'files'=>true)) }}
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title text-dark">Nuevo nivel</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="nivel">*Nivel</label>
                          <input type="text" name="nivel" id="nivel" value="" minlength="2" class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="proyecto">*Proyecto</label>
                          <select class="form-control" id="proyecto" name="proyecto">
                            @foreach ($proyectos as $p)
                              <option value="{{ $p->id_proyecto }}">{{ $p->nombre }}</option>
                            @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="orden">Orden</label>
                          <input type="number" name="orden" id="orden" value="0" class="letrasModal form-control" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                        <label for="nuevo_observacion_mdl">*Plano</label>
                        <div class="image-upload">
                          <label for="file-input">
                              <img src="{{ asset('/images/iconos/camara-de-fotos.png') }}" alt ="Click aquí para subir tu foto" class="ico-cam" title ="Click aquí para subir tu foto" > 
                          </label> 
                          <input id="file-input" class="Images" name="file-input" type="file" accept="image/*" capture />
                          <img id="blah-file-input" class="preview" src="" width="30%" alt="" title="" style="padding-left: 10%;" />
                        </div>
                      </div>
                  </div>              
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-info">Confirmar</button>
          </div>
      </div>
  </div>
  {{ Form::close()}}  
</div>
@push('scripts')
<script >
  jQuery(document).ready(function($)
  {
    $('#tabla_nivel').DataTable();
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