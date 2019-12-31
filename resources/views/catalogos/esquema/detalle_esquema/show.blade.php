@extends('layouts.admin')
@section('title')
Detalle de esquema de comision
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            {{ Form::open(array('action'=>array('DetalleEsquemaComisionController@update',$detalle_comision->id_detalle_esquema_comision),'method'=>'post')) }}
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="rubro">*Rubro</label>
                        <input type="text" name="rubro" id="rubro" value="{{ $detalle_comision->rubro }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="factor">*Factor</label>
                        <input type="number" step="any" name="factor" id="factor" value="{{ $detalle_comision->factor }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="tipo">*Tipo</label>
                        <select name="tipo" id="tipo" value=""  class="letrasModal form-control" required="true">
                            @foreach ($tiposesquema as $t)
                                @if ($t == $detalle_comision->tipo)
                                    <option selected="true" value="{{ $t }}">{{ $t }}</option>
                                @else
                                    <option value="{{ $t }}">{{ $t }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="usuario">*Usuario</label>
                        <select name="usuario" id="usuario" value=""  class="letrasModal form-control">
                          @foreach ($usuarios as $user)
                            @if ($detalle_comision->usuario == $user->id)
                                <option selected="true" value="{{ $user->id }}">{{ $user->name }}</option>
                            @else
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endif
                          @endforeach
    
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="persona">*Persona</label>
                        <input type="text" name="persona" id="persona" value="{{ $detalle_comision->persona }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
            </div>
            <div class="row">    
                <div class="col-md-3 offset-md-3">
                    <div class="form-group">
                        <a href="{{ route('esquema-show',["id"=>$detalle_comision->esquema_id]) }}" class="btn btn-dark btn-block">REGRESAR</a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <button type="submit" class="btn btn-info btn-block">ACTUALIZAR</button>
                    </div>
                </div>
            </div>
            {{ Form::close()}}
        </div>
    </div>
</div>

@push('scripts')
<script>
    jQuery(document).ready(function($)
    {

      $("#usuario").on('change', function(){
          $("#persona").val( $("#usuario option:selected").text());

      });
    });
</script>
@endpush 
@endsection