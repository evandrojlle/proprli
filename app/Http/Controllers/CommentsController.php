<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Task;
use App\Traits\Log;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    use Log;

    /**
     * Get Comment by id
     *
     * @param int $id - comment id
     * @return JsonResponse
     */
    public function get(int $id)
    {
        try {
            $comment = Comment::filters(['id' => $id])->first();
            if (! $comment) {
                return response()->json([
                    'message' => __('Comment not found.'),
                    'data' => []
                ], 200);
            }

            return response()->json([
                'message' => __('Show item found.'),
                'data' => $comment,
            ], 200);
        } catch (\Exception $e) {
            Log::save('error', $e);

            return response()->json([
                'message' => 'error',
                'error' => __('Ops! An error occurred while performing this action.')
            ], 500);
        }
    }

    /**
     * Get Comments by task
     *
     * @param int $task_id - task id
     * @return JsonResponse
     */
    public function byTask(int $task_id)
    {
        try {
            $task = Task::filters(['id' => $task_id])->with('comments')->first();
            if (! $task) {
                return response()->json([
                    'success' => false,
                    'message' => __('Task not found.'),
                    'data' => []
                ], 200);
            }

            $task->status_name = Status::from($task->status)->name;

            return response()->json([
                'success' => true,
                'message' => __('Show item found.'),
                'data' => $task,
            ], 200);
        } catch (\Exception $e) {
            Log::save('error', $e);

            return response()->json([
                'success' => false,
                'message' => 'error',
                'error' => __('Ops! An error occurred while performing this action.')
            ], 500);
        }
    }

    /**
     * Create Comment
     *
     * @param CommentRequest $request - Request form data
     * @return JsonResponse
     */
    public function store(CommentRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $comment = new Comment();
            foreach ($validated as $key => $value) {
                $comment->$key = $value;
            }
            $comment->created_at = Carbon::now()->format('Y-m-d H:i:s');
            $comment->updated_at = null;
            $comment->save();
            if (! $comment->id) {
                return response()->json([
                    'success' => false,
                    'message' => __('An error occurred while saving the comment.'),
                    'data' => []
                ], 200);
            }

            return response()->json([
                'success' => true,
                'message' => __('Task created successfully.'),
                'data' => [
                    'id' => $comment->id,
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::save('error', $e);

            return response()->json([
                'success' => false,
                'message' => __('Ops! An error occurred while performing this action.'),
                'data' => [],
            ], 200);
        }
    }

    /**
     * Update Comment
     *
     * @param CommentRequest $request - Request form data
     * @return JsonResponse
     */
    public function update(CommentRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $id = $validated['comment_id'];
            $comment = Comment::getById($id);
            if (! $comment) {
                return response()->json([
                    'message' => __('Comment not found.'),
                    'data' => []
                ], 200);
            }

            foreach ($validated as $key => $value) {
                if ($key !== 'comment_id') {
                    $comment->$key = $value;
                }
            }

            $comment->updated_at = Carbon::now()->format('Y-m-d H:i:s');
            if (! $comment->save()) {
                return response()->json([
                    'success' => false,
                    'message' => __('An error occurred while saving the comment.'),
                    'data' => []
                ], 200);
            }

            return response()->json([
                'success' => true,
                'message' => __('Comment updated successfully.'),
                'data' => [
                    'id' => $id,
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::save('error', $e);

            return response()->json([
                'success' => false,
                'message' => __('Ops! An error occurred while performing this action.'),
                'data' => [],
            ], 200);
        }
    }
}
