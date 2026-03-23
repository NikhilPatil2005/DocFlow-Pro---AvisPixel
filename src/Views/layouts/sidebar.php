<?php
// Fetch Badge Counts
$userModelForSidebar = new User($conn);
$noticeModelForSidebar = new Notice($conn);

$currentUserRole = $_SESSION['role'] ?? '';

$pendingRegistrationsCount = $userModelForSidebar->getPendingCount($currentUserRole);
$pendingNoticesCount = $noticeModelForSidebar->getPendingCount($currentUserRole);
?>

<div id="sidebar-container"
    class="w-64 bg-[#1F2933] text-[#E5E7EB] h-screen fixed left-0 top-0 z-50 flex flex-col transition-all duration-300 shadow-xl overflow-hidden group/sidebar">

    <!-- Header -->
    <div class="flex items-center justify-center h-16 border-b border-[#374151] bg-[#1a232c] flex-shrink-0">
        <span class="text-xl font-bold tracking-wider text-[#10B981] flex items-center gap-2">
            <i class="fas fa-layer-group"></i> E-OFFICE
        </span>
    </div>

    <!-- Scrollable Navigation -->
    <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1 custom-scrollbar pb-24">

        <!-- Dashboard -->
        <a href="index.php?action=<?php echo $currentUserRole; ?>_dashboard"
            class="flex items-center px-4 py-3 rounded-lg text-[#E5E7EB] hover:bg-[#374151] transition-colors <?php echo (strpos($_GET['action'] ?? '', 'dashboard') !== false) ? 'bg-[#374151] border-l-4 border-[#10B981]' : 'border-l-4 border-transparent'; ?>">
            <i class="fas fa-chart-pie w-5 h-5 mr-3 text-center"></i>
            <span class="font-medium">Dashboard</span>
        </a>

        <?php if (in_array($currentUserRole, ['admin', 'principal', 'hod', 'teacher'])): ?>
            <!-- Administration Section -->
            <div class="pt-4 pb-1">
                <button onclick="toggleDropdown('admin-dropdown')"
                    class="w-full flex items-center justify-between px-4 py-2 text-xs font-semibold text-[#9CA3AF] uppercase tracking-wider hover:text-white transition-colors focus:outline-none">
                    <span class="flex items-center gap-2"><i class="fas fa-sitemap w-4"></i> Administration</span>
                    <i class="fas fa-chevron-down text-[10px] transform transition-transform duration-200"
                        id="admin-dropdown-icon"></i>
                </button>

                <div id="admin-dropdown" class="mt-2 space-y-1 overflow-hidden transition-all duration-300 max-h-0">
                    <a href="index.php?action=registration_requests"
                        class="flex items-center justify-between px-4 py-2.5 pl-11 rounded-lg text-[#E5E7EB] hover:bg-[#374151] transition-colors <?php echo ($_GET['action'] ?? '') == 'registration_requests' ? 'bg-[#374151] border-l-4 border-[#10B981]' : 'border-l-4 border-transparent'; ?>">
                        <span class="text-sm">Registrations</span>
                        <?php if ($pendingRegistrationsCount > 0): ?>
                            <span
                                class="bg-[#10B981] text-white text-xs font-bold px-2 py-0.5 rounded-full"><?php echo $pendingRegistrationsCount; ?></span>
                        <?php endif; ?>
                    </a>

                    <?php if (in_array($currentUserRole, ['principal', 'admin', 'teacher'])): ?>
                        <a href="index.php?action=notice_approvals"
                            class="flex items-center justify-between px-4 py-2.5 pl-11 rounded-lg text-[#E5E7EB] hover:bg-[#374151] transition-colors <?php echo ($_GET['action'] ?? '') == 'notice_approvals' ? 'bg-[#374151] border-l-4 border-[#10B981]' : 'border-l-4 border-transparent'; ?>">
                            <span class="text-sm">Approvals</span>
                            <?php if ($pendingNoticesCount > 0): ?>
                                <span
                                    class="bg-yellow-500 text-slate-900 text-xs font-bold px-2 py-0.5 rounded-full"><?php echo $pendingNoticesCount; ?></span>
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>

                    <a href="index.php?action=manage_users"
                        class="flex items-center px-4 py-2.5 pl-11 rounded-lg text-[#E5E7EB] hover:bg-[#374151] transition-colors <?php echo (($_GET['action'] ?? '') == 'manage_users' || ($_GET['action'] ?? '') == 'view_user') ? 'bg-[#374151] border-l-4 border-[#10B981]' : 'border-l-4 border-transparent'; ?>">
                        <span class="text-sm">
                            <?php
                            if ($currentUserRole === 'teacher')
                                echo 'My Students';
                            elseif ($currentUserRole === 'hod')
                                echo 'Department Directory';
                            elseif ($currentUserRole === 'principal')
                                echo 'Staff & Student Directory';
                            else
                                echo 'User Directory';
                            ?>
                        </span>
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <?php if (in_array($currentUserRole, ['teacher', 'hod', 'principal', 'admin'])): ?>
            <!-- Leave System Section -->
            <div class="pt-4 pb-1">
                <button onclick="toggleDropdown('leave-dropdown')"
                    class="w-full flex items-center justify-between px-4 py-2 text-xs font-semibold text-[#9CA3AF] uppercase tracking-wider hover:text-white transition-colors focus:outline-none">
                    <span class="flex items-center gap-2"><i class="fas fa-calendar-alt w-4"></i> Leave System</span>
                    <i class="fas fa-chevron-down text-[10px] transform transition-transform duration-200"
                        id="leave-dropdown-icon"></i>
                </button>

                <div id="leave-dropdown" class="mt-2 space-y-1 overflow-hidden transition-all duration-300 max-h-0">
                    <?php if (in_array($currentUserRole, ['teacher', 'hod', 'principal'])): ?>
                        <a href="index.php?action=apply_leave"
                            class="flex items-center px-4 py-2.5 pl-11 rounded-lg text-[#E5E7EB] hover:bg-[#374151] transition-colors <?php echo ($_GET['action'] ?? '') == 'apply_leave' ? 'bg-[#374151] border-l-4 border-[#10B981]' : 'border-l-4 border-transparent'; ?>">
                            <span class="text-sm">Apply for OD</span>
                        </a>
                        <a href="index.php?action=my_leaves"
                            class="flex items-center px-4 py-2.5 pl-11 rounded-lg text-[#E5E7EB] hover:bg-[#374151] transition-colors <?php echo ($_GET['action'] ?? '') == 'my_leaves' ? 'bg-[#374151] border-l-4 border-[#10B981]' : 'border-l-4 border-transparent'; ?>">
                            <span class="text-sm">My Leaves</span>
                        </a>
                    <?php endif; ?>

                    <?php if (in_array($currentUserRole, ['hod', 'principal', 'admin'])): ?>
                        <a href="index.php?action=manage_leaves"
                            class="flex items-center justify-between px-4 py-2.5 pl-11 rounded-lg text-[#E5E7EB] hover:bg-[#374151] transition-colors <?php echo ($_GET['action'] ?? '') == 'manage_leaves' ? 'bg-[#374151] border-l-4 border-[#10B981]' : 'border-l-4 border-transparent'; ?>">
                            <span class="text-sm">Manage Leaves</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (in_array($currentUserRole, ['teacher', 'principal'])): ?>
            <!-- Salary System Section -->
            <div class="pt-4 pb-1">
                <button onclick="toggleDropdown('salary-dropdown')"
                    class="w-full flex items-center justify-between px-4 py-2 text-xs font-semibold text-[#9CA3AF] uppercase tracking-wider hover:text-white transition-colors focus:outline-none">
                    <span class="flex items-center gap-2"><i class="fas fa-money-check-alt w-4"></i> Salary System</span>
                    <i class="fas fa-chevron-down text-[10px] transform transition-transform duration-200"
                        id="salary-dropdown-icon"></i>
                </button>

                <div id="salary-dropdown" class="mt-2 space-y-1 overflow-hidden transition-all duration-300 max-h-0">
                    <?php if ($currentUserRole === 'teacher'): ?>
                        <a href="index.php?action=apply_salary"
                            class="flex items-center px-4 py-2.5 pl-11 rounded-lg text-[#E5E7EB] hover:bg-[#374151] transition-colors <?php echo ($_GET['action'] ?? '') == 'apply_salary' ? 'bg-[#374151] border-l-4 border-[#10B981]' : 'border-l-4 border-transparent'; ?>">
                            <span class="text-sm">Salary Certificate</span>
                        </a>
                        <a href="index.php?action=my_salary_certificates"
                            class="flex items-center px-4 py-2.5 pl-11 rounded-lg text-[#E5E7EB] hover:bg-[#374151] transition-colors <?php echo ($_GET['action'] ?? '') == 'my_salary_certificates' ? 'bg-[#374151] border-l-4 border-[#10B981]' : 'border-l-4 border-transparent'; ?>">
                            <span class="text-sm">My Certificates</span>
                        </a>
                    <?php endif; ?>

                    <?php if ($currentUserRole === 'principal'): ?>
                        <a href="index.php?action=manage_salary_requests"
                            class="flex items-center justify-between px-4 py-2.5 pl-11 rounded-lg text-[#E5E7EB] hover:bg-[#374151] transition-colors <?php echo ($_GET['action'] ?? '') == 'manage_salary_requests' ? 'bg-[#374151] border-l-4 border-[#10B981]' : 'border-l-4 border-transparent'; ?>">
                            <span class="text-sm">Salary Requests</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (in_array($currentUserRole, ['teacher', 'hod', 'student'])): ?>
            <!-- Examination System Section -->
            <div class="pt-4 pb-1">
                <button onclick="toggleDropdown('exam-dropdown')"
                    class="w-full flex items-center justify-between px-4 py-2 text-xs font-semibold text-[#9CA3AF] uppercase tracking-wider hover:text-white transition-colors focus:outline-none">
                    <span class="flex items-center gap-2"><i class="fas fa-edit w-4"></i> Examinations</span>
                    <i class="fas fa-chevron-down text-[10px] transform transition-transform duration-200"
                        id="exam-dropdown-icon"></i>
                </button>

                <div id="exam-dropdown" class="mt-2 space-y-1 overflow-hidden transition-all duration-300 max-h-0">
                    <?php if (in_array($currentUserRole, ['teacher', 'hod'])): ?>
                        <a href="index.php?action=teacher_exams"
                            class="flex items-center px-4 py-2.5 pl-11 rounded-lg text-[#E5E7EB] hover:bg-[#374151] transition-colors <?php echo ($_GET['action'] ?? '') == 'teacher_exams' ? 'bg-[#374151] border-l-4 border-[#10B981]' : 'border-l-4 border-transparent'; ?>">
                            <span class="text-sm">Manage Exams</span>
                        </a>
                        <a href="index.php?action=create_exam"
                            class="flex items-center px-4 py-2.5 pl-11 rounded-lg text-[#E5E7EB] hover:bg-[#374151] transition-colors <?php echo ($_GET['action'] ?? '') == 'create_exam' ? 'bg-[#374151] border-l-4 border-[#10B981]' : 'border-l-4 border-transparent'; ?>">
                            <span class="text-sm">Create Exam</span>
                        </a>
                    <?php endif; ?>

                    <?php if ($currentUserRole === 'student'): ?>
                        <a href="index.php?action=student_exams"
                            class="flex items-center justify-between px-4 py-2.5 pl-11 rounded-lg text-[#E5E7EB] hover:bg-[#374151] transition-colors <?php echo ($_GET['action'] ?? '') == 'student_exams' ? 'bg-[#374151] border-l-4 border-[#10B981]' : 'border-l-4 border-transparent'; ?>">
                            <span class="text-sm">My Exams</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Settings Section -->
        <div class="pt-4 pb-1">
            <button onclick="toggleDropdown('settings-dropdown')"
                class="w-full flex items-center justify-between px-4 py-2 text-xs font-semibold text-[#9CA3AF] uppercase tracking-wider hover:text-white transition-colors focus:outline-none">
                <span class="flex items-center gap-2"><i class="fas fa-cog w-4"></i> Settings</span>
                <i class="fas fa-chevron-down text-[10px] transform transition-transform duration-200"
                    id="settings-dropdown-icon"></i>
            </button>

            <div id="settings-dropdown" class="mt-2 space-y-1 overflow-hidden transition-all duration-300 max-h-0">
                <a href="index.php?action=profile"
                    class="flex items-center px-4 py-2.5 pl-11 rounded-lg text-[#E5E7EB] hover:bg-[#374151] transition-colors <?php echo ($_GET['action'] ?? '') == 'profile' ? 'bg-[#374151] border-l-4 border-[#10B981]' : 'border-l-4 border-transparent'; ?>">
                    <span class="text-sm">Profile</span>
                </a>
            </div>
        </div>

    </nav>

    <!-- Fixed Bottom Logout Button -->
    <div class="absolute bottom-0 w-full bg-[#1a232c] border-t border-[#374151] p-4 flex-shrink-0 z-10">
        <a href="index.php?action=logout"
            class="flex items-center justify-center w-full px-4 py-2.5 rounded-lg text-[#ef4444] border border-[#ef4444]/30 hover:bg-[#ef4444]/10 hover:border-[#ef4444]/50 transition-all group">
            <i class="fas fa-sign-out-alt mr-2 group-hover:-translate-x-1 transition-transform"></i>
            <span class="font-medium">Logout</span>
        </a>
    </div>

</div>

<!-- Styles and Scripts for UI behavior -->
<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #374151;
        border-radius: 4px;
    }

    .custom-scrollbar:hover::-webkit-scrollbar-thumb {
        background: #4B5563;
    }

    /* Open State Classes for JS Toggle */
    .dropdown-open {
        max-height: 500px !important;
    }

    .dropdown-icon-rotated {
        transform: rotate(180deg);
    }

    @media (max-width: 768px) {
        #sidebar-container {
            transform: translateX(-100%);
        }

        .sidebar-open {
            transform: translateX(0) !important;
        }
    }
</style>

<script>
    function toggleDropdown(id) {
        // Find element
        const dropdown = document.getElementById(id);
        const icon = document.getElementById(id + '-icon');

        // Check if currently open
        const isOpen = dropdown.classList.contains('dropdown-open');

        // Optional: Close all other dropdowns if "one open at a time" is strict
        document.querySelectorAll('[id$="-dropdown"]').forEach(el => el.classList.remove('dropdown-open'));
        document.querySelectorAll('[id$="-dropdown-icon"]').forEach(el => el.classList.remove('dropdown-icon-rotated'));

        // Toggle selected
        if (!isOpen) {
            dropdown.classList.add('dropdown-open');
            icon.classList.add('dropdown-icon-rotated');
        }
    }

    // Auto-open dropdown if an active link is inside it
    document.addEventListener("DOMContentLoaded", function () {
        // Find the link with the active styling border border-[#10B981]
        const activeLink = document.querySelector('nav a[class*="border-[#10B981]"]');
        if (activeLink) {
            const parentDropdown = activeLink.closest('[id$="-dropdown"]');
            if (parentDropdown) {
                const id = parentDropdown.id;
                parentDropdown.classList.add('dropdown-open');
                const icon = document.getElementById(id + '-icon');
                if (icon) icon.classList.add('dropdown-icon-rotated');
            }
        }
    });

    // Mobile Sidebar Toggle helper (assuming a button exists in the header.php)
    function toggleMobileSidebar() {
        document.getElementById('sidebar-container').classList.toggle('sidebar-open');
    }
</script>