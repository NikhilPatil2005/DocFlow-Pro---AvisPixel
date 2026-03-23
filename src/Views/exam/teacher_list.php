<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="px-4 py-6 sm:px-0">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Assigned Examinations</h1>
            <p class="mt-1 text-sm text-gray-500">A comprehensive list of all assessments you have created.</p>
        </div>
        <a href="index.php?action=create_exam" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Create Exam
        </a>
    </div>

    <!-- Replace standard table with glass UI styling or simple white card -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200">
        <?php if (empty($exams)): ?>
            <div class="p-10 text-center text-gray-500">No exams found. Click "Create Exam" to schedule a new one.</div>
        <?php else: ?>
            <ul class="divide-y divide-gray-200">
                <?php foreach ($exams as $exam): ?>
                    <li class="hover:bg-gray-50 transition-colors">
                        <div class="px-6 py-5 flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-indigo-600 truncate"><?php echo htmlspecialchars($exam['title']); ?></h3>
                                    <div class="ml-2 flex-shrink-0 flex">
                                        <?php if ($exam['status'] === 'published'): ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">Published</span>
                                        <?php else: ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">Draft</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="mt-2 flex">
                                    <div class="flex items-center text-sm text-gray-500 mr-6">
                                        <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <?php echo $exam['duration_minutes']; ?> mins
                                    </div>
                                    <div class="flex items-center text-sm text-gray-500 mr-6">
                                        <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <?php echo date('M d, Y H:i', strtotime($exam['start_time'])); ?> to <?php echo date('M d, Y H:i', strtotime($exam['end_time'])); ?>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                        </svg>
                                        <?php echo htmlspecialchars($exam['department_name']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="ml-6 flex items-center space-x-3">
                                <?php if ($exam['status'] === 'draft'): ?>
                                    <a href="index.php?action=publish_exam&id=<?php echo $exam['id']; ?>" class="text-sm font-medium text-blue-600 hover:text-blue-900 border border-blue-200 bg-blue-50 px-3 py-1.5 rounded-md hover:bg-blue-100 transition" onclick="return confirm('Are you sure you want to publish? Students will be notified and can take it once the start time is reached.')">Publish</a>
                                <?php endif; ?>
                                <a href="index.php?action=manage_exam_questions&id=<?php echo $exam['id']; ?>" class="text-sm font-medium text-indigo-600 hover:text-indigo-900 border border-indigo-200 bg-indigo-50 px-3 py-1.5 rounded-md hover:bg-indigo-100 transition">Questions (<?php echo $exam['question_count']; ?>)</a>
                                <a href="index.php?action=teacher_exam_results&id=<?php echo $exam['id']; ?>" class="text-sm font-medium text-green-600 hover:text-green-900 border border-green-200 bg-green-50 px-3 py-1.5 rounded-md hover:bg-green-100 transition">Results</a>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
