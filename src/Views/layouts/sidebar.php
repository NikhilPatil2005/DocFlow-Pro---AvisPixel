<div class="w-64 bg-slate-900 text-white min-h-screen fixed left-0 top-0 overflow-y-auto z-50 flex flex-col transition-all duration-300">
    <div class="flex items-center justify-center h-16 border-b border-slate-700 bg-slate-950">
        <span class="text-xl font-bold tracking-wider text-blue-400">DOCFLOW PRO</span>
    </div>

    <?php
// Fetch Badge Counts
$userModelForSidebar = new User($conn);
$noticeModelForSidebar = new Notice($conn);

$currentUserRole = $_SESSION['role'] ?? '';

$pendingRegistrationsCount = $userModelForSidebar->getPendingCount($currentUserRole);
$pendingNoticesCount = $noticeModelForSidebar->getPendingCount($currentUserRole);
?>

    <nav class="flex-1 px-4 py-6 space-y-2">
        <!-- Dashboard -->
        <a href="index.php?action=<?php echo $currentUserRole; ?>_dashboard" 
           class="flex items-center px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition-colors <?php echo(strpos($_GET['action'] ?? '', 'dashboard') !== false) ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : ''; ?>">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            Dashboard
        </a>

        <!-- Registration Requests -->
        <?php if (in_array($currentUserRole, ['super_admin', 'admin', 'teacher'])): ?>
        <a href="index.php?action=registration_requests" 
           class="flex items-center justify-between px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition-colors <?php echo($_GET['action'] ?? '') == 'registration_requests' ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : ''; ?>">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                <span>Registrations</span>
            </div>
            <?php if ($pendingRegistrationsCount > 0): ?>
                <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full"><?php echo $pendingRegistrationsCount; ?></span>
            <?php
    endif; ?>
        </a>
        <?php
endif; ?>

        <!-- Notice Approvals -->
        <?php if (in_array($currentUserRole, ['admin', 'teacher'])): ?>
        <a href="index.php?action=notice_approvals" 
           class="flex items-center justify-between px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition-colors <?php echo($_GET['action'] ?? '') == 'notice_approvals' ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : ''; ?>">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Approvals</span>
            </div>
            <?php if ($pendingNoticesCount > 0): ?>
                <span class="bg-yellow-500 text-slate-900 text-xs font-bold px-2 py-0.5 rounded-full"><?php echo $pendingNoticesCount; ?></span>
            <?php
    endif; ?>
        </a>
        <?php
endif; ?>
        
        <!-- User Management (Super Admin) -->
        <?php if ($currentUserRole === 'super_admin'): ?>
            <a href="index.php?action=manage_users"
               class="flex items-center px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition-colors <?php echo(($_GET['action'] ?? '') == 'manage_users' || ($_GET['action'] ?? '') == 'view_user') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : ''; ?>">
                <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                User Management
            </a>
        <?php
elseif ($currentUserRole === 'admin'): ?>
            <a href="index.php?action=manage_users"
               class="flex items-center px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition-colors <?php echo(($_GET['action'] ?? '') == 'manage_users' || ($_GET['action'] ?? '') == 'view_user') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : ''; ?>">
                <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Staff & Student Directory
            </a>
        <?php
elseif ($currentUserRole === 'teacher'): ?>
            <a href="index.php?action=manage_users"
               class="flex items-center px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition-colors <?php echo(($_GET['action'] ?? '') == 'manage_users' || ($_GET['action'] ?? '') == 'view_user') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : ''; ?>">
                <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                My Students
            </a>
        <?php
endif; ?>

        <!-- Create Notice (Super Admin) -->
         <?php if ($currentUserRole === 'super_admin'): ?>
            <a href="index.php?action=create_notice"
                class="flex items-center px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition-colors <?php echo($_GET['action'] ?? '') == 'create_notice' ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : ''; ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Create Notice
            </a>
        <?php
endif; ?>

        <div class="border-t border-slate-700 my-4"></div>

        <!-- Profile & Logout -->
        <a href="index.php?action=profile" class="flex items-center px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition-colors">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            Profile
        </a>
        <a href="index.php?action=logout" class="flex items-center px-4 py-3 rounded-lg text-red-300 hover:bg-red-900/20 hover:text-red-200 transition-colors">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            Logout
        </a>
    </nav>
</div>
