@extends('layouts.admin')
@section('title')
Profile
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            {{ Form::open(array('action'=>array('AgenteController@updateprofile',$usuario->id),'method'=>'post','files'=>true)) }}
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="nombre-user">*Nombre de usuario</label>
                        <input type="text" name="nombre-user" id="nombre-user" value="{{ $usuario->name }}"  class="letrasModal form-control" required="true"  />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="nombre-email">*Email de usuario</label>
                        <input type="text" name="nombre-email" id="nombre-email" value="{{ $usuario->email }}"  class="letrasModal form-control"  required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                      <label for="file-input">Foto de perfil</label>
                      <div class="image-upload">
                        <label for="file-input">
                            <img src="{{ asset('/images/iconos/camara-de-fotos.png') }}" alt ="Click aquí para subir tu foto" class="ico-cam" title ="Click aquí para subir tu foto" > 
                        </label> 
                        <input id="file-input" class="Images" name="file-input" type="file" accept="image/*" />
                        <img id="blah-file-input" class="preview" src="{{ asset($usuario->foto_perfil) }}" width="30%" alt="" title="" style="padding-left: 10%;" />
                      </div>
                  </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="nombre-pass">*Password</label>
                        <input type="password" name="nombre-pass" id="nombre-pass" value="{{ $passsinHash }}"  class="letrasModal form-control"  required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="nombre-estatus">*Estatus</label>
                        <input type="text" name="nombre-estatus" id="nombre-estatus" value="{{ $usuario->estatus }}"  class="letrasModal form-control" readonly="true" required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="nombre-rol">*Rol</label>
                        <input type="text" name="nombre-rol" id="nombre-rol" value="{{ $usuario->rol }}"  class="letrasModal form-control" readonly="true" required="true" />
                    </div>
                </div>
                
                <div class="col-md-3 offset-md-3">
                    <div class="form-group">
                        <a href="{{ route('welcome') }}" class="btn btn-dark btn-block">CANCELAR</a>
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