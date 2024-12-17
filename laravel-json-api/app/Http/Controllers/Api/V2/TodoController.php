<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Todo;

class TodoController extends Controller
{
    // Retrieve all todos
    public function index()
    {
        $todos = Todo::orderBy('isCompleted')
        ->orderBy('created_at')
        ->get();

        return response()->json($todos, 200);
    }

    // Create a new todo
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'isCompleted' => 'boolean',
        ]);

        $todo = Todo::create($validated);
        return response()->json($todo, 201);
    }

    // Update a specific todo
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'isCompleted' => 'boolean',
        ]);

        $todo = Todo::findOrFail($id);
        $todo->update($validated);

        return response()->json($todo, 200);
    }

    // Delete a specific todo
    public function destroy($id)
    {
        $todo = Todo::findOrFail($id);
        $todo->delete();

        return response()->json(['message' => 'Todo deleted successfully'], 200);
    }
}
