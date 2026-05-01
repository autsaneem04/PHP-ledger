<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Laravel App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

<div class="min-h-screen flex items-center justify-center">

    <div class="bg-white shadow-xl rounded-2xl p-10 text-center max-w-md w-full">

        <h1 class="text-4xl font-bold text-gray-800 mb-4">
            🚀 Hello Laravel
        </h1>

        <p class="text-gray-600 mb-8">
            Welcome to my Laravel project
        </p>

        <div class="flex justify-center gap-4">

            <a href="/login"
               class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                Login
            </a>

            <a href="/register"
               class="px-6 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                Register
            </a>

        </div>

    </div>

</div>

</body>
</html>
