<?php

namespace App\Http\Controllers;

use App\Enums\Status;
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
                    'success' => false,
                    'message' => __('Building not found.'),
                    'data' => []
                ], 403);
            }

            foreach ($building->tasks as &$task) {
                $task->status_name = Status::from($task->status)->name;
                $task->comments;
            }

            return response()->json([
                'success' => true,
                'message' => __('Show item found.'),
                'data' => $building,
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

    public function filters()
    {
        try {
            $args = func_get_arg(0);
            $arrArgs = explode('&&', $args);
            $filters = [];
            foreach ($arrArgs as $arg) {
                $explode = explode('=', $arg);
                $filters[reset($explode)] = end($explode);
            }
            
            $tasks  = Task::filters($filters, ['name', 'description'])->with('build', 'comments')->get();
            if ($tasks->count() === 0) {
                return response()->json([
                    'success' => false,
                    'message' => __('Tasks not found.'),
                    'data' => []
                ], 403);
            }

            return response()->json([
                'success' => true,
                'message' => __('Show items found.'),
                'data' => $tasks,
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
     * Create Task
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
                    'success' => false,
                    'message' => __('An error occurred while saving the task.'),
                    'data' => []
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => __('Task created successfully.'),
                'data' => [
                    'id' => $tasks->id,
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::save('error', $e);

            return response()->json([
                'success' => false,
                'message' => __('Ops! An error occurred while performing this action.'),
                'data' => [],
            ], 500);
        }
    }

    /**
     * Update Task
     *
     * @param TaskRequest $request - Request form data
     * @return JsonResponse
     */
    public function update(TaskRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $id = $validated['task_id'];
            $task = Task::getById($id);
            if (! $task) {
                return response()->json([
                    'message' => __('Task not found.'),
                    'data' => []
                ], 403);
            }

            foreach ($validated as $key => $value) {
                if ($key !== 'task_id') {
                    $task->$key = $value;
                }
            }

            $task->updated_at = Carbon::now()->format('Y-m-d H:i:s');
            if (! $task->save()) {
                return response()->json([
                    'success' => false,
                    'message' => __('An error occurred while saving the task.'),
                    'data' => []
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => __('Task updated successfully.'),
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
            ], 500);
        }
    }
}
