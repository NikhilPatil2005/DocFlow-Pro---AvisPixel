<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex justify-between items-center bg-white p-6 rounded-t-lg border-b border-slate-200">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Salary Certificate Requests</h1>
            <p class="text-sm text-slate-500 mt-1">Review and manage pending salary certificates</p>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="bg-green-50 text-green-700 p-4 rounded-lg flex items-center shadow-sm border border-green-100 mx-6">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <?php echo htmlspecialchars($_GET['success']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="bg-red-50 text-red-700 p-4 rounded-lg flex items-center shadow-sm border border-red-100 mx-6">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <div class="px-6 pb-6">
        <div class="bg-white shadow-sm rounded-lg border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Teacher Details</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Duration</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Purpose</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Requested On</th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        <?php if (empty($requests)): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                    <div class="flex flex-col items-center justify-center space-y-3">
                                        <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <p>No pending salary certificate requests found.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($requests as $request): ?>
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div
                                                    class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-lg">
                                                    <?php echo strtoupper(substr($request['teacher_name'], 0, 1)); ?>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-slate-900">
                                                    <?php echo htmlspecialchars($request['teacher_name']); ?>
                                                </div>
                                                <div class="text-sm text-slate-500">
                                                    <?php echo htmlspecialchars($request['department_name'] ?? 'No Dept'); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                        <?php echo date('M d, Y', strtotime($request['from_date'])) . ' - ' . date('M d, Y', strtotime($request['to_date'])); ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600 max-w-xs truncate"
                                        title="<?php echo htmlspecialchars($request['purpose']); ?>">
                                        <?php echo htmlspecialchars($request['purpose']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                        <?php echo date('M d, Y h:i A', strtotime($request['created_at'])); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="index.php?action=view_salary_request&id=<?php echo $request['id']; ?>"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 ml-2">
                                            View & Sign
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>