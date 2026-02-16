<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="bg-white rounded-lg shadow-md p-6 max-w-4xl mx-auto mt-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Notifications</h2>
        <form action="index.php?action=mark_notifications_read" method="POST">
            <button type="submit" class="text-sm text-primary hover:text-indigo-800">Mark all as read</button>
        </form>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            <?php
            require_once __DIR__ . '/../../Models/Notification.php';
            $notifModel = new Notification($conn);
            $notifications = $notifModel->getUnread(currentUser());

            if (empty($notifications)): ?>
                <li class="px-4 py-4 sm:px-6 text-gray-500">No new notifications.</li>
            <?php else:
                foreach ($notifications as $notif):
                    ?>
                    <li class="bg-blue-50">
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    <?php echo htmlspecialchars($notif['message']); ?>
                                </p>
                                <div class="ml-2 flex-shrink-0 flex">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        New
                                    </span>
                                </div>
                            </div>
                            <div class="mt-2 sm:flex sm:justify-between">
                                <div class="sm:flex">
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                    <p>
                                        <?php echo date('M d, Y H:i', strtotime($notif['created_at'])); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach; endif; ?>
        </ul>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>