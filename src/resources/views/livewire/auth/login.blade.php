<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100 px-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8">
        <div class="text-center mb-8">
            <div class="text-6xl mb-4">🍅</div>
            <h1 class="text-3xl font-bold text-gray-800">Focus Timer</h1>
            <p class="text-gray-500 mt-2">Kelola tugas dan fokus belajarmu</p>
        </div>
        
        @if($errorMessage)
            <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-600 rounded-lg text-sm">
                {{ $errorMessage }}
            </div>
        @endif
        
        <form wire:submit.prevent="login" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" wire:model="email" 
                       class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-500"
                       placeholder="contoh@email.com">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" wire:model="password" 
                       class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-500"
                       placeholder="••••••••">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2">
                    <input type="checkbox" wire:model="remember" class="w-4 h-4 text-indigo-600">
                    <span class="text-sm text-gray-600">Ingat saya</span>
                </label>
            </div>
            
            <button type="submit" 
                    class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 rounded-xl font-semibold hover:from-indigo-700 hover:to-purple-700 transition">
                Masuk
            </button>
        </form>
        
        <div class="mt-6 text-center text-sm text-gray-500">
            Belum punya akun? Hubungi admin untuk pendaftaran.
        </div>
    </div>
</div>