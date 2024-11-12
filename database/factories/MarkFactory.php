<?php

namespace Database\Factories;

use App\Models\Mark;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mark>
 */
class MarkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Mark::class;

    public function definition(): array
    {
        return [
             'student_id' => Student::factory(),  // Crea un estudiante automáticamente
             // Asignar una asignatura existente aleatoria
             'subject_id' => Subject::factory(),  // Crea una asignatura automáticamente
             'mark' => $this->faker->numberBetween(0, 10),  // Nota entre 0 y 10
        ];
    }
}
