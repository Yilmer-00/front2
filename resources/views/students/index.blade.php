@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 1100px; margin-top: 30px;">

    <!-- Navegación rápida -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <span class="text-muted fw-bold">Módulo de Estudiantes</span>

        <a href="{{ route('students.create') }}"
            class="btn btn-success btn-sm shadow-sm"
            style="background-color: #39A900; border: none;">
            + Registrar Nuevo Estudiante
        </a>
    </div>

    <!-- Alerta de éxito -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4"
        role="alert"
        style="border-left: 5px solid #39A900 !important;">

        <strong>¡Éxito!</strong> {{ session('success') }}

        <button type="button" class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close"></button>
    </div>
    @endif

    <!-- Alerta de errores -->
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4">
        <strong>¡Atención!</strong> Por favor revisa los siguientes errores.

        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>

        <button type="button" class="btn-close"
            data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Tarjeta del listado -->
    <div class="card shadow border-0">

        <div class="card-header text-white py-3"
            style="background-color: #39A900;">

            <h4 class="mb-0 fw-bold">
                Listado de Estudiantes
            </h4>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3 text-muted fw-bold">ID</th>
                            <th class="py-3 text-muted fw-bold">Imagen</th>
                            <th class="py-3 text-muted fw-bold">Nombre</th>
                            <th class="py-3 text-muted fw-bold">Apellido</th>
                            <th class="py-3 text-muted fw-bold">Fecha nacimiento</th>
                            <th class="py-3 text-muted fw-bold">Expediente</th>
                            <th class="py-3 text-center text-muted fw-bold">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($students as $student)

                        <tr>
                            <!-- ID -->
                            <td class="ps-4 fw-bold text-secondary">
                                {{ $student['id'] }}
                            </td>

                            <!-- Imagen de Cloudinary -->
                            <td>
                                @if(!empty($student['imagen_url']))
                                <img
                                    src="{{ $student['imagen_url'] }}"
                                    alt="Foto de {{ $student['nombre'] }}"
                                    width="55"
                                    height="55"
                                    class="rounded-circle shadow-sm"
                                    style="object-fit: cover;">
                                @else
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                    style="width: 55px; height: 55px;">
                                    <i class="fas fa-user text-secondary fs-4"></i>
                                </div>
                                @endif
                            </td>

                            <!-- Nombre -->
                            <td class="fw-semibold text-dark">
                                {{ $student['nombre'] }}
                            </td>

                            <!-- Apellido -->
                            <td class="fw-semibold text-dark">
                                {{ $student['apellido'] }}
                            </td>

                            <!-- Fecha de nacimiento -->
                            <td class="text-dark">
                                {{ $student['fecha_nacimiento'] }}
                            </td>

                            <!-- Expediente -->
                            <td class="fw-semibold text-dark">
                                {{ $student['expediente'] }}
                            </td>

                            <!-- Acciones -->
                            <td class="pe-3">
                                <div class="d-flex gap-2 justify-content-center align-items-center">

                                    <a href="{{ route('students.show', $student['id']) }}"
                                        class="btn btn-primary btn-sm shadow-sm">
                                        Mostrar
                                    </a>

                                    <a href="{{ route('students.edit', $student['id']) }}"
                                        class="btn btn-warning btn-sm text-dark fw-semibold shadow-sm">
                                        Editar
                                    </a>

                                    <form action="{{ route('students.destroy', $student['id']) }}"
                                        method="POST"
                                        class="d-inline m-0"
                                        onsubmit="return confirm('¿Estás seguro de que deseas eliminar este estudiante?');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="btn btn-danger btn-sm shadow-sm">
                                            Eliminar
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>

                        @empty

                        <!-- Sin registros -->
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">

                                <i class="fas fa-folder-open fs-2 mb-3 d-block text-secondary"></i>

                                No hay estudiantes registrados en el sistema.

                                <div class="mt-3">
                                    <a href="{{ route('students.create') }}"
                                        class="btn btn-success btn-sm"
                                        style="background-color: #39A900; border: none;">
                                        Registrar primer estudiante
                                    </a>
                                </div>

                            </td>
                        </tr>

                        @endforelse

                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
@endsection