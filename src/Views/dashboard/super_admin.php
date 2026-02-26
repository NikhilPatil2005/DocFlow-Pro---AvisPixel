<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Super Admin Dashboard</h2>
    <p class="mb-4 text-gray-600">Manage notices and system overview.</p>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <a href="index.php?action=super_admin_dashboard&status=pending" class="block">
            <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500 hover:bg-blue-100 transition cursor-pointer">
                <h3 class="font-bold text-blue-700">Pending Notices</h3>
                <p class="text-2xl font-bold text-gray-800"><?php echo $counts['pending_count'] ?? 0; ?></p>
            </div>
        </a>
        <a href="index.php?action=super_admin_dashboard&status=published" class="block">
            <div class="bg-green-50 p-4 rounded-lg border-l-4 border-green-500 hover:bg-green-100 transition cursor-pointer">
                <h3 class="font-bold text-green-700">Published Notices</h3>
                <p class="text-2xl font-bold text-gray-800"><?php echo $counts['published_count'] ?? 0; ?></p>
            </div>
        </a>
        <a href="index.php?action=super_admin_dashboard&status=rejected" class="block">
            <div class="bg-red-50 p-4 rounded-lg border-l-4 border-red-500 hover:bg-red-100 transition cursor-pointer">
                <h3 class="font-bold text-red-700">Rejected Notices</h3>
                <p class="text-2xl font-bold text-gray-800"><?php echo $counts['rejected_count'] ?? 0; ?></p>
            </div>
        </a>
    </div>

    </div>

    <div class="mt-8 bg-blue-50 p-6 rounded-lg border border-blue-200">
        <h3 class="text-xl font-bold text-blue-800 mb-2">Welcome to the Super Admin Dashboard</h3>
        <p class="text-gray-700">
            Use the sidebar to navigate to <strong>Registration Requests</strong> to approve new users, or <strong>Create Notice</strong> to broadcast announcements.
        </p>
    </div>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>