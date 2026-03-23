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
        background: linear-gradient(90deg, #1d4ed8, #9333ea);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>

<div class="px-4 py-6 sm:px-0">
    <!-- Header Section -->
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight gradient-text">Teacher Workspace</h1>
            <p class="mt-2 text-sm text-gray-600 font-medium">Manage your educational content, examinations, and student progress.</p>
        </div>
        <div class="flex space-x-3">
            <a href="index.php?action=create_exam" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 transform hover:scale-105">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create New Assessment
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="glass-card rounded-2xl p-6 relative overflow-hidden group">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-blue-100 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700 ease-in-out"></div>
            <div class="relative z-10">
                <dt class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Total Assessments</dt>
                <dd class="text-4xl font-extrabold text-gray-900"><?php echo $totalExams; ?></dd>
            </div>
            <div class="mt-4 relative z-10">
                <a href="index.php?action=teacher_exams" class="text-sm font-semibold text-blue-600 hover:text-blue-800 flex items-center">
                    View full archive <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>

        <div class="glass-card rounded-2xl p-6 relative overflow-hidden group">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-green-100 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700 ease-in-out"></div>
            <div class="relative z-10">
                <dt class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Active / Published</dt>
                <dd class="text-4xl font-extrabold text-gray-900"><?php echo $activeExams; ?></dd>
            </div>
            <div class="mt-4 relative z-10">
                <span class="text-sm text-green-600 bg-green-100 px-2 py-1 rounded-full font-medium border border-green-200">Live for students</span>
            </div>
        </div>

        <div class="glass-card rounded-2xl p-6 relative overflow-hidden group">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-purple-100 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700 ease-in-out"></div>
            <div class="relative z-10">
                <dt class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Pending Approvals</dt>
                <dd class="text-4xl font-extrabold text-gray-900">0</dd>
            </div>
            <div class="mt-4 relative z-10">
                <a href="index.php?action=notice_approvals" class="text-sm font-semibold text-purple-600 hover:text-purple-800 flex items-center">
                    Check notices <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Analytics Section -->
    <div class="mb-8">
        <h3 class="text-xl font-bold text-gray-900 mb-4 px-1 border-b-2 border-gray-200 pb-2 inline-block">Global Performance Analytics</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-2">
            <div class="bg-white rounded-2xl p-6 border-l-4 border-indigo-500 shadow-sm hover:shadow-md transition">
                <dt class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Total Submissions</dt>
                <dd class="text-3xl font-extrabold text-indigo-700"><?php echo $analytics['total_attempts'] ?? 0; ?></dd>
            </div>
            <div class="bg-white rounded-2xl p-6 border-l-4 border-teal-500 shadow-sm hover:shadow-md transition">
                <dt class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Average Global Score</dt>
                <dd class="text-3xl font-extrabold text-teal-700"><?php echo number_format($analytics['avg_score'] ?? 0, 1); ?></dd>
            </div>
            <div class="bg-white rounded-2xl p-6 border-l-4 border-orange-500 shadow-sm hover:shadow-md transition">
                <dt class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Highest Global Score</dt>
                <dd class="text-3xl font-extrabold text-orange-700"><?php echo $analytics['highest_score'] ?? 0; ?></dd>
            </div>
        </div>
    </div>

    <!-- Recent Exams Section -->
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-white/50">
            <h3 class="text-lg leading-6 font-bold text-gray-900">Recent Assignments & Exams</h3>
            <a href="index.php?action=teacher_exams" class="text-sm font-medium text-blue-600 hover:text-blue-500">View all</a>
        </div>
        
        <?php if (empty($exams)): ?>
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No exams created</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new assessment.</p>
                <div class="mt-6">
                    <a href="index.php?action=create_exam" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create Exam
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Title</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Schedule</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white/30 divide-y divide-gray-100">
                        <?php foreach ($exams as $exam): ?>
                            <tr class="hover:bg-white/60 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-lg bg-gradient-to-br from-blue-100 to-indigo-100 text-blue-600">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900"><?php echo htmlspecialchars($exam['title']); ?></div>
                                            <div class="text-sm text-gray-500"><?php echo $exam['question_count']; ?> questions • <?php echo $exam['duration_minutes']; ?> mins</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?php echo date('M d, Y h:i A', strtotime($exam['start_time'])); ?></div>
                                    <div class="text-xs text-gray-500">to <?php echo date('M d, Y h:i A', strtotime($exam['end_time'])); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($exam['status'] === 'published'): ?>
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">Published</span>
                                    <?php elseif ($exam['status'] === 'draft'): ?>
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">Draft</span>
                                    <?php else: ?>
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-200">Completed</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <a href="index.php?action=manage_exam_questions&id=<?php echo $exam['id']; ?>" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded shadow-sm text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                                        Questions
                                    </a>
                                    <a href="index.php?action=teacher_exam_results&id=<?php echo $exam['id']; ?>" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded shadow-sm text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                                        Results
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>