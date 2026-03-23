<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="px-4 py-6 sm:px-0 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="bg-white px-6 py-5 border-b border-gray-200 rounded-lg shadow-sm mb-6 flex justify-between items-center">
        <div>
            <h3 class="text-lg leading-6 font-bold text-gray-900">Manage Questions</h3>
            <p class="mt-1 text-sm text-gray-500">Exam: <span class="font-semibold text-indigo-600"><?php echo htmlspecialchars($exam['title']); ?></span></p>
        </div>
        <div class="flex space-x-3">
            <a href="index.php?action=teacher_exams" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">Back to Exams</a>
            <?php if ($exam['status'] === 'draft' && count($questions) > 0): ?>
                <a href="index.php?action=publish_exam&id=<?php echo $exam['id']; ?>" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" onclick="return confirm('Publishing will make this exam visible to students. Continue?')">
                    Publish Exam
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Current Questions List -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow sm:rounded-lg border border-gray-100">
                <div class="px-4 py-5 border-b border-gray-200 sm:px-6 flex justify-between">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Existing Questions (<?php echo count($questions); ?>)</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        Total Marks: <?php echo array_sum(array_column($questions, 'marks')); ?>
                    </span>
                </div>
                
                <?php if (empty($questions)): ?>
                    <div class="p-12 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        No questions added yet. Use the form on the right to add some.
                    </div>
                <?php else: ?>
                    <ul class="divide-y divide-gray-200">
                        <?php foreach($questions as $index => $q): ?>
                            <li class="p-6 hover:bg-gray-50 transition">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="text-sm font-bold text-gray-900 w-full mb-2">
                                        <span class="text-indigo-600 mr-2">Q<?php echo $index + 1; ?>.</span>
                                        <?php echo nl2br(htmlspecialchars($q['question_text'])); ?>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold bg-gray-100 text-gray-800 ml-4 border border-gray-200">
                                        <?php echo $q['marks']; ?> Marks
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                    <div class="p-3 rounded-md border <?php echo $q['correct_option'] === 'A' ? 'bg-green-50 border-green-200 font-medium text-green-800' : 'bg-white border-gray-200 text-gray-600'; ?>">
                                        <span class="font-bold mr-2 text-gray-400">A</span> <?php echo htmlspecialchars($q['option_a']); ?>
                                    </div>
                                    <div class="p-3 rounded-md border <?php echo $q['correct_option'] === 'B' ? 'bg-green-50 border-green-200 font-medium text-green-800' : 'bg-white border-gray-200 text-gray-600'; ?>">
                                        <span class="font-bold mr-2 text-gray-400">B</span> <?php echo htmlspecialchars($q['option_b']); ?>
                                    </div>
                                    <div class="p-3 rounded-md border <?php echo $q['correct_option'] === 'C' ? 'bg-green-50 border-green-200 font-medium text-green-800' : 'bg-white border-gray-200 text-gray-600'; ?>">
                                        <span class="font-bold mr-2 text-gray-400">C</span> <?php echo htmlspecialchars($q['option_c']); ?>
                                    </div>
                                    <div class="p-3 rounded-md border <?php echo $q['correct_option'] === 'D' ? 'bg-green-50 border-green-200 font-medium text-green-800' : 'bg-white border-gray-200 text-gray-600'; ?>">
                                        <span class="font-bold mr-2 text-gray-400">D</span> <?php echo htmlspecialchars($q['option_d']); ?>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right: Add New Question Form -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow sm:rounded-lg border border-gray-100 sticky top-6">
                <div class="px-4 py-5 border-b border-gray-200 sm:px-6 bg-gray-50/50">
                    <h3 class="text-base leading-6 font-bold text-gray-900">Add New Question</h3>
                </div>
                
                <?php if ($exam['status'] !== 'draft'): ?>
                    <div class="p-6 text-sm text-yellow-700 bg-yellow-50 rounded-b-lg">
                        This exam is already published or completed. You cannot add more questions.
                    </div>
                <?php else: ?>
                    <form action="index.php?action=store_exam_question" method="POST" class="p-6 space-y-4">
                        <input type="hidden" name="exam_id" value="<?php echo $exam['id']; ?>">
                        <input type="hidden" name="question_order" value="<?php echo count($questions) + 1; ?>">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Question Text</label>
                            <textarea name="question_text" rows="3" required class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md border px-3 py-2"></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Option A</label>
                            <input type="text" name="option_a" required class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md border px-3 py-2">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Option B</label>
                            <input type="text" name="option_b" required class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md border px-3 py-2">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Option C</label>
                            <input type="text" name="option_c" required class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md border px-3 py-2">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Option D</label>
                            <input type="text" name="option_d" required class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md border px-3 py-2">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Correct Option</label>
                                <select name="correct_option" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md border">
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Marks</label>
                                <input type="number" name="marks" value="1" min="1" required class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md border pl-3 py-2">
                            </div>
                        </div>
                        
                        <div class="pt-2">
                            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Add Question
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
