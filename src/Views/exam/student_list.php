<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="px-4 py-8 sm:px-0 max-w-7xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight text-indigo-900">My Examinations</h1>
        <p class="mt-2 text-sm text-gray-600 font-medium">View all upcoming, active, and past assessments requested by your department.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (empty($exams)): ?>
            <div class="col-span-3 text-center py-12 bg-white rounded-2xl shadow-sm border border-gray-100">
                <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                <h3 class="text-lg font-bold text-gray-900 mb-1">No Exams Scheduled</h3>
                <p class="text-gray-500 text-sm">There are currently no examinations available for your department.</p>
            </div>
        <?php else: ?>
            <?php foreach ($exams as $exam): 
                $now = new DateTime();
                $start = new DateTime($exam['start_time']);
                $end = new DateTime($exam['end_time']);
                
                // Get Attempt info if any
                require_once __DIR__ . '/../../Models/Exam.php';
                $examModel = new Exam($conn);
                $attempt = $examModel->getAttempt($exam['id'], $_SESSION['user_id']);
                
                $status = 'upcoming';
                $statusText = 'Upcoming';
                $statusColor = 'bg-yellow-100 text-yellow-800 border-yellow-200';
                $actionBtn = '<span class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-gray-400 bg-gray-100 cursor-not-allowed">Not Started Yet</span>';
                
                if ($attempt && $attempt['is_submitted']) {
                    $status = 'completed';
                    $statusText = 'Completed';
                    $statusColor = 'bg-blue-100 text-blue-800 border-blue-200';
                    $actionBtn = '<a href="index.php?action=student_exam_result&id='.$attempt['id'].'" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-blue-700 bg-blue-100 hover:bg-blue-200 transition">View Result</a>';
                } elseif ($now > $end) {
                    $status = 'ended';
                    $statusText = 'Time Over';
                    $statusColor = 'bg-red-100 text-red-800 border-red-200';
                    $actionBtn = '<span class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-red-400 bg-red-50 cursor-not-allowed">Missed</span>';
                } elseif ($now >= $start && $now <= $end) {
                    $status = 'active';
                    $statusText = 'Active Now';
                    $statusColor = 'bg-green-100 text-green-800 border-green-200 animate-pulse';
                    $btnText = $attempt ? 'Resume Exam' : 'Start Exam';
                    $actionBtn = '<a href="index.php?action=attempt_exam&id='.$exam['id'].'" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 shadow-md transform hover:-translate-y-0.5 transition">'.$btnText.'</a>';
                }
            ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-lg transition-shadow duration-300">
                    <div class="px-6 py-5 flex-grow">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border <?php echo $statusColor; ?>">
                                    <?php echo $statusText; ?>
                                </span>
                                <?php if (isset($exam['created_at']) && (new DateTime())->diff(new DateTime($exam['created_at']))->days <= 2): ?>
                                    <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800 uppercase animate-pulse">New</span>
                                <?php endif; ?>
                            </div>
                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-md font-medium">
                                <?php echo $exam['question_count']; ?> Qs
                            </span>
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2"><?php echo htmlspecialchars($exam['title']); ?></h3>
                        <p class="text-sm text-gray-500 mb-4 line-clamp-2"><?php echo htmlspecialchars($exam['description'] ?? 'No description provided.'); ?></p>
                        
                        <div class="space-y-3 mt-auto">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="h-5 w-5 mr-3 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span class="font-medium">Duration:</span> <span class="ml-1"><?php echo $exam['duration_minutes']; ?> Minutes</span>
                            </div>
                            <div class="flex items-start text-sm text-gray-600">
                                <svg class="h-5 w-5 mr-3 text-indigo-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                <div>
                                    <div class="font-medium">Window:</div>
                                    <div class="text-xs mt-1 text-gray-500"><?php echo date('M d, Y h:i A', strtotime($exam['start_time'])); ?></div>
                                    <div class="text-xs text-gray-400">to <?php echo date('M d, Y h:i A', strtotime($exam['end_time'])); ?></div>
                                </div>
                            </div>
                            <div class="flex items-center text-sm text-gray-600 border-t border-gray-100 pt-3 mt-3">
                                <svg class="h-5 w-5 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                <span class="text-xs">By <?php echo htmlspecialchars($exam['teacher_name']); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 mt-auto">
                        <?php echo $actionBtn; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
