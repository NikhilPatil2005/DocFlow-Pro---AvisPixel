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
                class="ml-auto px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 ml-2">
                <?php echo htmlspecialchars($notice['status']); ?>
            </span>
            <?php
$priorityColor = 'gray';
if (($notice['priority'] ?? 'Low') === 'High')
    $priorityColor = 'red';
if (($notice['priority'] ?? 'Low') === 'Medium')
    $priorityColor = 'yellow';
if (($notice['priority'] ?? 'Low') === 'Low')
    $priorityColor = 'green';
?>
            <span
                class="ml-2 px-2 inline-flex text-xs leading-5 font-bold rounded-full bg-<?php echo $priorityColor; ?>-100 text-<?php echo $priorityColor; ?>-800">
                Priority: <?php echo htmlspecialchars($notice['priority'] ?? 'Low'); ?>
            </span>
        </div>
    </div>

    <div class="prose max-w-none text-gray-700">
        <?php echo nl2br(htmlspecialchars($notice['content'])); ?>
    </div>
    
    <!-- Status History / Logs -->
    <div class="mt-8 pt-4 border-t">
        <h3 class="text-xl font-bold mb-4 text-gray-800">Status History</h3>
        <?php if (!empty($logs)): ?>
            <div class="flow-root">
                <ul role="list" class="-mb-8">
                    <?php foreach ($logs as $index => $log): ?>
                        <li>
                            <div class="relative pb-8">
                                <?php if ($index !== count($logs) - 1): ?>
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                <?php
        endif; ?>
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-gray-400 flex items-center justify-center ring-8 ring-white">
                                            <!-- Icon based on action? -->
                                            <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">
                                                <span class="font-medium text-gray-900"><?php echo htmlspecialchars($log['performed_by_name']); ?></span>
                                                (<?php echo htmlspecialchars($log['role']); ?>)
                                                performed 
                                                <span class="font-medium text-gray-900"><?php echo htmlspecialchars($log['action']); ?></span>
                                            </p>
                                            <?php if ($log['details']): ?>
                                                <p class="text-sm text-gray-500 mt-1 italic">
                                                    "<?php echo htmlspecialchars($log['details']); ?>"
                                                </p>
                                            <?php
        endif; ?>
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            <time datetime="<?php echo $log['created_at']; ?>"><?php echo date('M j, Y g:i a', strtotime($log['created_at'])); ?></time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php
    endforeach; ?>
                </ul>
            </div>
        <?php
else: ?>
            <p class="text-gray-500 text-sm">No history available.</p>
        <?php
endif; ?>
    </div>

    <div class="mt-8 pt-4 border-t flex justify-end">
        <button onclick="history.back()"
            class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
            Back
        </button>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>