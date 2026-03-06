<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">My Salary Certificates</h1>
            <p class="text-sm text-slate-500 mt-1">History of your salary certificate requests</p>
        </div>
        <a href="index.php?action=apply_salary"
            class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition-colors">
            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            New Request
        </a>
    </div>

    <!-- Feedback Messages -->
    <?php if (isset($_GET['success'])): ?>
        <div class="bg-green-50 text-green-700 p-4 rounded-lg flex items-center shadow-sm border border-green-100">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <?php echo htmlspecialchars($_GET['success']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="bg-red-50 text-red-700 p-4 rounded-lg flex items-center shadow-sm border border-red-100">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <!-- History Table -->
    <div class="bg-white shadow-sm rounded-lg border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            Date Requested</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            Duration</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            Purpose</th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            Status</th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    <?php if (empty($requests)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                You haven't made any salary certificate requests yet.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($requests as $request): ?>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 border-l-4 
                                <?php
                                echo match ($request['status']) {
                                    'approved' => 'border-green-500',
                                    'rejected' => 'border-red-500',
                                    default => 'border-yellow-500'
                                };
                                ?>">
                                    <?php echo date('M d, Y', strtotime($request['created_at'])); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                    <?php echo date('M d, Y', strtotime($request['from_date'])) . ' - ' . date('M d, Y', strtotime($request['to_date'])); ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 max-w-xs truncate"
                                    title="<?php echo htmlspecialchars($request['purpose']); ?>">
                                    <?php echo htmlspecialchars($request['purpose']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    <?php
                                    $statusClasses = match ($request['status']) {
                                        'approved' => 'bg-green-100 text-green-800 border-green-200',
                                        'rejected' => 'bg-red-100 text-red-800 border-red-200',
                                        default => 'bg-yellow-100 text-yellow-800 border-yellow-200'
                                    };
                                    ?>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border <?php echo $statusClasses; ?>">
                                        <?php echo ucfirst($request['status']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right space-x-2">
                                    <?php if ($request['status'] === 'approved'): ?>
                                        <a href="index.php?action=print_salary_certificate&id=<?php echo $request['id']; ?>"
                                            class="inline-flex items-center px-3 py-1.5 border border-slate-300 shadow-sm text-xs font-medium rounded text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                            target="_blank">
                                            <svg class="w-4 h-4 mr-1.5 text-slate-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                                </path>
                                            </svg>
                                            View / Print PDF
                                        </a>
                                    <?php else: ?>
                                        <span class="text-slate-400 text-xs italic">Pending Approval</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>