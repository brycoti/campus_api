<?php

namespace Tests\Feature;

use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubjectControllerTest extends TestCase
{
    use RefreshDatabase;

   public function test_it_can_return_all_subjects(): void
    {
        Subject::create([
            'name' => 'Matematicas',
            'year' => '1r'
        ]);

        $response = $this->getJson('/api/subjects');

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'name' => 'Matematicas',
            'year' => '1r'
        ]);
    }

    public function test_it_can_not_return_all_subjects(): void
    {
        $response = $this->getJson('/api/subjects');
        $response->assertStatus(404);
    }

    public function test_it_can_show_a_subject(): void {
        $subject = Subject::create([
            'name' => 'Fisica',
            'year' => '2n'
        ]);

        $response = $this->getJson("api/subject/{$subject->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'Fisica',
            'year' => '2n'
        ]);
    }

    public function test_it_can_not_show_a_non_existing_subject(): void {

        $student_id = 999;

        $response = $this->getJson("/api/subject/{$student_id}");
        $response->assertStatus(404);

        $response->assertJsonFragment([
            'message' => 'Asignatura no encontrada'
        ]);
    }

    public function test_it_can_create_a_subject(): void {
        $subject = [
            'name' => 'Egiptologia',
            'year' => '4t'
        ];

        $response = $this->postJson('api/subject', $subject);
        $response->assertStatus(201);

        $this->assertDatabaseHas('subjects', [
            'name' => 'Egiptologia',
            'year' => '4t'
        ]);
    }

    public function test_it_can_not_create_a_subject_with_wrong_year():void {
        $subject = [
            'name' => 'Ciencia Ficcion',
            'year' => '5t' // si no es 1r, 2n, 3r o 4t lanza error
        ];

        $response = $this->postJson('api/subject', $subject);
        $response->assertStatus(400);

    }

    public function test_it_can_update_a_subject(): void {

        $subject = Subject::create([
            'name' => 'Historia',
            'year' => '1r'
        ]);

        // Datos para actualizar el estudiante
        $updatedData = [
            'name' => 'Filosofia',
            'year' => '1r'
        ];

        $response = $this->putJson('/api/subject/' . $subject->id, $updatedData);
        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'Asignatura actualizada exitosamente',
        ]);

        $this->assertDatabaseHas('subjects', [
            'id' => $subject->id,
            'name' => 'Filosofia',
            'year' => '1r'
        ]);
    }

    public function test_it_can_not_update_a_student(): void
    {
        $updatedData = [
            'name' => 'Carlos',
            'surnames' => 'Gomez',
            'age' => 21,
        ];

        // Intentar hacer una solicitud PUT a un estudiante que no existe
        $nonExistentId = 999999;
        $response = $this->putJson('/api/student/' . $nonExistentId, $updatedData);
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Estudiante no encontrado',
        ]);
    }

    public function test_it_can_not_update_a_subject_with_wrong_year():void {
        $subject = Subject::create([
            'name' => 'Historia',
            'year' => '1r'
        ]);

        // Datos para actualizar el estudiante
        $updatedData = [
            'name' => 'Filosofia',
            'year' => '5t'
        ];

        $response = $this->putJson('api/subject/' . $subject->id, $updatedData);
        $response->assertStatus(400);

    }

    public function test_it_can_delete_a_subject(): void {
        $subject = Subject::create([
            'name' => 'Historia',
            'year' => '1r'
        ]);

        $response = $this->deleteJson('/api/subject/' . $subject->id);
        $response->assertStatus(200);

        $this->assertDatabaseMissing('subjects', [
            'id' => $subject->id
        ]);
    }

    public function test_it_can_not_delete_a_non_existing_subject(): void {
        $subject_id = 999999;

        $response = $this->deleteJson("/api/subject/{$subject_id}");
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Asignatura no encontrada'
        ]);
    }
}
