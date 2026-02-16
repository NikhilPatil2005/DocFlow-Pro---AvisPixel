<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Admin Dashboard</h2>
    <p class="mb-4 text-gray-600">Review pending notices from Super Admin.</p>

    <!-- Notices List -->
    <div class="mt-8">
        <h3 class="text-xl font-bold mb-4">Pending Approval</h3>
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                <?php
                require_once __DIR__ . '/../../Models/Notice.php';
                $noticeModel = new Notice($conn);
                $notices = $noticeModel->getAllByStatus('pending_admin');

                if (empty($notices)): ?>
                    <li class="px-4 py-4 sm:px-6 text-gray-500">No notices pending approval.</li>
                <?php else:
                    foreach ($notices as $notice):
                        ?>
                        <li>
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-lg font-bold text-primary"><?php echo htmlspecialchars($notice['title']); ?>
                                    </h4>
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending Review
                                    </span>
                                </div>
                                <div class="mt-2 text-gray-600">
                                    <?php echo nl2br(htmlspecialchars($notice['content'])); ?>
                                </div>
                                <div class="mt-4 flex justify-end space-x-3">
                                    <form action="index.php?action=reject_notice&id=<?php echo $notice['id']; ?>" method="POST"
                                        class="inline-flex">
                                        <input type="text" name="reason" placeholder="Rejection reason..." required
                                            class="border border-gray-300 rounded-l-md px-3 py-1 text-sm focus:ring-red-500 focus:border-red-500">
                                        <button type="submit"
                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded-r-md text-sm">
                                            Reject
                                        </button>
                                    </form>
                                    <a href="index.php?action=approve_notice&id=<?php echo $notice['id']; ?>"
                                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-sm flex items-center">
                                        Approve
                                    </a>
                                </div>
                                <div class="mt-2 text-xs text-gray-400">
                                    Created by <?php echo htmlspecialchars($notice['creator_name']); ?> on
                                    <?php echo $notice['created_at']; ?>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; endif; ?>
            </ul>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>