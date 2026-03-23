<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-5">
            <h2 class="text-xl font-bold text-white">Create New Assessment</h2>
            <p class="text-blue-100 text-sm mt-1">Configure details for the MCQ examination</p>
        </div>
        
        <form action="index.php?action=store_exam" method="POST" class="px-6 py-6 space-y-6">
            <input type="hidden" name="department_id" value="<?php echo htmlspecialchars($department_id); ?>">
            
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Exam Title</label>
                <div class="mt-1">
                    <input type="text" name="title" id="title" required class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 border px-3" placeholder="e.g. Midterm Computer Networks">
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description / Instructions</label>
                <div class="mt-1">
                    <textarea name="description" id="description" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 border px-3" placeholder="Enter instructions for students..."></textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700">Start Date & Time</label>
                    <div class="mt-1">
                        <input type="datetime-local" name="start_time" id="start_time" required class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 border px-3">
                    </div>
                </div>

                <div>
                    <label for="end_time" class="block text-sm font-medium text-gray-700">End Date & Time</label>
                    <div class="mt-1">
                        <input type="datetime-local" name="end_time" id="end_time" required class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 border px-3">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Students must submit before this time.</p>
                </div>
            </div>

            <div>
                <label for="duration" class="block text-sm font-medium text-gray-700">Duration (Minutes)</label>
                <div class="mt-1 relative rounded-md shadow-sm w-1/3">
                    <input type="number" name="duration" id="duration" required min="1" class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 border px-3" placeholder="60">
                </div>
            </div>

            <div class="pt-4 border-t border-gray-200 flex justify-end space-x-3">
                <a href="index.php?action=teacher_exams" class="bg-white py-2 px-4 border border-gray-300 py-2 px-4 shadow-sm text-sm font-medium rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Create & Continue to Questions
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
