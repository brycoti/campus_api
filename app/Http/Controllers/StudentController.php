<?php

namespace App\Http\Controllers;
use App\Models\Student;
use Illuminate\Http\Request;

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
}
