@extends('layouts.master')

@section('title', 'Inicio IMC')
@section('content')
    <div class="container">
        <div class="row g-4">
            <div class="col-12 col-lg-7">
                <div class="card card-custom">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Bienvenido</h5>
                        <p class="text-muted mb-4">Calcula tu IMC, guarda tu historial y recibe sugerencias de dieta y rutina
                            según tu perfil.</p>
                        @auth
                            <a href="{{ route('bmi.index') }}" class="btn btn-primary">Ir a la calculadora</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary">Iniciar sesión</a>
                        @endauth
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-5">
                <div class="card card-custom">
                    <div class="card-body">
                        <h6 class="text-uppercase text-muted">Características</h6>
                        <ul class="mt-3 mb-0">
                            <li>Registro de IMC con fecha/hora</li>
                            <li>Recomendaciones automáticas (dieta/rutina)</li>
                            <li>Historial consultable</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
