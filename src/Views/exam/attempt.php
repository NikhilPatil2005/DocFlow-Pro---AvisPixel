<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($exam['title']); ?> - Assessment Attempt</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
        .palette-btn.active { border: 2px solid #4f46e5; transform: scale(1.1); }
        .palette-btn.answered { background-color: #10b981; color: white; border-color: #10b981; }
        .palette-btn.review { background-color: #f59e0b; color: white; border-color: #f59e0b; }
        .palette-btn.not-answered { background-color: #ef4444; color: white; border-color: #ef4444; }
        .palette-btn.unvisited { background-color: white; color: #374151; border-color: #d1d5db; }
        
        .option-label { transition: all 0.2s; cursor: pointer; }
        .option-radio:checked + div { background-color: #e0e7ff; border-color: #6366f1; }
        .option-radio:checked + div .circle { background-color: #4f46e5; border-color: #4f46e5; }
        /* Prevent selection of text to avoid cheating easily */
        .noselect {
            -webkit-user-select: none; /* Safari */
            -ms-user-select: none; /* IE 10 and IE 11 */
            user-select: none; /* Standard syntax */
        }
    </style>
</head>
<body class="h-screen flex flex-col overflow-hidden noselect">

    <!-- Top Navigation Bar -->
    <nav class="bg-indigo-900 shadow-md z-10 sticky top-0">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Title & Branding -->
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center h-8 w-8 bg-white rounded-md justify-center">
                        <i class="fa-solid fa-graduation-cap text-indigo-900 text-lg"></i>
                    </div>
                    <div class="ml-4 flex flex-col">
                        <span class="text-white font-bold text-sm sm:text-lg truncate max-w-xs sm:max-w-md"><?php echo htmlspecialchars($exam['title']); ?></span>
                        <span class="text-indigo-200 text-xs">Student ID: <?php echo $_SESSION['user_id']; ?></span>
                    </div>
                </div>
                
                <!-- Timer Display -->
                <div class="flex items-center">
                    <div class="bg-indigo-800 rounded-lg border border-indigo-700 px-4 py-2 flex items-center shadow-inner">
                        <i class="fa-regular fa-clock text-indigo-300 mr-2 animate-pulse"></i>
                        <span id="timer-display" class="text-white font-mono text-xl font-bold tracking-wider">00:00:00</span>
                    </div>
                </div>
                
                <!-- Submit Action (Top Right optionally) -->
                <div class="flex items-center hidden sm:flex ml-4">
                     <form action="index.php?action=submit_exam_attempt" method="POST" id="submitForm" onsubmit="return confirm('Are you sure you want to submit the exam? Once submitted, you cannot resume.')">
                        <input type="hidden" name="attempt_id" value="<?php echo $attempt['id']; ?>">
                        <button type="submit" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-2 px-6 rounded shadow-md border-b-4 border-green-800 active:border-b-0 active:mt-1 transition-all flex items-center">
                            <i class="fa-solid fa-check-double mr-2"></i> Submit Test
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Workspace -->
    <div class="flex-1 flex overflow-hidden">
        
        <!-- Left Sidebar (Question Palette) hidden on small mobile if needed, but keeping simple flex structure -->
        <div class="w-64 md:w-80 bg-white border-r border-gray-200 flex flex-col shadow-xl z-20">
            <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Question Palette</h3>
                <span id="answered-count" class="text-xs bg-indigo-100 text-indigo-800 px-2 py-1 rounded-full font-bold">0 / <?php echo count($questions); ?></span>
            </div>
            
            <div class="p-4 overflow-y-auto flex-1">
                <div class="grid grid-cols-5 md:grid-cols-5 gap-2" id="palette-grid">
                    <?php foreach ($questions as $index => $q): 
                        $statusClass = 'unvisited';
                        if (isset($savedAnswers[$q['id']])) {
                            $statusClass = 'answered';
                        }
                    ?>
                        <button type="button" onclick="goToQuestion(<?php echo $index; ?>)" id="palette-btn-<?php echo $index; ?>" class="palette-btn <?php echo $statusClass; ?> h-10 w-10 text-xs font-bold rounded shadow-sm border flex items-center justify-center transition focus:outline-none">
                            <?php echo $index + 1; ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Legend Area -->
            <div class="p-4 border-t border-gray-200 bg-gray-50 text-xs space-y-2 font-medium text-gray-600">
                <div class="flex items-center"><div class="w-4 h-4 rounded bg-[#10b981] mr-2 shadow-sm border border-[#059669]"></div> Answered</div>
                <div class="flex items-center"><div class="w-4 h-4 rounded bg-[#ef4444] mr-2 shadow-sm border border-[#dc2626]"></div> Not Answered</div>
                <div class="flex items-center"><div class="w-4 h-4 rounded bg-white mr-2 border border-gray-300"></div> Not Visited</div>
                <div class="flex items-center"><div class="w-4 h-4 rounded bg-[#f59e0b] mr-2 shadow-sm border border-[#d97706]"></div> Marked for Review</div>
            </div>
        </div>

        <!-- Main Content Area (Question Display) -->
        <div class="flex-1 flex flex-col relative bg-gray-50">
            <div class="flex-1 overflow-y-auto p-4 sm:p-8 relative" id="question-container">
                <!-- Question views injected by JS -->
                <?php foreach ($questions as $index => $q): 
                    $savedOpt = $savedAnswers[$q['id']] ?? null;
                ?>
                    <div id="quest-view-<?php echo $index; ?>" style="display: none;" class="question-view" data-qid="<?php echo $q['id']; ?>">
                        <div class="flex justify-between items-center mb-6">
                            <span class="text-xl font-bold text-gray-900 border-b-2 border-indigo-500 pb-1 inline-block">Question <?php echo $index + 1; ?> of <?php echo count($questions); ?></span>
                            <span class="text-sm font-semibold bg-white px-3 py-1 border border-gray-200 rounded text-gray-600 shadow-sm">Marks: <?php echo $q['marks']; ?></span>
                        </div>
                        
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8 text-lg font-medium text-gray-800">
                            <?php echo nl2br(htmlspecialchars($q['question_text'])); ?>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php
                            $opts = ['A' => $q['option_a'], 'B' => $q['option_b'], 'C' => $q['option_c'], 'D' => $q['option_d']];
                            foreach ($opts as $key => $val):
                                $isChecked = ($savedOpt === $key) ? 'checked' : '';
                            ?>
                            <label class="option-label group w-full">
                                <input type="radio" name="q_<?php echo $q['id']; ?>" value="<?php echo $key; ?>" class="sr-only option-radio" onchange="saveAnswer(<?php echo $index; ?>, '<?php echo $key; ?>')" <?php echo $isChecked; ?>>
                                <div class="bg-white border-2 border-gray-200 rounded-xl p-4 flex items-center group-hover:bg-gray-50">
                                    <div class="circle w-6 h-6 mr-4 rounded-full border-2 border-gray-300 flex items-center justify-center flex-shrink-0 transition-colors"></div>
                                    <span class="font-bold text-gray-500 mr-2 w-6 font-mono"><?php echo $key; ?>.</span>
                                    <span class="text-gray-700"><?php echo htmlspecialchars($val); ?></span>
                                </div>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Bottom Action Bar -->
            <div class="bg-white border-t border-gray-200 p-4 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] z-20">
                <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0">
                    <div class="flex space-x-3 w-full sm:w-auto overflow-x-auto pb-1 sm:pb-0">
                        <button type="button" onclick="navPrev()" id="btn-prev" class="bg-white border border-gray-300 text-gray-700 font-medium py-2 px-6 rounded shadow-sm hover:bg-gray-50 transition flex-shrink-0">
                            <i class="fa-solid fa-arrow-left mr-2"></i> Previous
                        </button>
                        <button type="button" onclick="markReview()" id="btn-review" class="bg-amber-100 border border-amber-300 text-amber-800 font-medium py-2 px-6 rounded shadow-sm hover:bg-amber-200 transition flex-shrink-0">
                            <i class="fa-regular fa-bookmark mr-2"></i> Mark for Review
                        </button>
                    </div>
                    
                    <div class="flex space-x-3 w-full sm:w-auto">
                        <button type="button" onclick="clearSelection()" class="bg-white border border-gray-300 text-gray-700 font-medium py-2 px-6 rounded shadow-sm hover:bg-gray-50 transition flex-shrink-0 text-sm">
                            Clear Response
                        </button>
                        <button type="button" onclick="navNext()" id="btn-next" class="bg-indigo-600 border border-transparent text-white font-medium py-2 px-8 rounded shadow-sm hover:bg-indigo-700 transition flex items-center justify-center flex-1 sm:flex-initial">
                            Save & Next <i class="fa-solid fa-arrow-right ml-2 text-indigo-200"></i>
                        </button>
                    </div>
                    
                    <!-- Mobile submit button -->
                    <div class="sm:hidden w-full mt-4 flex justify-end">
                        <button type="button" onclick="document.getElementById('submitForm').submit()" class="bg-green-600 text-white font-medium py-2 px-6 rounded w-full">
                            Submit Test
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Saving indicator popup -->
            <div id="save-indicator" class="fixed bottom-24 right-8 bg-gray-900 border border-gray-700 text-white px-4 py-2 rounded-lg shadow-2xl opacity-0 transform translate-y-4 transition-all duration-300 flex items-center z-50 pointer-events-none">
                <i class="fa-solid fa-spinner fa-spin mr-3 text-indigo-400"></i>
                <span class="font-medium text-sm tracking-wide">Saving response...</span>
            </div>
        </div>
    </div>

    <script>
        const totalQuestions = <?php echo count($questions); ?>;
        const attemptId = <?php echo $attempt['id']; ?>;
        let currentIndex = 0;
        let timerSeconds = <?php echo $timerSeconds; ?>;
        
        // State Array: answers[idx] = selected option, reviews[idx] = true/false
        let answers = [];
        let reviews = [];
        let visited = [];
        
        <?php foreach($questions as $index => $q): 
            $val = isset($savedAnswers[$q['id']]) ? "'".$savedAnswers[$q['id']]."'" : "null";
        ?>
            answers[<?php echo $index; ?>] = <?php echo $val; ?>;
            reviews[<?php echo $index; ?>] = false;
            visited[<?php echo $index; ?>] = <?php echo isset($savedAnswers[$q['id']]) ? "true" : "false"; ?>;
        <?php endforeach; ?>
        
        // Initialization
        window.onload = function() {
            startTimer();
            goToQuestion(0);
            updatePalette();
            updateCounts();
        };
        
        function updatePalette() {
            for(let i=0; i<totalQuestions; i++) {
                let btn = document.getElementById('palette-btn-' + i);
                if (!btn) continue;
                
                // Clear state classes
                btn.classList.remove('active', 'answered', 'review', 'not-answered', 'unvisited');
                
                if (i === currentIndex) {
                    btn.classList.add('active');
                }
                
                if (reviews[i]) {
                    btn.classList.add('review');
                } else if (answers[i]) {
                    btn.classList.add('answered');
                } else if (visited[i]) {
                    btn.classList.add('not-answered');
                } else {
                    btn.classList.add('unvisited');
                }
            }
            updateCounts();
        }

        function updateCounts() {
            let answered = answers.filter(a => a !== null).length;
            document.getElementById('answered-count').innerText = answered + " / " + totalQuestions;
        }
        
        function goToQuestion(index) {
            // Mark current as visited if not already
            if (currentIndex !== -1 && !visited[currentIndex]) {
                visited[currentIndex] = true;
            }
            
            // Hide all
            for(let i=0; i<totalQuestions; i++) {
                const el = document.getElementById('quest-view-' + i);
                if (el) el.style.display = 'none';
            }
            
            // Show target
            currentIndex = index;
            const targetEl = document.getElementById('quest-view-' + currentIndex);
            if (targetEl) targetEl.style.display = 'block';
            
            visited[currentIndex] = true;
            
            // Update button states
            document.getElementById('btn-prev').disabled = (currentIndex === 0);
            document.getElementById('btn-prev').className = (currentIndex === 0) ? "bg-gray-100 border border-gray-200 text-gray-400 font-medium py-2 px-6 rounded cursor-not-allowed flex-shrink-0" : "bg-white border border-gray-300 text-gray-700 font-medium py-2 px-6 rounded shadow-sm hover:bg-gray-50 transition flex-shrink-0";
            
            if (currentIndex === totalQuestions - 1) {
                document.getElementById('btn-next').innerHTML = 'Save & Finish <i class="fa-solid fa-flag-checkered ml-2 text-white"></i>';
                document.getElementById('btn-next').className = 'bg-green-600 border border-transparent text-white font-medium py-2 px-8 rounded shadow-sm hover:bg-green-700 transition flex items-center justify-center flex-1 sm:flex-initial';
            } else {
                document.getElementById('btn-next').innerHTML = 'Save & Next <i class="fa-solid fa-arrow-right ml-2 text-indigo-200"></i>';
                document.getElementById('btn-next').className = 'bg-indigo-600 border border-transparent text-white font-medium py-2 px-8 rounded shadow-sm hover:bg-indigo-700 transition flex items-center justify-center flex-1 sm:flex-initial';
            }
            
            updatePalette();
        }
        
        function navNext() {
            if (currentIndex < totalQuestions - 1) {
                goToQuestion(currentIndex + 1);
            } else {
                if(confirm("You have reached the end of the test. Do you want to submit?")) {
                    document.getElementById('submitForm').submit();
                }
            }
        }
        
        function navPrev() {
            if (currentIndex > 0) goToQuestion(currentIndex - 1);
        }
        
        function markReview() {
            reviews[currentIndex] = !reviews[currentIndex];
            updatePalette();
        }
        
        function clearSelection() {
            const div = document.getElementById('quest-view-' + currentIndex);
            const qid = div.getAttribute('data-qid');
            
            const radios = div.querySelectorAll('input[type="radio"]');
            radios.forEach(r => r.checked = false);
            
            if (answers[currentIndex] !== null) {
                answers[currentIndex] = null;
                // Also remove via AJAX
                showSaveIndicator();
                fetch('index.php?action=save_exam_answer', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `attempt_id=${attemptId}&question_id=${qid}&selected_option=`
                }).then(() => hideSaveIndicator()).catch(e => { console.error(e); hideSaveIndicator(); });
            }
            
            updatePalette();
        }
        
        function saveAnswer(index, option) {
            answers[index] = option;
            reviews[index] = false; // clear review if answered newly
            updatePalette();
            
            // AJAX Save
            const qid = document.getElementById('quest-view-' + index).getAttribute('data-qid');
            
            showSaveIndicator();
            fetch('index.php?action=save_exam_answer', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `attempt_id=${attemptId}&question_id=${qid}&selected_option=${option}`
            }).then(resp => resp.json())
              .then(data => { hideSaveIndicator(); })
              .catch(err => { console.error(err); hideSaveIndicator(); });
        }
        
        // Visual Indicators for Saving
        let saveTimeout;
        function showSaveIndicator() {
            const indicator = document.getElementById('save-indicator');
            indicator.classList.remove('opacity-0', 'translate-y-4');
            clearTimeout(saveTimeout);
        }
        
        function hideSaveIndicator() {
            saveTimeout = setTimeout(() => {
                const indicator = document.getElementById('save-indicator');
                indicator.classList.add('opacity-0', 'translate-y-4');
            }, 800);
        }
        
        // Timer Logic
        function startTimer() {
            const display = document.getElementById('timer-display');
            const nav = document.querySelector('nav');
            
            const timerInterval = setInterval(function() {
                if (timerSeconds <= 0) {
                    clearInterval(timerInterval);
                    display.innerText = "00:00:00";
                    alert("Time's up! Your exam will be auto-submitted.");
                    document.getElementById('submitForm').submit();
                    return;
                }
                
                timerSeconds--;
                
                // Format
                const h = Math.floor(timerSeconds / 3600);
                const m = Math.floor((timerSeconds % 3600) / 60);
                const s = timerSeconds % 60;
                
                display.innerText = 
                    (h < 10 ? "0" + h : h) + ":" + 
                    (m < 10 ? "0" + m : m) + ":" + 
                    (s < 10 ? "0" + s : s);
                    
                // Visual warnings
                if (timerSeconds < 300) { // last 5 min
                    display.classList.add('text-red-300');
                    display.parentNode.classList.replace('border-indigo-700', 'border-red-500');
                    display.parentNode.classList.replace('bg-indigo-800', 'bg-red-900');
                }
            }, 1000);
        }
        
        // Anti-Cheat: Tab Switch Detection
        let warningCount = 0;
        document.addEventListener("visibilitychange", () => {
            if (document.hidden) {
                // User switched tabs
                warningCount++;
                if (warningCount > 3) {
                    alert('Exam auto-submitted due to multiple tab-switching violations.');
                    document.getElementById('submitForm').submit();
                } else {
                    alert('WARNING ' + warningCount + '/3: Please do not change tabs or minimize the window during the exam. Doing this ' + (4 - warningCount) + ' more times will auto-submit your exam.');
                }
            }
        });
    </script>
</body>
</html>
