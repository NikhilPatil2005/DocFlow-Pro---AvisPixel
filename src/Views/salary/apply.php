<?php
require_once __DIR__ . '/../../Models/User.php';
global $conn;
$userModel = new User($conn);
$userInfo = $userModel->getUserById($_SESSION['user_id']);

// Fetch department name
$deptName = "Not Assigned";
if ($userInfo['department_id']) {
    $deptResult = $conn->query("SELECT name FROM departments WHERE id = " . $userInfo['department_id']);
    if ($deptResult && $row = $deptResult->fetch_assoc()) {
        $deptName = $row['name'];
    }
}
$designation = $userInfo['designation'] ?: "__________";
$username = $userInfo['username'];
?>

<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div
    class="max-w-4xl mx-auto bg-white p-8 sm:p-12 shadow-md border border-gray-300 rounded-sm mt-6 font-serif text-gray-800">

    <!-- Form Header -->
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold uppercase underline underline-offset-4 tracking-wider mb-8">Salary Certificate
            Form</h1>
        <div class="text-left space-y-1 text-[15px]">
            <p>Form ID: DYPCET: Account/06</p>
            <br>
            <p>To,</p>
            <p>The Principal</p>
            <p>D. Y. Patil College of Engineering and Technology,</p>
            <p>Kasaba Bawada, Kolhapur.</p>
            <br>
            <p>Date:
                <?php echo date('d-m-Y'); ?>
            </p>
        </div>
    </div>

    <!-- Subject -->
    <div class="text-center font-bold underline underline-offset-4 mb-8 text-[15px]">
        Subject: Application for Salary Certificate
    </div>

    <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <!-- Form Body -->
    <form action="index.php?action=apply_salary" method="POST" class="space-y-8 text-[15px] leading-relaxed">

        <div>
            <p class="mb-4">Respected Sir,</p>
            <p class="mb-4 text-justify">
                I <span
                    class="font-semibold underline underline-offset-4 decoration-dotted decoration-gray-400 uppercase">
                    <?php echo htmlspecialchars($username); ?>
                </span>
                am working as
                <input type="text" name="designation"
                    value="<?php echo htmlspecialchars($designation === '__________' ? '' : $designation); ?>"
                    class="font-semibold border-0 border-b-2 border-dotted border-gray-400 focus:border-gray-800 focus:ring-0 uppercase bg-transparent w-48 text-center px-1 py-0 inline-block text-[15px] m-0"
                    placeholder="DESIGNATION" required>
                in department of <span
                    class="font-semibold underline underline-offset-4 decoration-dotted decoration-gray-400 uppercase">
                    <?php echo htmlspecialchars($deptName); ?>
                </span>.
            </p>
            <p class="mb-4 text-justify">
                I need salary certificate from
                <input type="date" name="from_date" required
                    class="border-0 border-b-2 border-dotted border-gray-400 focus:border-gray-800 focus:ring-0 uppercase bg-transparent w-40 text-center px-1 py-0 inline-block text-[15px] m-0">
                to
                <input type="date" name="to_date" required
                    class="border-0 border-b-2 border-dotted border-gray-400 focus:border-gray-800 focus:ring-0 uppercase bg-transparent w-40 text-center px-1 py-0 inline-block text-[15px] m-0">
                for
                <input type="text" name="purpose" required
                    class="border-0 border-b-2 border-dotted border-gray-400 focus:border-gray-800 focus:ring-0 bg-transparent w-64 px-1 py-0 inline-block text-[15px] m-0"
                    placeholder="purpose">
                purpose.
            </p>
            <p>I request you to kindly provide the same.</p>
        </div>

        <div class="flex justify-between pt-12">
            <div class="text-center w-48">
                <!-- Empty for Registrar Signature area if needed -->
            </div>
            <div class="text-center w-48">
                <span class="inline-block w-full border-b border-gray-400 mb-1"></span>
                <div class="text-sm italic text-gray-600">
                    Signature of staff
                </div>
            </div>
        </div>

        <div class="flex justify-start pt-8 pb-4">
            <div class="text-center w-48 font-bold">
                <span class="inline-block w-full border-b border-gray-400 mb-1"></span>
                <div class="text-sm">
                    Registrar
                </div>
            </div>
        </div>

        <div class="text-xs text-gray-600 space-y-1 italic mt-8 font-sans">
            <p>Note: Submit this duly signed form to Account Section.</p>
        </div>

        <!-- Submit Button -->
        <div class="pt-8 text-center no-print">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-8 rounded font-sans transition-colors shadow">
                Submit Request
            </button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>