<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Office Login - Digital Administration Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            color: #374151;
        }

        /* Card Container */
        .card-container {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }

        .hidden-view {
            display: none;
        }

        /* Portal Card */
        .portal-btn {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #e5e7eb;
            border-radius: 12px;
        }

        .portal-btn:not(.opacity-60):hover {
            border-color: #10b981;
            box-shadow: 0 8px 24px rgba(16, 185, 129, 0.12);
            transform: translateY(-2px);
        }

        /* Modern Button */
        .modern-btn {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .modern-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        .modern-btn:active {
            transform: translateY(0);
        }

        /* Modern Input */
        .modern-input {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }

        .modern-input:focus {
            background: #ffffff;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
            outline: none;
        }

        /* Portal Icon Styling */
        .portal-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            border-radius: 12px;
            font-size: 20px;
            transition: all 0.3s ease;
        }

        .portal-btn:not(.opacity-60):hover .portal-icon {
            transform: scale(1.05);
        }

        .icon-orange {
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }

        .icon-indigo {
            background: rgba(79, 70, 229, 0.1);
            color: #4f46e5;
        }

        .icon-emerald {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .icon-purple {
            background: rgba(168, 85, 247, 0.1);
            color: #a855f7;
        }

        .icon-cyan {
            background: rgba(34, 211, 238, 0.1);
            color: #22d3ee;
        }

        .icon-pink {
            background: rgba(236, 72, 153, 0.1);
            color: #ec4899;
        }

        .icon-sky {
            background: rgba(14, 165, 233, 0.1);
            color: #0ea5e9;
        }

        .icon-rose {
            background: rgba(244, 63, 94, 0.1);
            color: #f43f5e;
        }

        .icon-slate {
            background: rgba(100, 116, 139, 0.1);
            color: #64748b;
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen p-4">

    <!-- Card Container -->
    <div class="card-container w-full max-w-md overflow-hidden relative">

        <?php if (isset($error)): ?>
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 m-6 mb-0 text-sm rounded-r-lg" role="alert">
                <p class="font-bold flex items-center"><i class="fas fa-exclamation-circle mr-2"></i>Error</p>
                <p><?php echo htmlspecialchars($error); ?></p>
            </div>
        <?php endif; ?>

        <!-- === PORTALS LIST VIEW === -->
        <div id="portals-view">
            <!-- Header -->
            <div class="p-6 border-b border-gray-100 flex items-center space-x-4 bg-gradient-to-r from-emerald-50 to-white">
                <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 p-3 rounded-xl text-white shadow-lg">
                    <i class="fas fa-door-open text-lg"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Login Portals</h1>
                    <p class="text-sm text-gray-600">Choose your role to continue</p>
                </div>
            </div>

            <!-- Portals List (Scrollable) -->
            <div class="p-4 sm:p-6 max-h-[65vh] overflow-y-auto custom-scrollbar space-y-3">

                <!-- Student Portal -->
                <button onclick="showLogin('student', 'Student Portal')" class="portal-btn w-full text-left flex items-center p-4 rounded-xl transition-all bg-white">
                    <div class="portal-icon icon-orange">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <h2 class="text-gray-900 font-semibold text-base">Student Portal</h2>
                        <p class="text-gray-600 text-sm">Access your academic records</p>
                    </div>
                    <div class="text-gray-400 text-sm"><i class="fas fa-chevron-right"></i></div>
                </button>

                <!-- Faculty Portal (Teacher) -->
                <button onclick="showLogin('teacher', 'Faculty Portal')" class="portal-btn w-full text-left flex items-center p-4 rounded-xl transition-all bg-white">
                    <div class="portal-icon icon-indigo">
                        <i class="fas fa-chalkboard-user"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <h2 class="text-gray-900 font-semibold text-base">Faculty Portal</h2>
                        <p class="text-gray-600 text-sm">Faculty management system</p>
                    </div>
                    <div class="text-gray-400 text-sm"><i class="fas fa-chevron-right"></i></div>
                </button>

                <!-- HOD Portal -->
                <button onclick="showLogin('hod', 'HOD Portal')" class="portal-btn w-full text-left flex items-center p-4 rounded-xl transition-all bg-white">
                    <div class="portal-icon icon-emerald">
                        <i class="fas fa-sitemap"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="flex items-center space-x-2">
                            <h2 class="text-gray-900 font-semibold text-base">HOD Portal</h2>
                            <span class="bg-emerald-100 text-emerald-700 text-xs font-medium px-2 py-0.5 rounded">Department</span>
                        </div>
                        <p class="text-gray-600 text-sm">Department approvals & management</p>
                    </div>
                    <div class="text-gray-400 text-sm"><i class="fas fa-chevron-right"></i></div>
                </button>

                <!-- Principal Portal -->
                <button onclick="showLogin('principal', 'Principal Portal')" class="portal-btn w-full text-left flex items-center p-4 rounded-xl transition-all bg-white">
                    <div class="portal-icon icon-purple">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="flex items-center space-x-2">
                            <h2 class="text-gray-900 font-semibold text-base">Principal Portal</h2>
                            <span class="bg-purple-100 text-purple-700 text-xs font-medium px-2 py-0.5 rounded">Executive</span>
                        </div>
                        <p class="text-gray-600 text-sm">Leadership & final approvals</p>
                    </div>
                    <div class="text-gray-400 text-sm"><i class="fas fa-chevron-right"></i></div>
                </button>

                <!-- Admin Portal -->
                <button onclick="showLogin('admin', 'Admin Portal')" class="portal-btn w-full text-left flex items-center p-4 rounded-xl transition-all bg-white">
                    <div class="portal-icon icon-cyan">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="flex items-center space-x-2">
                            <h2 class="text-gray-900 font-semibold text-base">Admin Portal</h2>
                            <span class="bg-cyan-100 text-cyan-700 text-xs font-medium px-2 py-0.5 rounded">System</span>
                        </div>
                        <p class="text-gray-600 text-sm">System administration</p>
                    </div>
                    <div class="text-gray-400 text-sm"><i class="fas fa-chevron-right"></i></div>
                </button>

                <!-- Coming Soon Portals -->

                <!-- Parent Portal -->
                <button onclick="event.preventDefault()" class="portal-btn w-full text-left flex items-center p-4 rounded-xl cursor-not-allowed bg-white opacity-60">
                    <div class="portal-icon icon-pink">
                        <i class="fas fa-heart"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <h2 class="text-gray-900 font-semibold text-base">Parent Portal</h2>
                        <p class="text-gray-600 text-sm">Track your ward's progress</p>
                    </div>
                    <div class="text-gray-400 text-sm"><i class="fas fa-lock"></i></div>
                </button>

                <!-- Alumni Portal -->
                <button onclick="event.preventDefault()" class="portal-btn w-full text-left flex items-center p-4 rounded-xl cursor-not-allowed bg-white opacity-60">
                    <div class="portal-icon icon-sky">
                        <i class="fas fa-network-wired"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="flex items-center space-x-2">
                            <h2 class="text-gray-900 font-semibold text-base">Alumni Portal</h2>
                            <span class="bg-gray-100 text-gray-700 text-xs font-medium px-2 py-0.5 rounded">Coming Soon</span>
                        </div>
                        <p class="text-gray-600 text-sm">Connect and share with alumni</p>
                    </div>
                    <div class="text-gray-400 text-sm"><i class="fas fa-lock"></i></div>
                </button>

                <!-- IRINS Portal -->
                <button onclick="event.preventDefault()" class="portal-btn w-full text-left flex items-center p-4 rounded-xl cursor-not-allowed bg-white opacity-60">
                    <div class="portal-icon icon-rose">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="flex items-center space-x-2">
                            <h2 class="text-gray-900 font-semibold text-base">IRINS</h2>
                            <span class="bg-gray-100 text-gray-700 text-xs font-medium px-2 py-0.5 rounded">External</span>
                        </div>
                        <p class="text-gray-600 text-sm">Research portal access</p>
                    </div>
                    <div class="text-gray-400 text-sm"><i class="fas fa-external-link-alt"></i></div>
                </button>

                <!-- Private Cloud Portal -->
                <button onclick="event.preventDefault()" class="portal-btn w-full text-left flex items-center p-4 rounded-xl cursor-not-allowed bg-white opacity-60">
                    <div class="portal-icon icon-slate">
                        <i class="fas fa-cloud"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="flex items-center space-x-2">
                            <h2 class="text-gray-900 font-semibold text-base">Private Cloud</h2>
                            <span class="bg-gray-100 text-gray-700 text-xs font-medium px-2 py-0.5 rounded">External</span>
                        </div>
                        <p class="text-gray-600 text-sm truncate">Access databases & resources</p>
                    </div>
                    <div class="text-gray-400 text-sm"><i class="fas fa-external-link-alt"></i></div>
                </button>

            </div>
        </div>

        <!-- === ACTUAL LOGIN FORM VIEW === -->
        <div id="login-view" class="hidden-view p-6 sm:p-8">
            <button onclick="showPortals()" class="mb-6 flex items-center text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                <i class="fas fa-arrow-left mr-2 text-emerald-600"></i>Back to Portals
            </button>

            <div class="mb-8">
                <h2 id="login-title" class="text-2xl font-bold text-gray-900">Sign In</h2>
                <p class="text-gray-600 mt-2 text-sm">Enter your credentials to continue</p>
            </div>

            <form action="index.php?action=login" method="POST" class="space-y-5">
                <!-- Selected Role (Hidden) -->
                <input type="hidden" name="role" id="role-input" value="student">

                <!-- Username Field -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <input type="text" name="username" id="username" required autocomplete="username" placeholder="Enter your username"
                        class="modern-input w-full text-sm">
                </div>

                <!-- Password Field -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <a href="index.php?action=check_status" class="text-xs font-medium text-emerald-600 hover:text-emerald-700">Check registration?</a>
                    </div>
                    <input type="password" name="password" id="password" required autocomplete="current-password" placeholder="Enter your password"
                        class="modern-input w-full text-sm">
                </div>

                <!-- Submit Button -->
                <button type="submit" class="modern-btn w-full flex items-center justify-center gap-2 mt-6 sm:mt-8">
                    <i class="fas fa-sign-in-alt"></i>Sign In
                </button>
            </form>

            <!-- Footer Link -->
            <div class="mt-6 text-center text-sm text-gray-600 border-t border-gray-200 pt-6">
                New here? <a href="index.php?action=register" class="font-medium text-emerald-600 hover:text-emerald-700">Create account</a>
            </div>
        </div>

    </div>

    <!-- JavaScript -->
    <script>
        function showLogin(role, title) {
            document.getElementById('portals-view').classList.add('hidden-view');
            document.getElementById('login-view').classList.remove('hidden-view');
            document.getElementById('role-input').value = role;
            document.getElementById('login-title').innerText = 'Sign in to ' + title;
            document.getElementById('username').focus();
        }

        function showPortals() {
            document.getElementById('login-view').classList.add('hidden-view');
            document.getElementById('portals-view').classList.remove('hidden-view');
        }

        // Auto-show login form if there was an error
        <?php if (isset($error) && isset($_POST['role'])): ?>
            let attemptedRole = <?php echo json_encode($_POST['role']); ?>;
            let titleMap = {
                'student': 'Student Portal',
                'teacher': 'Faculty Portal',
                'hod': 'HOD Portal',
                'principal': 'Principal Portal',
                'admin': 'Admin Portal',
                'parent': 'Parent Portal',
                'alumni': 'Alumni Portal',
                'irins': 'IRINS',
                'cloud': 'Private Cloud'
            };
            showLogin(attemptedRole, titleMap[attemptedRole] || 'Portal');
        <?php endif; ?>
    </script>
</body>

</html>