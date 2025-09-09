@extends('layouts.master')

@section('title', 'Calculadora IMC')
@section('content')
    <div class="container">

        {{-- Registrar nuevo IMC --}}
        <div class="card card-custom mb-4">
            <div class="card-body">
                <h5 class="card-title">Registrar nuevo IMC</h5>
                <form method="POST" action="{{ route('bmi.store') }}" class="row g-3 mt-1">
                    @csrf
                    <div class="col-12 col-sm-4">
                        <label class="form-label">Peso (kg)</label>
                        <input type="number" step="0.01" name="weight_kg"
                            class="form-control @error('weight_kg') is-invalid @enderror" required>
                        @error('weight_kg')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 col-sm-4">
                        <label class="form-label">Estatura (cm)</label>
                        <input type="number" name="height_cm" class="form-control @error('height_cm') is-invalid @enderror"
                            placeholder="{{ $client->height_cm }}">
                        @error('height_cm')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 col-sm-4">
                        <label class="form-label">Fecha/Hora</label>
                        <input type="datetime-local" name="measured_at" class="form-control">
                        <div class="form-text">
                            Si lo dejas vacío, se registrará la fecha y hora actual.
                        </div>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Último IMC --}}
        @php $last = $client->bmiRecords()->latest('measured_at')->first(); @endphp
        @if ($last)
            <div class="row g-4 mb-1">
                <div class="col-12 col-lg-4">
                    <div class="card card-custom">
                        <div class="card-body">
                            <h6 class="text-uppercase text-muted">Último IMC</h6>
                            <div class="display-6 fw-bold">{{ $last->bmi }}</div>
                            <div class="mb-2">
                                Categoría:
                                <span class="badge bg-primary">{{ $last->bmi_category }}</span>
                            </div>
                            {{-- Español + 12h am/pm --}}
                            <small class="text-muted">
                                Fecha: {{ $last->measured_at->translatedFormat('Y-m-d h:i a') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Historial --}}
        <div class="card card-custom">
            <div class="card-body">
                <h5 class="card-title">Historial</h5>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Peso</th>
                                <th>Estatura</th>
                                <th>IMC</th>
                                <th>Categoría</th>
                                <th>Recomendaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($records as $r)
                                @php
                                    // Normalización para soportar claves nuevas (es) y antiguas (en)
                                    $diet = $r->diet_suggestion ?? [];
                                    $routine = $r->routine_suggestion ?? [];

                                    // Si vienen en inglés, mapeamos a español para mostrar homogéneo
                                    if (isset($diet['calories'])) {
                                        $diet = [
                                            'calorias' => $diet['calories'] ?? null,
                                            'macros' => [
                                                'proteina' => $diet['macros']['protein'] ?? null,
                                                'carbohidratos' => $diet['macros']['carbs'] ?? null,
                                                'grasa' => $diet['macros']['fat'] ?? null,
                                            ],
                                            'notas' => $diet['notes'] ?? [],
                                        ];
                                    }
                                    if (isset($routine['days_per_week'])) {
                                        $ses = [];
                                        foreach ($routine['sessions'] ?? [] as $s) {
                                            $ses[] = [
                                                'nombre' => $s['name'] ?? 'Sesión',
                                                'duracion_min' => $s['duration_min'] ?? null,
                                            ];
                                        }
                                        $routine = [
                                            'dias_por_semana' => $routine['days_per_week'] ?? null,
                                            'sesiones' => $ses,
                                            'notas' => $routine['notes'] ?? [],
                                        ];
                                    }
                                @endphp
                                <tr>
                                    {{-- Español + 12h am/pm --}}
                                    <td>{{ $r->measured_at->translatedFormat('Y-m-d h:i a') }}</td>
                                    <td>{{ $r->weight_kg }} kg</td>
                                    <td>{{ $r->height_cm }} cm</td>
                                    <td class="fw-semibold">{{ $r->bmi }}</td>
                                    <td><span class="badge bg-info">{{ $r->bmi_category }}</span></td>
                                    <td>
                                        <div class="accordion" id="acc-{{ $r->id }}">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="h-{{ $r->id }}">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#c-{{ $r->id }}">
                                                        Ver detalles
                                                    </button>
                                                </h2>
                                                <div id="c-{{ $r->id }}" class="accordion-collapse collapse"
                                                    data-bs-parent="#acc-{{ $r->id }}">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-12 col-lg-6">
                                                                <h6 class="mb-2">Dieta</h6>
                                                                @if (!empty($diet))
                                                                    <ul class="mb-2">
                                                                        @if (isset($diet['calorias']))
                                                                            <li><strong>Calorías:</strong>
                                                                                {{ $diet['calorias'] }} kcal</li>
                                                                        @endif
                                                                        @if (isset($diet['macros']))
                                                                            <li>
                                                                                <strong>Macros:</strong>
                                                                                Prot.
                                                                                {{ $diet['macros']['proteina'] ?? '?' }}% ·
                                                                                Carb.
                                                                                {{ $diet['macros']['carbohidratos'] ?? '?' }}%
                                                                                ·
                                                                                Grasa
                                                                                {{ $diet['macros']['grasa'] ?? '?' }}%
                                                                            </li>
                                                                        @endif
                                                                    </ul>
                                                                    @if (!empty($diet['notas']))
                                                                        <small class="text-muted d-block">Notas:</small>
                                                                        <ul class="mb-0">
                                                                            @foreach ($diet['notas'] as $n)
                                                                                <li>{{ $n }}</li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @endif
                                                                @else
                                                                    <span class="text-muted">Sin datos</span>
                                                                @endif
                                                            </div>
                                                            <div class="col-12 col-lg-6">
                                                                <h6 class="mb-2">Rutina</h6>
                                                                @if (!empty($routine))
                                                                    @if (isset($routine['dias_por_semana']))
                                                                        <p class="mb-2"><strong>Días por semana:</strong>
                                                                            {{ $routine['dias_por_semana'] }}</p>
                                                                    @endif
                                                                    @if (!empty($routine['sesiones']))
                                                                        <ul class="mb-2">
                                                                            @foreach ($routine['sesiones'] as $s)
                                                                                <li>{{ $s['nombre'] ?? 'Sesión' }} —
                                                                                    {{ $s['duracion_min'] ?? '?' }} min
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @endif
                                                                    @if (!empty($routine['notas']))
                                                                        <small class="text-muted d-block">Notas:</small>
                                                                        <ul class="mb-0">
                                                                            @foreach ($routine['notas'] as $n)
                                                                                <li>{{ $n }}</li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @endif
                                                                @else
                                                                    <span class="text-muted">Sin datos</span>
                                                                @endif
                                                            </div>
                                                        </div> {{-- row --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div> {{-- accordion --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $records->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
