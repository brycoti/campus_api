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
            ], 200);
        }

        return response()->json($subjects); 
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
    ]);   
    }

    public function store(Request $request)
    {
        $subject = new Subject;

        $subject->name = $request->input('name');
        $subject->year = $request->input('year');

        $subject->save();

        return response()->json([
           'message' => 'Subject created succesfully',
           'subject' => $subject 
        ]);
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
        ]);
    }
}
