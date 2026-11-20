@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 600px; margin-top: 30px;">

    <!-- Botones de Navegación Rápida -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('teachers.index') }}" class="btn btn-secondary btn-sm shadow-sm">
            ← Volver al Listado
        </a>
        <span class="text-muted fw-bold">Módulo de Profesores</span>
    </div>

    <!-- Alerta de Éxito Estilizada -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert" style="border-left: 5px solid #39A900 !important;">
        <strong>¡Éxito!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Alerta de Errores de Validación -->
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
        <strong>¡Atención!</strong> Por favor corrige los errores en el formulario.
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Tarjeta del Formulario -->
    <div class="card shadow border-0">
        <!-- Encabezado con el Verde SENA -->
        <div class="card-header text-white py-3" style="background-color: #39A900;">
            <h4 class="mb-0 fw-bold">➕ Registrar Nuevo Profesor</h4>
        </div>

        <div class="card-body p-4 bg-light">
            <form action="{{ route('teachers.store') }}" method="POST">
                @csrf

                <!-- Sección: Información del Profesor -->
                <h5 class="text-success mb-3 border-bottom pb-2">📂 Datos del Profesor</h5>

                <!-- Nombre -->
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Nombre:</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" class="form-control shadow-sm @error('nombre') is-invalid @enderror" placeholder="Ej. Juan" required>
                    @error('nombre')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Apellido -->
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Apellido:</label>
                    <input type="text" name="apellido" value="{{ old('apellido') }}" class="form-control shadow-sm @error('apellido') is-invalid @enderror" placeholder="Ej. Pérez" required>
                    @error('apellido')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Teléfono -->
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Teléfono:</label>
                    <input type="text" name="telefono" value="{{ old('telefono') }}" class="form-control shadow-sm @error('telefono') is-invalid @enderror" placeholder="Ej. 3001234567" required>
                    @error('telefono')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Cédula -->
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Cédula:</label>
                    <input type="text" name="cedula" value="{{ old('cedula') }}" class="form-control shadow-sm @error('cedula') is-invalid @enderror" placeholder="Ej. 1020304050" required>
                    @error('cedula')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Botón de Registro -->
                <div class="text-end border-top pt-3">
                    <button type="submit" class="btn btn-success px-4 shadow" style="background-color: #39A900; border: none;">
                        💾 Guardar Profesor
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection