<?php

namespace App\Http\Controllers;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Requests\Student\UpdateStudentRequest;



class StudentController extends Controller
{
    public function index()
    {
        $students = Student::all();

        if ($students->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron estudiantes.',
                'data' => []
            ], 404);
        }

        return response()->json($students, 200);
    }

    public function show ($id) {
       
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'message' => 'Estudiante no encontrado',
            ], 404);
        }

        return response()->json([
            'student' => $student,
        ]);
    }

    public function store(StoreStudentRequest $request)
    {
        $student = new Student;

        $student->name = $request->input('name');
        $student->surnames = $request->input('surnames');
        $student->age = $request->input('age');

        $student->save();

        return response()->json([
           'message' => 'Student created succesfully',
           'student' => $student
        ], 201);
    }
    public function update(StoreStudentRequest $request, $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'message' => 'Estudiante no encontrado'
            ], 404);
        }

        $student->name = $request->input('name');
        $student->surnames = $request->input('surnames');
        $student->age = $request->input('age');

        $student->save();

        return response()->json([
            'message' => 'Estudiante actualizado exitosamente',
            'student' => $student
        ], 201);
    }

    public function destroy($id) {
        
        $student = Student::find($id);
   
        if (!$student) {
            return response()->json([
                'message' => 'Estudiante no encontrado',
            ], 404);
        }

        $student->delete();

        return response()->json([
            'message' => 'Estudiante eliminado exitosamente',
        ], 200);
    }
}
