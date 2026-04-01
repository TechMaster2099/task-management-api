<?php

namespace App\Http\Controllers;
use App\Models\Task;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
       'title' => 'required|string|unique:tasks,title',
       'due_date' => 'required|date|after_or_equal:today',
       'priority' => 'required|in:low,medium,high',
    ]);

        // Check duplicate title for same due_date
        $exists = Task::where('title', $validated['title'])
            ->where('due_date', $validated['due_date'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Task with this title already exists for the selected date.'
            ], 400);
        }

        // Create task
        $task = Task::create([
            'title' => $validated['title'],
            'due_date' => $validated['due_date'],
            'priority' => $validated['priority'],
            'status' => 'pending',
        ]);

        // Return response
        return response()->json($task, 201);
    }

    public function index(Request $request)
    {
        $query = Task::query();

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Sort by priority (high → medium → low)
        $query->orderByRaw("
            CASE 
                WHEN priority = 'high' THEN 1
                WHEN priority = 'medium' THEN 2
                WHEN priority = 'low' THEN 3
            END
        ");

        // Then sort by due_date
        $query->orderBy('due_date', 'asc');

        // Get results
        $tasks = $query->get();

        // Check if no tasks were found
    if ($tasks->isEmpty()) {
        return response()->json([
            'status' => 'success',
            'message' => 'No tasks found',
            'data' => []
        ]);
    }

        // Return JSON
        return response()->json([
            'status' => 'success',
            'data' => $tasks
        ]);
    }

    public function updateStatus(Request $request, $id)
{
    //  Validate input
    $request->validate([
        'status' => 'required|in:pending,in_progress,done'
    ]);

    //  Find task
    $task = Task::find($id);

    if (!$task) {
        return response()->json([
            'status' => 'error',
            'message' => 'Task not found'
        ], 404);
    }

    $currentStatus = $task->status;
    $newStatus = $request->status;

    //  Define allowed transitions
    $allowedTransitions = [
        'pending' => 'in_progress',
        'in_progress' => 'done'
    ];

    //  Check if already done
    if ($currentStatus === 'done') {
        return response()->json([
            'status' => 'error',
            'message' => 'Task is already completed and cannot be changed'
        ], 400);
    }

    //  Prevent invalid transitions
    if (!isset($allowedTransitions[$currentStatus]) || 
        $allowedTransitions[$currentStatus] !== $newStatus) {
        
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid status transition'
        ], 400);
    }

    //  Update status
    $task->status = $newStatus;
    $task->save();

    //  Return success response
    return response()->json([
        'status' => 'success',
        'data' => $task
    ]);
}


public function destroy($id)
{
    //  Find the task
    $task = Task::find($id);

    if (!$task) {
        return response()->json([
            'status' => 'error',
            'message' => 'Task not found'
        ], 404);
    }

    //  Check if task is done
    if ($task->status !== 'done') {
        return response()->json([
            'status' => 'error',
            'message' => 'Only completed tasks can be deleted'
        ], 403); // Forbidden
    }

    //  Delete task
    $task->delete();

    //  Return success response
    return response()->json([
        'status' => 'success',
        'message' => 'Task deleted successfully'
    ]);
}



public function dailyReport(Request $request)
{
    // Validate date input
    $request->validate([
        'date' => 'required|date'
    ]);

    // Get the date from the request
    $date = $request->date;

    // Get all tasks for that date
    $tasks = Task::whereDate('due_date', $date)->get();

    // Define priority and status options
    $priorities = ['high', 'medium', 'low'];
    $statuses = ['pending', 'in_progress', 'done'];

    // Aggregate tasks by priority and status
    $summary = [];
    foreach ($priorities as $priority) {
        foreach ($statuses as $status) {
            $summary[$priority][$status] = $tasks
                ->where('priority', $priority)
                ->where('status', $status)
                ->count();
        }
    }

    // Return JSON response
    return response()->json([
        'date' => $date,
        'summary' => $summary
    ]);
}
}