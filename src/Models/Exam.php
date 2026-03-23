<?php

class Exam
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // ----------------------------------------------------
    // EXAM MANAGEMENT (TEACHER)
    // ----------------------------------------------------

    public function createExam($title, $description, $departmentId, $createdBy, $startTime, $endTime, $duration)
    {
        $query = "INSERT INTO exams (title, description, department_id, created_by, start_time, end_time, duration_minutes, status, is_published) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, 'draft', 0)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssiissi", $title, $description, $departmentId, $createdBy, $startTime, $endTime, $duration);
        return $stmt->execute() ? $stmt->insert_id : false;
    }

    public function updateExam($id, $title, $description, $startTime, $endTime, $duration)
    {
        $query = "UPDATE exams SET title = ?, description = ?, start_time = ?, end_time = ?, duration_minutes = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssssii", $title, $description, $startTime, $endTime, $duration, $id);
        return $stmt->execute();
    }

    public function deleteExam($id)
    {
        $query = "DELETE FROM exams WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function publishExam($id)
    {
        $query = "UPDATE exams SET status = 'published', is_published = 1 WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getExamsByTeacher($teacherId)
    {
        $query = "SELECT e.*, d.name as department_name, count(eq.id) as question_count 
                  FROM exams e 
                  LEFT JOIN departments d ON e.department_id = d.id 
                  LEFT JOIN exam_questions eq ON e.id = eq.exam_id 
                  WHERE e.created_by = ? 
                  GROUP BY e.id ORDER BY e.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $teacherId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getExamById($id)
    {
        $query = "SELECT * FROM exams WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // ----------------------------------------------------
    // QUESTION MANAGEMENT
    // ----------------------------------------------------

    public function addQuestion($examId, $text, $optA, $optB, $optC, $optD, $correctOpt, $marks, $order = 0)
    {
        $query = "INSERT INTO exam_questions (exam_id, question_text, option_a, option_b, option_c, option_d, correct_option, marks, question_order) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("issssssii", $examId, $text, $optA, $optB, $optC, $optD, $correctOpt, $marks, $order);
        return $stmt->execute() ? $stmt->insert_id : false;
    }

    public function getQuestionsByExam($examId)
    {
        $query = "SELECT * FROM exam_questions WHERE exam_id = ? ORDER BY question_order ASC, id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $examId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function deleteQuestion($id)
    {
        $query = "DELETE FROM exam_questions WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // ----------------------------------------------------
    // STUDENT EXAM ENGINE
    // ----------------------------------------------------

    public function getAvailableExamsForStudent($departmentId)
    {
        $query = "SELECT e.*, u.full_name as teacher_name, 
                  (SELECT count(*) FROM exam_questions eq WHERE eq.exam_id = e.id) as question_count 
                  FROM exams e 
                  LEFT JOIN users u ON e.created_by = u.id 
                  WHERE e.department_id = ? AND e.status = 'published' AND e.is_published = 1 
                  ORDER BY e.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $departmentId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAttempt($examId, $userId)
    {
        $query = "SELECT * FROM exam_attempts WHERE exam_id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $examId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function startAttempt($examId, $userId)
    {
        // Check if already exists
        $existing = $this->getAttempt($examId, $userId);
        if ($existing) return $existing['id'];

        $startTime = date('Y-m-d H:i:s');
        $query = "INSERT INTO exam_attempts (exam_id, user_id, start_time, status) VALUES (?, ?, ?, 'in_progress')";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iis", $examId, $userId, $startTime);
        return $stmt->execute() ? $stmt->insert_id : false;
    }

    public function saveAnswer($attemptId, $questionId, $selectedOption)
    {
        // Upsert answer
        $query = "INSERT INTO exam_answers (attempt_id, question_id, selected_option) 
                  VALUES (?, ?, ?) 
                  ON DUPLICATE KEY UPDATE selected_option = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iiss", $attemptId, $questionId, $selectedOption, $selectedOption);
        return $stmt->execute();
    }

    public function getAttemptAnswers($attemptId)
    {
        $query = "SELECT question_id, selected_option FROM exam_answers WHERE attempt_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $attemptId);
        $stmt->execute();
        $result = $stmt->get_result();
        $answers = [];
        while ($row = $result->fetch_assoc()) {
            $answers[$row['question_id']] = $row['selected_option'];
        }
        return $answers;
    }

    public function getAttemptById($attemptId)
    {
        $query = "SELECT * FROM exam_attempts WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $attemptId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function submitAttempt($attemptId, $status = 'submitted')
    {
        // First get the attempt to know exam
        $query = "SELECT * FROM exam_attempts WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $attemptId);
        $stmt->execute();
        $attempt = $stmt->get_result()->fetch_assoc();

        if (!$attempt || $attempt['is_submitted']) return false;

        $examId = $attempt['exam_id'];
        
        // Fetch all questions with correct option and marks
        $questions = $this->getQuestionsByExam($examId);
        
        // Fetch student answers
        $answersQuery = "SELECT * FROM exam_answers WHERE attempt_id = ?";
        $stmt = $this->conn->prepare($answersQuery);
        $stmt->bind_param("i", $attemptId);
        $stmt->execute();
        $answersRes = $stmt->get_result();
        
        $answers = [];
        while ($row = $answersRes->fetch_assoc()) {
            $answers[$row['question_id']] = $row;
        }
        
        $totalScore = 0;
        $totalMarks = 0;
        
        // Evaluate
        foreach ($questions as $q) {
            $totalMarks += $q['marks'];
            $qId = $q['id'];
            
            if (isset($answers[$qId]) && $answers[$qId]['selected_option'] === $q['correct_option']) {
                $marksAwarded = $q['marks'];
                $totalScore += $marksAwarded;
                // Update answer record
                $updateAnsQuery = "UPDATE exam_answers SET is_correct = 1, marks_awarded = ? WHERE attempt_id = ? AND question_id = ?";
                $uoStmt = $this->conn->prepare($updateAnsQuery);
                $uoStmt->bind_param("iii", $marksAwarded, $attemptId, $qId);
                $uoStmt->execute();
            } else if (isset($answers[$qId])) {
                // Not correct
                $updateAnsQuery = "UPDATE exam_answers SET is_correct = 0, marks_awarded = 0 WHERE attempt_id = ? AND question_id = ?";
                $uoStmt = $this->conn->prepare($updateAnsQuery);
                $uoStmt->bind_param("iii", $attemptId, $qId);
                $uoStmt->execute();
            }
        }
        
        // Update attempt record
        $endTime = date('Y-m-d H:i:s');
        $updateQuery = "UPDATE exam_attempts SET end_time = ?, status = ?, score = ?, total_marks = ?, is_submitted = 1 WHERE id = ?";
        $stmt = $this->conn->prepare($updateQuery);
        $stmt->bind_param("ssiii", $endTime, $status, $totalScore, $totalMarks, $attemptId);
        return $stmt->execute();
    }

    public function getAttemptResults($attemptId)
    {
        $query = "SELECT a.*, e.title, e.duration_minutes, e.start_time as exam_start_time, e.end_time as exam_end_time 
                  FROM exam_attempts a 
                  JOIN exams e ON a.exam_id = e.id 
                  WHERE a.id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $attemptId);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        if ($data) {
            // Fetch questions and answers
            $qaQuery = "SELECT q.*, a.selected_option, a.is_correct, a.marks_awarded 
                        FROM exam_questions q 
                        LEFT JOIN exam_answers a ON q.id = a.question_id AND a.attempt_id = ? 
                        WHERE q.exam_id = ? ORDER BY q.question_order ASC, q.id ASC";
            $qaStmt = $this->conn->prepare($qaQuery);
            $qaStmt->bind_param("ii", $attemptId, $data['exam_id']);
            $qaStmt->execute();
            $data['qa'] = $qaStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }

        return $data;
    }

    // ----------------------------------------------------
    // ANALYTICS & TEACHER VIEW RESULTS
    // ----------------------------------------------------

    public function getExamAttempts($examId)
    {
        $query = "SELECT a.*, u.full_name as student_name, u.username as student_roll 
                  FROM exam_attempts a 
                  JOIN users u ON a.user_id = u.id 
                  WHERE a.exam_id = ? AND a.is_submitted = 1 
                  ORDER BY a.score DESC, a.end_time ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $examId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getTeacherExamAnalytics($teacherId)
    {
        $query = "SELECT COUNT(a.id) as total_attempts, AVG(a.score) as avg_score, MAX(a.score) as highest_score 
                  FROM exam_attempts a 
                  JOIN exams e ON a.exam_id = e.id 
                  WHERE e.created_by = ? AND a.is_submitted = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $teacherId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
