<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Notice Approvals</h2>
    <p class="mb-4 text-gray-600">Review and manage notices pending approval.</p>

    <?php if (empty($notices)): ?>
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        No notices pending approval found.
                    </p>
                </div>
            </div>
        </div>
    <?php
else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($notices as $notice): ?>
                        <tr>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($notice['title']); ?></div>
                                <div class="text-sm text-gray-500 truncate w-64"><?php echo htmlspecialchars(substr($notice['content'], 0, 50)) . '...'; ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?php echo htmlspecialchars($notice['creator_name']); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?php
        echo $notice['priority'] === 'High' ? 'bg-red-100 text-red-800' :
            ($notice['priority'] === 'Medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800');
?>">
                                    <?php echo $notice['priority']; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo date('M d, Y', strtotime($notice['created_at'])); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                     <!-- Actions differ by role/status -->
                                     <?php if ($_SESSION['role'] === 'teacher'): ?>
                                        <a href="index.php?action=publish_notice&id=<?php echo $notice['id']; ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">Publish</a>
                                     <?php
        else: ?>
                                        <a href="index.php?action=approve_notice&id=<?php echo $notice['id']; ?>" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs">Approve</a>
                                     <?php
        endif; ?>
                                    
                                    <form action="index.php?action=reject_notice&id=<?php echo $notice['id']; ?>" method="POST" class="inline flex items-center">
                                         <input type="text" name="reason" placeholder="Reason" class="border border-gray-300 rounded px-2 py-1 text-xs mr-2 w-24" required>
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">Reject</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php
    endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php
endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
