@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 650px; margin-top: 30px;">

    <!-- Navegación rápida -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <a href="{{ route('students.index') }}"
            class="btn btn-secondary btn-sm shadow-sm">
            ← Volver al Listado
        </a>

        <span class="text-muted fw-bold">
            Módulo de Estudiantes
        </span>
    </div>

    <!-- Alertas de errores -->
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4">

        <strong>¡Atención!</strong> Por favor corrige los errores en el formulario.

        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>

        <button type="button" class="btn-close"
            data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Tarjeta del formulario -->
    <div class="card shadow border-0">

        <div class="card-header text-white py-3"
            style="background-color: #39A900;">

            <h4 class="mb-0 fw-bold">
                ➕ Registrar Nuevo Estudiante
            </h4>
        </div>

        <div class="card-body p-4 bg-light">

            <form action="{{ route('students.store') }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf

                <!-- Información personal -->
                <h5 class="text-success mb-3 border-bottom pb-2">
                    📂 Datos del Estudiante
                </h5>

                <!-- Nombre -->
                <div class="mb-3">

                    <label class="form-label fw-bold text-muted">
                        Nombre:
                    </label>

                    <input
                        type="text"
                        name="nombre"
                        value="{{ old('nombre') }}"
                        class="form-control shadow-sm @error('nombre') is-invalid @enderror"
                        placeholder="Ej. Carlos"
                        required>

                    @error('nombre')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Apellido -->
                <div class="mb-3">

                    <label class="form-label fw-bold text-muted">
                        Apellido:
                    </label>

                    <input
                        type="text"
                        name="apellido"
                        value="{{ old('apellido') }}"
                        class="form-control shadow-sm @error('apellido') is-invalid @enderror"
                        placeholder="Ej. Pérez"
                        required>

                    @error('apellido')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Fecha de nacimiento -->
                <div class="mb-3">

                    <label class="form-label fw-bold text-muted">
                        Fecha de nacimiento:
                    </label>

                    <input
                        type="date"
                        name="fecha_nacimiento"
                        value="{{ old('fecha_nacimiento') }}"
                        class="form-control shadow-sm @error('fecha_nacimiento') is-invalid @enderror"
                        required>

                    @error('fecha_nacimiento')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Expediente -->
                <div class="mb-3">

                    <label class="form-label fw-bold text-muted">
                        Expediente:
                    </label>

                    <input
                        type="text"
                        name="expediente"
                        value="{{ old('expediente') }}"
                        class="form-control shadow-sm @error('expediente') is-invalid @enderror"
                        placeholder="Ej. EST-001"
                        required>

                    @error('expediente')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Imagen -->
                <h5 class="text-success mb-3 border-bottom pb-2 mt-4">
                    🖼️ Fotografía del Estudiante
                </h5>

                <div class="mb-3">

                    <label class="form-label fw-bold text-muted">
                        Seleccionar imagen:
                    </label>

                    <input
                        type="file"
                        name="imagen"
                        accept="image/jpeg,image/png,image/webp"
                        class="form-control shadow-sm @error('imagen') is-invalid @enderror">

                    <small class="text-muted">
                        Formatos permitidos: JPG, JPEG, PNG y WEBP. Máximo 2 MB.
                    </small>

                    @error('imagen')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Delegado -->
                <h5 class="text-success mb-3 border-bottom pb-2 mt-4">
                    👤 Delegación
                </h5>

                <div class="mb-3">

                    <label class="form-label fw-bold text-muted">
                        Seleccionar delegado:
                    </label>

                    <select
                        name="delegado_id"
                        class="form-select shadow-sm @error('delegado_id') is-invalid @enderror">

                        <option value="">
                            Sin delegado
                        </option>

                        @foreach($students as $item)

                        <option
                            value="{{ $item['id'] }}"
                            {{ old('delegado_id') == $item['id'] ? 'selected' : '' }}>
                            {{ $item['nombre'] }} {{ $item['apellido'] }}
                        </option>

                        @endforeach

                    </select>

                    @error('delegado_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>

                <!-- Módulos -->
                <h5 class="text-success mb-3 border-bottom pb-2 mt-4">
                    📚 Módulos del Estudiante
                </h5>

                <div class="mb-3">

                    <label class="form-label fw-bold text-muted">
                        Seleccionar módulos:
                    </label>

                    <div class="card border-0 shadow-sm">
                        <div class="card-body">

                            @forelse($modules as $module)

                            <div class="form-check mb-2">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="module_ids[]"
                                    value="{{ $module['id'] }}"
                                    id="module_{{ $module['id'] }}"
                                    {{ in_array($module['id'], old('module_ids', [])) ? 'checked' : '' }}>

                                <label
                                    class="form-check-label"
                                    for="module_{{ $module['id'] }}">
                                    {{ $module['nombre'] }}
                                </label>

                            </div>

                            @empty

                            <p class="text-muted mb-0">
                                No hay módulos registrados.
                            </p>

                            @endforelse

                        </div>
                    </div>

                    @error('module_ids')
                    <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror

                </div>

                <!-- Botón guardar -->
                <div class="text-end border-top pt-3 mt-4">

                    <a href="{{ route('students.index') }}"
                        class="btn btn-secondary px-3">
                        Cancelar
                    </a>

                    <button
                        type="submit"
                        class="btn btn-success px-4 shadow"
                        style="background-color: #39A900; border: none;">
                        💾 Guardar Estudiante
                    </button>

                </div>

            </form>
        </div>
    </div>
</div>
@endsection