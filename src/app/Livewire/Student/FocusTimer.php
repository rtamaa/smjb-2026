<?php

namespace App\Livewire\Student;

use Livewire\Component;
use App\Models\FocusSession;
use Illuminate\Support\Facades\Auth;

class FocusTimer extends Component
{
    public $isRunning = false;
    public $remainingSeconds = 0;
    public $totalSeconds = 0;
    public $currentTaskTitle = '';
    public $currentTaskId = null;
    public $todayTotalMinutes = 0;
    
    protected $listeners = ['start-timer' => 'start', 'task-updated' => 'refreshStats', 'stop-timer' => 'stop'];
    
    public function mount()
    {
        $this->checkActiveSession();
        $this->refreshStats();
    }
    
    public function checkActiveSession()
    {
        $active = FocusSession::where('user_id', Auth::id())
            ->whereNull('ended_at')
            ->where('is_completed', false)
            ->where('is_cancelled', false)
            ->with('task')
            ->first();
        
        if ($active) {
            $elapsed = $active->started_at->diffInSeconds(now());
            if ($elapsed < $active->duration_target) {
                $this->currentTaskId = $active->task_id;
                $this->currentTaskTitle = $active->task->title;
                $this->totalSeconds = $active->duration_target;
                $this->remainingSeconds = $this->totalSeconds - $elapsed;
                $this->isRunning = true;
                $this->dispatch('timer-running', [
                    'remaining' => $this->remainingSeconds,
                    'total' => $this->totalSeconds
                ]);
            } else {
                $active->complete();
            }
        }
    }
    
    public function start($params)
    {
        $taskId = $params['taskId'] ?? null;
        $taskTitle = $params['taskTitle'] ?? null;
        $minutes = $params['minutes'] ?? null;
        
        if (!$taskId || !$taskTitle || !$minutes) {
            return;
        }
        
        if ($this->isRunning) {
            $this->stop();
        }
        
        $this->currentTaskId = $taskId;
        $this->currentTaskTitle = $taskTitle;
        $this->totalSeconds = $minutes * 60;
        $this->remainingSeconds = $this->totalSeconds;
        $this->isRunning = true;
        
        FocusSession::create([
            'task_id' => $taskId,
            'user_id' => Auth::id(),
            'started_at' => now(),
            'duration_target' => $this->totalSeconds,
        ]);
        
        $this->dispatch('notify', [
            'title' => 'Fokus Dimulai! 🍅',
            'body' => "Kerjakan '{$taskTitle}' selama {$minutes} menit",
        ]);
        
        $this->dispatch('timer-start', [
            'remaining' => $this->remainingSeconds,
            'total' => $this->totalSeconds
        ]);
    }
    
    public function stop()
    {
        $session = FocusSession::where('user_id', Auth::id())
            ->whereNull('ended_at')
            ->latest()
            ->first();
        
        if ($session) {
            $session->cancel();
        }
        
        $this->resetTimer();
        $this->refreshStats();
        
        $this->dispatch('focus-stats-updated', [
            'todayMinutes' => $this->todayTotalMinutes
        ]);
        $this->dispatch('task-updated');
    }
    
    public function complete()
    {
        $session = FocusSession::where('user_id', Auth::id())
            ->whereNull('ended_at')
            ->latest()
            ->first();
        
        if ($session) {
            $session->complete();
        }
        
        $this->dispatch('notify', [
            'title' => 'Fokus Selesai! 🎉',
            'body' => "Bagus! '{$this->currentTaskTitle}' selesai. Ambil jeda sebentar.",
        ]);
        
        $this->resetTimer();
        $this->refreshStats();
        
        $this->dispatch('focus-stats-updated', [
            'todayMinutes' => $this->todayTotalMinutes
        ]);
        $this->dispatch('task-updated');
    }
    
    public function resetTimer()
    {
        $this->isRunning = false;
        $this->remainingSeconds = 0;
        $this->totalSeconds = 0;
        $this->currentTaskTitle = '';
        $this->currentTaskId = null;
        $this->dispatch('timer-reset');
    }
    
    public function refreshStats()
    {
        $this->todayTotalMinutes = Auth::user()->today_focus_minutes;
        \Log::info('FocusTimer refreshStats', ['todayTotalMinutes' => $this->todayTotalMinutes]);
    }
    
    public function render()
    {
        return view('livewire.student.focus-timer');
    }
}