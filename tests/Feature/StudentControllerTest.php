<?php

namespace Tests\Feature;

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_return_all_students(): void
    {

        Student::create([
            'name' => 'Juan',
            'surnames' => 'Perez',
            'age' => 20
        ]);

        Student::create([
            'name' => 'Maria',
            'surnames' => 'Lopez',
            'age' => 22
        ]);
    
        $response = $this->getJson('/api/students');

        $response->assertStatus(200);

        // Verificar que la respuesta contenga los datos de los estudiantes
        $response->assertJsonFragment([
            'name' => 'Juan',
            'surnames' => 'Perez',
            'age' => 20
        ]);

        $response->assertJsonFragment([
            'name' => 'Maria',
            'surnames' => 'Lopez',
            'age' => 22
        ]);
    }

   
    public function test_it_returns_404_if_no_students_found(): void
    {
        $response = $this->getJson('/api/students');
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'No se encontraron estudiantes.',
            'data' => [],
        ]);
    }

   
    public function test_it_can_show_an_existing_student(): void
    {

        $student = Student::create([
            'name' => 'Juan',
            'surnames' => 'Perez',
            'age' => 20
        ]);

        $response = $this->getJson("/api/student/{$student->id}");
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'Juan',
            'surnames' => 'Perez',
            'age' => 20
        ]);
    }


    public function test_it_can_delete_a_student(): void
    {
        $student = Student::create([
            'name' => 'Juan',
            'surnames' => 'Perez',
            'age' => 20,
        ]);

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'name' => 'Juan',
            'surnames' => 'Perez',
            'age' => 20,
        ]);

        $response = $this->deleteJson("/api/student/{$student->id}");
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Estudiante eliminado exitosamente',
        ]);

        // Verificar que el estudiante ya no exista en la base de datos
        $this->assertDatabaseMissing('students', [
            'id' => $student->id,
        ]);
    }
   
    public function test_it_can_not_delete_a_non_existent_student(): void
    {
        // Intentar eliminar un estudiante con un ID que no existe
        $nonExistentId = 999;

        // Hacer la solicitud DELETE a la ruta /api/student/{id} para eliminar el estudiante que no existe
        $response = $this->deleteJson("/api/student/{$nonExistentId}");
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Estudiante no encontrado',
        ]);
    }


    public function test_it_can_create_a_student(): void
    {
        $student = [
            'id' => 1,
            'name' => 'Juan',
            'surnames' => 'Perez',
            'age' => 20,
        ];

          $response = $this->postJson('/api/student', $student);
          $response->assertStatus(201);
        $this->assertDatabaseHas('students', [
            'id' => 1,
            'name' => 'Juan',
            'surnames' => 'Perez',
            'age' => 20,
        ]);

    }

    public function test_it_can_not_create_a_student(): void
    {
        // Crear un estudiante vacio
        $student = [

        ];

        $response = $this->postJson('/api/student', $student);

        $response->assertStatus(422);
    }

    public function test_it_can_update_a_student(): void {

        $student = Student::create([
            'name' => 'Juan',
            'surnames' => 'Perez',
            'age' => 20,
        ]);

        // Datos para actualizar el estudiante
        $updatedData = [
            'name' => 'Carlos',
            'surnames' => 'Gomez',
            'age' => 21,
        ];

        $response = $this->putJson('/api/student/' . $student->id, $updatedData);
        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'Estudiante actualizado exitosamente',
        ]);

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'name' => 'Carlos',    // Verificamos que el nombre se haya actualizado
            'surnames' => 'Gomez', // Verificamos que el apellido se haya actualizado
            'age' => 21,           // Verificamos que la edad se haya actualizado
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

}