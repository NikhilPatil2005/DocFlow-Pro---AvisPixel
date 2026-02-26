<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-6 rounded-lg shadow">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Check Registration Status
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Enter your email or username to track your application.
            </p>
        </div>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php
endif; ?>

        <?php if (isset($userStatus)): ?>
            <div class="mt-4">
                <h3 class="text-lg font-medium text-gray-900">Application Status for: <span class="text-indigo-600"><?php echo htmlspecialchars($username); ?></span></h3>
                <p class="text-sm text-gray-500 mb-4">Role: <?php echo ucfirst($role); ?></p>

                <div class="relative pt-1">
                    <div class="flex mb-2 items-center justify-between">
                        <div>
                    <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-indigo-600 bg-indigo-200">
                      Progress
                    </span>
                        </div>
                        <div class="text-right">
                    <span class="text-xs font-semibold inline-block text-indigo-600">
                        <?php
    $percentage = 0;
    if ($userStatus === 'active')
        $percentage = 100;
    elseif ($userStatus === 'pending_super_admin')
        $percentage = 75;
    elseif ($userStatus === 'pending_admin')
        $percentage = 50;
    elseif ($userStatus === 'pending_teacher')
        $percentage = 25;
    elseif ($userStatus === 'rejected')
        $percentage = 0; // Or handling differently
    echo $percentage . '%';
?>
                    </span>
                        </div>
                    </div>
                    <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-indigo-200">
                        <div style="width:<?php echo $percentage; ?>%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-indigo-500"></div>
                    </div>
                    
                    <!-- Timeline Steps -->
                    <div class="flex justify-between text-xs text-center">
                        <?php if ($role === 'student'): ?>
                            <div class="<?php echo($userStatus == 'pending_teacher' || $percentage >= 25) ? 'text-indigo-600 font-bold' : 'text-gray-400'; ?>">Teacher Review</div>
                            <div class="<?php echo($userStatus == 'pending_admin' || $percentage >= 50) ? 'text-indigo-600 font-bold' : 'text-gray-400'; ?>">Admin Review</div>
                            <div class="<?php echo($userStatus == 'pending_super_admin' || $percentage >= 75) ? 'text-indigo-600 font-bold' : 'text-gray-400'; ?>">Super Admin</div>
                        <?php
    elseif ($role === 'teacher'): ?>
                             <div class="<?php echo($userStatus == 'pending_teacher' || $percentage >= 25) ? 'text-gray-400 hidden' : 'text-gray-400 hidden'; ?>">NA</div>
                            <div class="<?php echo($userStatus == 'pending_admin' || $percentage >= 50) ? 'text-indigo-600 font-bold' : 'text-gray-400'; ?>">Admin Review</div>
                            <div class="<?php echo($userStatus == 'pending_super_admin' || $percentage >= 75) ? 'text-indigo-600 font-bold' : 'text-gray-400'; ?>">Super Admin</div>
                        <?php
    endif; ?>
                            <div class="<?php echo($userStatus == 'active') ? 'text-green-600 font-bold' : 'text-gray-400'; ?>">Active</div>
                    </div>
                    
                     <?php if ($userStatus === 'rejected'): ?>
                        <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <strong>Status: Rejected</strong>
                            <p>Please contact support for more details.</p>
                        </div>
                    <?php
    endif; ?>
                </div>
            </div>
            
             <div class="mt-6 text-center">
                 <a href="index.php?action=login" class="font-medium text-indigo-600 hover:text-indigo-500">Back to Login</a>
            </div>

        <?php
else: ?>
            <form class="mt-8 space-y-6" action="index.php?action=check_status" method="POST">
                <input type="hidden" name="check_status" value="1">
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="username" class="sr-only">Username</label>
                        <input id="username" name="username" type="text" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Username">
                    </div>
                </div>

                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Check Status
                    </button>
                </div>
                
                 <div class="text-sm text-center">
                    <a href="index.php?action=login" class="font-medium text-indigo-600 hover:text-indigo-500">
                        Back to Login
                    </a>
                </div>
            </form>
        <?php
endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
