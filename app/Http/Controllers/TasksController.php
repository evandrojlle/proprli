<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Building;
use App\Models\Task;
use App\Traits\Log;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    use Log;

    public function get(int $building_id)
    {
        try {
            $building = Building::filters(['id' => $building_id])->with('tasks')->first();
            if (! $building) {
                return response()->json([
                    'message' => __('Building not found.'),
                    'data' => []
                ], 200);
            }

            foreach ($building->tasks as &$task) {
                $task->comments;
            }

            return response()->json([
                'message' => __('Show item found.'),
                'data' => $building,
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
     * Create User
     *
     * @param TaskRequest $request - Request form data
     * @return JsonResponse
     */
    public function store(TaskRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $tasks = new Task();
            foreach ($validated as $key => $value) {
                $tasks->$key = $value;
            }
            $tasks->created_at = Carbon::now()->format('Y-m-d H:i:s');
            $tasks->updated_at = null;
            $tasks->save();
            if (! $tasks->id) {
                return response()->json([
                    'message' => __('An error occurred while saving the task.'),
                    'data' => []
                ], 200);
            }

            return response()->json([
                'message' => __('Task created successfully.'),
                'data' => [
                    'id' => $tasks->id,
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::save('error', $e);

            return response()->json([
                'message' => __('Ops! An error occurred while performing this action.'),
                'data' => [],
            ], 200);
        }
    }
}
