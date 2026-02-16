<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Notice Management</title>
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

<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <nav class="bg-white shadow-md fixed w-full z-10 top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <span class="font-bold text-xl text-primary">DOCFLOW-PRO</span>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <?php
if (isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/../../Models/Notification.php';
    $notifModel = new Notification($conn);
    $unreadCount = count($notifModel->getUnread($_SESSION['user_id']));
?>
                        <a href="index.php?action=notifications" class="text-gray-500 hover:text-gray-700 relative">
                            <!-- Bell Icon -->
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <?php if ($unreadCount > 0): ?>
                                <span
                                    class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full"><?php echo $unreadCount; ?></span>
                            <?php
    endif; ?>
                        </a>

                        <span class="text-gray-700">Welcome,
                            <?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?>
                            (<?php echo ucfirst(str_replace('_', ' ', $_SESSION['role'] ?? '')); ?>)</span>
                        <a href="index.php?action=logout"
                            class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">Logout</a>
                    <?php
}?>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex pt-16 h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-md hidden md:block h-full fixed overflow-y-auto">
            <div class="p-6">
                <ul class="space-y-4">
                    <li>
                        <a href="index.php?action=<?php echo $_SESSION['role'] ?? 'login'; ?>_dashboard"
                            class="flex items-center text-gray-700 hover:text-primary transition">
                            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'super_admin'): ?>
                        <li>
                            <a href="index.php?action=create_notice"
                                class="flex items-center text-gray-700 hover:text-primary transition">
                                <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Create Notice
                            </a>
                        </li>
                    <?php
endif; ?>
                    <li>
                        <a href="index.php?action=notifications"
                            class="flex items-center text-gray-700 hover:text-primary transition">
                            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            Notifications
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="w-full md:ml-64 p-8 overflow-y-auto">