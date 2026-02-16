<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Student Dashboard</h2>
    <p class="mb-4 text-gray-600">View published notices.</p>

    <!-- Notices List -->
    <div class="mt-8">
        <h3 class="text-xl font-bold mb-4">Latest Notices</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php
            require_once __DIR__ . '/../../Models/Notice.php';
            $noticeModel = new Notice($conn);
            $notices = $noticeModel->getAllByStatus('teacher_published');

            // Get read receipts (hacky but quick)
            $studentId = currentUser();
            $readQuery = "SELECT notice_id FROM read_receipts WHERE student_id = $studentId";
            $readResult = $conn->query($readQuery);
            $readIds = [];
            while ($row = $readResult->fetch_assoc()) {
                $readIds[] = $row['notice_id'];
            }

            if (empty($notices)): ?>
                <div class="col-span-2 text-gray-500">No notices available.</div>
            <?php else:
                foreach ($notices as $notice):
                    $isRead = in_array($notice['id'], $readIds);
                    ?>
                    <div
                        class="bg-white overflow-hidden shadow rounded-lg hover:shadow-lg transition duration-300 border-l-4 <?php echo $isRead ? 'border-green-500' : 'border-blue-500'; ?>">
                        <div class="px-4 py-5 sm:p-6">
                            <a href="index.php?action=view_notice&id=<?php echo $notice['id']; ?>" class="block group">
                                <h4 class="text-lg leading-6 font-medium text-gray-900 group-hover:text-primary transition">
                                    <?php echo htmlspecialchars($notice['title']); ?>
                                    <?php if (!$isRead): ?>
                                        <span
                                            class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                            New
                                        </span>
                                    <?php endif; ?>
                                </h4>
                            </a>
                            <div class="mt-2 max-w-xl text-sm text-gray-500 line-clamp-3">
                                <p><?php echo nl2br(htmlspecialchars(substr($notice['content'], 0, 150))) . (strlen($notice['content']) > 150 ? '...' : ''); ?>
                                </p>
                            </div>
                            <div class="mt-3 flex items-center justify-between text-xs text-gray-400">
                                <span>Posted by <?php echo htmlspecialchars($notice['creator_name']); ?> on
                                    <?php echo date('M d, Y', strtotime($notice['created_at'])); ?></span>
                                <?php if ($isRead): ?>
                                    <span class="text-green-600 font-semibold uppercase tracking-wide">Read</span>
                                <?php else: ?>
                                    <a href="index.php?action=view_notice&id=<?php echo $notice['id']; ?>"
                                        class="text-primary hover:underline">Read More &rarr;</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>