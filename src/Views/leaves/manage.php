<?php
$title = $title ?? 'Manage Leaves';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">
            <?php echo htmlspecialchars($title); ?>
        </h1>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
            <p>
                <?php echo htmlspecialchars($_GET['success']); ?>
            </p>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
            <p>
                <?php echo htmlspecialchars($_GET['error']); ?>
            </p>
        </div>
    <?php endif; ?>

    <div class="bg-white shadow overflow-hidden sm:rounded-md mt-6">
        <?php if (empty($leaves)): ?>
            <div class="p-6 text-center text-gray-500">
                No leave applications found.
            </div>
        <?php else: ?>
            <ul class="divide-y divide-gray-200">
                <?php foreach ($leaves as $leave): ?>
                    <li>
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="text-sm font-medium text-indigo-600 truncate">
                                    <?php echo htmlspecialchars($leave['username']); ?>
                                    <span class="text-gray-500 font-normal">
                                        (
                                        <?php echo htmlspecialchars($leave['designation'] ?: 'No designation'); ?>,
                                        <?php echo htmlspecialchars($leave['department_name']); ?>)
                                    </span>
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
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                            aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Date:
                                        <?php echo htmlspecialchars($leave['leave_date']); ?>
                                        (
                                        <?php echo htmlspecialchars($leave['time_from']); ?> -
                                        <?php echo htmlspecialchars($leave['time_to']); ?>)
                                    </p>
                                    <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:mr-6">
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Venue:
                                        <?php echo htmlspecialchars($leave['venue']); ?>
                                    </p>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                    <p>
                                        <span class="font-bold text-gray-700">Reason:</span>
                                        <?php echo htmlspecialchars($leave['reason']); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="mt-2 text-sm text-gray-500">
                                <span class="font-bold text-gray-700">Workload Adjusted With:</span>
                                <?php echo htmlspecialchars($leave['workload_adjusted_with']); ?>
                            </div>

                            <?php if ($_SESSION['role'] !== 'admin' && in_array($leave['status'], ['pending_hod', 'pending_principal'])): ?>
                                <div class="mt-4 flex space-x-3">
                                    <form action="index.php?action=approve_leave" method="POST" class="inline">
                                        <input type="hidden" name="leave_id" value="<?php echo $leave['id']; ?>">
                                        <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            Approve
                                        </button>
                                    </form>
                                    <form action="index.php?action=reject_leave" method="POST" class="inline">
                                        <input type="hidden" name="leave_id" value="<?php echo $leave['id']; ?>">
                                        <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                            onclick="return confirm('Are you sure you want to reject this request?');">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>