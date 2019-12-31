@extends('layouts.admin')
@section('title')
Condiciones de entrega detalle
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            {{ Form::open(array('action'=>array('CondicionEntregaDetalleController@update',$resultado->id_condicion_entrega_detalle),'method'=>'post')) }}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="condicion">*Condicion</label>
                        <input type="text" name="condicion" id="condicion" value="{{ $resultado->condicion }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="modified_data">*Fecha</label>
                        <input type="date" name="modified_data" id="modified_data" value="{{ date('Y-m-d',strtotime($resultado->modified_date)) }}"  class="letrasModal form-control" readonly="true" required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                      <div class="form-group">
                          <label for="tipo">*Tipo</label>
                          <select name="tipo" id="tipo"  class="letrasModal form-control" required="true"> 
                              @foreach ($tipo_condicion as $e)
                                @if ($resultado->tipo == $e)
                                <option selected value="{{ $e }}">{{ $e }} </option>
                                @else
                                <option value="{{ $e }}">{{ $e }} </option>
                                @endif
                              @endforeach
                          </select>
                      </div>
                </div>
                <div class="col-md-6 ocultar">
                  <div class="form-group">
                      <label for="subtitulo">Subtitulo</label>
                      <input type="text" name="subtitulo" id="subtitulo" value="{{ $resultado->subtitulo }}"  class="letrasModal form-control" />
                  </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="uso_propiedad_id">*Uso de propiedad</label>
                        <select name="uso_propiedad_id" id="uso_propiedad_id"  class="letrasModal form-control" required="true">
                            @foreach ($usos_propiedad as $r)
                                @if ($r->id_uso_propiedad == $resultado->uso_propiedad_id)
                                    <option selected="true" value="{{ $r->id_uso_propiedad }}">{{ $r->uso_propiedad }}</option>
                                @else
                                    <option value="{{ $r->id_uso_propiedad }}">{{ $r->uso_propiedad }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="proyecto_id">*Proyecto</label>
                        <select name="proyecto_id" id="proyecto_id"  class="letrasModal form-control" required="true">
                            @foreach ($proyectos as $r)
                                @if ($r->id_proyecto == $resultado->proyecto_id)
                                    <option selected="true" value="{{ $r->id_proyecto }}">{{ $r->nombre }}</option>
                                @else
                                    <option value="{{ $r->id_proyecto }}">{{ $r->nombre }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="condicion_entrega_id">*Condicion de entrega</label>
                        <select name="condicion_entrega_id" id="condicion_entrega_id"  class="letrasModal form-control" required="true">
                            @foreach ($condicion_entrega as $r)
                                @if ($r->id_condicion_entrega == $resultado->condicion_entrega_id)
                                    <option selected="true" value="{{ $r->id_condicion_entrega }}">{{ $r->titulo }}</option>
                                @else
                                    <option value="{{ $r->id_condicion_entrega }}">{{ $r->titulo }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2 offset-md-5">
                    <div class="form-group">
                        @if ($procedencia == 'Menu')
                        <a href="{{ route('condicion_entrega_detalle') }}" class="btn btn-dark btn-block">CANCELAR</a>
                        @else
                        <a href="{{ route($procedencia, $resultado->condicion_entrega_id) }}" class="btn btn-dark btn-block">CANCELAR</a>
                        @endif
                    </div>
                </div> 
                <div class="col-md-2">
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
<script type="text/javascript">
    jQuery(document).ready(function($)
    {   
        tipo = $('#tipo').val();
        if (tipo == 'Agregado') {
            $('.ocultar').removeClass('oculto');
        }else{
            $('.ocultar').addClass('oculto');
        }
        $("#tipo").on('change', function(){
            tipo = $('#tipo').val();
            if (tipo == 'Agregado') {
                $('.ocultar').removeClass('oculto');
            }else{
                $('.ocultar').addClass('oculto');
            }
        });
    });
</script>
@endpush 
@endsection