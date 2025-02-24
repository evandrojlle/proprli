<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSuccess()
    {
        $buildingId = 6;
        $method = 'GET';
        $endpoint = "/api/tasks/list/{$buildingId}";
        $this->headers['Authorization'] = 'Bearer ' . $this->response->token;
        $data = [];
        $response = $this->json($method, $endpoint, $data, $this->headers);
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
    }

    /**
     * @test
     */
    public function shouldNotFound()
    {
        $buildingId = 999;
        $method = 'GET';
        $endpoint = "/api/tasks/list/{$buildingId}";
        $this->headers['Authorization'] = 'Bearer ' . $this->response->token;
        $data = [];
        $response = $this->json($method, $endpoint, $data, $this->headers);
        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
        ]);
    }

    /**
     * @test
     */
    public function shouldError()
    {
        $buildingId = 'foo';
        $method = 'GET';
        $endpoint = "/api/tasks/list/{$buildingId}";
        $this->headers['Authorization'] = 'Bearer ' . $this->response->token;
        $data = [];
        $response = $this->json($method, $endpoint, $data, $this->headers);
        $response->assertStatus(500);
        $response->assertServerError();
    }

    /**
     * @test
     */
    public function shouldFilteredSuccess()
    {
        $initialData = '2025-02-22';
        $finalData = '2025-02-28';
        $method = 'GET';
        $endpoint = "/api/tasks/filters/initial={$initialData}&&final={$finalData}";
        $this->headers['Authorization'] = 'Bearer ' . $this->response->token;
        $data = [];
        $response = $this->json($method, $endpoint, $data, $this->headers);
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
    }

    /**
     * @test
     */
    public function shouldNotFoundFiltered()
    {
        $initialData = '2025-01-01';
        $finalData = '2025-01-30';
        $method = 'GET';
        $endpoint = "/api/tasks/filters/initial={$initialData}&&final={$finalData}";
        $this->headers['Authorization'] = 'Bearer ' . $this->response->token;
        $data = [];
        $response = $this->json($method, $endpoint, $data, $this->headers);
        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
        ]);
    }

    /**
     * @test
     */
    public function shouldErrorFiltered()
    {
        $initialData = '2025-01-01';
        $finalData = '2025-01-30';
        $method = 'GET';
        $endpoint = "/api/tasks/filters/initials={$initialData}&&finals={$finalData}";
        $this->headers['Authorization'] = 'Bearer ' . $this->response->token;
        $data = [];
        $response = $this->json($method, $endpoint, $data, $this->headers);
        $response->assertStatus(500);
        $response->assertServerError();
    }

    /**
     * @test
     */
    public function shouldStoreFailureWithoutUserId()
    {
        $method = 'POST';
        $endpoint = "/api/tasks/store";
        $data = [
            'user_id' => null,
            'building_id' => 8,
            'name' => fake()->text(150),
            'description' => fake()->text(300)
        ];
        $this->headers['Authorization'] = 'Bearer ' . $this->response->token;
        $response = $this->json($method, $endpoint, $data, $this->headers);
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [],
        ]);
    }

    /**
     * @test
     */
    public function shouldStoreFailureUserIdNotExists()
    {
        $method = 'POST';
        $endpoint = "/api/tasks/store";
        $data = [
            'user_id' => 999,
            'building_id' => 8,
            'name' => fake()->text(150),
            'description' => fake()->text(300)
        ];
        $this->headers['Authorization'] = 'Bearer ' . $this->response->token;
        $response = $this->json($method, $endpoint, $data, $this->headers);
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [],
        ]);
    }

    /**
     * @test
     */
    public function shouldStoreFailureWithoutBuildingId()
    {
        $method = 'POST';
        $endpoint = "/api/tasks/store";
        $data = [
            'user_id' => 10,
            'building_id' => null,
            'name' => fake()->text(150),
            'description' => fake()->text(300)
        ];
        $this->headers['Authorization'] = 'Bearer ' . $this->response->token;
        $response = $this->json($method, $endpoint, $data, $this->headers);
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [],
        ]);
    }

    /**
     * @test
     */
    public function shouldStoreFailureBuildingIdNotExists()
    {
        $method = 'POST';
        $endpoint = "/api/tasks/store";
        $data = [
            'user_id' => 10,
            'building_id' => 9999999,
            'name' => fake()->text(150),
            'description' => fake()->text(300)
        ];
        $this->headers['Authorization'] = 'Bearer ' . $this->response->token;
        $response = $this->json($method, $endpoint, $data, $this->headers);
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [],
        ]);
    }

    /**
     * @test
     */
    public function shouldStoreFailureWithoutName()
    {
        $method = 'POST';
        $endpoint = "/api/tasks/store";
        $data = [
            'user_id' => 10,
            'building_id' => 8,
            'name' => null,
            'description' => fake()->text(300)
        ];
        $this->headers['Authorization'] = 'Bearer ' . $this->response->token;
        $response = $this->json($method, $endpoint, $data, $this->headers);
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [],
        ]);
    }

    /**
     * @test
     */
    public function shouldStoreFailureSmallName()
    {
        $method = 'POST';
        $endpoint = "/api/tasks/store";
        $data = [
            'user_id' => 10,
            'building_id' => 8,
            'name' => fake()->text(5),
            'description' => fake()->text(300)
        ];
        $this->headers['Authorization'] = 'Bearer ' . $this->response->token;
        $response = $this->json($method, $endpoint, $data, $this->headers);
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [],
        ]);
    }

    /**
     * @test
     */
    public function shouldStoreFailureLongName()
    {
        $method = 'POST';
        $endpoint = "/api/tasks/store";
        $data = [
            'user_id' => 10,
            'building_id' => 8,
            'name' => fake()->words(150),
            'description' => fake()->text(990)
        ];
        $this->headers['Authorization'] = 'Bearer ' . $this->response->token;
        $response = $this->json($method, $endpoint, $data, $this->headers);
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [],
        ]);
    }

    /**
     * @test
     */
    public function shouldStoreFailureDuplicatedName()
    {
        $task = Task::first();
        $method = 'POST';
        $endpoint = "/api/tasks/store";
        $data = [
            'user_id' => 10,
            'building_id' => 8,
            'name' => $task->name,
            'description' => fake()->text(300)
        ];
        $this->headers['Authorization'] = 'Bearer ' . $this->response->token;
        $response = $this->json($method, $endpoint, $data, $this->headers);
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [],
        ]);
    }

    /**
     * @test
     */
    public function shouldStoreFailureWithoutDescription()
    {
        $method = 'POST';
        $endpoint = "/api/tasks/store";
        $data = [
            'user_id' => 10,
            'building_id' => 8,
            'name' => fake()->text(150),
            'description' => null
        ];
        $this->headers['Authorization'] = 'Bearer ' . $this->response->token;
        $response = $this->json($method, $endpoint, $data, $this->headers);
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [],
        ]);
    }

    /**
     * @test
     */
    public function shouldStoreFailureLongDescription()
    {
        $method = 'POST';
        $endpoint = "/api/tasks/store";
        $data = [
            'user_id' => 10,
            'building_id' => 8,
            'name' => fake()->text(150),
            'description' => fake()->words(1000),
        ];
        $this->headers['Authorization'] = 'Bearer ' . $this->response->token;
        $response = $this->json($method, $endpoint, $data, $this->headers);
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [],
        ]);
    }

    /**
     * @test
     */
    public function shouldStoreFailureStatusInvalid()
    {
        $method = 'POST';
        $endpoint = "/api/tasks/store";
        $data = [
            'user_id' => 10,
            'building_id' => 8,
            'name' => fake()->text(150),
            'description' => fake()->text(1000),
            'status' => 10
        ];
        $this->headers['Authorization'] = 'Bearer ' . $this->response->token;
        $response = $this->json($method, $endpoint, $data, $this->headers);
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [],
        ]);
    }

    /**
     * @test
     */
    public function shouldStoreSuccess()
    {
        $method = 'POST';
        $endpoint = "/api/tasks/store";
        $data = [
            'user_id' => 10,
            'building_id' => 8,
            'name' => fake()->text(150),
            'description' => fake()->text(300)
        ];
        $this->headers['Authorization'] = 'Bearer ' . $this->response->token;
        $response = $this->json($method, $endpoint, $data, $this->headers);
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
    }

    /**
     * @test
     */
    public function shouldUpdateFailureWithoutUserId()
    {
        $method = 'PUT';
        $endpoint = "/api/tasks/update";
        $data = [
            'task_id' => 1,
            'user_id' => null,
            'building_id' => 8,
            'name' => fake()->text(150),
            'description' => fake()->text(300)
        ];
        $this->headers['Authorization'] = 'Bearer ' . $this->response->token;
        $response = $this->json($method, $endpoint, $data, $this->headers);
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [],
        ]);
    }

    /**
     * @test
     */
    public function shouldUpdateFailureUserIdNotExists()
    {
        $method = 'PUT';
        $endpoint = "/api/tasks/update";
        $data = [
            'task_id' => 1,
            'user_id' => 999,
            'building_id' => 8,
            'name' => fake()->text(150),
            'description' => fake()->text(300)
        ];
        $this->headers['Authorization'] = 'Bearer ' . $this->response->token;
        $response = $this->json($method, $endpoint, $data, $this->headers);
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [],
        ]);
    }

    /**
     * @test
     */
    public function shouldUpdateFailureWithoutBuildingId()
    {
        $method = 'PUT';
        $endpoint = "/api/tasks/update";
        $data = [
            'task_id' => 1,
            'user_id' => 10,
            'building_id' => null,
            'name' => fake()->text(150),
            'description' => fake()->text(300)
        ];
        $this->headers['Authorization'] = 'Bearer ' . $this->response->token;
        $response = $this->json($method, $endpoint, $data, $this->headers);
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [],
        ]);
    }

    /**
     * @test
     */
    public function shouldUpdateFailureBuildingIdNotExists()
    {
        $method = 'PUT';
        $endpoint = "/api/tasks/update";
        $data = [
            'task_id' => 1,
            'user_id' => 10,
            'building_id' => 9999999,
            'name' => fake()->text(150),
            'description' => fake()->text(300)
        ];
        $this->headers['Authorization'] = 'Bearer ' . $this->response->token;
        $response = $this->json($method, $endpoint, $data, $this->headers);
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [],
        ]);
    }

    /**
     * @test
     */
    public function shouldUpdateFailureWithoutName()
    {
        $method = 'PUT';
        $endpoint = "/api/tasks/update";
        $data = [
            'task_id' => 1,
            'user_id' => 10,
            'building_id' => 8,
            'name' => null,
            'description' => fake()->text(300)
        ];
        $this->headers['Authorization'] = 'Bearer ' . $this->response->token;
        $response = $this->json($method, $endpoint, $data, $this->headers);
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [],
        ]);
    }

    /**
     * @test
     */
    public function shouldUpdateFailureSmallName()
    {
        $method = 'PUT';
        $endpoint = "/api/tasks/update";
        $data = [
            'task_id' => 1,
            'user_id' => 10,
            'building_id' => 8,
            'name' => fake()->text(5),
            'description' => fake()->text(300)
        ];
        $this->headers['Authorization'] = 'Bearer ' . $this->response->token;
        $response = $this->json($method, $endpoint, $data, $this->headers);
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [],
        ]);
    }

    /**
     * @test
     */
    public function shouldUpdateFailureLongName()
    {
        $method = 'PUT';
        $endpoint = "/api/tasks/update";
        $data = [
            'task_id' => 1,
            'user_id' => 10,
            'building_id' => 8,
            'name' => fake()->words(150),
            'description' => fake()->text(990)
        ];
        $this->headers['Authorization'] = 'Bearer ' . $this->response->token;
        $response = $this->json($method, $endpoint, $data, $this->headers);
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [],
        ]);
    }

    /**
     * @test
     */
    public function shouldUpdateFailureDuplicatedName()
    {
        $task = Task::first();
        $method = 'PUT';
        $endpoint = "/api/tasks/update";
        $data = [
            'task_id' => 1,
            'user_id' => 10,
            'building_id' => 8,
            'name' => $task->name,
            'description' => fake()->text(300)
        ];
        $this->headers['Authorization'] = 'Bearer ' . $this->response->token;
        $response = $this->json($method, $endpoint, $data, $this->headers);
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [],
        ]);
    }

    /**
     * @test
     */
    public function shouldUpdateFailureWithoutDescription()
    {
        $method = 'PUT';
        $endpoint = "/api/tasks/update";
        $data = [
            'task_id' => 1,
            'user_id' => 10,
            'building_id' => 8,
            'name' => fake()->text(150),
            'description' => null
        ];
        $this->headers['Authorization'] = 'Bearer ' . $this->response->token;
        $response = $this->json($method, $endpoint, $data, $this->headers);
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [],
        ]);
    }

    /**
     * @test
     */
    public function shouldUpdateFailureLongDescription()
    {
        $method = 'PUT';
        $endpoint = "/api/tasks/update";
        $data = [
            'task_id' => 1,
            'user_id' => 10,
            'building_id' => 8,
            'name' => fake()->text(150),
            'description' => fake()->words(1000),
        ];
        $this->headers['Authorization'] = 'Bearer ' . $this->response->token;
        $response = $this->json($method, $endpoint, $data, $this->headers);
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [],
        ]);
    }

    /**
     * @test
     */
    public function shouldUpdateFailureStatusInvalid()
    {
        $method = 'PUT';
        $endpoint = "/api/tasks/update";
        $data = [
            'task_id' => 1,
            'user_id' => 10,
            'building_id' => 8,
            'name' => fake()->text(150),
            'description' => fake()->text(1000),
            'status' => 10
        ];
        $this->headers['Authorization'] = 'Bearer ' . $this->response->token;
        $response = $this->json($method, $endpoint, $data, $this->headers);
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [],
        ]);
    }

    /**
     * @test
     */
    public function shouldUpdateSuccess()
    {
        $taskId = Task::max('id');
        $method = 'PUT';
        $endpoint = "/api/tasks/update";
        $data = [
            'task_id' => $taskId,
            'user_id' => 14,
            'building_id' => 8,
            'name' => fake()->text(150),
            'description' => fake()->text(300),
            'status' => 2
        ];
        $this->headers['Authorization'] = 'Bearer ' . $this->response->token;
        $response = $this->json($method, $endpoint, $data, $this->headers);
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
    }
}
