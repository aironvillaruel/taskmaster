<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    //
    public function dashboard(Request $request)
    {
        $userId = Auth::user()->id;
        $status = $request->input('status');

        $query = Todo::where('user_id', $userId)
            ->whereNull('deleted_at');

        if (!empty($status)) {
            $query->where('status', $status);
        }

        $todos = $query->latest()->get();

        return view('dashboard', compact('todos', 'status'));
    }


    public function createTodo(Request $request)
    {
        $userId = Auth::user()->id;
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:pending,in_progress,done',
            'due_date' => 'nullable|date'
        ]);

        $validated['user_id'] = $userId;

        $todo = Todo::create($validated);

        return response()->json([
            'message' => 'Todo created successfully!',
            'data' => $todo
        ]);
    }
    public function updateTodo(Request $request, $id)
    {
        // Find the todo item or fail if not found
        $todo = Todo::findOrFail($id);

        // Validate incoming data (optional but recommended)
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string',  // Adjust as needed (e.g. in:pending,done)
            'due_date' => 'nullable|date',
        ]);

        // Update the todo item with validated data
        $todo->title = $validatedData['title'];
        $todo->description = $validatedData['description'] ?? null;
        $todo->status = $validatedData['status'];
        $todo->due_date = $validatedData['due_date'] ?? null;

        $todo->save();

        return response()->json([
            'message' => 'Todo updated successfully!',
            'data' => $todo
        ]);
    }

    public function removeTodo(Request $request, $id)
    {
        // Find the todo item or fail if not found
        $todo = Todo::findOrFail($id);
        $todo->deleted_at = now();
        $todo->save();

        return response()->json([
            'message' => 'Todo removed successfully!',
            'data' => $todo
        ]);
    }

    public function filterTodo(Request $request)
    {
        $status = $request->input('status'); // e.g., pending, in_progress, done

        // Fetch all todos matching the given status
        $todos = Todo::where('status', $status)->get();
        var_dump($todos);
        // Return todos as JSON (or pass to a view)
        return response()->json($todos);

        // OR if you want to return a view:
        // return view('todos.index', compact('todos'));
    }
}
