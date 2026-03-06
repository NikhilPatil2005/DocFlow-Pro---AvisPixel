<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php
$user = $data['user'] ?? [];
$departments = $data['departments'] ?? [];
$successMessage = $data['success'] ?? null;
$errorMessage = $data['error'] ?? null;
?>

<!-- Minimal Professional Profile UI (#F5F7FA Background naturally inherited from main layout) -->
<div class="max-w-6xl mx-auto px-4 py-8">

    <!-- Header Section -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-[#374151]">My Profile</h1>
            <p class="text-gray-500 mt-1">Manage your account credentials and personal data</p>
        </div>
    </div>

    <?php if ($successMessage): ?>
        <div
            class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded shadow-sm flex items-center">
            <i class="fas fa-check-circle mr-3 text-emerald-500"></i>
            <?php echo htmlspecialchars($successMessage); ?>
        </div>
    <?php endif; ?>

    <?php if ($errorMessage): ?>
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm flex items-center">
            <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
            <?php echo htmlspecialchars($errorMessage); ?>
        </div>
    <?php endif; ?>

    <!-- Two-column grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Column 1: Identity & Password -->
        <div class="space-y-8 lg:col-span-1">

            <!-- Quick Identity Peek -->
            <div class="bg-white rounded-xl shadow-[0_2px_10px_rgba(0,0,0,0.06)] overflow-hidden">
                <div class="p-6 text-center border-b border-gray-100">
                    <div
                        class="w-24 h-24 rounded-full bg-[#1F2933] text-white flex items-center justify-center text-4xl mx-auto mb-4 shadow-md font-bold">
                        <?php echo strtoupper(substr($user['username'] ?? 'U', 0, 1)); ?>
                    </div>
                    <h2 class="text-xl font-bold text-[#374151] truncate">
                        <?php echo htmlspecialchars($user['full_name'] ?: $user['username']); ?>
                    </h2>
                    <p class="text-gray-500 text-sm mb-3">@
                        <?php echo htmlspecialchars($user['username']); ?>
                    </p>
                    <span
                        class="inline-block px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full uppercase tracking-wide">
                        <?php echo htmlspecialchars($user['role'] ?? 'user'); ?>
                    </span>
                </div>
                <div class="p-4 bg-gray-50/50 flex flex-col gap-2 text-sm text-gray-600">
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-400">Account Status</span>
                        <span
                            class="font-semibold <?php echo ($user['status'] === 'active') ? 'text-emerald-600' : 'text-amber-600'; ?>">
                            <?php echo ucfirst(htmlspecialchars($user['status'])); ?>
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-400">Joined Date</span>
                        <span class="font-semibold text-gray-700">
                            <?php echo date('M d, Y', strtotime($user['created_at'])); ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Change Password Card -->
            <div class="bg-white rounded-xl shadow-[0_2px_10px_rgba(0,0,0,0.06)] overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center gap-3">
                    <i class="fas fa-lock text-[#10B981] bg-emerald-50 p-2 rounded-lg"></i>
                    <h3 class="font-semibold text-[#374151] text-lg">Change Password</h3>
                </div>
                <div class="p-5">
                    <form action="index.php?action=update_password" method="POST" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                            <input type="password" name="current_password" required
                                class="w-full px-4 py-2 bg-[#F5F7FA] border border-gray-200 rounded-lg focus:ring-[#10B981] focus:border-[#10B981] transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                            <input type="password" name="new_password" required
                                class="w-full px-4 py-2 bg-[#F5F7FA] border border-gray-200 rounded-lg focus:ring-[#10B981] focus:border-[#10B981] transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                            <input type="password" name="confirm_password" required
                                class="w-full px-4 py-2 bg-[#F5F7FA] border border-gray-200 rounded-lg focus:ring-[#10B981] focus:border-[#10B981] transition-colors">
                        </div>
                        <div class="pt-2">
                            <button type="submit"
                                class="w-full bg-[#10B981] hover:bg-[#059669] text-white px-4 py-2.5 rounded-lg font-medium shadow-[0_4px_12px_rgba(16,185,129,0.3)] transition-all active:translate-y-[1px]">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <!-- Column 2: User Information Fields -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-xl shadow-[0_2px_10px_rgba(0,0,0,0.06)] overflow-hidden relative"
                x-data="{ editing: false }">

                <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-white sm:p-8">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-id-card text-[#10B981] bg-emerald-50 p-2.5 rounded-lg text-xl"></i>
                        <h3 class="font-semibold text-[#374151] text-xl">Personal Information</h3>
                    </div>
                    <button type="button" @click="editing = !editing"
                        class="text-sm px-4 py-2 border border-gray-200 rounded-lg font-medium text-gray-600 hover:bg-gray-50 hover:text-[#10B981] transition-colors"
                        x-show="!editing">
                        <i class="fas fa-pen mr-2 text-xs"></i>Edit Profile
                    </button>
                </div>

                <div class="p-6 sm:p-8 bg-white">
                    <form action="index.php?action=update_profile" method="POST">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Username (Readonly ALWAYS) -->
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-sm font-medium text-gray-500 mb-1">Username (Immutable)</label>
                                <input type="text" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>"
                                    readonly
                                    class="w-full px-4 py-2.5 bg-gray-100 border border-gray-200 rounded-lg text-gray-500 cursor-not-allowed select-none">
                            </div>

                            <!-- Full Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                                <input type="text" name="full_name"
                                    value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>"
                                    :readonly="!editing"
                                    :class="editing ? 'bg-[#F5F7FA] border-gray-300 focus:ring-[#10B981] focus:border-[#10B981]' : 'bg-transparent border-transparent px-0 font-medium text-gray-900 pointer-events-none'"
                                    class="w-full px-4 py-2 border rounded-lg transition-all" required
                                    placeholder="John Doe">
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                                <input type="email" name="email"
                                    value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" :readonly="!editing"
                                    :class="editing ? 'bg-[#F5F7FA] border-gray-300 focus:ring-[#10B981] focus:border-[#10B981]' : 'bg-transparent border-transparent px-0 font-medium text-gray-900 pointer-events-none'"
                                    class="w-full px-4 py-2 border rounded-lg transition-all" required
                                    placeholder="john@example.com">
                            </div>

                            <!-- Designation -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Designation</label>
                                <input type="text" name="designation"
                                    value="<?php echo htmlspecialchars($user['designation'] ?? ''); ?>"
                                    :readonly="!editing"
                                    :class="editing ? 'bg-[#F5F7FA] border-gray-300 focus:ring-[#10B981] focus:border-[#10B981]' : 'bg-transparent border-transparent px-0 font-medium text-gray-900 pointer-events-none'"
                                    class="w-full px-4 py-2 border rounded-lg transition-all"
                                    placeholder="e.g. Associate Professor">
                            </div>

                            <!-- Department Wrapper -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>

                                <!-- Readonly View -->
                                <div x-show="!editing" class="py-2 font-medium text-gray-900">
                                    <?php
                                    $deptName = 'Not Assigned';
                                    foreach ($departments as $d) {
                                        if ($d['id'] == ($user['department_id'] ?? null)) {
                                            $deptName = $d['name'];
                                            break;
                                        }
                                    }
                                    echo htmlspecialchars($deptName);
                                    ?>
                                </div>

                                <!-- Editable Select View -->
                                <div x-show="editing" style="display: none;">
                                    <select name="department_id"
                                        class="w-full px-4 py-2 bg-[#F5F7FA] border border-gray-300 rounded-lg focus:ring-[#10B981] focus:border-[#10B981]">
                                        <option value="">-- No Department --</option>
                                        <?php foreach ($departments as $dept): ?>
                                            <option value="<?php echo $dept['id']; ?>" <?php echo (($user['department_id'] ?? '') == $dept['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($dept['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-8 flex items-center justify-end gap-3 pt-6 border-t border-gray-100"
                            x-show="editing" style="display: none;">
                            <button type="button" @click="editing = false"
                                class="px-5 py-2 text-gray-600 hover:bg-gray-100 font-medium rounded-lg transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                class="bg-[#1F2933] hover:bg-gray-900 text-white px-6 py-2 rounded-lg font-medium shadow-md transition-all active:translate-y-[1px]">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-xs text-center text-gray-400 mt-6">
                <i class="fas fa-shield-alt mr-1"></i> Changes are monitored and must adhere to faculty policies.
            </div>
        </div>

    </div>
</div>

<!-- Alpine.js is ideal for simple UI toggles in robust MVC layouts -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>