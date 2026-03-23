<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<!-- Tailwind Extracted Glassmorphism Styles -->
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
    }
    .gradient-text {
        background: linear-gradient(90deg, #2563eb, #db2777);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>

<div class="px-4 py-6 sm:px-0">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight gradient-text">Student Portal</h1>
        <p class="mt-2 text-sm text-gray-600 font-medium">Access your notices, examinations, and academic updates.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content Area (2/3 width) -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Examinations Section -->
            <div class="glass-card rounded-2xl overflow-hidden relative">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-indigo-100 rounded-full opacity-50"></div>
                
                <div class="relative z-10 px-6 py-5 border-b border-gray-100/50 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-bold text-indigo-900 flex items-center">
                        <svg class="mr-2 h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        Upcoming & Active Examinations
                    </h3>
                    <a href="index.php?action=student_exams" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">View All</a>
                </div>
                
                <div class="relative z-10 px-6 py-4">
                    <?php if (empty($upcomingExams)): ?>
                        <div class="text-center py-6">
                            <p class="text-gray-500 text-sm">No upcoming examinations scheduled for your department.</p>
                        </div>
                    <?php else: ?>
                        <ul class="space-y-4">
                            <?php foreach (array_slice($upcomingExams, 0, 3) as $exam): 
                                $now = new DateTime();
                                $start = new DateTime($exam['start_time']);
                                $end = new DateTime($exam['end_time']);
                                
                                require_once __DIR__ . '/../../Models/Exam.php';
                                $examModel = new Exam($conn);
                                $attempt = $examModel->getAttempt($exam['id'], $_SESSION['user_id']);
                                
                                $status = 'upcoming';
                                $btnClass = 'bg-gray-100 text-gray-400 cursor-not-allowed';
                                $btnText = 'Not Started Yet';
                                
                                if ($attempt && $attempt['is_submitted']) {
                                    $status = 'completed';
                                    $btnClass = 'bg-blue-100 text-blue-700 hover:bg-blue-200 shadow-sm transition';
                                    $btnText = 'View Result';
                                } elseif ($now > $end) {
                                    $status = 'ended';
                                    $btnClass = 'bg-red-50 text-red-500 border border-red-100';
                                    $btnText = 'Missed';
                                } elseif ($now >= $start && $now <= $end) {
                                    $status = 'active';
                                    $btnClass = 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-md transform hover:-translate-y-0.5 transition';
                                    $btnText = $attempt ? 'Resume Exam' : 'Start Exam';
                                }
                            ?>
                                <li class="bg-white/80 rounded-xl p-4 border border-indigo-50 shadow-sm hover:shadow-md transition duration-200">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="text-md font-bold text-gray-900"><?php echo htmlspecialchars($exam['title']); ?></h4>
                                            <div class="mt-1 flex items-center text-xs text-gray-500 space-x-4">
                                                <span class="flex items-center">
                                                    <svg class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <?php echo $exam['duration_minutes']; ?> mins
                                                </span>
                                                <span class="flex items-center">
                                                    <svg class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <?php echo date('M d, h:i A', strtotime($exam['start_time'])); ?> - <?php echo date('h:i A', strtotime($exam['end_time'])); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div>
                                            <?php if ($status === 'completed'): ?>
                                                <a href="index.php?action=student_exam_result&id=<?php echo $attempt['id']; ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg <?php echo $btnClass; ?>">
                                                    <?php echo $btnText; ?>
                                                </a>
                                            <?php elseif ($status === 'active'): ?>
                                                <a href="index.php?action=attempt_exam&id=<?php echo $exam['id']; ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg <?php echo $btnClass; ?>">
                                                    <?php echo $btnText; ?>
                                                </a>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg <?php echo $btnClass; ?>">
                                                    <?php echo $btnText; ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Notices Section -->
            <div class="glass-card rounded-2xl overflow-hidden relative">
                <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-blue-100 rounded-full opacity-50"></div>
                
                <div class="relative z-10 px-6 py-5 border-b border-gray-100/50">
                    <h3 class="text-lg leading-6 font-bold text-gray-900">Recent Department Notices</h3>
                </div>
                
                <div class="relative z-10 px-6 py-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php
                        require_once __DIR__ . '/../../Models/Notice.php';
                        $noticeModel = new Notice($conn);
                        $notices = $noticeModel->getAllByStatus('teacher_published');

                        // Get read receipts (hacky but quick)
                        $studentId = currentUser();
                        $readQuery = "SELECT notice_id FROM read_receipts WHERE student_id = $studentId";
                        $readResult = $conn->query($readQuery);
                        $readIds = [];
                        while ($row = $readResult->fetch_assoc()) {
                            $readIds[] = $row['notice_id'];
                        }

                        if (empty($notices)): ?>
                            <div class="col-span-2 text-gray-500 text-sm">No notices available.</div>
                        <?php else:
                            foreach (array_slice($notices, 0, 4) as $notice):
                                $isRead = in_array($notice['id'], $readIds);
                                ?>
                                <a href="index.php?action=view_notice&id=<?php echo $notice['id']; ?>" class="block group">
                                    <div class="bg-white rounded-xl p-4 shadow-sm border <?php echo $isRead ? 'border-gray-100' : 'border-blue-300 ring-1 ring-blue-100'; ?> hover:shadow-md transition">
                                        <div class="flex justify-between items-start mb-2">
                                            <h4 class="text-sm font-bold text-gray-900 group-hover:text-blue-600 line-clamp-1">
                                                <?php echo htmlspecialchars($notice['title']); ?>
                                            </h4>
                                            <?php if (!$isRead): ?>
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-blue-100 text-blue-800 ml-2">New</span>
                                            <?php endif; ?>
                                        </div>
                                        <p class="text-xs text-gray-500 line-clamp-2"><?php echo htmlspecialchars(strip_tags($notice['content'])); ?></p>
                                        <p class="text-[10px] text-gray-400 mt-3"><?php echo date('M d, Y', strtotime($notice['created_at'])); ?></p>
                                    </div>
                                </a>
                            <?php endforeach; endif; ?>
                    </div>
                </div>
            </div>
            
        </div>
        
        <!-- Sidebar Tracker Area (1/3 width) -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Academic Stats -->
            <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl shadow-lg p-6 text-white text-center">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 backdrop-blur-sm">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold">Academic Status</h3>
                <p class="text-blue-100 text-sm mt-1">Keep up the good work!</p>
                
                <div class="mt-6 pt-6 border-t border-white/20 grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-2xl font-black"><?php echo $avgScore ?? '--'; ?></p>
                        <p class="text-xs text-blue-200">Average Score</p>
                    </div>
                    <div>
                        <p class="text-2xl font-black"><?php echo $examsTaken ?? '--'; ?></p>
                        <p class="text-xs text-blue-200">Exams Taken</p>
                    </div>
                </div>
            </div>
            
            <a href="index.php?action=my_salary_certificates" class="block bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition flex items-center">
                <div class="rounded-full bg-pink-100 p-3 mr-4">
                    <svg class="h-6 w-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-900">My Requests</h4>
                    <p class="text-xs text-gray-500">View leaves and certificates</p>
                </div>
                <div class="ml-auto">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </div>
            </a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>