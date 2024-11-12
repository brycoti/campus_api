<?php

namespace App\Http\Controllers;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Requests\StoreStudentRequest;


class StudentController extends Controller
{
    public function index()
    {
        $students = Student::all();


        if ($students->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron estudiantes.',
                'data' => []
            ], 200);
        }

        return response()->json($students); 
    }

    public function show ($id) {
            // Buscar al estudiante por su ID
    $student = Student::find($id);

    // Si no se encuentra el estudiante, devolver un error 404
    if (!$student) {
        return response()->json([
            'message' => 'Estudiante no encontrado',
        ], 404);
    }

    // Si el estudiante existe, devolver los detalles
    return response()->json([
        'student' => $student,
    ]);   
    }

    public function store(Request $request)
    {
        $student = new Student;

        $student->name = $request->input('name');
        $student->surnames = $request->input('surnames');
        $student->age = $request->input('age');

        $student->save();

        return response()->json([
           'message' => 'Student created succesfully',
           'student' => $student 
        ]);
    }

    public function destroy($id) {
        // Buscar al estudiante por su ID
        $student = Student::find($id);

        // Si no se encuentra el estudiante, devolver un error 404
        if (!$student) {
            return response()->json([
                'message' => 'Estudiante no encontrado',
            ], 404);
        }

        // Eliminar el estudiante
        $student->delete();

        // Respuesta de éxito
        return response()->json([
            'message' => 'Estudiante eliminado exitosamente',
        ]);
    }
}

   