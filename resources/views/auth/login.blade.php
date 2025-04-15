<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kasir App</title>
    <link rel="icon" href="{{ asset('logo.png') }}" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <script src="{{ asset('js/login.js') }}"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4"
    style="font-family: 'Montserrat', sans-serif;">
    <!-- Error Alert -->
    @if ($errors->any())
        <div id="errorAlert"
            class="fixed top-4 right-4 max-w-xs bg-white border-l-4 border-red-500 p-4 rounded shadow-lg">
            <div class="flex">
                <i class="ri-error-warning-line text-red-500 mt-0.5"></i>
                <div class="ml-3">
                    <p class="text-sm text-gray-800 font-medium">Login Failed</p>
                    <p class="text-xs text-gray-600">{{ $errors->first() }}</p>
                </div>
                <button id="closeError" class="ml-auto text-gray-400 hover:text-gray-600">
                    <i class="ri-close-line"></i>
                </button>
            </div>
        </div>
    @endif

    <div class="w-full max-w-sm bg-white rounded-lg shadow-md p-6">
        <!-- Header -->
        <div class="text-center mb-6">
            <img src="{{ asset('logo.png') }}" alt="Logo" class="h-10 mx-auto mb-2">
            <h1 class="text-xl font-bold text-gray-800">Sign In</h1>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="space-y-4">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="ri-mail-line"></i>
                        </span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="w-full pl-10 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter your email" required>
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="ri-lock-line"></i>
                        </span>
                        <input type="password" id="password" name="password"
                            class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="••••••••" required>
                        <button type="button" id="togglePassword"
                            class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-700">
                            <i class="ri-eye-line"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors">
                    Sign In
                </button>
            </div>
        </form>
    </div>
</body>

</html>
