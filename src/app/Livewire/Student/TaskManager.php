<?php

namespace App\Livewire\Student;

use Livewire\Component;
use App\Models\Task;
use App\Models\Reminder;
use Illuminate\Support\Facades\Auth;

class TaskManager extends Component
{
    public $tasks = [];
    public $title, $description, $material_link, $focus_minutes = 25;
    public $editingId = null;
    public $filter = 'all';
    public $showCreateForm = false;
    
    protected $rules = [
        'title' => 'required|min:3',
        'material_link' => 'nullable|url',
        'focus_minutes' => 'integer|min:1|max:180',
    ];
    
    public function mount()
    {
        $this->loadTasks();
    }
    
    public function loadTasks()
    {
        $query = Task::where('user_id', Auth::id());
        
        if ($this->filter === 'active') {
            $query->where('is_completed', false);
        } elseif ($this->filter === 'completed') {
            $query->where('is_completed', true);
        }
        
        $this->tasks = $query->orderBy('is_completed')->orderBy('sort_order')->get();
        
        $total = Task::where('user_id', Auth::id())->count();
        $completed = Task::where('user_id', Auth::id())->where('is_completed', true)->count();
        
        $this->dispatch('task-stats-updated', [
            'total' => $total,
            'completed' => $completed
        ]);
    }
    
    public function save()
    {
        $this->validate();
        
        Task::create([
            'user_id' => Auth::id(),
            'title' => $this->title,
            'description' => $this->description,
            'material_link' => $this->material_link,
            'focus_minutes' => $this->focus_minutes,
        ]);
        
        $this->reset(['title', 'description', 'material_link', 'focus_minutes']);
        $this->showCreateForm = false;
        $this->loadTasks();
        $this->dispatch('task-updated');
        session()->flash('message', '✅ Tugas ditambahkan!');
    }
    
    public function edit($id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);
        $this->editingId = $id;
        $this->title = $task->title;
        $this->description = $task->description;
        $this->material_link = $task->material_link;
        $this->focus_minutes = $task->focus_minutes;
        $this->showCreateForm = true;
    }
    
    public function update()
    {
        $this->validate();
        
        Task::where('user_id', Auth::id())->findOrFail($this->editingId)->update([
            'title' => $this->title,
            'description' => $this->description,
            'material_link' => $this->material_link,
            'focus_minutes' => $this->focus_minutes,
        ]);
        
        $this->editingId = null;
        $this->reset(['title', 'description', 'material_link', 'focus_minutes']);
        $this->showCreateForm = false;
        $this->loadTasks();
        session()->flash('message', '✏️ Tugas diupdate!');
    }
    
    public function complete($id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);
        $task->complete();
        $this->loadTasks();
        $this->dispatch('task-updated');
        
        $this->dispatch('notify', [
            'title' => 'Tugas Selesai! 🎉',
            'body' => "Selamat! Tugas '{$task->title}' sudah selesai.",
        ]);
    }
    
    public function delete($id)
    {
        Task::where('user_id', Auth::id())->findOrFail($id)->delete();
        $this->loadTasks();
        $this->dispatch('task-updated');
        session()->flash('message', '🗑️ Tugas dihapus!');
    }
    
    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->loadTasks();
        // Kirim update statistik ke dashboard
        $total = Task::where('user_id', Auth::id())->count();
        $completed = Task::where('user_id', Auth::id())->where('is_completed', true)->count();
        
        $this->dispatch('task-stats-updated', [
            'total' => $total,
            'completed' => $completed
        ]);
    }
    
    public function startTimer($taskId, $taskTitle, $minutes)
    {
        $this->dispatch('start-timer', [
            'taskId' => $taskId,
            'taskTitle' => $taskTitle,
            'minutes' => $minutes
        ]);
    }
    
    public function setReminder($taskId)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($taskId);
        
        Reminder::create([
            'user_id' => Auth::id(),
            'task_id' => $taskId,
            'title' => "Kerjakan: {$task->title}",
            'remind_at' => now()->addMinutes(30),
            'type' => 'task',
        ]);
        
        session()->flash('message', '⏰ Reminder 30 menit lagi!');
    }
    
    public function cancelForm()
    {
        $this->showCreateForm = false;
        $this->editingId = null;
        $this->reset(['title', 'description', 'material_link', 'focus_minutes']);
    }
    
    public function render()
    {
        return view('livewire.student.task-manager');
    }
}