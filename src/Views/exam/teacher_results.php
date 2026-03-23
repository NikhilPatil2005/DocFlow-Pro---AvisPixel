<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="px-4 py-8 sm:px-0 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Exam Results</h1>
            <p class="mt-1 text-sm text-gray-500">Viewing attempts for: <span class="font-semibold text-indigo-600"><?php echo htmlspecialchars($exam['title']); ?></span></p>
        </div>
        <div class="flex space-x-3">
            <a href="index.php?action=teacher_exams" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Back to Exams</a>
            <button onclick="window.print()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Report
            </button>
        </div>
    </div>

    <!-- Stats summary -->
    <?php
        $totalAttempts = count($attempts);
        $totalScores = array_column($attempts, 'score');
        $avgScore = $totalAttempts > 0 ? array_sum($totalScores) / $totalAttempts : 0;
        $maxScore = $totalAttempts > 0 ? max($totalScores) : 0;
    ?>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
        <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-100">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Total Submissions</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900"><?php echo $totalAttempts; ?></dd>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-100">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Average Score</dt>
                <dd class="mt-1 text-3xl font-semibold text-indigo-600"><?php echo number_format($avgScore, 2); ?></dd>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-100">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Highest Score</dt>
                <dd class="mt-1 text-3xl font-semibold text-green-600"><?php echo $maxScore; ?></dd>
            </div>
        </div>
    </div>

    <!-- Results Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200">
        <?php if (empty($attempts)): ?>
            <div class="p-10 text-center text-gray-500">No students have submitted this exam yet.</div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Roll No</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Percentage</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted At</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($attempts as $index => $attempt): 
                            $percentage = $attempt['total_marks'] > 0 ? ($attempt['score'] / $attempt['total_marks']) * 100 : 0;
                            // Rank coloring
                            $rankColor = "text-gray-500";
                            $bgVal = "";
                            if ($index == 0) { $rankColor = "text-yellow-600 font-bold text-lg"; $bgVal = "bg-yellow-50"; }
                            elseif ($index == 1) { $rankColor = "text-gray-400 font-bold text-lg"; $bgVal = "bg-gray-50"; }
                            elseif ($index == 2) { $rankColor = "text-orange-500 font-bold text-lg"; $bgVal = "bg-orange-50"; }
                        ?>
                            <tr class="<?php echo $bgVal; ?> hover:bg-blue-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm <?php echo $rankColor; ?>">
                                    #<?php echo $index + 1; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($attempt['student_name']); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo htmlspecialchars($attempt['student_roll']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-full bg-blue-100 text-blue-800">
                                        <?php echo $attempt['score']; ?> / <?php echo $attempt['total_marks']; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex items-center justify-center">
                                        <span class="<?php echo $percentage >= 40 ? 'text-green-600' : 'text-red-600'; ?>">
                                            <?php echo number_format($percentage, 1); ?>%
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                                    <?php echo date('M d, Y H:i:s', strtotime($attempt['end_time'])); ?>
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
