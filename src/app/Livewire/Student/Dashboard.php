<?php

namespace App\Livewire\Student;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;

class Dashboard extends Component
{
    public $displayName;
    public $todayFocusMinutes = 0;
    public $totalTasks = 0;
    public $completedTasks = 0;
    
    protected $listeners = [
        'task-updated' => 'refreshStats',
        'focus-stats-updated' => 'updateFocusStats'  // TAMBAHKAN INI
    ];
    
    public function mount()
    {
        $this->displayName = Auth::user()->display_name ?? Auth::user()->name;
        $this->refreshStats();
    }
    
    public function updateFocusStats($data)
    {
        \Log::info('Dashboard received focus-stats-updated', $data);
        if (isset($data['todayMinutes'])) {
            $this->todayFocusMinutes = $data['todayMinutes'];
        }
    }
    
    public function refreshStats()
    {
        $this->todayFocusMinutes = Auth::user()->today_focus_minutes ?? 0;
        $this->totalTasks = Task::where('user_id', Auth::id())->count();
        $this->completedTasks = Task::where('user_id', Auth::id())->where('is_completed', true)->count();
        
        $this->dispatch('task-stats-updated', [
            'total' => $this->totalTasks,
            'completed' => $this->completedTasks
        ]);
        
        $this->dispatch('focus-stats-updated', [
            'todayMinutes' => $this->todayFocusMinutes
        ]);
    }
    
    public function render()
    {
        return view('livewire.student.dashboard', [
            'totalTasks' => $this->totalTasks,
            'completedTasks' => $this->completedTasks
        ])->layout('layouts.app');
    }
}