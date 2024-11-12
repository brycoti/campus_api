<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Subject;
use App\Models\Mark;
use App\Models\Student;

class MarkControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_get_all_marks()
    {
        
        Student::factory()->create();
        Subject::factory()->create();
        
        $mark = Mark::create([
            'student_id' => 1,
            'subject_id' => 1,
            'mark' => 10
        ]);

        $response = $this->getJson('/api/marks');

        $response->assertStatus(200);
        $response->assertJson([$mark->toArray()]);
    }

    public function test_it_can_not_get_all_marks()
    {
        $response = $this->getJson('/api/marks');
        $response->assertStatus(404);

        $response->assertJson([
            'message' => 'No se encontraron notas.',
        ]);
    }

    public function test_it_can_show_a_mark(): void
    {
        $mark = Mark::factory()->create();

        $response = $this->getJson("/api/mark/{$mark->id}");

        $response->assertStatus(200);
        
        $response->assertJsonFragment([
            'id' => $mark->id,
            'student_id' => $mark->student_id,
            'subject_id' => $mark->subject_id,
            'mark' => $mark->mark,
        ]);
    }

    public function test_it_can_not_show_a_non_existing_mark(): void {
        $mark_id = 999999;

        $response = $this->getJson("/api/mark/{$mark_id}");
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Nota no encontrada'
        ]);
    }

    public function test_it_can_create_a_mark(): void {
        $student = Student::factory()->create();
        $subject = Subject::factory()->create();

        $response = $this->postJson('/api/mark', [
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'mark' => 10
        ]);

        $response->assertStatus(201);
       
        $response->assertJsonFragment([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'mark' => 10
        ]);

        $this->assertDatabaseHas('marks', [
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'mark' => 10
        ]);
    }

    public function test_it_can_not_create_a_mark_with_invalid_data(): void {
        $response = $this->postJson('/api/mark', [
            'student_id' => 99,
            'subject_id' => 199,
            'mark' => 11
        ]);

        $response->assertStatus(422);
    }

    public function test_it_can_update_a_mark(): void {
        $mark = Mark::factory()->create();

        $response = $this->putJson("/api/mark/{$mark->id}", [
            'student_id' => $mark->student_id,
            'subject_id' => $mark->subject_id,
            'mark' => 9
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'student_id' => $mark->student_id,
            'subject_id' => $mark->subject_id,
            'mark' => 9
        ]);

        $this->assertDatabaseHas('marks', [
            'id' => $mark->id,
            'student_id' => $mark->student_id,
            'subject_id' => $mark->subject_id,
            'mark' => 9
        ]);
    }

    public function test_it_can_not_update_a_non_existing_mark(): void {
        $mark_id = 999999;

        $response = $this->putJson("/api/mark/{$mark_id}", [
            'student_id' => 1,
            'subject_id' => 1,
            'mark' => 9
        ]);

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Nota no encontrada'
        ]);
    }

    public function test_it_can_not_update_a_mark_with_non_existing_data(): void {
        $mark = Mark::factory()->create();

        $response = $this->putJson("/api/mark/{$mark->id}", [
            'student_id' => 199,
            'subject_id' => 199,
            'mark' => 9
        ]);

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Estudiante o asignatura no encontrados'
        ]);
    }

    public function test_it_can_delete_a_mark(): void {
        $mark = Mark::factory()->create();

        $response = $this->deleteJson("/api/mark/{$mark->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'message' => 'Nota eliminada exitosamente'
        ]);

        $this->assertDatabaseMissing('marks', [
            'id' => $mark->id
        ]);
    }

        public function test_it_can_get_marks_by_student(): void
    {
        $student = Student::factory()->create();
        $subject = Subject::factory()->create();

        Mark::factory()->create([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'mark' => 8,
        ]);
        Mark::factory()->create([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'mark' => 9,
        ]);


        $response = $this->getJson("/api/marksByStudent/{$student->id}");
        $response->assertStatus(200);

        // Verificar que las calificaciones estén en la respuesta
        $response->assertJsonFragment([
            'student_id' => $student->id,
            'mark' => 8,
        ]);
        $response->assertJsonFragment([
            'student_id' => $student->id,
            'mark' => 9,
        ]);
    }

    public function test_it_can_get_avg_marks_by_student(): void
    {

        $student = Student::factory()->create();
        $subject = Subject::factory()->create();

        Mark::factory()->create([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'mark' => 8,
        ]);
        Mark::factory()->create([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'mark' => 9,
        ]);

        $response = $this->getJson("/api/marksAvgByStudent/{$student->id}");
        $response->assertStatus(200);

        // Verificar que el promedio sea correcto
        $response->assertJsonFragment([
            'average' => 8.5,
        ]);
    }

    public function test_it_can_get_all_avg_marks(): void
    {

        $student1 = Student::factory()->create();
        $student2 = Student::factory()->create();
        $subject = Subject::factory()->create();

        Mark::factory()->create([
            'student_id' => $student1->id,
            'subject_id' => $subject->id,
            'mark' => 8,
        ]);
        Mark::factory()->create([
            'student_id' => $student2->id,
            'subject_id' => $subject->id,
            'mark' => 10,
        ]);

        $response = $this->getJson('/api/allMarksAvg');
        $response->assertStatus(200);

        // Verificar que el promedio global sea el esperado (9)
        $response->assertJsonFragment([
            'average' => 9,
        ]);
    }

    public function test_it_can_get_avg_marks_by_subject(): void
    {

        $student1 = Student::factory()->create();
        $student2 = Student::factory()->create();
        $subject = Subject::factory()->create();

        Mark::factory()->create([
            'student_id' => $student1->id,
            'subject_id' => $subject->id,
            'mark' => 8,
        ]);
        Mark::factory()->create([
            'student_id' => $student2->id,
            'subject_id' => $subject->id,
            'mark' => 10,
        ]);

        $response = $this->getJson("/api/marksAvgBySubject/{$subject->id}/");
        $response->assertStatus(200);

        // Verificar que el promedio sea el esperado (9)
        $response->assertJsonFragment([
            'average_mark' => 9,
        ]);
    }

}