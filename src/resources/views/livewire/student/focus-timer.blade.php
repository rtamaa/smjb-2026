<div class="bg-white rounded-xl shadow-md overflow-hidden sticky top-4 border border-gray-200">
    <div class="bg-pink-200 px-6 py-4">
        <h2 class="text-xl font-bold text-black">🍅 Focus Timer</h2>
        <p class="text-gray-600 text-sm">Pilih tugas untuk memulai</p>
    </div>
    
    <div class="p-6 text-center">
        @if($isRunning)
            <div class="mb-4">
                <div class="text-6xl font-mono font-bold text-indigo-600" id="timer-display">00:00</div>
                <div class="text-gray-700 mt-2">Fokus pada: <strong class="text-black">{{ $currentTaskTitle }}</strong></div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                <div class="bg-green-500 h-2 rounded-full transition-all duration-1000" id="timer-progress" style="width: 0%"></div>
            </div>
            <button wire:click="stop" class="w-full bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">⏹️ Stop</button>
        @else
            <div class="text-center py-8">
                <div class="text-6xl mb-4">🍅</div>
                <p class="text-gray-700">Belum ada sesi fokus aktif</p>
                <p class="text-sm text-gray-500 mt-2">Klik "Mulai" pada tugas untuk memulai timer</p>
            </div>
        @endif
        
        <div class="mt-6 pt-4 border-t border-gray-200">
            <div class="text-sm text-gray-600">Total fokus hari ini</div>
            <div class="text-2xl font-bold text-indigo-600" id="today-focus-minutes">{{ $todayTotalMinutes }} menit</div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    console.log('🔵 FocusTimer component loaded');
    let timerInterval = null;
    window.isTimerRunning = false;
    
    function formatTime(seconds) {
        if (isNaN(seconds)) seconds = 0;
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    }
    
    function updateDisplay(remaining, total) {
        console.log('📊 updateDisplay called - remaining:', remaining, 'total:', total);
        const display = document.getElementById('timer-display');
        const progress = document.getElementById('timer-progress');
        const todayFocusEl = document.getElementById('today-focus-minutes');
        
        if (display) {
            const newTime = formatTime(remaining);
            if (display.textContent !== newTime) {
                display.textContent = newTime;
                console.log('⏱️ Timer updated to:', newTime);
            }
        }
        if (progress && total > 0) {
            const percent = (remaining / total) * 100;
            progress.style.width = Math.max(0, Math.min(100, percent)) + '%';
        }
        
        // Update total fokus hari ini jika ada perubahan
        if (todayFocusEl && window.livewireTodayMinutes !== undefined) {
            todayFocusEl.textContent = window.livewireTodayMinutes + ' menit';
        }
    }
    
    // Event untuk memulai timer baru
    Livewire.on('timer-start', (data) => {
        console.log('🎯 timer-start event received:', data);
        
        if (timerInterval) clearInterval(timerInterval);
        
        let remaining = 0, total = 0;
        if (Array.isArray(data) && data.length > 0) {
            remaining = data[0]?.remaining || 0;
            total = data[0]?.total || 0;
        } else if (typeof data === 'object') {
            remaining = data.remaining || 0;
            total = data.total || 0;
        }
        
        console.log(`⏱️ Timer values: remaining=${remaining}s (${Math.floor(remaining/60)}m ${remaining%60}s), total=${total}s`);
        
        if (total <= 0) {
            console.error('❌ Invalid total seconds:', total);
            return;
        }
        
        window.isTimerRunning = true;
        let currentRemaining = remaining;
        
        updateDisplay(currentRemaining, total);
        
        if (timerInterval) clearInterval(timerInterval);
        timerInterval = setInterval(() => {
            if (currentRemaining <= 0) {
                clearInterval(timerInterval);
                window.isTimerRunning = false;
                Livewire.dispatch('complete');
                console.log('✅ Timer completed!');
                return;
            }
            currentRemaining--;
            updateDisplay(currentRemaining, total);
        }, 1000);
    });
    
    // Event untuk restore timer yang sedang berjalan
    Livewire.on('timer-running', (data) => {
        console.log('🔄 timer-running event received:', data);
        
        if (timerInterval) clearInterval(timerInterval);
        
        let remaining = 0, total = 0;
        if (Array.isArray(data) && data.length > 0) {
            remaining = data[0]?.remaining || 0;
            total = data[0]?.total || 0;
        } else if (typeof data === 'object') {
            remaining = data.remaining || 0;
            total = data.total || 0;
        }
        
        console.log(`⏱️ Restoring timer: remaining=${remaining}s, total=${total}s`);
        
        if (total <= 0) return;
        
        window.isTimerRunning = true;
        let currentRemaining = remaining;
        
        updateDisplay(currentRemaining, total);
        
        if (timerInterval) clearInterval(timerInterval);
        timerInterval = setInterval(() => {
            if (currentRemaining <= 0) {
                clearInterval(timerInterval);
                window.isTimerRunning = false;
                Livewire.dispatch('complete');
                return;
            }
            currentRemaining--;
            updateDisplay(currentRemaining, total);
        }, 1000);
    });
    
    // Event untuk reset timer
    Livewire.on('timer-reset', () => {
        console.log('🛑 timer-reset event received');
        if (timerInterval) clearInterval(timerInterval);
        window.isTimerRunning = false;
        updateDisplay(0, 100);
    });
    
        // Event untuk update total fokus dari Livewire
    Livewire.on('focus-stats-updated', (data) => {
        console.log('📊 focus-stats-updated received:', data);
        const todayFocusEl = document.getElementById('today-focus-minutes');
        if (todayFocusEl && data && data.todayMinutes !== undefined) {
            todayFocusEl.textContent = data.todayMinutes + ' menit';
            console.log('✅ Total fokus updated to:', data.todayMinutes, 'menit');
        }
    });

    // Event untuk update stats dari Dashboard (jika diperlukan)
    Livewire.on('task-stats-updated', (data) => {
        console.log('📊 task-stats-updated received (from timer):', data);
    });
</script>
@endpush