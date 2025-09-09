@extends('layouts.master')

@section('title', 'Perfil')
@section('content')
    <div class="container">
        <div class="card card-custom">
            <div class="card-body">
                <h5 class="card-title mb-3">Perfil</h5>

                <form method="POST" action="{{ route('clients.update', $client) }}" class="row g-3">
                    @csrf @method('PUT')

                    <div class="col-sm-6">
                        <label class="form-label">Nombres</label>
                        <input name="first_name" value="{{ old('first_name', $client->first_name) }}"
                            class="form-control @error('first_name') is-invalid @enderror">
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-sm-6">
                        <label class="form-label">Apellidos</label>
                        <input name="last_name" value="{{ old('last_name', $client->last_name) }}" class="form-control">
                    </div>

                    <div class="col-sm-4">
                        <label class="form-label">Género</label>
                        <select name="gender" class="form-select">
                            <option value="">—</option>
                            <option value="male" @selected(old('gender', $client->gender) === 'Masculino')>Masculino</option>
                            <option value="female" @selected(old('gender', $client->gender) === 'Femenino')>Femenino</option>
                            <option value="other" @selected(old('gender', $client->gender) === 'Otro')>Otro</option>
                        </select>
                    </div>

                    <div class="col-sm-4">
                        <label class="form-label">Fecha de nacimiento</label>
                        <input type="date" name="birthdate"
                            value="{{ old('birthdate', optional($client->birthdate)->format('Y-m-d')) }}"
                            class="form-control">
                    </div>

                    <div class="col-sm-4">
                        <label class="form-label">Estatura (cm)</label>
                        <input type="number" name="height_cm" value="{{ old('height_cm', $client->height_cm) }}"
                            class="form-control">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Condiciones / lesiones</label>
                        <textarea name="condition_notes" rows="4" class="form-control">{{ old('condition_notes', $client->condition_notes) }}</textarea>
                        <div class="form-text">Ej: “hipertensión”, “lesión de rodilla”, “dolor lumbar”, “diabetes”.</div>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary">Guardar</button>
                        <a href="{{ route('bmi.index') }}" class="btn btn-outline-secondary">Volver</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
