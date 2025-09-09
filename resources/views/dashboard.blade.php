@extends('layouts.master')

@section('title', 'Panel')
@section('content')
    <div class="container">
        <div class="row g-4">
            <div class="col-12 col-lg-8">
                <div class="card card-custom">
                    <div class="card-body">
                        <h5 class="card-title">Hola, {{ auth()->user()->name }}</h5>
                        <p class="text-muted">Completa tu perfil y registra tu IMC.</p>
                        <a href="{{ route('clients.edit', $client) }}" class="btn btn-outline-primary me-2">Editar Perfil</a>
                        <a href="{{ route('bmi.index') }}" class="btn btn-primary">Historial IMC</a>
                    </div>
                </div>
            </div>

            @if ($client->bmiRecords->count())
                @php $last = $client->bmiRecords->first(); @endphp
                <div class="col-12 col-lg-4">
                    <div class="card card-custom">
                        <div class="card-body">
                            <h6 class="text-uppercase text-muted">Último IMC</h6>
                            <div class="display-6 fw-bold">{{ $last->bmi }}</div>
                            <div class="mb-2">Categoría: <span class="badge bg-primary">{{ $last->bmi_category }}</span>
                            </div>
                            <small class="text-muted">Fecha: {{ $last->measured_at->format('Y-m-d H:i') }}</small>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
