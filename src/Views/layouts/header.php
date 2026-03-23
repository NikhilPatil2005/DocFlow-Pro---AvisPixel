<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Faculty Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <?php
        if (isset($_SESSION['user_id'])) {
            require_once __DIR__ . '/sidebar.php';
        }
        ?>

        <!-- Main Content -->
        <div
            class="flex-1 flex flex-col <?php echo isset($_SESSION['user_id']) ? 'ml-64' : ''; ?> transition-all duration-300">
            <!-- Top Navbar -->
            <nav class="bg-white shadow-sm h-16 flex items-center justify-between px-6 z-10">
                <div class="flex items-center">
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <span class="font-bold text-xl text-primary">FACULTY SYSTEM</span>
                        <?php
                    else: ?>
                        <h2 class="text-xl font-semibold text-gray-800">
                            <?php
                            $action = $_GET['action'] ?? 'dashboard';
                            echo ucwords(str_replace('_', ' ', $action));
                            ?>
                        </h2>
                        <?php
                    endif; ?>
                </div>

                <div class="flex items-center space-x-4">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php
                        require_once __DIR__ . '/../../Models/Notification.php';
                        $notifModel = new Notification($conn);
                        $unreadCount = count($notifModel->getUnread($_SESSION['user_id']));
                        ?>
                        <div class="flex items-center space-x-6">
                            <!-- Notification Bell -->
                            <!-- Notification Bell -->
                            <div class="relative" id="notificationDropdownContainer">
                                <button id="notificationBtn" class="relative text-gray-500 hover:text-indigo-600 transition-colors mt-1 focus:outline-none" title="Notifications">
                                    <i class="fa-regular fa-bell text-xl"></i>
                                    <?php if ($unreadCount > 0): ?>
                                        <span class="absolute -top-1.5 -right-2 inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1 text-[10px] font-bold text-white bg-red-600 rounded-full border-2 border-white">
                                            <?php echo $unreadCount; ?>
                                        </span>
                                    <?php endif; ?>
                                </button>

                                <!-- Dropdown Menu -->
                                <div id="notificationMenu" class="hidden absolute right-0 mt-3 w-80 bg-white rounded-xl shadow-2xl border border-gray-100 z-[100] overflow-hidden transform transition-all">
                                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                                        <h3 class="text-sm font-bold text-gray-900">Notifications</h3>
                                        <?php if ($unreadCount > 0): ?>
                                            <a href="index.php?action=mark_notifications_read" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">Mark all read</a>
                                        <?php endif; ?>
                                    </div>
                                    <div class="max-h-72 overflow-y-auto">
                                        <?php
                                        // Fetch latest 5 notifications regardless of read status
                                        $allNotifs = $conn->query("SELECT * FROM notifications WHERE user_id = ".$_SESSION['user_id']." ORDER BY created_at DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);
                                        ?>
                                        <?php if (empty($allNotifs)): ?>
                                            <div class="px-4 py-8 text-center flex flex-col items-center justify-center">
                                                <i class="fa-regular fa-bell-slash text-gray-300 text-3xl mb-2"></i>
                                                <p class="text-sm text-gray-500">No recent notifications.</p>
                                            </div>
                                        <?php else: ?>
                                            <ul class="divide-y divide-gray-100">
                                                <?php foreach ($allNotifs as $n): ?>
                                                    <li class="px-4 py-3 <?php echo $n['is_read'] ? 'bg-white' : 'bg-blue-50/50'; ?> hover:bg-gray-50 transition-colors">
                                                        <p class="text-sm text-gray-800 <?php echo $n['is_read'] ? '' : 'font-bold'; ?>"><?php echo htmlspecialchars($n['message']); ?></p>
                                                        <div class="flex items-center text-[10px] text-gray-500 mt-1.5">
                                                            <i class="fa-regular fa-clock mr-1"></i>
                                                            <?php echo date('M d, Y h:i A', strtotime($n['created_at'])); ?>
                                                        </div>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </div>
                                    <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 text-center group">
                                        <a href="index.php?action=notifications" class="text-xs font-bold text-gray-600 group-hover:text-indigo-600 block w-full transition-colors">View All History</a>
                                    </div>
                                </div>
                            </div>
                            
                            <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const btn = document.getElementById('notificationBtn');
                                const menu = document.getElementById('notificationMenu');
                                if(btn && menu) {
                                    btn.addEventListener('click', function(e) {
                                        e.stopPropagation();
                                        menu.classList.toggle('hidden');
                                    });
                                    document.addEventListener('click', function(e) {
                                        if (!menu.contains(e.target) && !btn.contains(e.target)) {
                                            menu.classList.add('hidden');
                                        }
                                    });
                                }
                            });
                            </script>
                            
                            <div class="flex items-center space-x-3 border-l border-gray-200 pl-5">
                                <span class="text-sm text-gray-600 hidden md:inline">Welcome,
                                    <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
                            <div
                                class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                                <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                            </div>
                        </div>
                        <?php
                    else: ?>
                        <a href="index.php?action=login" class="text-gray-600 hover:text-primary font-medium">Login</a>
                        <?php
                    endif; ?>
                </div>
            </nav>

            <!-- Content Scroll Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">