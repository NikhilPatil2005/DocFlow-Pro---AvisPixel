<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - DocFlow Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4f46e5',
                        secondary: '#10b981',
                        accent: '#f59e0b',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full mx-auto bg-white rounded-xl shadow-lg p-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-gray-900">Create an Account</h2>
            <p class="mt-2 text-sm text-gray-600">
                Already have an account? <a href="index.php?action=login" class="font-medium text-primary hover:text-indigo-500">Sign in</a>
            </p>
        </div>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p><?php echo $error; ?></p>
            </div>
        <?php
endif; ?>

        <form class="space-y-6" action="index.php?action=register" method="POST" enctype="multipart/form-data">
            
            <!-- Role Selection -->
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700">I am a...</label>
                <select id="role" name="role" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md" onchange="updateFormFields()">
                    <option value="">Select Role</option>
                    <option value="student">Student</option>
                    <option value="teacher">Teacher</option>
                    <option value="admin">Admin</option>
                    <option value="super_admin">Super Admin</option>
                </select>
            </div>

            <!-- Common Fields -->
            <div>
                <label for="username" class="sr-only">Username</label>
                <input id="username" name="username" type="text" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Username">
            </div>
            <div>
                <label for="email" class="sr-only">Email address</label>
                <input id="email" name="email" type="email" autocomplete="email" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Email address">
            </div>
            <div>
                <label for="password" class="sr-only">Password</label>
                <input type="password" name="password" id="password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Password">
            </div>

            <!-- Dynamic Fields -->
            <div id="document-fields" class="space-y-4 hidden">
                <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Required Documents</h3>
                
                <div id="doc-admin" class="hidden doc-group">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Identity Proof (Aadhar/PAN)</label>
                        <input type="file" name="identity_proof" accept=".pdf,.jpg,.jpeg,.png" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>
                    <div class="mt-3">
                        <label class="block text-sm font-medium text-gray-700">Appointment Letter</label>
                        <input type="file" name="appointment_letter" accept=".pdf,.jpg,.jpeg,.png" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>
                </div>

                <div id="doc-teacher" class="hidden doc-group">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Educational Certificates</label>
                        <input type="file" name="educational_certificates" accept=".pdf,.jpg,.jpeg,.png" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>
                    <div class="mt-3">
                        <label class="block text-sm font-medium text-gray-700">College ID Card</label>
                        <input type="file" name="college_id_card" accept=".pdf,.jpg,.jpeg,.png" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>
                </div>

                <div id="doc-student" class="hidden doc-group">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Admission Receipt</label>
                        <input type="file" name="admission_receipt" accept=".pdf,.jpg,.jpeg,.png" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>
                    <div class="mt-3">
                        <label class="block text-sm font-medium text-gray-700">Previous Year Marksheet</label>
                        <input type="file" name="previous_marksheet" accept=".pdf,.jpg,.jpeg,.png" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>
                </div>

                <div id="key-super-admin" class="hidden doc-group">
                    <label class="block text-sm font-medium text-gray-700">Master Security Key</label>
                    <input type="password" name="master_key" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                </div>
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition duration-150 ease-in-out">
                    Register
                </button>
            </div>
        </form>
    </div>

    <script>
        function updateFormFields() {
            const role = document.getElementById('role').value;
            const docFields = document.getElementById('document-fields');
            const groups = document.querySelectorAll('.doc-group');
            
            // Hide all groups
            groups.forEach(group => group.classList.add('hidden'));
            
            // Reset required attributes
            document.querySelectorAll('input[type="file"], input[name="master_key"]').forEach(input => input.required = false);

            if (role) {
                docFields.classList.remove('hidden');
                
                if (role === 'admin') {
                    const group = document.getElementById('doc-admin');
                    group.classList.remove('hidden');
                    group.querySelectorAll('input').forEach(input => input.required = true);
                } else if (role === 'teacher') {
                    const group = document.getElementById('doc-teacher');
                    group.classList.remove('hidden');
                    group.querySelectorAll('input').forEach(input => input.required = true);
                } else if (role === 'student') {
                    const group = document.getElementById('doc-student');
                    group.classList.remove('hidden');
                    group.querySelectorAll('input').forEach(input => input.required = true);
                } else if (role === 'super_admin') {
                    const group = document.getElementById('key-super-admin');
                    group.classList.remove('hidden');
                    group.querySelector('input').required = true;
                }
            } else {
                docFields.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
