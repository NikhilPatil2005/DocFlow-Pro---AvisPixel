<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Certificate -
        <?php echo htmlspecialchars($request['teacher_name']); ?>
    </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #f3f4f6;
        }

        .print-area {
            background-color: white;
            width: 210mm;
            min-height: 297mm;
            padding: 20mm;
            margin: 20px auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            font-family: 'Times New Roman', Times, serif;
            color: #111827;
        }

        @media print {
            body {
                background-color: white;
            }

            .print-area {
                box-shadow: none;
                margin: 0;
                padding: 0mm 10mm;
                width: auto;
                height: auto;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body>
    <div class="no-print mt-6 mb-2 flex justify-center space-x-4">
        <button onclick="window.print()"
            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow transition-colors">
            Print Certificate
        </button>
        <button onclick="window.close()"
            class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded shadow transition-colors">
            Close View
        </button>
    </div>

    <div class="print-area text-[15px] leading-relaxed">
        <!-- Form Header -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold uppercase underline tracking-wider mb-8">Salary Certificate Form</h1>
            <div class="text-left space-y-1">
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
        <div class="text-center font-bold underline mb-8">
            Subject: Application for Salary Certificate
        </div>

        <!-- Form Body -->
        <div class="space-y-8 mb-16">
            <div>
                <p class="mb-4">Respected Sir,</p>
                <p class="mb-4 text-justify">
                    I <span class="font-semibold uppercase">
                        <?php echo htmlspecialchars($request['teacher_name']); ?>
                    </span>
                    am working as <span class="font-semibold uppercase">
                        <?php echo htmlspecialchars($request['designation'] ?: '__________'); ?>
                    </span>
                    in department of <span class="font-semibold uppercase">
                        <?php echo htmlspecialchars($request['department_name'] ?? '__________'); ?>
                    </span>.
                </p>
                <p class="mb-4 text-justify">
                    I need salary certificate from
                    <span class="font-semibold uppercase">
                        <?php echo date('d-m-Y', strtotime($request['from_date'])); ?>
                    </span>
                    to
                    <span class="font-semibold uppercase">
                        <?php echo date('d-m-Y', strtotime($request['to_date'])); ?>
                    </span>
                    for
                    <span class="font-semibold uppercase">
                        <?php echo htmlspecialchars($request['purpose']); ?>
                    </span> purpose.
                </p>
                <p>I request you to kindly provide the same.</p>
            </div>

            <div class="flex justify-between pt-12">
                <div class="text-center w-48"></div>
                <div class="text-center w-48">
                    <div class="mb-1 font-bold italic text-gray-800">
                        <?php echo htmlspecialchars($request['teacher_name']); ?>
                    </div>
                    <span class="inline-block w-full border-b border-gray-400 mb-1"></span>
                    <div class="text-sm italic text-gray-600">Signature of staff</div>
                </div>
            </div>

            <div class="flex justify-start pt-8 pb-4">
                <div class="text-center w-48 font-bold">
                    <span class="inline-block w-full border-b border-gray-400 mb-1"></span>
                    <div class="text-sm">Registrar</div>
                </div>
            </div>
        </div>

        <!-- Approval Box -->
        <div class="mt-8 pt-8 border-t-2 border-dashed border-gray-400 relative">
            <span class="absolute -top-3 bg-white px-2 text-xs italic text-gray-500 left-8">For Office Use Only</span>

            <div class="text-center mt-6">
                <p class="font-bold uppercase text-lg mb-2">Approved</p>
                <p class="text-sm text-gray-600">Approved on:
                    <?php echo date('d-m-Y h:i A', strtotime($request['approved_at'])); ?>
                </p>
            </div>

            <div class="flex justify-end pr-16 mt-8">
                <div class="text-center w-64">
                    <div class="font-bold italic text-blue-800 text-xl border-b border-gray-400 pb-1 mb-1">
                        Signed:
                        <?php echo htmlspecialchars($request['principal_signature']); ?>
                    </div>
                    <div class="text-sm font-bold">Principal</div>
                    <div class="text-xs text-gray-600">D.Y.P.C.E.T., Kolhapur</div>
                </div>
            </div>
        </div>

        <div class="text-center mt-16 pt-8 text-[11px] text-gray-500 font-sans border-t border-gray-200">
            Approved By AICTE, New Delhi, Recognised by Govt. of Maharashtra & Affiliated to Shivaji University,
            Kolhapur.
        </div>
    </div>
</body>

</html>