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
        <?php endif; ?>

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
            </div>

            <div>
                <button type="submit"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition duration-150 ease-in-out">
                    Sign In
                </button>
            </div>
        </form>

        <div class="mt-6 text-center text-sm text-gray-500">
            <p>Don't have an account? Contact your administrator.</p>
        </div>
    </div>

</body>

</html>