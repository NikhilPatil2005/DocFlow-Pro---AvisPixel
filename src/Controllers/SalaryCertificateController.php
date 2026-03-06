<?php
require_once __DIR__ . '/../Models/SalaryCertificate.php';
require_once __DIR__ . '/../Models/User.php';

class SalaryCertificateController
{
    private $salaryModel;
    private $userModel;

    public function __construct($db)
    {
        $this->salaryModel = new SalaryCertificate($db);
        $this->userModel = new User($db);
    }

    public function apply()
    {
        requireLogin();
        $role = $_SESSION['role'];
        requireRole(['teacher']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user_id'];
            $userInfo = $this->userModel->getUserById($user_id);
            $department_id = $userInfo['department_id'];

            if (!$department_id) {
                view('salary/apply', ['error' => 'You must be assigned to a department to request a salary certificate.']);
                return;
            }

            $designation = $_POST['designation'] ?? '';
            $from_date = $_POST['from_date'] ?? '';
            $to_date = $_POST['to_date'] ?? '';
            $purpose = $_POST['purpose'] ?? '';

            if (empty($from_date) || empty($to_date) || empty($purpose)) {
                view('salary/apply', ['error' => 'All fields are required.']);
                return;
            }

            if ($this->salaryModel->createRequest($user_id, $department_id, $designation, $from_date, $to_date, $purpose)) {
                redirect('index.php?action=my_salary_certificates&success=Salary Certificate requested successfully');
            } else {
                view('salary/apply', ['error' => 'Failed to submit request.']);
            }
        } else {
            view('salary/apply');
        }
    }

    public function myCertificates()
    {
        requireLogin();
        requireRole(['teacher']);
        $user_id = $_SESSION['user_id'];
        $requests = $this->salaryModel->getRequestsByTeacher($user_id);
        view('salary/history', ['requests' => $requests]);
    }

    public function manage()
    {
        requireLogin();
        requireRole(['principal']);

        $pendingRequests = $this->salaryModel->getPendingForPrincipal();
        view('salary/manage', ['requests' => $pendingRequests]);
    }

    public function viewRequest()
    {
        requireLogin();
        requireRole(['principal']);

        $request_id = $_GET['id'] ?? null;
        if (!$request_id) {
            redirect('index.php?action=manage_salary_requests&error=Invalid request');
            return;
        }

        $request = $this->salaryModel->getRequestById($request_id);
        if (!$request) {
            redirect('index.php?action=manage_salary_requests&error=Request not found');
            return;
        }

        view('salary/view_request', ['request' => $request]);
    }

    public function approve()
    {
        requireLogin();
        requireRole(['principal']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $request_id = $_POST['request_id'] ?? null;
            $signature = $_POST['signature'] ?? '';

            if (!$request_id || empty($signature)) {
                redirect('index.php?action=manage_salary_requests&error=Invalid request or empty signature');
                return;
            }

            if ($this->salaryModel->approveRequest($request_id, $signature)) {
                redirect('index.php?action=manage_salary_requests&success=Request approved and signed successfully');
            } else {
                redirect('index.php?action=manage_salary_requests&error=Failed to approve request');
            }
        }
    }

    public function reject()
    {
        requireLogin();
        requireRole(['principal']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $request_id = $_POST['request_id'] ?? null;

            if (!$request_id) {
                redirect('index.php?action=manage_salary_requests&error=Invalid request');
                return;
            }

            if ($this->salaryModel->rejectRequest($request_id)) {
                redirect('index.php?action=manage_salary_requests&success=Request rejected successfully');
            } else {
                redirect('index.php?action=manage_salary_requests&error=Failed to reject request');
            }
        }
    }

    public function printCertificate()
    {
        requireLogin();
        requireRole(['teacher']);

        $request_id = $_GET['id'] ?? null;
        if (!$request_id) {
            redirect('index.php?action=my_salary_certificates&error=Invalid request');
            return;
        }

        $request = $this->salaryModel->getRequestById($request_id);
        if (!$request || $request['teacher_id'] != $_SESSION['user_id'] || $request['status'] !== 'approved') {
            redirect('index.php?action=my_salary_certificates&error=Certificate not available');
            return;
        }

        view('salary/print', ['request' => $request]);
    }
}
