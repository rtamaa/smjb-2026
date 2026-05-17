<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('user_id', auth()->id())->get();
        return response()->json(['success' => true, 'data' => $tasks]);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:3',
            'focus_minutes' => 'integer|min:1|max:180',
        ]);
        
        $task = Task::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'material_link' => $request->material_link,
            'focus_minutes' => $request->focus_minutes ?? 25,
        ]);
        
        return response()->json(['success' => true, 'data' => $task], 201);
    }
    
    public function show($id)
    {
        $task = Task::where('user_id', auth()->id())->findOrFail($id);
        return response()->json(['success' => true, 'data' => $task]);
    }
    
    public function update(Request $request, $id)
    {
        $task = Task::where('user_id', auth()->id())->findOrFail($id);
        $task->update($request->all());
        return response()->json(['success' => true, 'data' => $task]);
    }
    
    public function destroy($id)
    {
        $task = Task::where('user_id', auth()->id())->findOrFail($id);
        $task->delete();
        return response()->json(['success' => true]);
    }
    
    public function complete($id)
    {
        $task = Task::where('user_id', auth()->id())->findOrFail($id);
        $task->complete();
        return response()->json(['success' => true, 'data' => $task]);
    }
}