<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    return response()->json($request->user()->tasks, 200);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request): JsonResponse {
        // A validált adatokat kérjük le
        $validated = $request->validated();

        // Létrehozzuk a feladatot a bejelentkezett felhasználóhoz kötve
        $task = $request->user()->tasks()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? '',
            'status' => $validated['status'] ?? 'pending',
        ]);

        return response()->json($task, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
    $this->authorizeUser($task);
    return response()->json($task);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
{
    $this->authorizeUser($task);

    $task->update($request->validated());
    return response()->json($task);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorizeUser($task);
        $task->delete();
    
        return response()->json(['message' => 'Task deleted successfully'], 200);
    }

    private function authorizeUser(Task $task)
{
    if ($task->user_id !== auth()->id()) {
        abort(403, 'Ehhez a feladathoz nincs jogosultságod.');
    }
}
}
