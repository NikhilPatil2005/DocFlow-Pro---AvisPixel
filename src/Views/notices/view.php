<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="bg-white rounded-lg shadow-md p-8 max-w-3xl mx-auto mt-10">
    <div class="mb-6 border-b pb-4">
        <h1 class="text-3xl font-bold text-gray-800">
            <?php echo htmlspecialchars($notice['title']); ?>
        </h1>
        <div class="flex items-center text-sm text-gray-500 mt-2">
            <span class="mr-4">By
                <?php echo htmlspecialchars($notice['creator_name']); ?>
            </span>
            <span>
                <?php echo date('F j, Y, g:i a', strtotime($notice['created_at'])); ?>
            </span>
            <span
                class="ml-auto px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                <?php echo htmlspecialchars($notice['status']); ?>
            </span>
        </div>
    </div>

    <div class="prose max-w-none text-gray-700">
        <?php echo nl2br(htmlspecialchars($notice['content'])); ?>
    </div>

    <div class="mt-8 pt-4 border-t flex justify-end">
        <button onclick="history.back()"
            class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
            Back
        </button>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>