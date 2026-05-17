<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FocusSession;
use App\Models\Task;
use Illuminate\Http\Request;

class FocusSessionController extends Controller
{
    public function start(Request $request)
    {
        $request->validate(['task_id' => 'required|exists:tasks,id']);
        
        $task = Task::where('user_id', auth()->id())->findOrFail($request->task_id);
        
        FocusSession::where('user_id', auth()->id())->whereNull('ended_at')
            ->update(['ended_at' => now(), 'is_cancelled' => true]);
        
        $session = FocusSession::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'started_at' => now(),
            'duration_target' => $task->focus_minutes * 60,
        ]);
        
        return response()->json(['success' => true, 'data' => $session]);
    }
    
    public function stop($id)
    {
        $session = FocusSession::where('user_id', auth()->id())->findOrFail($id);
        $session->cancel();
        return response()->json(['success' => true, 'data' => $session]);
    }
    
    public function complete($id)
    {
        $session = FocusSession::where('user_id', auth()->id())->findOrFail($id);
        $session->complete();
        return response()->json(['success' => true, 'data' => $session]);
    }
    
    public function history()
    {
        $sessions = FocusSession::where('user_id', auth()->id())
            ->with('task')->orderBy('started_at', 'desc')->get();
        return response()->json(['success' => true, 'data' => $sessions]);
    }
    
    public function stats()
    {
        return response()->json(['success' => true, 'data' => [
            'today_minutes' => auth()->user()->today_focus_minutes,
            'total_minutes' => auth()->user()->total_focus_minutes,
            'total_tasks' => auth()->user()->tasks()->count(),
            'completed_tasks' => auth()->user()->tasks()->where('is_completed', true)->count(),
        ]]);
    }
}