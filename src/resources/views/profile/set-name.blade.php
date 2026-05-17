<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Nama - Focus Timer</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gradient-to-br from-pink-100 to-pink-200 min-h-screen flex items-center justify-center px-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8 text-center">
        <div class="text-5xl mb-4">👋</div>
        <h1 class="text-2xl font-bold text-black mb-2">Halo, selamat datang!</h1>
        <p class="text-gray-500 mb-6">Siapa nama panggilanmu?</p>
        <form method="POST" action="{{ route('profile.set-name') }}">
            @csrf
            <input type="text" name="display_name" placeholder="Masukkan nama panggilan" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-pink-500 text-center text-lg text-black" required autofocus>
            <button type="submit" class="w-full bg-pink-500 text-white py-3 rounded-xl font-semibold mt-4 hover:bg-pink-600 transition">Mulai Fokus!</button>
        </form>
    </div>
</body>
</html>