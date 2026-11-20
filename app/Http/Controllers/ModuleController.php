<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class ModuleController extends Controller
{
    private function apiUrl()
    {
        return rtrim(env('URL_SERVER_API'), '/');
    }

    private function fetchDataFromApi($url)
    {
        $response = Http::acceptJson()->get($url);
        if ($response->failed()) {
            return [];
        }
        return $response->json();
    }

    // Mostrar todos los modulos
    public function index()
    {
        $response = $this->fetchDataFromApi($this->apiUrl() . '/modules');
        $modules = $response['data'] ?? [];
        return view('modules.index', compact('modules'));
    }

    // Formulario para crear
    public function create()
    {
        return view('modules.create');
    }

    // Guardar profesor (AQUÍ FALTABA EL POST)
    public function store(Request $request)
    {
        // En el frontend validamos lo básico. La regla "unique" la hace el Backend.
        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string',
        ]);

        // Hacemos la petición POST real a la API
        $response = Http::acceptJson()->post($this->apiUrl() . '/modules', [
            'nombre' => $request->nombre,
            'codigo' => $request->codigo,
        ]);

        // Si el backend rechaza la petición (ej. cédula duplicada), mostramos el error
        if ($response->failed()) {
            return back()
                ->withErrors(['error' => $response->json('message') ?? 'Error al crear el profesor'])
                ->withInput();
        }

        return redirect()
            ->route('modules.index')
            ->with('success', 'Profesor creado correctamente.');
    }

    // Mostrar un profesor
    public function show($id)
    {
        $response = $this->fetchDataFromApi($this->apiUrl() . '/modules/' . $id);
        $modulo = $response['data'] ?? null;

        if (!$modulo) {
            return redirect()->route('modules.index')->withErrors(['error' => 'Profesor no encontrado.']);
        }

        return view('modules.show', compact('modulo'));
    }

    // Formulario para editar
    public function edit($id)
    {
        $response = $this->fetchDataFromApi($this->apiUrl() . '/modules/' . $id);
        $modulo = $response['data'] ?? null;

        if (!$modulo) {
            return redirect()
                ->route('modules.index')
                ->withErrors(['error' => 'Profesor no encontrado.']);
        }

        return view('modules.edit', compact('modulo'));
    }

    // Actualizar profesor (AQUÍ ESTABA EL ERROR DE ENVIAR SOLO 'name')
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string',
        ]);

        // Enviamos TODOS los campos que espera el backend
        $response = Http::acceptJson()->put(
            $this->apiUrl() . '/modules/' . $id,
            [
                'nombre' => $request->nombre,
                'codigo' => $request->codigo,
            ]
        );

        if ($response->failed()) {
            return back()
                ->withErrors([
                    'error' => $response->json('message') ?? 'Error al actualizar el profesor'
                ])
                ->withInput();
        }

        return redirect()
            ->route('modules.index')
            ->with('success', 'Profesor actualizado correctamente.');
    }

    // Eliminar profesor
    public function destroy($id)
    {
        $response = Http::acceptJson()->delete($this->apiUrl() . '/modules/' . $id);

        if ($response->failed()) {
            return back()->withErrors([
                'error' => $response->json('message') ?? 'Error al eliminar el profesor'
            ]);
        }

        return redirect()
            ->route('modules.index')
            ->with('success', 'Profesor eliminado correctamente.');
    }
}
