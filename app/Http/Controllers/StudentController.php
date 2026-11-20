<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StudentController extends Controller
{
    // URL base de la API
    private function apiUrl()
    {
        return rtrim(env('URL_SERVER_API'), '/');
    }

    // Consultar datos de la API
    private function fetchDataFromApi($url)
    {
        $response = Http::acceptJson()->get($url);

        if ($response->failed()) {
            return [];
        }

        return $response->json();
    }

    // Mostrar todos los estudiantes
    public function index()
    {
        $response = $this->fetchDataFromApi(
            $this->apiUrl() . '/students'
        );

        $students = $response['data'] ?? [];

        return view('students.index', compact('students'));
    }

    // Formulario para crear estudiante
    public function create()
    {
        // Obtener módulos para el formulario
        $response = $this->fetchDataFromApi(
            $this->apiUrl() . '/modules'
        );

        $modules = $response['data'] ?? [];

        // Obtener estudiantes para seleccionar delegado
        $response = $this->fetchDataFromApi(
            $this->apiUrl() . '/students'
        );

        $students = $response['data'] ?? [];

        return view('students.create', compact(
            'modules',
            'students'
        ));
    }

    // Guardar estudiante
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'expediente' => 'required|string|max:255',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'delegado_id' => 'nullable|integer',
            'module_ids' => 'nullable|array',
            'module_ids.*' => 'integer',
        ]);

        // Preparar datos para enviar a la API
        $http = Http::acceptJson();

        $data = [
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'expediente' => $request->expediente,
        ];

        if ($request->filled('delegado_id')) {
            $data['delegado_id'] = $request->delegado_id;
        }

        foreach ($request->input('module_ids', []) as $index => $moduleId) {
            $data["module_ids[$index]"] = $moduleId;
        }

        // Adjuntar imagen si existe
        if ($request->hasFile('imagen')) {
            $http = $http->attach(
                'imagen',
                file_get_contents($request->file('imagen')->getRealPath()),
                $request->file('imagen')->getClientOriginalName()
            );
        }

        // Enviar POST a la API
        $response = $http->post(
            $this->apiUrl() . '/students',
            $data
        );

        if ($response->failed()) {
            return back()
                ->withErrors([
                    'error' => $response->json('message')
                        ?? 'Error al crear el estudiante'
                ])
                ->withInput();
        }

        return redirect()
            ->route('students.index')
            ->with('success', 'Estudiante creado correctamente.');
    }

    // Mostrar un estudiante
    public function show($id)
    {
        $response = $this->fetchDataFromApi(
            $this->apiUrl() . '/students/' . $id
        );

        $student = $response['data'] ?? null;

        if (!$student) {
            return redirect()
                ->route('students.index')
                ->withErrors(['error' => 'Estudiante no encontrado.']);
        }

        return view('students.show', compact('student'));
    }

    // Formulario para editar estudiante
    public function edit($id)
    {
        $response = $this->fetchDataFromApi(
            $this->apiUrl() . '/students/' . $id
        );

        $student = $response['data'] ?? null;

        if (!$student) {
            return redirect()
                ->route('students.index')
                ->withErrors(['error' => 'Estudiante no encontrado.']);
        }

        // Obtener módulos
        $response = $this->fetchDataFromApi(
            $this->apiUrl() . '/modules'
        );

        $modules = $response['data'] ?? [];

        // Obtener estudiantes para seleccionar delegado
        $response = $this->fetchDataFromApi(
            $this->apiUrl() . '/students'
        );

        $students = $response['data'] ?? [];

        return view('students.edit', compact(
            'student',
            'modules',
            'students'
        ));
    }

    // Actualizar estudiante
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'expediente' => 'required|string|max:255',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'delegado_id' => 'nullable|integer',
            'module_ids' => 'nullable|array',
            'module_ids.*' => 'integer',
        ]);

        $http = Http::acceptJson();

        $data = [
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'expediente' => $request->expediente,
            '_method' => 'PUT',
        ];

        if ($request->filled('delegado_id')) {
            $data['delegado_id'] = $request->delegado_id;
        } else {
            $data['delegado_id'] = '';
        }

        foreach ($request->input('module_ids', []) as $index => $moduleId) {
            $data["module_ids[$index]"] = $moduleId;
        }

        // Adjuntar nueva imagen si existe
        if ($request->hasFile('imagen')) {
            $http = $http->attach(
                'imagen',
                file_get_contents($request->file('imagen')->getRealPath()),
                $request->file('imagen')->getClientOriginalName()
            );
        }

        // Laravel recibe el POST y utiliza el método PUT simulado
        $response = $http->post(
            $this->apiUrl() . '/students/' . $id,
            $data
        );

        if ($response->failed()) {
            return back()
                ->withErrors([
                    'error' => $response->json('message')
                        ?? 'Error al actualizar el estudiante'
                ])
                ->withInput();
        }

        return redirect()
            ->route('students.index')
            ->with('success', 'Estudiante actualizado correctamente.');
    }

    // Eliminar estudiante
    public function destroy($id)
    {
        $response = Http::acceptJson()->delete(
            $this->apiUrl() . '/students/' . $id
        );

        if ($response->failed()) {
            return back()->withErrors([
                'error' => $response->json('message')
                    ?? 'Error al eliminar el estudiante'
            ]);
        }

        return redirect()
            ->route('students.index')
            ->with('success', 'Estudiante eliminado correctamente.');
    }
}
