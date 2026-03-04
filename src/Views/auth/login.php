<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Faculty Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Custom scrollbar for the portals list */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .hidden-view {
            display: none;
        }
    </style>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">

    <!-- Card Container -->
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden relative">

        <?php if (isset($error)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 m-6 mb-0 text-sm rounded-r-lg" role="alert">
                <p class="font-bold">Error</p>
                <p><?php echo htmlspecialchars($error); ?></p>
            </div>
        <?php endif; ?>

        <!-- === PORTALS LIST VIEW === -->
        <div id="portals-view">
            <!-- Header -->
            <div class="p-6 border-b border-gray-100 flex items-center space-x-4">
                <div class="bg-blue-100 p-3 rounded-xl text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Login Portals</h1>
            </div>

            <!-- Portals List (Scrollable) -->
            <div class="p-4 sm:p-6 max-h-[60vh] overflow-y-auto custom-scrollbar space-y-3">

                <!-- Student Portal -->
                <button onclick="showLogin('student', 'Student Portal')"
                    class="w-full text-left flex items-center p-4 border border-gray-100 rounded-xl hover:shadow-md hover:border-gray-200 transition-all group bg-white">
                    <div class="bg-gray-50 p-3 rounded-lg text-gray-500 group-hover:bg-gray-100 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        </svg>
                    </div>
                    <div class="ml-4 flex-1">
                        <h2 class="text-gray-900 font-semibold text-lg">Student Portal</h2>
                        <p class="text-gray-500 text-sm">Access your academic records</p>
                    </div>
                    <div class="text-gray-300 group-hover:text-gray-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </button>

                <!-- Faculty Portal (Teacher) -->
                <button onclick="showLogin('teacher', 'Faculty Portal')"
                    class="w-full text-left flex items-center p-4 border border-gray-100 rounded-xl hover:shadow-md hover:border-gray-200 transition-all group bg-white">
                    <div class="bg-gray-50 p-3 rounded-lg text-gray-500 group-hover:bg-gray-100 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4 flex-1">
                        <h2 class="text-gray-900 font-semibold text-lg">Faculty Portal</h2>
                        <p class="text-gray-500 text-sm">Teacher and faculty access</p>
                    </div>
                    <div class="text-gray-300 group-hover:text-gray-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </button>

                <!-- HOD Portal -->
                <button onclick="showLogin('hod', 'HOD Portal')"
                    class="w-full text-left flex items-center p-4 border border-gray-100 rounded-xl hover:shadow-md hover:border-gray-200 transition-all group bg-white">
                    <div
                        class="bg-orange-50 p-3 rounded-lg text-orange-600 group-hover:bg-orange-100 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="flex items-center space-x-2">
                            <h2 class="text-gray-900 font-semibold text-lg">HOD Portal</h2>
                            <span
                                class="bg-indigo-100 text-indigo-700 text-xs font-medium px-2 py-0.5 rounded">Management</span>
                        </div>
                        <p class="text-gray-500 text-sm">Department approvals & management</p>
                    </div>
                    <div class="text-gray-300 group-hover:text-gray-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </button>

                <!-- Principal Portal -->
                <button onclick="showLogin('principal', 'Principal Portal')"
                    class="w-full text-left flex items-center p-4 border border-gray-100 rounded-xl hover:shadow-md hover:border-gray-200 transition-all group bg-white">
                    <div
                        class="bg-purple-50 p-3 rounded-lg text-purple-600 group-hover:bg-purple-100 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        </svg>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="flex items-center space-x-2">
                            <h2 class="text-gray-900 font-semibold text-lg">Principal Portal</h2>
                            <span
                                class="bg-green-100 text-green-700 text-xs font-medium px-2 py-0.5 rounded">Executive</span>
                        </div>
                        <p class="text-gray-500 text-sm">Institution overview & final approvals</p>
                    </div>
                    <div class="text-gray-300 group-hover:text-gray-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </button>

                <!-- Admin Portal -->
                <button onclick="showLogin('admin', 'Admin Portal')"
                    class="w-full text-left flex items-center p-4 border border-gray-100 rounded-xl hover:shadow-md hover:border-gray-200 transition-all group bg-white">
                    <div class="bg-blue-50 p-3 rounded-lg text-blue-500 group-hover:bg-blue-100 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="flex items-center space-x-2">
                            <h2 class="text-gray-900 font-semibold text-lg">Admin / System Portal</h2>
                            <span class="bg-red-100 text-red-700 text-xs font-medium px-2 py-0.5 rounded">System</span>
                        </div>
                        <p class="text-gray-500 text-sm">System administration and controls</p>
                    </div>
                    <div class="text-gray-300 group-hover:text-gray-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </button>

                <!-- Parent Portal (Mock) -->
                <button onclick="showLogin('parent', 'Parent Portal (Mock)')"
                    class="w-full text-left flex items-center p-4 border border-gray-100 rounded-xl hover:shadow-md hover:border-gray-200 transition-all group bg-white">
                    <div class="bg-gray-50 p-3 rounded-lg text-gray-500 group-hover:bg-gray-100 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <div class="ml-4 flex-1">
                        <h2 class="text-gray-900 font-semibold text-lg">Parent Portal</h2>
                        <p class="text-gray-500 text-sm">Track your ward's progress</p>
                    </div>
                    <div class="text-gray-300 group-hover:text-gray-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </button>

                <!-- Alumni Portal -->
                <button onclick="showLogin('alumni', 'Alumni Portal (Mock)')"
                    class="w-full text-left flex items-center p-4 border border-gray-100 rounded-xl hover:shadow-md hover:border-gray-200 transition-all group bg-white">
                    <div class="bg-gray-50 p-3 rounded-lg text-gray-500 group-hover:bg-gray-100 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        </svg>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="flex items-center space-x-2">
                            <h2 class="text-gray-900 font-semibold text-lg">Alumni Portal</h2>
                            <span
                                class="bg-green-100 text-green-700 text-xs font-medium px-2 py-0.5 rounded">External</span>
                        </div>
                        <p class="text-gray-500 text-sm">Connect, Share, Inspire</p>
                    </div>
                    <div class="text-gray-300 group-hover:text-gray-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </button>

                <!-- IRINS -->
                <button onclick="showLogin('irins', 'IRINS (Mock)')"
                    class="w-full text-left flex items-center p-4 border border-gray-100 rounded-xl hover:shadow-md hover:border-gray-200 transition-all group bg-white">
                    <div class="bg-gray-50 p-3 rounded-lg text-gray-500 group-hover:bg-gray-100 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="flex items-center space-x-2">
                            <h2 class="text-gray-900 font-semibold text-lg">IRINS</h2>
                            <span
                                class="bg-green-100 text-green-700 text-xs font-medium px-2 py-0.5 rounded">External</span>
                        </div>
                        <p class="text-gray-500 text-sm">Research Portal Access</p>
                    </div>
                    <div class="text-gray-300 group-hover:text-gray-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </button>

                <!-- Private Cloud -->
                <button onclick="showLogin('cloud', 'Private Cloud (Mock)')"
                    class="w-full text-left flex items-center p-4 border border-gray-100 rounded-xl hover:shadow-md hover:border-gray-200 transition-all group bg-white">
                    <div class="bg-gray-50 p-3 rounded-lg text-gray-500 group-hover:bg-gray-100 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                        </svg>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="flex items-center space-x-2">
                            <h2 class="text-gray-900 font-semibold text-lg">Private Cloud</h2>
                            <span
                                class="bg-green-100 text-green-700 text-xs font-medium px-2 py-0.5 rounded">External</span>
                        </div>
                        <p class="text-gray-500 text-sm truncate w-48"
                            title="Access to all Databases, E-books, E-journals">Access to all Databases, E-books...</p>
                    </div>
                    <div class="text-gray-300 group-hover:text-gray-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </button>

            </div>
        </div>

        <!-- === ACTUAL LOGIN FORM VIEW === -->
        <div id="login-view" class="hidden-view p-6 sm:p-8">
            <button onclick="showPortals()"
                class="mb-6 flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Portals
            </button>

            <div class="mb-8 text-center sm:text-left">
                <h2 id="login-title" class="text-2xl font-bold text-gray-900">Sign In</h2>
                <p class="text-gray-500 mt-2">Enter your credentials to continue</p>
            </div>

            <form action="index.php?action=login" method="POST" class="space-y-6">
                <!-- Selected Role will be stored here -->
                <input type="hidden" name="role" id="role-input" value="student">

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" name="username" id="username" required
                        class="mt-1 block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-150 ease-in-out">
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <a href="index.php?action=check_status"
                            class="text-sm font-medium text-blue-600 hover:text-blue-500">Registration Status?</a>
                    </div>
                    <input type="password" name="password" id="password" required
                        class="mt-1 block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-150 ease-in-out">
                </div>

                <div>
                    <button type="submit"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-blue-500 group-hover:text-blue-400"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                        Sign in
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center text-sm text-gray-500">
                <p>Don't have an account? <a href="index.php?action=register"
                        class="font-medium text-blue-600 hover:text-blue-500">Register here</a>.</p>
            </div>
        </div>

    </div>

    <!-- JS to handle flipping between Views -->
    <script>
        function showLogin(role, title) {
            document.getElementById('portals-view').classList.add('hidden-view');
            document.getElementById('login-view').classList.remove('hidden-view');
            document.getElementById('role-input').value = role;
            document.getElementById('login-title').innerText = 'Log in to ' + title;
        }

        function showPortals() {
            document.getElementById('login-view').classList.add('hidden-view');
            document.getElementById('portals-view').classList.remove('hidden-view');
        }

        // If there was a login error, automatically switch to the form view
        <?php if (isset($error) && isset($_POST['role'])): ?>
            let attemptedRole = <?php echo json_encode($_POST['role']); ?>;
            let titleMap = {
                'student': 'Student Portal',
                'teacher': 'Faculty Portal',
                'hod': 'HOD Portal',
                'principal': 'Principal Portal',
                'admin': 'Admin / System Portal'
            };
            showLogin(attemptedRole, titleMap[attemptedRole] || 'Portal');
        <?php endif; ?>
    </script>
</body>

</html>