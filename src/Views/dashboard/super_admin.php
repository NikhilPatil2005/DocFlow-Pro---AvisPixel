<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Super Admin Dashboard</h2>
    <p class="mb-4 text-gray-600">Manage notices and system overview.</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500">
            <h3 class="font-bold text-blue-700">Pending Notices</h3>
            <p class="text-2xl font-bold text-gray-800">0</p>
        </div>
        <div class="bg-green-50 p-4 rounded-lg border-l-4 border-green-500">
            <h3 class="font-bold text-green-700">Published Notices</h3>
            <p class="text-2xl font-bold text-gray-800">0</p>
        </div>
        <div class="bg-red-50 p-4 rounded-lg border-l-4 border-red-500">
            <h3 class="font-bold text-red-700">Rejected Notices</h3>
            <p class="text-2xl font-bold text-gray-800">0</p>
        </div>
    </div>

    <!-- Notices List -->
    <div class="mt-8">
        <h3 class="text-xl font-bold mb-4">All Notices</h3>
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                <?php
                require_once __DIR__ . '/../../Models/Notice.php';
                $noticeModel = new Notice($conn);
                $notices = $noticeModel->getAllForSuperAdmin();

                foreach ($notices as $notice):
                    $statusColor = 'gray';
                    if ($notice['status'] == 'published' || $notice['status'] == 'teacher_published')
                        $statusColor = 'green';
                    if (strpos($notice['status'], 'rejected') !== false)
                        $statusColor = 'red';
                    if (strpos($notice['status'], 'pending') !== false)
                        $statusColor = 'yellow';
                    ?>
                    <li>
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-primary truncate">
                                    <?php echo htmlspecialchars($notice['title']); ?></p>
                                <div class="ml-2 flex-shrink-0 flex">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-<?php echo $statusColor; ?>-100 text-<?php echo $statusColor; ?>-800">
                                        <?php echo htmlspecialchars($notice['status']); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="mt-2 sm:flex sm:justify-between">
                                <div class="sm:flex">
                                    <p class="flex items-center text-sm text-gray-500">
                                        Created by <?php echo htmlspecialchars($notice['creator_name']); ?>
                                    </p>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                    <p>
                                        <?php echo $notice['updated_at']; ?>
                                    </p>
                                </div>
                            </div>
                            <?php if ($notice['status'] === 'admin_rejected'): ?>
                                <div class="mt-2 bg-red-50 p-2 rounded text-sm text-red-700">
                                    <strong>Rejection Reason:</strong>
                                    <?php echo htmlspecialchars($notice['rejection_reason']); ?>
                                    <br>
                                    <a href="index.php?action=edit_notice&id=<?php echo $notice['id']; ?>"
                                        class="text-red-900 underline mt-1 inline-block">Edit & Resubmit</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>