<?php

namespace Tests\Feature;

use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSuccess()
    {
        $id = 2;
        $method = 'GET';
        $endpoint = "/api/comments/id/{$id}";
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
        $id = 999;
        $method = 'GET';
        $endpoint = "/api/comments/id/{$id}";
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
        $id = 'foo';
        $method = 'GET';
        $endpoint = "/api/comments/id/{$id}";
        $this->headers['Authorization'] = 'Bearer ' . $this->response->token;
        $data = [];
        $response = $this->json($method, $endpoint, $data, $this->headers);
        $response->assertStatus(500);
        $response->assertServerError();
    }

    /**
     * @test
     */
    public function shouldByTaskSuccess()
    {
        $taskId = 3;
        $method = 'GET';
        $endpoint = "/api/comments/task/{$taskId}";
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
    public function shouldByTaskNotFound()
    {
        $taskId = 99999;
        $method = 'GET';
        $endpoint = "/api/comments/task/{$taskId}";
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
    public function shouldByTaskError()
    {
        $taskId = 'fooo';
        $method = 'GET';
        $endpoint = "/api/comments/task/{$taskId}";
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
        $endpoint = "/api/comments/store";
        $data = [
            'user_id' => null,
            'task_id' => 10,
            'comment' => fake()->text(300)
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
        $endpoint = "/api/comments/store";
        $data = [
            'user_id' => 999,
            'task_id' => 10,
            'comment' => fake()->text(300)
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
        $endpoint = "/api/comments/store";
        $data = [
            'user_id' => 10,
            'task_id' => null,
            'comment' => fake()->text(300)
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
        $endpoint = "/api/comments/store";
        $data = [
            'user_id' => 10,
            'task_id' => 9999999,
            'comment' => fake()->text(300)
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
    public function shouldStoreFailureWithoutComment()
    {
        $method = 'POST';
        $endpoint = "/api/comments/store";
        $data = [
            'user_id' => 10,
            'task_id' => 10,
            'comment' => null
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
    public function shouldStoreFailureLongComment()
    {
        $method = 'POST';
        $endpoint = "/api/comments/store";
        $data = [
            'user_id' => 10,
            'task_id' => 10,
            'comment' => fake()->words(1000),
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
        $endpoint = "/api/comments/store";
        $data = [
            'user_id' => 10,
            'task_id' => 10,
            'comment' => fake()->text(300)
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
        $commentId = Comment::max('id');
        $method = 'PUT';
        $endpoint = "/api/comments/update";
        $data = [
            'comment_id' => $commentId,
            'user_id' => null,
            'task_id' => 10,
            'comment' => fake()->text(300)
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
        $commentId = Comment::max('id');
        $method = 'PUT';
        $endpoint = "/api/comments/update";
        $data = [
            'comment_id' => $commentId,
            'user_id' => 999,
            'task_id' => 10,
            'comment' => fake()->text(300)
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
    public function shouldUpdateFailureWithoutTaskId()
    {
        $commentId = Comment::max('id');
        $method = 'PUT';
        $endpoint = "/api/comments/update";
        $data = [
            'comment_id' => $commentId,
            'user_id' => 10,
            'task_id' => null,
            'comment' => fake()->text(300)
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
    public function shouldUpdateFailureTaskIdNotExists()
    {
        $commentId = Comment::max('id');
        $method = 'PUT';
        $endpoint = "/api/comments/update";
        $data = [
            'comment_id' => $commentId,
            'user_id' => 10,
            'task_id' => 9999999,
            'comment' => fake()->text(300)
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
    public function shouldUpdateFailureWithoutComment()
    {
        $commentId = Comment::max('id');
        $method = 'PUT';
        $endpoint = "/api/comments/update";
        $data = [
            'comment_id' => $commentId,
            'user_id' => 10,
            'task_id' => 10,
            'comment' => null
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
    public function shouldUpdateFailureLongComment()
    {
        $commentId = Comment::max('id');
        $method = 'PUT';
        $endpoint = "/api/comments/update";
        $data = [
            'comment_id' => $commentId,
            'user_id' => 10,
            'task_id' => 10,
            'comment' => fake()->words(1000),
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
        $commentId = Comment::max('id');
        $method = 'PUT';
        $endpoint = "/api/comments/update";
        $data = [
            'comment_id' => $commentId,
            'user_id' => 14,
            'task_id' => 10,
            'comment' => fake()->text(300),
        ];
        $this->headers['Authorization'] = 'Bearer ' . $this->response->token;
        $response = $this->json($method, $endpoint, $data, $this->headers);
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
    }
}
