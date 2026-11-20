@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 1000px; margin-top: 30px;">

    <!-- Botones de Navegación Rápida -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <span class="text-muted fw-bold">Módulo de Profesores</span>
        <a href="{{ route('modules.create') }}" class="btn btn-success btn-sm shadow-sm" style="background-color: #39A900; border: none;">
            + Registrar Nuevo Profesor
        </a>
    </div>

    <!-- Alerta de Éxito -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert" style="border-left: 5px solid #39A900 !important;">
        <strong>¡Éxito!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Tarjeta que contiene la Tabla -->
    <div class="card shadow border-0">
        <div class="card-header text-white py-3" style="background-color: #39A900;">
            <h4 class="mb-0 fw-bold"> Listado de modulos</h4>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3 text-muted fw-bold">ID</th>
                            <th class="py-3 text-muted fw-bold">Nombre</th>
                            <th class="py-3 text-muted fw-bold">codigo</th>
                            <th class="py-3 text-center text-muted fw-bold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($modules as $module) <!-- CORREGIDO AQUÍ -->
                        <tr>
                            <td class="ps-4 fw-bold text-secondary">
                                {{ $module['id'] }} <!-- CORREGIDO AQUÍ -->
                            </td>
                            <td class="fw-semibold text-dark">
                                {{ $module['nombre'] }} <!-- CORREGIDO AQUÍ -->
                            </td>
                            <td class="fw-semibold text-dark">
                                {{ $module['codigo'] }}
                            </td>
                            <!-- Botones de Acciones -->
                            <td class="pe-4">
                                <div class="d-flex gap-2 justify-content-center align-items-center">
                                    <a href="{{ route('modules.show', $module['id']) }}" class="btn btn-primary btn-sm shadow-sm">
                                        Mostrar
                                    </a>
                                    <a href="{{ route('modules.edit', $module['id']) }}" class="btn btn-warning btn-sm text-dark fw-semibold shadow-sm">
                                        Editar
                                    </a>
                                    <form action="{{ route('modules.destroy', $module['id']) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este profesor?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm shadow-sm">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <!-- Mensaje por si aún no hay profesores registrados -->
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted"> <!-- CORREGIDO A 6 -->
                                <i class="fas fa-folder-open fs-2 mb-3 d-block text-secondary"></i>
                                No hay profesores registrados en el sistema.
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