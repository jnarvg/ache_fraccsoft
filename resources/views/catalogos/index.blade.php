@extends('layouts.admin')
@section('title')
Todos los catalogos
@endsection
@section('content')
<div class="content mt-3">
    @if (auth()->user()->id == 1)
    <div class="row">
        <div class="col-md-12">
            <h3><a class="text-secondary" data-toggle="collapse" href="#administrador" role="button" aria-expanded="false" aria-controls="administrador"><em>Administrador</em></a></h3>
            <hr class="hr-titulo" width="100%" size="10">
        </div>
    </div>
    <div class="row show" id="administrador">
        <div class="col-md-2">
            <a href="{{ route('rol') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-user-tie"></i>
                    <span class="text-grubsa-clear">Roles</span>
                </div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('tipo_propiedad') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-cogs"></i>
                    <span class="text-grubsa-clear">Tipo de propiedad sistema</span>
                </div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('estatus_prospecto') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-signal"></i>
                    <span class="text-grubsa-clear">Estatus prospecto</span>
                </div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('configuracion_general') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-tools"></i>
                    <span class="text-grubsa-clear">Configuracion general</span>
                </div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('campos_configuracion') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-tools"></i>
                    <span class="text-grubsa-clear">Configuracion campos</span>
                </div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('detalle_esquema_pago') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-list-ul"></i>
                    <span class="text-grubsa-clear">Detalle esquema pago</span>
                </div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('condicion_entrega_detalle') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-list-ul"></i>
                    <span class="text-grubsa-clear">Condiciones entrega detalle</span>
                </div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('home') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-arrow-left"></i>
                    <span class="text-grubsa-clear">Regresar</span>
                </div>
            </a>
        </div>
    </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <h3><a class="text-secondary" data-toggle="collapse" href="#catalogos" role="button" aria-expanded="false" aria-controls="catalogos"><em>Catalogos</em></a></h3>
            <hr class="hr-titulo" width="100%" size="10">
        </div>
    </div>
    <div class="row show" id="catalogos">
        <div class="col-md-2">
            <a href="{{ route('empresa') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-kaaba"></i>
                    <span class="text-grubsa-clear">Empresas</span>
                </div>
            </a>
        </div>
        <div class=" col-md-2">
            <a href="{{ route('proyectos') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-building"></i>
                    <span class="text-grubsa-clear">Proyectos</span>
                </div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('grupo_esquema') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-list-ul"></i>
                    <span class="text-grubsa-clear">Grupos Esquemas</span>
                </div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('amenidades') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-swimming-pool"></i>
                    <span class="text-grubsa-clear">Amenidades</span>
                </div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('tipo_modelo') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-hotel"></i>
                    <span class="text-grubsa-clear">Tipo de propiedad</span>

                </div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('motivo_perdida') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-running"></i>
                    <span class="text-grubsa-clear">Motivo de perdida</span>
                </div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('medio_contacto') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-bullhorn"></i>
                    <span class="text-grubsa-clear">Medio de contacto</span>
                </div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('requisito') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-tasks"></i>
                    <span class="text-grubsa-clear">Requisitos</span>
                </div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('uso-propiedad') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-warehouse"></i>
                    <span class="text-grubsa-clear">Uso de propiedad</span>
                </div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('esquema_pago') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-list-ul"></i>
                    <span class="text-grubsa-clear">Esquema pago</span>
                </div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('condicion_entrega') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-list-ul"></i>
                    <span class="text-grubsa-clear">Condiciones de entrega</span>
                </div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('tipo_operacion') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-money-bill-wave"></i>
                    <span class="text-grubsa-clear">Tipo de operacion</span>
                </div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('forma_pago') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-money-check"></i>
                    <span class="text-grubsa-clear">Forma de pago</span>
                </div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('home') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-arrow-left"></i>
                    <span class="text-grubsa-clear">Regresar</span>
                </div>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3><a class="text-secondary" data-toggle="collapse" href="#fijos" role="button" aria-expanded="false" aria-controls="fijos"><em>Fijos</em></a></h3>
            <hr class="hr-titulo" width="100%" size="10">
        </div>
    </div>
    <div class="row show" id="fijos">
        <div class="col-md-2">
            <a href="{{ route('pais') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-globe-americas"></i>
                    <span class="text-grubsa-clear">Pais</span>
                </div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('estado') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-flag"></i>
                    <span class="text-grubsa-clear">Estado</span>
                </div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('ciudades') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-city"></i>
                    <span class="text-grubsa-clear">Ciudad</span>
                </div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('regimen_fiscal') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-university"></i>
                    <span class="text-grubsa-clear">Regimen fiscal</span>
                </div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('banco') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-piggy-bank"></i>
                    <span class="text-grubsa-clear">Bancos</span>
                </div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('moneda') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-euro-sign"></i>
                    <span class="text-grubsa-clear">Moneda</span>
                </div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('home') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-arrow-left"></i>
                    <span class="text-grubsa-clear">Regresar</span>
                </div>
            </a>
        </div>
    </div>
    @if (auth()->user()->rol == 3)
    <div class="row">
        <div class="col-md-12">
            <h3><a class="text-secondary" data-toggle="collapse" href="#configuracion" role="button" aria-expanded="false" aria-controls="configuracion"><em>Configuracion</em></a></h3>
            <hr class="hr-titulo" width="100%" size="10">
        </div>
    </div>
    <div class="row show" id="configuracion">
        <div class="col-md-2">
            <a href="{{ route('nivel') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-layer-group"></i>
                    <span class="text-grubsa-clear">Niveles</span>
                </div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('usuarios') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-user-tie"></i>
                    <span class="text-grubsa-clear">Agentes</span>
                </div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('usuarios_externos') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-users"></i>
                    <span class="text-grubsa-clear">Usuarios externos</span>
                </div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('estatus_propiedad') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-house-damage"></i>
                    <span class="text-grubsa-clear">Estatus propiedad</span>
                </div>
            </a>
        </div>
        
        <div class="col-md-2">
            <a href="{{ route('esquema') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-stream"></i>
                    <span class="text-grubsa-clear">Esquemas de comisiones</span>
                </div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('color') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-palette"></i>
                    <span class="text-grubsa-clear">Colores</span>
                </div>
            </a>
        </div>
        
        <div class="col-md-2">
            <a href="{{ route('home') }}">
                <div class="social-box bg-grubsa text-primary">
                    <i class="fas fa-arrow-left"></i>
                    <span class="text-grubsa-clear">Regresar</span>
                </div>
            </a>
        </div>
    </div>
    @endif
    
</div>

@push('scripts')
@endpush 
@endsection