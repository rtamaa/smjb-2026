<div>
    <div class="bg-gradient-to-r from-pink-200 to-pink-100 shadow-lg rounded-lg mb-6">
        <div class="px-4 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-black">Halo, {{ $displayName }}! 👋</h1>
                    <p class="text-gray-600 mt-1">Semangat fokus hari ini!</p>
                </div>
                <div class="text-right bg-white/50 rounded-lg px-4 py-2">
                    <div class="text-4xl font-mono font-bold text-black" x-data="{ time: '' }" x-init="setInterval(() => { time = new Date().toLocaleTimeString('id-ID', { hour12: false }); }, 1000); time = new Date().toLocaleTimeString('id-ID', { hour12: false });" x-text="time"></div>
                    <div class="text-sm text-gray-600" x-data="{ date: '' }" x-init="date = new Date().toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });" x-text="date"></div>
                </div>
            </div>
            
            <div class="grid grid-cols-3 gap-4 mt-6">
                <div class="bg-white/50 rounded-lg p-3 text-center">
                    <div class="text-2xl font-bold text-black" id="today-focus-minutes">{{ $todayFocusMinutes }}</div>
                    <div class="text-xs text-gray-600">menit fokus hari ini</div>
                </div>
                <div class="bg-white/50 rounded-lg p-3 text-center">
                    <div class="text-2xl font-bold text-black" id="total-tasks">{{ $totalTasks }}</div>
                    <div class="text-xs text-gray-600">total tugas</div>
                </div>
                <div class="bg-white/50 rounded-lg p-3 text-center">
                    <div class="text-2xl font-bold text-black" id="completed-tasks">{{ $completedTasks }}</div>
                    <div class="text-xs text-gray-600">selesai</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <livewire:student.task-manager />
        </div>
        <div>
            <livewire:student.focus-timer />
        </div>
    </div>
</div>

@push('scripts')
<script>
        Livewire.on('task-stats-updated', (data) => {
        console.log('RAW data:', data);
        
        let total = 0;
        let completed = 0;
        
        if (Array.isArray(data) && data.length >= 2) {
            total = data[0];
            completed = data[1];
        } else if (data && typeof data === 'object') {
            total = data.total || data[0]?.total || 0;
            completed = data.completed || data[0]?.completed || 0;
        }
        
        const totalEl = document.getElementById('total-tasks');
        const completedEl = document.getElementById('completed-tasks');
        if (totalEl) totalEl.textContent = total;
        if (completedEl) completedEl.textContent = completed;
    });
</script>
@endpush