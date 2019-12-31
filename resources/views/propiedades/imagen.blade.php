@extends('layouts.admin')
@section('title')
Imagenes en la propiedad
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            {{ Form::open(array('action'=>array('ImagenesPropiedadController@update',$i->id_imagen),'method'=>'post', 'files'=>true)) }}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                      <label for="titulo">*Titulo</label>
                      <input type="text" name="titulo" id="titulo" value="{{ $i->titulo }}"  class="letrasModal form-control" required="true"/>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="estado">*Imagen</label>
                        <div class="image-upload">
                          <label for="file-input">
                              <img src="{{ asset('/images/iconos/camara-de-fotos.png') }}" alt ="Click aquí para subir tu foto" class="ico-cam" title ="Click aquí para subir tu foto" > 
                          </label> 
                          <input id="file-input" class="Images" name="file-input" type="file" accept="image/*" capture required="true" />
                          <img id="blah-file-input" class="preview" src="{{ asset($i->imagen_path) }}" width="30%" alt="" title="" style="padding-left: 10%;" />
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 offset-md-3">
                    <div class="form-group">
                        <a href="{{ route('propiedades-show',[$i->propiedad_id, 'propiedades','Menu']) }}" class="btn btn-dark btn-block">REGRESAR</a>
                    </div>
                </div> 
                <div class="col-md-3">
                    <div class="form-group">
                        <button type="submit" class="btn btn-info btn-block">GUARDAR</button>
                    </div>
                </div>
            </div>
            {{ Form::close()}}
        </div>
    </div>
</div>

@push('scripts')
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