<?php

namespace App\Http\Controllers;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
        public function index()
    {
        $subjects = Subject::all();

        if ($subjects->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron asignaturas.',
                'data' => []
            ], 404);
        }

        return response()->json($subjects, 200);
    }

    public function show ($id) {
    $subject = Subject::find($id);

    if (!$subject) {
        return response()->json([
            'message' => 'Asignatura no encontrada',
        ], 404);
    }

    return response()->json([
        'subject' => $subject,
    ], 200);
    }

    public function store(Request $request)
    {
        $subject = new Subject;

        $subject->name = $request->name;

          // Validar que el año sea uno de los valores permitidos
        if (!in_array($request->year, ['1r', '2n', '3r', '4t'])) {
            return response()->json([
                'message' => 'El valor de curso debe ser uno de los siguientes: 1r, 2n, 3r o 4t.'
            ], 400);
        }

        $subject->year = $request->year;

        $subject->save();

        return response()->json([
           'message' => 'Asignatura creada exitosamente',
           'subject' => $subject,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $subject = Subject::find($id);

        if (!$subject) {
            return response()->json([
                'message' => 'Asignatura no encontrada'
            ], 404);
        }

        $subject->name = $request->name;
        $subject->year = $request->year;

        $subject->save();

        return response()->json([
            'message' => 'Asignatura actualizada exitosamente',
            'subject' => $subject,
        ], 201);
    }

    public function destroy($id) {

        $subject = Subject::find($id);

        if (!$subject) {
            return response()->json([
                'message' => 'Asignatura no encontrada',
            ], 404);
        }

        $subject->delete();

        return response()->json([
            'message' => 'Asignatura eliminada exitosamente',
        ], 200);
    }
}
