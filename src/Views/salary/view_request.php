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
                <?php echo date('d-m-Y', strtotime($request['created_at'])); ?>
            </p>
        </div>
    </div>

    <!-- Subject -->
    <div class="text-center font-bold underline underline-offset-4 mb-8 text-[15px]">
        Subject: Application for Salary Certificate
    </div>

    <!-- Form Body -->
    <div class="space-y-8 text-[15px] leading-relaxed mb-12">
        <div>
            <p class="mb-4">Respected Sir,</p>
            <p class="mb-4 text-justify">
                I <span
                    class="font-semibold underline underline-offset-4 decoration-dotted decoration-gray-400 uppercase">
                    <?php echo htmlspecialchars($request['teacher_name']); ?>
                </span>
                am working as
                <span
                    class="font-semibold underline underline-offset-4 decoration-dotted decoration-gray-400 uppercase">
                    <?php echo htmlspecialchars($request['designation'] ?: '__________'); ?>
                </span>
                in department of <span
                    class="font-semibold underline underline-offset-4 decoration-dotted decoration-gray-400 uppercase">
                    <?php echo htmlspecialchars($request['department_name'] ?? '__________'); ?>
                </span>.
            </p>
            <p class="mb-4 text-justify">
                I need salary certificate from
                <span
                    class="font-semibold underline underline-offset-4 decoration-dotted decoration-gray-400 uppercase">
                    <?php echo date('d-m-Y', strtotime($request['from_date'])); ?>
                </span>
                to
                <span
                    class="font-semibold underline underline-offset-4 decoration-dotted decoration-gray-400 uppercase">
                    <?php echo date('d-m-Y', strtotime($request['to_date'])); ?>
                </span>
                for
                <span
                    class="font-semibold underline underline-offset-4 decoration-dotted decoration-gray-400 uppercase">
                    <?php echo htmlspecialchars($request['purpose']); ?>
                </span>
                purpose.
            </p>
            <p>I request you to kindly provide the same.</p>
        </div>

        <div class="flex justify-between pt-12">
            <div class="text-center w-48">
                <!-- Empty for Registrar Signature area if needed -->
            </div>
            <div class="text-center w-48">
                <div class="mb-1 font-bold italic">
                    <?php echo htmlspecialchars($request['teacher_name']); ?>
                </div>
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
    </div>

    <!-- Actions Area (Not Printed) -->
    <div class="no-print mt-10 pt-6 border-t border-gray-300">
        <h3 class="text-lg font-bold text-gray-900 mb-4 font-sans text-center">Principal Approval Section</h3>

        <div class="flex justify-center space-x-8 max-w-2xl mx-auto">
            <form action="index.php?action=reject_salary_request" method="POST" class="w-1/3">
                <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                <button type="submit" onclick="return confirm('Are you sure you want to reject this request?');"
                    class="w-full bg-red-100 hover:bg-red-200 text-red-700 border border-red-300 font-bold py-3 px-4 rounded font-sans transition-colors">
                    Reject Request
                </button>
            </form>

            <form action="index.php?action=approve_salary_request" method="POST"
                class="w-2/3 flex flex-col items-center">
                <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                <input type="text" name="signature" required placeholder="Type Name for Digital Signature"
                    class="w-full mb-3 border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm font-sans text-center">
                <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded font-sans shadow transition-colors">
                    Approve & Sign Certificate
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    @media print {
        .no-print {
            display: none !important;
        }

        nav,
        aside {
            display: none !important;
        }

        .max-w-4xl {
            max-width: none !important;
            margin: 0 !important;
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>