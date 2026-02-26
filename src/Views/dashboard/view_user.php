<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
    <div class="container mx-auto px-6 py-8">
        <div class="flex justify-between items-center">
            <h3 class="text-gray-700 text-3xl font-medium">User Profile</h3>
            <a href="index.php?action=manage_users" class="text-indigo-600 hover:text-indigo-900">Back to Users</a>
        </div>

        <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    <?php echo htmlspecialchars($user['username']); ?>
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    <?php echo htmlspecialchars($user['email'] ?? 'No email'); ?>
                </p>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Role
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                             <?php echo ucfirst($user['role']); ?>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Status
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?php echo ucfirst(str_replace('_', ' ', $user['status'])); ?>
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Documents
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <ul class="border border-gray-200 rounded-md divide-y divide-gray-200">
                                <?php if (!empty($documents)): ?>
                                    <?php foreach ($documents as $doc):
        $fileUrl = BASE_URL . $doc['file_path'];
?>
                                        <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                            <div class="w-0 flex-1 flex items-center">
                                                <span class="ml-2 flex-1 w-0 truncate">
                                                    <?php echo htmlspecialchars($doc['document_type']); ?>
                                                </span>
                                            </div>
                                            <div class="ml-4 flex-shrink-0">
                                                <a href="<?php echo htmlspecialchars($fileUrl); ?>" target="_blank" class="font-medium text-indigo-600 hover:text-indigo-500">
                                                    Download
                                                </a>
                                            </div>
                                        </li>
                                    <?php
    endforeach; ?>
                                <?php
else: ?>
                                    <li class="pl-3 pr-4 py-3 text-sm text-gray-500">No documents uploaded.</li>
                                <?php
endif; ?>
                            </ul>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Administrative Actions -->
        <div class="mt-8 bg-white shadow sm:rounded-lg p-6">
            <h4 class="text-lg font-medium text-gray-900 mb-4">Administrative Actions</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Change Status -->
                <div class="border rounded p-4">
                    <h5 class="font-medium text-gray-700 mb-2">Change Status</h5>
                    <form action="index.php?action=update_user_status" method="POST">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <select name="status" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="active" <?php echo $user['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo $user['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                            <option value="rejected" <?php echo $user['status'] === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                        </select>
                        <button type="submit" class="mt-2 w-full bg-yellow-500 text-white py-2 px-4 rounded hover:bg-yellow-600">Update Status</button>
                    </form>
                </div>

                <!-- Update Role -->
                <div class="border rounded p-4">
                    <h5 class="font-medium text-gray-700 mb-2">Update Role</h5>
                    <form action="index.php?action=update_user_role" method="POST">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                         <select name="role" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="student" <?php echo $user['role'] === 'student' ? 'selected' : ''; ?>>Student</option>
                            <option value="teacher" <?php echo $user['role'] === 'teacher' ? 'selected' : ''; ?>>Teacher</option>
                            <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                        </select>
                         <button type="submit" class="mt-2 w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Change Role</button>
                    </form>
                </div>

                <!-- Delete User -->
                <div class="border rounded p-4 border-red-200 bg-red-50">
                    <h5 class="font-medium text-red-700 mb-2">Danger Zone</h5>
                    <form action="index.php?action=delete_user" method="POST" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                         <button type="submit" class="w-full bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700">Delete User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
