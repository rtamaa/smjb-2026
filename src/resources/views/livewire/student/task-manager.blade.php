<div>
    @if(session('message'))
        <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4">{{ session('message') }}</div>
    @endif
    
    @if(!$showCreateForm)
        <div class="mb-4">
            <button wire:click="$set('showCreateForm', true)" class="bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600">
                + Tambah Tugas Baru
            </button>
        </div>
    @endif
    
    @if($showCreateForm)
    <div class="bg-white rounded-xl shadow-md p-6 mb-6 border border-gray-200">
        <h3 class="font-semibold text-black mb-4">{{ $editingId ? '✏️ Edit Tugas' : '➕ Tambah Tugas Baru' }}</h3>
        <form wire:submit.prevent="{{ $editingId ? 'update' : 'save' }}">
            <input type="text" wire:model="title" placeholder="Nama tugas..." class="w-full p-2 border border-gray-300 rounded-lg mb-2 text-black" required>
            <textarea wire:model="description" placeholder="Deskripsi (opsional)" class="w-full p-2 border border-gray-300 rounded-lg mb-2 text-black" rows="2"></textarea>
            <input type="url" wire:model="material_link" placeholder="Link materi (YouTube, Google Drive, dll)" class="w-full p-2 border border-gray-300 rounded-lg mb-2 text-black">
            <div class="flex gap-2">
                <input type="number" wire:model="focus_minutes" class="w-32 p-2 border border-gray-300 rounded-lg text-black" step="5" min="1" max="180">
                <button type="submit" class="flex-1 bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600">
                    {{ $editingId ? 'Update Tugas' : 'Simpan' }}
                </button>
                <button type="button" wire:click="cancelForm" class="bg-gray-300 text-black px-4 py-2 rounded-lg hover:bg-gray-400">
                    Batal
                </button>
            </div>
        </form>
    </div>
    @endif
    
    <div class="flex gap-2 mb-4">
        <button wire:click="setFilter('all')" class="px-3 py-1 rounded text-sm {{ $filter === 'all' ? 'bg-pink-500 text-white' : 'bg-gray-200 text-black' }}">Semua</button>
        <button wire:click="setFilter('active')" class="px-3 py-1 rounded text-sm {{ $filter === 'active' ? 'bg-pink-500 text-white' : 'bg-gray-200 text-black' }}">Aktif</button>
        <button wire:click="setFilter('completed')" class="px-3 py-1 rounded text-sm {{ $filter === 'completed' ? 'bg-pink-500 text-white' : 'bg-gray-200 text-black' }}">Selesai</button>
    </div>
    
    <div class="space-y-3">
        @forelse($tasks as $task)
        <div class="bg-white rounded-xl shadow-md p-4 border border-gray-200 {{ $task->is_completed ? 'opacity-75' : '' }}">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" 
                               wire:click="complete({{ $task->id }})" 
                               wire:key="task-{{ $task->id }}"
                               {{ $task->is_completed ? 'checked' : '' }} 
                               class="w-5 h-5 rounded border-gray-300 text-green-500 focus:ring-green-500 cursor-pointer">
                        <h3 class="font-semibold text-black {{ $task->is_completed ? 'line-through text-gray-500' : '' }}">
                            {{ $task->title }}
                        </h3>
                    </div>
                    @if($task->description)<p class="text-gray-600 text-sm ml-7">{{ $task->description }}</p>@endif
                    @if($task->material_link)<a href="{{ $task->material_link }}" target="_blank" class="text-blue-500 text-sm ml-7 hover:underline">🔗 Buka Materi</a>@endif
                    <div class="flex gap-2 mt-2 ml-7">
                        <span class="text-xs bg-pink-100 text-pink-700 px-2 py-1 rounded">⏱️ {{ $task->focus_minutes }} menit</span>
                    </div>
                </div>
                <div class="flex gap-1">
                    @if(!$task->is_completed)
                        <button wire:click="startTimer({{ $task->id }}, '{{ addslashes($task->title) }}', {{ $task->focus_minutes }})" class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600">▶ Mulai</button>
                        <button wire:click="setReminder({{ $task->id }})" class="bg-yellow-500 text-white px-2 py-1 rounded text-sm">⏰</button>
                    @endif
                    <button wire:click="edit({{ $task->id }})" class="bg-gray-500 text-white px-2 py-1 rounded text-sm">✏️</button>
                    <button wire:click="delete({{ $task->id }})" onclick="return confirm('Hapus tugas ini?')" class="bg-red-500 text-white px-2 py-1 rounded text-sm">🗑️</button>
                </div>
            </div>
        </div>
        @empty
            <div class="bg-white rounded-xl shadow-md p-8 text-center text-gray-500 border border-gray-200">
                Belum ada tugas. Klik "+ Tambah Tugas Baru" untuk mulai!
            </div>
        @endforelse
    </div>
</div>