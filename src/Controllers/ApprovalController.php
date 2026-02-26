<?php

require_once __DIR__ . '/../Models/User.php';

class ApprovalController
{
    private $userModel;

    public function __construct($db)
    {
        $this->userModel = new User($db);
    }

    public function approve()
    {
        $this->processApproval('approve');
    }

    public function reject()
    {
        $this->processApproval('reject');
    }

    private function processApproval($action)
    {
        requireLogin();
        $currentUserRole = $_SESSION['role'];
        $userId = $_POST['user_id'] ?? null;
        $remarks = $_POST['remarks'] ?? '';

        if (!$userId) {
            redirect('index.php?action=registration_requests&error=No user specified');
            return;
        }

        $targetUser = $this->userModel->getUserById($userId);
        if (!$targetUser) {
            redirect('index.php?action=registration_requests&error=User not found');
            return;
        }

        $targetRole = $targetUser['role'];
        $currentStatus = $targetUser['status'] ?? ''; // Safely access
        $newStatus = '';

        if ($action === 'reject') {
            $newStatus = 'rejected';
        }
        else {
            // Approval Logic based on roles and current status
            if ($currentUserRole === 'teacher') {
                // Teacher approves Student (pending_teacher -> pending_admin)
                if ($targetRole === 'student' && $targetUser['status'] === 'pending_teacher') {
                    $newStatus = 'pending_admin';
                }
            }
            elseif ($currentUserRole === 'admin') {
                // Admin approves Teacher (pending_admin -> pending_super_admin)
                if ($targetRole === 'teacher' && $targetUser['status'] === 'pending_admin') {
                    $newStatus = 'pending_super_admin';
                }
                // Admin approves Student (pending_admin -> pending_super_admin)
                elseif ($targetRole === 'student' && $targetUser['status'] === 'pending_admin') {
                    $newStatus = 'pending_super_admin';
                }
            }
            elseif ($currentUserRole === 'super_admin') {
                // Super Admin approves ANY pending_super_admin -> active
                if ($targetUser['status'] === 'pending_super_admin') {
                    $newStatus = 'active';
                }
            }
        }

        if ($newStatus) {
            if ($this->userModel->updateStatus($userId, $newStatus)) {
                // Log the action (ApprovalController handling logging via userModel if method exists, or implementing here)
                // Assuming logApproval logic exists in User model as per previous code view, or we add it traversing logs?
                // The previous code had: $this->userModel->logApproval(...)
                // We'll keep that if it exists, but we need to check User.php to be sure. 
                // For now, I'll assume it exists or I'll double check User.php.
                // Just in case, I will comment it out if not sure, but the previous code used it so it must be there.
                if (method_exists($this->userModel, 'logApproval')) {
                    $this->userModel->logApproval($userId, $_SESSION['user_id'], $newStatus, $remarks);
                }

                redirect('index.php?action=registration_requests&success=User ' . ucfirst($action) . 'ed successfully');
            }
            else {
                redirect('index.php?action=registration_requests&error=Database update failed');
            }
        }
        else {
            redirect('index.php?action=registration_requests&error=Unauthorized or Invalid Action for this Role');
        }
    }
}
