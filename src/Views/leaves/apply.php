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
        <h1 class="text-2xl font-bold uppercase underline underline-offset-4 tracking-wider mb-8">On Duty (OD)</h1>
        <div class="text-left space-y-1 text-[15px]">
            <p>To,</p>
            <p>The Principal,</p>
            <p>D. Y. Patil College of Engineering and Technology,</p>
            <p>Kasaba Bawada, Kolhapur.</p>
        </div>
    </div>

    <!-- Subject -->
    <div class="text-center font-bold underline underline-offset-4 mb-8 text-[15px]">
        Subject: Application for On Duty (OD)
    </div>

    <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <!-- Form Body -->
    <form action="index.php?action=apply_leave" method="POST" class="space-y-8 text-[15px] leading-relaxed">

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
                Kindly allow me to avail On-duty leave as under –
            </p>
        </div>

        <!-- Table inputs -->
        <div class="overflow-x-auto border border-gray-800">
            <table class="w-full text-center border-collapse">
                <thead>
                    <tr class="border-b border-gray-800 divide-x divide-gray-800">
                        <th class="py-2 px-3 font-normal">Sr No</th>
                        <th class="py-2 px-3 font-normal w-32">Date</th>
                        <th class="py-2 px-3 font-normal w-32">Time (From)</th>
                        <th class="py-2 px-3 font-normal w-32">Time (To)</th>
                        <th class="py-2 px-3 font-normal">Venue</th>
                        <th class="py-2 px-3 font-normal">Reason</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    <tr class="divide-x divide-gray-800 group">
                        <td class="py-3 px-3">1</td>
                        <td class="py-3 px-3">
                            <input type="date" name="leave_date" required
                                class="w-full bg-transparent border-0 focus:ring-0 text-center font-sans text-sm p-0">
                        </td>
                        <td class="py-3 px-3">
                            <input type="time" name="time_from" required
                                class="w-full bg-transparent border-0 focus:ring-0 text-center font-sans text-sm p-0">
                        </td>
                        <td class="py-3 px-3">
                            <input type="time" name="time_to" required
                                class="w-full bg-transparent border-0 focus:ring-0 text-center font-sans text-sm p-0">
                        </td>
                        <td class="py-3 px-3">
                            <input type="text" name="venue" required
                                class="w-full bg-transparent border-0 focus:ring-0 font-sans text-sm p-0 text-center"
                                placeholder="Venue">
                        </td>
                        <td class="py-3 px-3">
                            <input type="text" name="reason" required
                                class="w-full bg-transparent border-0 focus:ring-0 font-sans text-sm p-0 text-center"
                                placeholder="Reason details">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="flex items-center space-x-2">
            <span>I have adjusted my workload with:</span>
            <input type="text" name="workload_adjusted_with" required
                class="flex-1 bg-transparent border-b border-gray-400 border-dotted focus:border-gray-800 focus:outline-none font-sans text-sm px-2 py-1"
                placeholder="Name of staff member">
        </div>

        <div class="flex justify-end pt-12 pr-8">
            <div class="text-center">
                <input type="text" name="signature" required
                    class="bg-transparent border-0 border-b border-gray-400 focus:border-gray-800 focus:ring-0 text-center font-sans text-[15px] px-2 py-1 w-56 italic"
                    placeholder="Type Name to Sign">
                <div class="text-sm italic text-gray-600 mt-1">
                    Digital Signature of Staff
                </div>
            </div>
        </div>

        <div class="pt-8 border-t border-dashed border-gray-400 mt-12 mb-8 relative">
            <span class="absolute -top-3 bg-white px-2 text-xs italic text-gray-500">For Office Use Only</span>
            <div class="space-y-6">
                <div>
                    <span class="font-bold">Forwarded by HoD –</span> <span
                        class="inline-block w-64 border-b border-gray-400"></span>
                    <span class="text-xs ml-2">(Attendance Certificate required after availing OD – Yes/No)</span>
                </div>
                <div>
                    <span class="font-bold">Approved by Principal -</span> <span
                        class="inline-block w-64 border-b border-gray-400"></span>
                </div>
            </div>
        </div>

        <div class="text-xs text-gray-600 space-y-1 italic mt-8">
            <p>Note: 1. Submit duly signed hard copy of application to <span
                    class="font-bold font-sans">Establishment</span> along with supporting documents.</p>
            <p class="pl-8">2. Apply for OD online on JUNO ERP along with this letter and supporting documents.</p>
            <p class="pl-8">3. Submit attendance certificate after availing OD (If applicable) to <span
                    class="font-bold font-sans">Establishment</span>.</p>
        </div>

        <div class="text-center mt-12 text-[10px] text-gray-500">
            Approved By AICTE, New Delhi, Recognised by Govt. of Maharashtra & Affiliated to Shivaji University,
            Kolhapur.
        </div>

        <!-- Submit Button -->
        <div class="pt-8 text-center no-print">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-8 rounded font-sans transition-colors shadow">
                Submit Application
            </button>
        </div>
    </form>
</div>

<!-- Print utility for hiding submit button/header when printing -->
<style>
    @media print {
        .no-print {
            display: none !important;
        }

        .max-w-4xl {
            max-width: none !important;
            margin: 0 !important;
            padding: 2rem !important;
            border: none !important;
            box-shadow: none !important;
        }

        body {
            background-color: white !important;
        }

        nav,
        aside {
            display: none !important;
        }
    }
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>