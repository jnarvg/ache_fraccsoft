@extends('layouts.admin')
@section('title')
Reportes
@endsection
@section('content')
<div class="content mt-3">
    <div class="row">
        @if (\Auth()->user()->rol == 6 /*Cobranza*/)
            <div class="col-md-2">
                <a href="{{ route('reportes_pagos') }}">
                    <div class="social-box bg-grubsa">
                        <i class="fas fa-chart-line"></i>
                        <span class="text-grubsa-clear">Pagos</span>
                    </div>
                </a>
            </div>
            <div class="col-md-2">
                <a href="{{ route('home') }}">
                    <div class="social-box bg-grubsa">
                        <i class="fas fa-arrow-left"></i>
                        <span class="text-grubsa-clear">Regresar</span>
                    </div>
                </a>
            </div>
        @else
            {{-- Para adminitrador - gerente --}}
            <div class="col-md-2">
                <a href="{{ route('reportes_analisis_metros') }}">
                    <div class="social-box bg-grubsa">
                        <i class="fas fa-chart-line"></i>
                        <span class="text-grubsa-clear">Analisis de metros</span>
                    </div>
                </a>
            </div>
            <div class="col-md-2">
                <a href="{{ route('reportes_cliente_mes') }}">
                    <div class="social-box bg-grubsa">
                        <i class="fas fa-chart-line"></i>
                        <span class="text-grubsa-clear">Clientes por mes</span>
                    </div>
                </a>
            </div>
            <div class="col-md-2">
                <a href="{{ route('reportes_visitas') }}">
                    <div class="social-box bg-grubsa">
                        <i class="fas fa-chart-line"></i>
                        <span class="text-grubsa-clear">Visitas</span>
                    </div>
                </a>
            </div>
            <div class="col-md-2">
                <a href="{{ route('reportes_clientes_asesor') }}">
                    <div class="social-box bg-grubsa">
                        <i class="fas fa-chart-line"></i>
                        <span class="text-grubsa-clear">Clientes por asesor</span>
                    </div>
                </a>
            </div>
            <div class="col-md-2">
                <a href="{{ route('cuenta_cobrar') }}">
                    <div class="social-box bg-grubsa">
                        <i class="fas fa-chart-line"></i>
                        <span class="text-grubsa-clear">Cuenta por cobrar</span>
                    </div>
                </a>
            </div>
            <div class="col-md-2">
                <a href="{{ route('reportes_enganche_estimado') }}">
                    <div class="social-box bg-grubsa">
                        <i class="fas fa-chart-line"></i>
                        <span class="text-grubsa-clear">Enganche a recibir</span>
                    </div>
                </a>
            </div>
            <div class="col-md-2">
                <a href="{{ route('reportes_estatus_propiedad') }}">
                    <div class="social-box bg-grubsa">
                        <i class="fas fa-chart-line"></i>
                        <span class="text-grubsa-clear">Estatus de propiedad</span>
                    </div>
                </a>
            </div>
            <div class="col-md-2">
                <a href="{{ route('reportes_medio_contacto') }}">
                    <div class="social-box bg-grubsa">
                        <i class="fas fa-chart-line"></i>
                        <span class="text-grubsa-clear">Medio de contacto</span>
                    </div>
                </a>
            </div>
            <div class="col-md-2">
                <a href="{{ route('semaforo') }}">
                    <div class="social-box bg-grubsa">
                        <i class="fas fa-chart-line"></i>
                        <span class="text-grubsa-clear">Semaforo</span>
                    </div>
                </a>
            </div>
            <div class="col-md-2">
                <a href="{{ route('reportes_oportunidades') }}">
                    <div class="social-box bg-grubsa">
                        <i class="fas fa-chart-line"></i>
                        <span class="text-grubsa-clear">Clientes y prospectos</span>
                    </div>
                </a>
            </div>
            <div class="col-md-2">
                <a href="{{ route('reportes_pagos') }}">
                    <div class="social-box bg-grubsa">
                        <i class="fas fa-chart-line"></i>
                        <span class="text-grubsa-clear">Pagos</span>
                    </div>
                </a>
            </div>
            <div class="col-md-2">
                <a href="{{ route('reportes_ventas') }}">
                    <div class="social-box bg-grubsa">
                        <i class="fas fa-chart-line"></i>
                        <span class="text-grubsa-clear">Ventas</span>
                    </div>
                </a>
            </div>
            <div class="col-md-2">
                <a href="{{ route('reportes_comparativo') }}">
                    <div class="social-box bg-grubsa">
                        <i class="fas fa-chart-line"></i>
                        <span class="text-grubsa-clear">Comparativo</span>
                    </div>
                </a>
            </div>
            <div class="col-md-2">
                <a href="{{ route('home') }}">
                    <div class="social-box bg-grubsa">
                        <i class="fas fa-arrow-left"></i>
                        <span class="text-grubsa-clear">Regresar</span>
                    </div>
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
@endpush 
@endsection