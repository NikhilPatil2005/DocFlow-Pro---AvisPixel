<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="bg-white rounded-lg shadow-md p-6 max-w-2xl mx-auto mt-10">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit & Resubmit Notice</h2>

    <?php if (isset($notice['rejection_reason'])): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
            <p class="font-bold">Rejection Reason:</p>
            <p>
                <?php echo htmlspecialchars($notice['rejection_reason']); ?>
            </p>
        </div>
    <?php endif; ?>

    <form action="index.php?action=edit_notice&id=<?php echo $notice['id']; ?>" method="POST" class="space-y-6">
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
            <input type="text" name="title" id="title" required
                value="<?php echo htmlspecialchars($notice['title']); ?>"
                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
        </div>

        <div>
            <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
            <textarea name="content" id="content" rows="6" required
                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm"><?php echo htmlspecialchars($notice['content']); ?></textarea>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="index.php?action=super_admin_dashboard"
                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Cancel
            </a>
            <button type="submit"
                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                Resubmit Notice
            </button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>