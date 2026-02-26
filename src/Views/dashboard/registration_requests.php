<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Registration Requests</h2>
    <p class="mb-4 text-gray-600">Review and manage new user registrations.</p>

    <?php if (empty($pendingUsers)): ?>
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        No pending registration requests found.
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
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Documents</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($pendingUsers as $user): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($user['username']); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <?php echo ucfirst($user['role']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <ul class="list-disc list-inside text-sm text-gray-500">
                <?php if (!empty($user['documents'])): ?>
                    <?php foreach ($user['documents'] as $doc):
                // Absolute server path for checking existence
                $filePath = $doc['file_path']; // e.g., assets/user_docs/file.pdf
                // Determine absolute path based on where this script is running.
                // Assuming index.php is in public/ and assets is in root.
                // We need the project root path.
                // Simplest is to assume standard layout:
                $serverPath = __DIR__ . '/../../../' . $filePath;

                // URL for the link
                $fileUrl = BASE_URL . $filePath;
?>
                        <li>
                            <?php if (file_exists($serverPath)): ?>
                                <a href="<?php echo htmlspecialchars($fileUrl); ?>" target="_blank" class="text-indigo-600 hover:text-indigo-900 hover:underline">
                                    <?php echo ucwords(str_replace('_', ' ', $doc['document_type'])); ?>
                                </a>
                            <?php
                else: ?>
                                <span class="text-red-500" title="File not found at: <?php echo htmlspecialchars($serverPath); ?>">
                                    <?php echo ucwords(str_replace('_', ' ', $doc['document_type'])); ?> (Missing)
                                </span>
                            <?php
                endif; ?>
                        </li>
                    <?php
            endforeach; ?>
                <?php
        else: ?>
                    <li>No documents</li>
                <?php
        endif; ?>
            </ul>
                            </td>
                             <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <?php echo ucfirst(str_replace('_', ' ', $user['status'])); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <form action="index.php?action=approve_user" method="POST" class="inline">
                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs">Approve</button>
                                    </form>
                                    <form action="index.php?action=reject_user" method="POST" class="inline flex items-center">
                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                        <input type="text" name="remarks" placeholder="Reason" class="border border-gray-300 rounded px-2 py-1 text-xs mr-2 w-32" required>
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
