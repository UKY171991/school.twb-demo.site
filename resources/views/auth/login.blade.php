<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Multi School Management System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-white mb-2">School Management System</h1>
            <p class="text-white/80">Multi-School Platform</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-lg shadow-2xl overflow-hidden">
            <div class="p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Log In</h2>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-medium mb-2">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                        <input id="password" type="password" name="password" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-purple-600 shadow-sm focus:ring-purple-500">
                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="text-sm text-purple-600 hover:text-purple-800">
                            Lost your password?
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold py-3 rounded-lg hover:from-purple-700 hover:to-indigo-700 transition duration-200">
                        Log In
                    </button>
                </form>
            </div>

            <!-- Demo Logins Section -->
            <div class="bg-gray-50 p-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 text-center">For Demo Login Please Click Below...</h3>
                
                <!-- Super Admin -->
                <div class="mb-4">
                    <button onclick="fillLogin('superadmin@school.com', 'password')" class="w-full bg-gray-800 text-white py-2 px-4 rounded hover:bg-gray-900 transition">
                        Super Admin
                    </button>
                </div>

                <!-- Windsor Park School -->
                <div class="mb-4">
                    <h4 class="font-semibold text-gray-700 mb-2">Windsor Park School:</h4>
                    <div class="grid grid-cols-2 gap-2">
                        <button onclick="fillLogin('admin.windsor@school.com', 'password')" class="bg-blue-600 text-white py-2 px-3 rounded text-sm hover:bg-blue-700">Admin</button>
                        <button onclick="fillLogin('guardian.windsor@school.com', 'password')" class="bg-blue-600 text-white py-2 px-3 rounded text-sm hover:bg-blue-700">Guardian</button>
                        <button onclick="fillLogin('student.windsor@school.com', 'password')" class="bg-blue-600 text-white py-2 px-3 rounded text-sm hover:bg-blue-700">Student</button>
                        <button onclick="fillLogin('teacher.windsor@school.com', 'password')" class="bg-blue-600 text-white py-2 px-3 rounded text-sm hover:bg-blue-700">Teacher</button>
                        <button onclick="fillLogin('accountant.windsor@school.com', 'password')" class="bg-blue-600 text-white py-2 px-3 rounded text-sm hover:bg-blue-700">Accountant</button>
                        <button onclick="fillLogin('librarian.windsor@school.com', 'password')" class="bg-blue-600 text-white py-2 px-3 rounded text-sm hover:bg-blue-700">Librarian</button>
                        <button onclick="fillLogin('receptionist.windsor@school.com', 'password')" class="bg-blue-600 text-white py-2 px-3 rounded text-sm hover:bg-blue-700">Receptionist</button>
                        <button onclick="fillLogin('staff.windsor@school.com', 'password')" class="bg-blue-600 text-white py-2 px-3 rounded text-sm hover:bg-blue-700">Staff</button>
                    </div>
                </div>

                <!-- Ideal Stevenson School -->
                <div>
                    <h4 class="font-semibold text-gray-700 mb-2">Ideal Stevenson School:</h4>
                    <div class="grid grid-cols-2 gap-2">
                        <button onclick="fillLogin('admin.stevenson@school.com', 'password')" class="bg-green-600 text-white py-2 px-3 rounded text-sm hover:bg-green-700">Admin</button>
                        <button onclick="fillLogin('guardian.stevenson@school.com', 'password')" class="bg-green-600 text-white py-2 px-3 rounded text-sm hover:bg-green-700">Guardian</button>
                        <button onclick="fillLogin('student.stevenson@school.com', 'password')" class="bg-green-600 text-white py-2 px-3 rounded text-sm hover:bg-green-700">Student</button>
                        <button onclick="fillLogin('teacher.stevenson@school.com', 'password')" class="bg-green-600 text-white py-2 px-3 rounded text-sm hover:bg-green-700">Teacher</button>
                        <button onclick="fillLogin('accountant.stevenson@school.com', 'password')" class="bg-green-600 text-white py-2 px-3 rounded text-sm hover:bg-green-700">Accountant</button>
                        <button onclick="fillLogin('librarian.stevenson@school.com', 'password')" class="bg-green-600 text-white py-2 px-3 rounded text-sm hover:bg-green-700">Librarian</button>
                        <button onclick="fillLogin('receptionist.stevenson@school.com', 'password')" class="bg-green-600 text-white py-2 px-3 rounded text-sm hover:bg-green-700">Receptionist</button>
                        <button onclick="fillLogin('staff.stevenson@school.com', 'password')" class="bg-green-600 text-white py-2 px-3 rounded text-sm hover:bg-green-700">Staff</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function fillLogin(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>
</body>
</html>
