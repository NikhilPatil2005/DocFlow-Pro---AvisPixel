<?php
$title = 'My Leave History';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">
            <?php echo htmlspecialchars($title); ?>
        </h1>
        <a href="index.php?action=apply_leave"
            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
            Apply for OD
        </a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
            <p>
                <?php echo htmlspecialchars($_GET['success']); ?>
            </p>
        </div>
    <?php endif; ?>

    <div class="bg-white shadow overflow-hidden sm:rounded-md mt-6">
        <?php if (empty($leaves)): ?>
            <div class="p-6 text-center text-gray-500">
                You haven't applied for any leaves yet.
            </div>
        <?php else: ?>
            <ul class="divide-y divide-gray-200">
                <?php foreach ($leaves as $leave): ?>
                    <li>
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="text-sm font-medium text-indigo-600 truncate">
                                    Leave Date:
                                    <?php echo htmlspecialchars($leave['leave_date']); ?>
                                </div>
                                <div class="ml-2 flex-shrink-0 flex">
                                    <?php
                                    $statusClass = 'bg-gray-100 text-gray-800';
                                    $statusText = $leave['status'];
                                    if ($leave['status'] === 'approved') {
                                        $statusClass = 'bg-green-100 text-green-800';
                                        $statusText = 'Approved';
                                    } elseif ($leave['status'] === 'rejected') {
                                        $statusClass = 'bg-red-100 text-red-800';
                                        $statusText = 'Rejected';
                                    } elseif ($leave['status'] === 'pending_hod') {
                                        $statusClass = 'bg-yellow-100 text-yellow-800';
                                        $statusText = 'Pending HOD';
                                    } elseif ($leave['status'] === 'pending_principal') {
                                        $statusClass = 'bg-blue-100 text-blue-800';
                                        $statusText = 'Pending Principal';
                                    }
                                    ?>
                                    <p
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $statusClass; ?>">
                                        <?php echo $statusText; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="mt-2 sm:flex sm:justify-between">
                                <div class="sm:flex">
                                    <p class="flex items-center text-sm text-gray-500 sm:mr-6">
                                        Time:
                                        <?php echo htmlspecialchars($leave['time_from']); ?> -
                                        <?php echo htmlspecialchars($leave['time_to']); ?>
                                    </p>
                                    <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:mr-6">
                                        Venue:
                                        <?php echo htmlspecialchars($leave['venue']); ?>
                                    </p>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                    <p>
                                        Reason:
                                        <?php echo htmlspecialchars($leave['reason']); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>