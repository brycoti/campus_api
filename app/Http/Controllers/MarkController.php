<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mark;
use App\Models\Student;
use App\Models\Subject;


class MarkController extends Controller
{

    public function index()
    {
        $marks = Mark::all();

        if ($marks->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron notas.',
                'data' => []
            ], 200);
        }

        return response()->json($marks);
    }

    public function show($id)
    {

        $mark = Mark::find($id);
    
        if (!$mark) {
            return response()->json([
                'message' => 'Nota no encontrada',
            ], 404);
        }
    
        return response()->json([
            'mark' => $mark,
        ]);
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'mark' => 'required|numeric|min:0|max:10',
        ]);

        $mark = Mark::updateOrCreate(
            ['student_id' => $request->student_id, 'subject_id' => $request->subject_id],
            ['mark' => $request->mark]
        );

        return response()->json($mark, 201);
    }



    public function getMarksByStudent($id) {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'message' => 'Estudiante no encontrado',
            ], 404);
        }

        $student->marks();

        return response()->json([
            'marks' => $student->marks,
        ]);
    }

    public function getAvgMarksByStudent($id) {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'message' => 'Estudiante no encontrado',
            ], 404);
        }

        $average = $student->marks()->avg('mark');

        return response()->json([
            'marks' => $student->marks,
            'average' => $average,
        ]);
    }

    public function getAllAvgMarks() {
        $average = Mark::all()->avg('mark');

        return response()->json([
            'average' => $average,
        ]);
    }

    public function getAvgMarksBySubject($subject_id) {
        $average = Mark::where('subject_id', $subject_id)->avg('mark');

        // Si no se encuentra ningún registro con ese subject_id, retornamos un mensaje
        if ($average === null) {
            return response()->json([
                'message' => 'No se encontraron notas para la asignatura con ID ' . $subject_id,
            ], 404);
        }

        // Retornamos el promedio calculado
        return response()->json([
            'subject_id' => $subject_id,
            'average_mark' => $average,
        ]);
    }

    public function update(Request $request, $id)
    {
        $mark = Mark::find($id);
        
        if (!$mark) {
            return response()->json([
                'message' => 'Nota no encontrada',
            ], 404);
        }

        $mark->update($request->all());

        return response()->json([
            'message' => 'Nota actualizado exitosamente',
            'student' => $mark
        ]);
    }

    public function destroy($id)
    {
        $mark = Mark::find($id);

        if (!$mark) {
            return response()->json([
                'message' => 'Nota no encontrada',
            ], 404);
        }

        $mark->delete();

        return response()->json([
            'message' => 'Nota eliminada exitosamente',
        ]);
   
    }
}