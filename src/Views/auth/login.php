<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Notice Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4f46e5',
                        secondary: '#10b981',
                        accent: '#f59e0b',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50 flex items-center justify-center h-screen">

    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Welcome Back</h1>
            <p class="text-gray-500 mt-2">Sign in to your account</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p class="font-bold">Error</p>
                <p>
                    <?php echo $error; ?>
                </p>
            </div>
        <?php
endif; ?>

        <form action="index.php?action=login" method="POST" class="space-y-6">

            <!-- Role Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Role</label>
                <div class="flex flex-wrap gap-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="role" value="student" checked
                            class="form-radio text-primary focus:ring-primary h-4 w-4">
                        <span class="ml-2 text-gray-700">Student</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="role" value="teacher"
                            class="form-radio text-primary focus:ring-primary h-4 w-4">
                        <span class="ml-2 text-gray-700">Teacher</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="role" value="admin"
                            class="form-radio text-primary focus:ring-primary h-4 w-4">
                        <span class="ml-2 text-gray-700">Admin</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="role" value="super_admin"
                            class="form-radio text-primary focus:ring-primary h-4 w-4">
                        <span class="ml-2 text-gray-700">Super Admin</span>
                    </label>
                </div>
            </div>
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" name="username" id="username" required
                    class="mt-1 block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary sm:text-sm transition duration-150 ease-in-out"
                    placeholder="Enter your username">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" required
                    class="mt-1 block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary sm:text-sm transition duration-150 ease-in-out"
                    placeholder="Enter your password">
                <div class="text-sm">
                    <a href="index.php?action=check_status" class="font-medium text-indigo-600 hover:text-indigo-500">
                        Check Registration Status
                    </a>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <!-- Heroicon name: solid/lock-closed -->
                        <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    Sign in
                </button>
            </div>
        </form>

        <div class="mt-6 text-center text-sm text-gray-500">
            <p>Don't have an account? <a href="index.php?action=register" class="font-medium text-primary hover:text-indigo-500">Register here</a>.</p>
        </div>
    </div>

</body>

</html>