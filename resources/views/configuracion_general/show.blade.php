@extends('layouts.admin')
@section('title')
Configuracion general
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            {{ Form::open(array('action'=>array('ConfiguracionGeneralController@update',$configuracion_general->id_configuracion_general),'method'=>'post','files'=>'true', 'id')) }}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nombre_cliente">*Cliente</label>
                        <input type="text" name="nombre_cliente" id="nombre_cliente" value="{{ $configuracion_general->nombre_cliente }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="jefe_proyecto">*Proyecto contacto</label>
                        <input type="text" name="jefe_proyecto" id="jefe_proyecto" value="{{ $configuracion_general->jefe_proyecto }}"  class="letrasModal form-control" required="true" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="correo_contacto">Correo</label>
                        <input type="email" name="correo_contacto" id="correo_contacto" value="{{ $configuracion_general->correo_contacto }}"  class="letrasModal form-control" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="estatus">Estatus</label>
                        <select type="text" name="estatus" id="estatus"  class="letrasModal form-control">
                            @foreach ($estatus as $ele)
                                @if ($ele == $configuracion_general->estatus)
                                    <option selected value="{{ $ele }}">{{ $ele }}</option>
                                @else
                                    <option value="{{ $ele }}">{{ $ele }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="empresa_principal">Empresa</label>
                        <select name="empresa_principal" id="empresa_principal" class="letrasModal form-control">
                          @foreach ($empresas as $pros)
                              @if ($configuracion_general->empresa_principal_id == $pros->id_empresa)
                                  <option selected="true" value="{{ $pros->id_empresa }}">{{ $pros->nombre_comercial }}</option>
                              @else
                                  <option value="{{ $pros->id_empresa }}">{{ $pros->nombre_comercial }}</option>
                              @endif
                          @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <h3><em>Limites y tasas</em></h3>
                         <hr class="hr-titulo" width="100%" size="10">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="limite_propiedades">Limite Propiedades</label>
                        <input type="numeric" name="limite_propiedades" id="limite_propiedades" value="{{ $configuracion_general->limite_propiedades }}"  class="letrasModal form-control"  />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="limite_usuarios">Limite Usuarios</label>
                        <input type="numeric" name="limite_usuarios" id="limite_usuarios" value="{{ $configuracion_general->limite_usuarios }}"  class="letrasModal form-control"  />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="tasa_interes_mora">Tasa de interes</label>
                        <input type="numeric" name="tasa_interes_mora" id="tasa_interes_mora" value="{{ $configuracion_general->tasa_interes_mora }}"  class="letrasModal form-control"  />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 offset-md-3">
                    <div class="form-group">
                        <a href="{{ route('configuracion_general') }}" class="btn btn-dark btn-block">CANCELAR</a>
                    </div>
                </div>
                @if ($id == 1)
                <div class="col-md-3">
                    <div class="form-group">
                        <button type="submit" class="btn btn-info btn-block">GUARDAR</button>
                    </div>
                </div>
                @endif
            </div>
            
            {{ Form::close()}}
        </div>
    </div>
</div>

@push('scripts')
@endpush 
@endsection