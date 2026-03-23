<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="px-4 py-8 sm:px-0 max-w-4xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Assessment Result</h1>
        <a href="index.php?action=student_exams" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 flex items-center bg-indigo-50 px-3 py-1.5 rounded-lg">
            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg> Back to Exams
        </a>
    </div>

    <!-- Score Card -->
    <?php 
        $percentage = $examResult['total_marks'] > 0 ? ($examResult['score'] / $examResult['total_marks']) * 100 : 0; 
        $isPass = $percentage >= 40;
        
        $correctCount = 0;
        $incorrectCount = 0;
        $unattemptedCount = 0;
        foreach ($examResult['qa'] as $qa) {
            if ($qa['is_correct']) $correctCount++;
            elseif (is_null($qa['selected_option'])) $unattemptedCount++;
            else $incorrectCount++;
        }
    ?>
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 mb-8 transform transition-all">
        <div class="relative px-6 py-12 text-center <?php echo $isPass ? 'bg-gradient-to-br from-green-500 to-teal-600' : 'bg-gradient-to-br from-red-500 to-pink-600'; ?>">
            <div class="absolute inset-x-0 top-0 h-full overflow-hidden opacity-20 pointer-events-none">
                <svg viewBox="0 0 100 100" preserveAspectRatio="none" class="absolute h-full w-full">
                    <polygon fill="white" points="0,100 100,0 100,100"></polygon>
                </svg>
            </div>
            
            <h2 class="relative z-10 text-3xl font-extrabold text-white mb-2"><?php echo htmlspecialchars($examResult['title']); ?></h2>
            <p class="relative z-10 text-white/80 font-medium mb-8">Completed on <?php echo date('M d, Y \a\t h:i A', strtotime($examResult['end_time'])); ?></p>
            
            <div class="relative z-10 inline-flex items-center justify-center w-48 h-48 rounded-full border-8 border-white/30 bg-white/20 backdrop-blur-sm shadow-2xl">
                <div>
                    <span class="block text-5xl font-black text-white leading-none"><?php echo $examResult['score']; ?></span>
                    <span class="block text-sm font-bold text-white/80 mt-1 uppercase tracking-widest">Out of <?php echo $examResult['total_marks']; ?></span>
                </div>
            </div>
            
            <div class="relative z-10 mt-4 inline-block bg-white/20 backdrop-blur-md rounded-full px-6 py-2 text-white font-bold text-lg shadow-inner">
                <?php echo number_format($percentage, 1); ?>% - <?php echo $isPass ? 'PASSED 🏆' : 'FAILED ❌'; ?>
            </div>
            
            <div class="relative z-10 mt-8 max-w-sm mx-auto bg-black/10 rounded-xl p-4 backdrop-blur-md border border-white/10">
                <div class="flex justify-between text-xs font-bold text-white mb-3">
                    <span class="uppercase tracking-wider">Performance Metrics</span>
                    <span><?php echo count($examResult['qa']); ?> Qs</span>
                </div>
                <!-- Mini Progress Bar -->
                <?php $totalQs = count($examResult['qa']) ?: 1; ?>
                <div class="flex h-2 w-full bg-white/20 rounded-full overflow-hidden mb-3">
                    <div style="width: <?php echo ($correctCount / $totalQs) * 100; ?>%" class="bg-white"></div>
                    <div style="width: <?php echo ($incorrectCount / $totalQs) * 100; ?>%" class="bg-red-400"></div>
                </div>
                <div class="flex justify-between text-[11px] font-semibold text-white/90">
                    <span class="flex items-center"><div class="w-2.5 h-2.5 rounded-full bg-white mr-1.5 shadow-sm"></div> <?php echo $correctCount; ?> Correct</span>
                    <span class="flex items-center"><div class="w-2.5 h-2.5 rounded-full bg-red-400 mr-1.5 shadow-sm"></div> <?php echo $incorrectCount; ?> Incorrect</span>
                    <span class="flex items-center"><div class="w-2.5 h-2.5 rounded-full bg-white/30 mr-1.5 shadow-sm"></div> <?php echo $unattemptedCount; ?> Skipped</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Analysis -->
    <h3 class="text-lg font-bold text-gray-900 mb-4 px-1 border-b-2 border-gray-200 pb-2 inline-block">Detailed Review</h3>
    
    <div class="space-y-6">
        <?php foreach ($examResult['qa'] as $index => $qa): 
            $isCorrect = $qa['is_correct'];
            $notAnswered = is_null($qa['selected_option']);
        ?>
            <div class="bg-white rounded-xl shadow-sm border <?php echo $isCorrect ? 'border-green-200' : ($notAnswered ? 'border-gray-200' : 'border-red-200'); ?> overflow-hidden">
                <div class="px-5 py-4 <?php echo $isCorrect ? 'bg-green-50' : ($notAnswered ? 'bg-gray-50' : 'bg-red-50'); ?> border-b <?php echo $isCorrect ? 'border-green-100' : ($notAnswered ? 'border-gray-100' : 'border-red-100'); ?> flex justify-between items-start">
                    <div class="font-bold text-gray-900 flex-1 pr-4">
                        <span class="mr-2 text-gray-500">Q<?php echo $index + 1; ?>.</span> 
                        <?php echo nl2br(htmlspecialchars($qa['question_text'])); ?>
                    </div>
                    <div class="flex-shrink-0 text-sm font-bold <?php echo $isCorrect ? 'text-green-600' : 'text-gray-500'; ?>">
                        <?php echo $qa['marks_awarded']; ?> / <?php echo $qa['marks']; ?> Marks
                    </div>
                </div>
                
                <div class="px-5 py-5 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <?php 
                    $options = ['A' => $qa['option_a'], 'B' => $qa['option_b'], 'C' => $qa['option_c'], 'D' => $qa['option_d']];
                    foreach ($options as $key => $val): 
                        $isSelected = $qa['selected_option'] === $key;
                        $isActualCorrect = $qa['correct_option'] === $key;
                        
                        $optClass = "bg-white border-gray-200 text-gray-600";
                        $icon = "";
                        
                        // Highlighting logic
                        if ($isActualCorrect) {
                            $optClass = "bg-green-100 border-green-500 text-green-800 font-bold shadow-sm ring-1 ring-green-500";
                            $icon = '<svg class="h-5 w-5 text-green-600 absolute right-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                        } elseif ($isSelected && !$isActualCorrect) {
                            $optClass = "bg-red-50 border-red-300 text-red-800";
                            $icon = '<svg class="h-5 w-5 text-red-500 absolute right-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
                        }
                    ?>
                        <div class="relative p-3 rounded-lg border <?php echo $optClass; ?>">
                            <span class="font-bold mr-2 <?php echo $isActualCorrect ? 'text-green-700' : 'text-gray-400'; ?>"><?php echo $key; ?></span> 
                            <?php echo htmlspecialchars($val); ?>
                            <?php echo $icon; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
