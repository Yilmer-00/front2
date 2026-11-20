<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TeacherController extends Controller
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

    // Mostrar todos los profesores
    public function index()
    {
        $response = $this->fetchDataFromApi($this->apiUrl() . '/teachers');
        $teachers = $response['data'] ?? [];
        return view('teachers.index', compact('teachers'));
    }

    // Formulario para crear
    public function create()
    {
        return view('teachers.create');
    }

    // Guardar profesor (AQUÍ FALTABA EL POST)
    public function store(Request $request)
    {
        // En el frontend validamos lo básico. La regla "unique" la hace el Backend.
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'telefono' => 'required|string|max:255',
            'cedula' => 'required|string',
        ]);

        // Hacemos la petición POST real a la API
        $response = Http::acceptJson()->post($this->apiUrl() . '/teachers', [
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'telefono' => $request->telefono,
            'cedula' => $request->cedula,
        ]);

        // Si el backend rechaza la petición (ej. cédula duplicada), mostramos el error
        if ($response->failed()) {
            return back()
                ->withErrors(['error' => $response->json('message') ?? 'Error al crear el profesor'])
                ->withInput();
        }

        return redirect()
            ->route('teachers.index')
            ->with('success', 'Profesor creado correctamente.');
    }

    // Mostrar un profesor
    public function show($id)
    {
        $response = $this->fetchDataFromApi($this->apiUrl() . '/teachers/' . $id);
        $teacher = $response['data'] ?? null;

        if (!$teacher) {
            return redirect()->route('teachers.index')->withErrors(['error' => 'Profesor no encontrado.']);
        }

        return view('teachers.show', compact('teacher'));
    }

    // Formulario para editar
    public function edit($id)
    {
        $response = $this->fetchDataFromApi($this->apiUrl() . '/teachers/' . $id);
        $teacher = $response['data'] ?? null;

        if (!$teacher) {
            return redirect()
                ->route('teachers.index')
                ->withErrors(['error' => 'Profesor no encontrado.']);
        }

        return view('teachers.edit', compact('teacher'));
    }

    // Actualizar profesor (AQUÍ ESTABA EL ERROR DE ENVIAR SOLO 'name')
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'telefono' => 'required|string|max:255',
            'cedula' => 'required|string',
        ]);

        // Enviamos TODOS los campos que espera el backend
        $response = Http::acceptJson()->put(
            $this->apiUrl() . '/teachers/' . $id,
            [
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'telefono' => $request->telefono,
                'cedula' => $request->cedula,
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
            ->route('teachers.index')
            ->with('success', 'Profesor actualizado correctamente.');
    }

    // Eliminar profesor
    public function destroy($id)
    {
        $response = Http::acceptJson()->delete($this->apiUrl() . '/teachers/' . $id);

        if ($response->failed()) {
            return back()->withErrors([
                'error' => $response->json('message') ?? 'Error al eliminar el profesor'
            ]);
        }

        return redirect()
            ->route('teachers.index')
            ->with('success', 'Profesor eliminado correctamente.');
    }
}
