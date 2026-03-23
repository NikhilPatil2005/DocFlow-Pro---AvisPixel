<?php

class ExamController
{
    private $conn;
    private $examModel;

    public function __construct($conn)
    {
        $this->conn = $conn;
        require_once __DIR__ . '/../Models/Exam.php';
        $this->examModel = new Exam($conn);
    }

    // ==========================================
    // TEACHER ACTIONS
    // ==========================================

    public function teacherIndex()
    {
        requireLogin();
        requireRole(['teacher', 'hod']);
        
        $exams = $this->examModel->getExamsByTeacher($_SESSION['user_id']);
        view('exam/teacher_list', ['exams' => $exams]);
    }

    public function create()
    {
        requireLogin();
        requireRole(['teacher', 'hod']);
        
        // Need to get departments. Since the user can only create an exam for their own department?
        // Let's pass their department ID or allow them to select if HOD.
        require_once __DIR__ . '/../Models/User.php';
        $userModel = new User($this->conn);
        $user = $userModel->getUserById($_SESSION['user_id']);
        
        view('exam/create', ['department_id' => $user['department_id']]);
    }

    public function store()
    {
        requireLogin();
        requireRole(['teacher', 'hod']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $departmentId = $_POST['department_id'];
            $startTime = $_POST['start_time'];
            $endTime = $_POST['end_time'];
            $duration = $_POST['duration'];

            $examId = $this->examModel->createExam($title, $description, $departmentId, $_SESSION['user_id'], $startTime, $endTime, $duration);
            
            if ($examId) {
                redirect("index.php?action=manage_exam_questions&id=$examId");
            } else {
                die("Failed to create exam.");
            }
        }
    }

    public function manageQuestions()
    {
        requireLogin();
        requireRole(['teacher', 'hod']);
        
        $examId = $_GET['id'] ?? null;
        if (!$examId) redirect('index.php?action=teacher_exams');

        $exam = $this->examModel->getExamById($examId);
        if ($exam['created_by'] != $_SESSION['user_id']) die("Unauthorized");

        $questions = $this->examModel->getQuestionsByExam($examId);
        
        view('exam/manage_questions', ['exam' => $exam, 'questions' => $questions]);
    }

    public function storeQuestion()
    {
        requireLogin();
        requireRole(['teacher', 'hod']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $examId = $_POST['exam_id'];
            $text = $_POST['question_text'];
            $optA = $_POST['option_a'];
            $optB = $_POST['option_b'];
            $optC = $_POST['option_c'];
            $optD = $_POST['option_d'];
            $correct = $_POST['correct_option'];
            $marks = $_POST['marks'];
            $order = $_POST['question_order'] ?? 0;

            $this->examModel->addQuestion($examId, $text, $optA, $optB, $optC, $optD, $correct, $marks, $order);
            redirect("index.php?action=manage_exam_questions&id=$examId");
        }
    }

    public function publish()
    {
        requireLogin();
        requireRole(['teacher', 'hod']);
        
        $examId = $_GET['id'] ?? null;
        if ($examId) {
            $this->examModel->publishExam($examId);
            
            // Notify students (optional enhancement)
            require_once __DIR__ . '/../Models/Notification.php';
            require_once __DIR__ . '/../Models/User.php';
            $notifModel = new Notification($this->conn);
            $userModel = new User($this->conn);
            $exam = $this->examModel->getExamById($examId);
            
            // Get all active students of this department
            $query = "SELECT id FROM users WHERE role = 'student' AND department_id = ? AND status = 'active'";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $exam['department_id']);
            $stmt->execute();
            $students = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            
            foreach ($students as $stu) {
                $notifModel->create($stu['id'], "New Assignment Published: " . $exam['title']);
            }
        }
        redirect('index.php?action=teacher_exams');
    }

    public function teacherResults()
    {
        requireLogin();
        requireRole(['teacher', 'hod']);
        
        $examId = $_GET['id'] ?? null;
        if (!$examId) redirect('index.php?action=teacher_exams');

        $exam = $this->examModel->getExamById($examId);
        if ($exam['created_by'] != $_SESSION['user_id']) die("Unauthorized");

        $attempts = $this->examModel->getExamAttempts($examId);
        
        view('exam/teacher_results', ['exam' => $exam, 'attempts' => $attempts]);
    }

    // ==========================================
    // STUDENT ACTIONS
    // ==========================================

    public function studentIndex()
    {
        requireLogin();
        requireRole(['student']);
        
        require_once __DIR__ . '/../Models/User.php';
        $userModel = new User($this->conn);
        $user = $userModel->getUserById($_SESSION['user_id']);
        
        $exams = $this->examModel->getAvailableExamsForStudent($user['department_id']);
        view('exam/student_list', ['exams' => $exams]);
    }

    public function attempt()
    {
        requireLogin();
        requireRole(['student']);
        
        $examId = $_GET['id'] ?? null;
        if (!$examId) redirect('index.php?action=student_exams');

        $exam = $this->examModel->getExamById($examId);
        if (!$exam) die("Exam not found.");

        require_once __DIR__ . '/../Models/User.php';
        $userModel = new User($this->conn);
        $user = $userModel->getUserById($_SESSION['user_id']);
        
        if ($exam['department_id'] != $user['department_id']) {
            die("Unauthorized: You do not belong to the correct department for this exam.");
        }
        
        // Validate time
        $now = new DateTime();
        $start = new DateTime($exam['start_time']);
        $end = new DateTime($exam['end_time']);
        
        if ($now < $start || $now > $end) {
            die("Exam is not active right now. Please check the timings.");
        }

        // Check attempt status
        $attempt = $this->examModel->getAttempt($examId, $_SESSION['user_id']);
        if ($attempt && $attempt['is_submitted']) {
            redirect("index.php?action=student_exam_result&id=" . $attempt['id']);
        }

        // Start or resume attempt
        $attemptId = $this->examModel->startAttempt($examId, $_SESSION['user_id']);
        $attempt = $this->examModel->getAttempt($examId, $_SESSION['user_id']); // refresh to get full data

        // Check if duration exceeded
        $attemptStart = new DateTime($attempt['start_time']);
        $durationInterval = new DateInterval('PT' . $exam['duration_minutes'] . 'M');
        $attemptDeadline = clone $attemptStart;
        $attemptDeadline->add($durationInterval);

        if ($now > $attemptDeadline || $now > $end) {
            // Auto submit
            $this->examModel->submitAttempt($attemptId, 'auto_submitted');
            redirect("index.php?action=student_exam_result&id=$attemptId");
        }

        // Calculate remaining seconds
        $remainingSeconds = $attemptDeadline->getTimestamp() - $now->getTimestamp();
        $examEndRemaining = $end->getTimestamp() - $now->getTimestamp();
        $timerSeconds = min($remainingSeconds, $examEndRemaining);

        $questions = $this->examModel->getQuestionsByExam($examId);
        $savedAnswers = $this->examModel->getAttemptAnswers($attemptId);
        
        view('exam/attempt', [
            'exam' => $exam, 
            'attempt' => $attempt, 
            'questions' => $questions, 
            'savedAnswers' => $savedAnswers,
            'timerSeconds' => $timerSeconds
        ]);
    }

    public function saveAnswer()
    {
        requireLogin();
        requireRole(['student']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $attemptId = $_POST['attempt_id'];
            $questionId = $_POST['question_id'];
            $selectedOption = $_POST['selected_option'];
            
            $attempt = $this->examModel->getAttemptById($attemptId);
            if (!$attempt || $attempt['user_id'] != $_SESSION['user_id']) {
                echo json_encode(['success' => false, 'error' => 'Unauthorized attempt.']);
                exit;
            }
            if ($attempt['is_submitted']) {
                echo json_encode(['success' => false, 'error' => 'Exam already submitted.']);
                exit;
            }
            
            $success = $this->examModel->saveAnswer($attemptId, $questionId, $selectedOption);
            echo json_encode(['success' => $success]);
            exit;
        }
    }

    public function submitAttempt()
    {
        requireLogin();
        requireRole(['student']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $attemptId = $_POST['attempt_id'];
            $attempt = $this->examModel->getAttemptById($attemptId);
            
            if (!$attempt || $attempt['user_id'] != $_SESSION['user_id']) {
                die("Unauthorized attempt.");
            }
            if ($attempt['is_submitted']) {
                die("Exam already submitted.");
            }
            
            $exam = $this->examModel->getExamById($attempt['exam_id']);
            $now = new DateTime();
            $attemptStart = new DateTime($attempt['start_time']);
            // Add duration + 2 mins tolerance
            $durationInterval = new DateInterval('PT' . ($exam['duration_minutes'] + 2) . 'M');
            $attemptStart->add($durationInterval);
            
            $status = 'submitted';
            if ($now > $attemptStart) {
                // Time exceeded limit
                $status = 'auto_submitted';
            }
            
            $this->examModel->submitAttempt($attemptId, $status);
            redirect("index.php?action=student_exam_result&id=$attemptId");
        }
    }

    public function studentResult()
    {
        requireLogin();
        requireRole(['student']);
        
        $attemptId = $_GET['id'] ?? null;
        if (!$attemptId) redirect('index.php?action=student_exams');

        $resultData = $this->examModel->getAttemptResults($attemptId);
        
        if ($resultData['user_id'] != $_SESSION['user_id']) die("Unauthorized");

        view('exam/student_result', ['examResult' => $resultData]);
    }
}
