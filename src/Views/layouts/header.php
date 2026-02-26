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

    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <?php
if (isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/sidebar.php';
}
?>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col <?php echo isset($_SESSION['user_id']) ? 'ml-64' : ''; ?> transition-all duration-300">
            <!-- Top Navbar -->
            <nav class="bg-white shadow-sm h-16 flex items-center justify-between px-6 z-10">
                <div class="flex items-center">
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <span class="font-bold text-xl text-primary">DOCFLOW-PRO</span>
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
                        <div class="flex items-center space-x-3">
                            <span class="text-sm text-gray-600">Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
                            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
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