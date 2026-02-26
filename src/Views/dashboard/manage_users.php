<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

<?php

$pageTitle = 'User Management';
if ($currentUserRole === 'admin')
    $pageTitle = 'Staff & Student Directory';
elseif ($currentUserRole === 'teacher')
    $pageTitle = 'My Students';
?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
    <div class="container mx-auto px-6 py-8">
        <h3 class="text-gray-700 text-3xl font-medium"><?php echo $pageTitle; ?></h3>

        <!-- Search and Filter -->
        <div class="mt-4">
            <form action="index.php" method="GET" class="flex flex-col md:flex-row gap-4">
                <input type="hidden" name="action" value="manage_users">
                <div class="flex-1">
                     <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by name or email" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-600">
                </div>
                 <div>
                    <select name="status" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-600">
                        <option value="">All Statuses</option>
                        <option value="active" <?php echo $status === 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo $status === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                        <option value="pending" <?php echo strpos($status, 'pending') !== false ? 'selected' : ''; ?>>Pending</option>
                    </select>
                </div>
                <div>
                     <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Filter</button>
                </div>
            </form>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?php echo htmlspecialchars($_GET['success']); ?></span>
            </div>
        <?php
endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?php echo htmlspecialchars($_GET['error']); ?></span>
            </div>
        <?php
endif; ?>

        <!-- Tabs -->
        <div class="mt-8">
            <ul class="flex border-b">
                <?php if ($currentUserRole === 'super_admin'): ?>
                <li class="-mb-px mr-1">
                    <a class="bg-white inline-block border-l border-t border-r rounded-t py-2 px-4 text-blue-700 font-semibold" href="#admins" id="tab-admins" onclick="openTab(event, 'admins')">Admins</a>
                </li>
                <?php
endif; ?>
                
                <?php if (in_array($currentUserRole, ['super_admin', 'admin'])): ?>
                <li class="mr-1">
                    <a class="bg-white inline-block py-2 px-4 text-blue-500 hover:text-blue-800 font-semibold" href="#teachers" id="tab-teachers" onclick="openTab(event, 'teachers')">Teachers</a>
                </li>
                <?php
endif; ?>

                <li class="mr-1">
                    <a class="bg-white inline-block py-2 px-4 text-blue-500 hover:text-blue-800 font-semibold" href="#students" id="tab-students" onclick="openTab(event, 'students')">Students</a>
                </li>
            </ul>

            <div class="bg-white p-4 rounded-b-lg shadow-md">
                <?php
$roleGroups = ['admin' => [], 'teacher' => [], 'student' => []];
foreach ($users as $user) {
    if (isset($roleGroups[$user['role']])) {
        $roleGroups[$user['role']][] = $user;
    }
}
?>

                <!-- Admin Tab -->
                <?php if ($currentUserRole === 'super_admin'): ?>
                <div id="admins" class="tab-content block">
                    <?php renderUserTable($roleGroups['admin'], $currentUserRole); ?>
                </div>
                <?php
endif; ?>

                <!-- Teacher Tab -->
                <?php if (in_array($currentUserRole, ['super_admin', 'admin'])): ?>
                <div id="teachers" class="tab-content <?php echo($currentUserRole === 'admin') ? 'block' : 'hidden'; ?>">
                    <?php renderUserTable($roleGroups['teacher'], $currentUserRole); ?>
                </div>
                <?php
endif; ?>

                <!-- Student Tab -->
                <div id="students" class="tab-content <?php echo($currentUserRole === 'teacher') ? 'block' : 'hidden'; ?>">
                    <?php renderUserTable($roleGroups['student'], $currentUserRole); ?>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
function openTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tab-content");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].classList.add("hidden");
        tabcontent[i].classList.remove("block");
    }
    tablinks = document.getElementsByTagName("a");
    // Reset basic tab styles if needed, but for now simple hidden/block toggle
    // Improve active class logic
    document.getElementById(tabName).classList.remove("hidden");
    document.getElementById(tabName).classList.add("block");
    evt.currentTarget.classList.add("border-l", "border-t", "border-r", "rounded-t", "text-blue-700");
    // Remove active styles from others? (Simplified for brevity)
}
</script>

<?php
function renderUserTable($users, $viewerRole)
{
    if (empty($users)) {
        echo '<p class="text-gray-500">No users found.</p>';
        return;
    }
?>
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name/Email</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered</th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($user['username']); ?></div>
                                                <div class="text-sm text-gray-500"><?php echo htmlspecialchars($user['email'] ?? 'No email'); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?php echo ucfirst($user['role']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                         <?php
        $statusColor = 'bg-gray-100 text-gray-800';
        if ($user['status'] === 'active')
            $statusColor = 'bg-green-100 text-green-800';
        elseif ($user['status'] === 'rejected')
            $statusColor = 'bg-red-100 text-red-800';
        elseif (strpos($user['status'], 'pending') !== false)
            $statusColor = 'bg-yellow-100 text-yellow-800';
?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $statusColor; ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', $user['status'])); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo isset($user['created_at']) ? date('M d, Y', strtotime($user['created_at'])) : '-'; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="index.php?action=view_user&id=<?php echo $user['id']; ?>" class="text-indigo-600 hover:text-indigo-900">View</a>
                                    </td>
                                </tr>
                            <?php
    endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
