<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TASK MASTER</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-indigo-50 min-h-screen flex items-center justify-center">

    <div class="bg-white shadow-xl rounded-xl p-10 max-w-md w-full text-center">
        <h1 class="text-4xl font-bold text-indigo-700 mb-4">TASK MASTER</h1>
        <p class="text-gray-600 mb-6">Master your tasks, one click at a time.</p>

        @if (Route::has('login'))
            <nav class="flex items-center justify-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}"
                       class="inline-block px-5 py-2 border border-indigo-600 text-indigo-700 hover:bg-indigo-50 rounded-md text-sm font-medium transition">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="inline-block px-5 py-2 text-white bg-indigo-600 hover:bg-indigo-700 rounded-md text-sm font-medium transition">
                        Log in
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="inline-block px-5 py-2 border border-indigo-600 text-indigo-600 hover:bg-indigo-50 rounded-md text-sm font-medium transition">
                            Register
                        </a>
                    @endif
                @endauth
            </nav>
        @endif
    </div>

</body>
</html>
