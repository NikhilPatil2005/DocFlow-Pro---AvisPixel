# Role Based Notice System

A PHP-based web application for managing notices with a multi-role approval workflow.

## Features
-   **Multi-Role System**: Super Admin, Admin, Teacher, Student.
-   **Notice Workflow**: Creation -> Admin Approval -> Teacher Publishing -> Student Viewing.
-   **Rejection Handling**: Detailed feedback loops for rejected notices.
-   **Dashboard**: Role-specific dashboards with relevant actions.
-   **Notifications**: Real-time alerts for system events.

## Setup
1.  Import `setup.sql` into your MySQL database.
2.  Configure database credentials in `config/db.php`.
3.  Serve the application via XAMPP/Apache.

## Default Credentials
All users have the password: `password123`

-   **Super Admin**: `superadmin`
-   **Admin**: `admin`
-   **Teacher**: `teacher`
-   **Student**: `student`

---

## System Documentation

### 1. System Overview
The **Role Based Notice System** is a web application designed to manage the lifecycle of notices within an educational institution. It features a multi-tiered approval workflow involving Super Admins, Admins, Teachers, and Students.

### 2. User Roles & Responsibilities

| Role | Access Level | Responsibilities |
| :--- | :--- | :--- |
| **Super Admin** | High | - Create new notices.<br>- Edit rejected notices.<br>- Resubmit notices for approval.<br>- View notice history. |
| **Admin** | Medium | - Review notices created by Super Admin.<br>- Approve notices (moves to Teacher).<br>- Reject notices (returns to Super Admin). |
| **Teacher** | Medium | - Review notices approved by Admin.<br>- Publish notices (visible to Students).<br>- Reject notices (returns to Admin/Super Admin). |
| **Student** | Low | - View published notices.<br>- Mark notices as read (automatically tracked). |

### 3. Authentication Flow
The system uses session-based authentication with role verification.

```mermaid
sequenceDiagram
    participant U as User
    participant L as Login Page
    participant C as AuthController
    participant D as Database
    participant S as Session

    U->>L: Enter Username, Password, Select Role
    L->>C: POST /login (username, password, role)
    C->>D: Query User by Username
    D-->>C: Return User Data (Hash, Role)
    C->>C: Verify Password Hash
    alt Password Valid
        C->>C: Verify Selected Role matches DB Role
        alt Role Match
            C->>S: Store User ID, Role in Session
            C-->>U: Redirect to Role Dashboard
        else Role Mismatch
            C-->>L: Show Error "Role Mismatch"
        end
    else Invalid Password
        C-->>L: Show Error "Invalid Credentials"
    end
```

### 4. Notice Workflow (State Machine)
Notices go through a strict approval pipeline.

```mermaid
stateDiagram-v2
    [*] --> PendingAdmin: Super Admin Creates Notice
    
    state PendingAdmin {
        [*] --> AwaitingAdminApproval
        AwaitingAdminApproval --> AdminApproved: Admin Approves
        AwaitingAdminApproval --> AdminRejected: Admin Rejects
    }

    state AdminApproved {
        [*] --> AwaitingTeacherAction
        AwaitingTeacherAction --> TeacherPublished: Teacher Publishes
        AwaitingTeacherAction --> TeacherRejected: Teacher Rejects
    }

    state Rejected {
        AdminRejected --> PendingAdmin: Super Admin Edits & Resubmits
        TeacherRejected --> AdminApproved: (Hypothetical Re-review)
        TeacherRejected --> PendingAdmin: Recovers to Draft/Edit
    }

    TeacherPublished --> [*]: Visible to Students

    note right of PendingAdmin
        Visible to: Super Admin, Admin
    end note

    note right of AdminApproved
        Visible to: Admin, Teacher
    end note

    note right of TeacherPublished
        Visible to: Everyone (Final State)
    end note
```

#### Detailed Workflow Steps:
1.  **Draft/Creation**: Super Admin creates a notice. Status is set to `pending_admin`.
2.  **Admin Review**: 
    -   If **Approved**: Status becomes `admin_approved`. Notified: Teacher.
    -   If **Rejected**: Status becomes `admin_rejected`. Notified: Super Admin (with reason).
3.  **Teacher Review**:
    -   If **Published**: Status becomes `teacher_published`. Notified: Students.
    -   If **Rejected**: Status becomes `teacher_rejected`. Notified: Admin.
4.  **Resubmission**: Super Admin can edit `admin_rejected` notices, resetting status to `pending_admin`.

### 5. Database Schema
The system maps users, notices, logs, and interaction data.

```mermaid
erDiagram
    USERS ||--o{ NOTICES : "creates"
    USERS {
        int id PK
        string username
        string password
        enum role
    }
    
    NOTICES ||--o{ NOTICE_LOGS : "has history"
    NOTICES ||--o{ READ_RECEIPTS : "viewed by"
    NOTICES {
        int id PK
        string title
        text content
        enum status
        int created_by FK
        text rejection_reason
    }

    NOTICE_LOGS {
        int id PK
        int notice_id FK
        int performed_by FK
        string action
        string old_status
        string new_status
    }

    NOTIFICATIONS {
        int id PK
        int user_id FK
        string message
        boolean is_read
    }

    READ_RECEIPTS {
        int id PK
        int notice_id FK
        int student_id FK
        timestamp viewed_at
    }
```

### 6. Directory Structure
The application follows a simple **MVC (Model-View-Controller)** pattern:

-   `config/`: Database configuration (`db.php`).
-   `public/`: Public entry point (`index.php`) and assets.
-   `src/`: Application source code.
    -   `Controllers/`: Logic for handling requests (`AuthController`, `NoticeController`, `DashboardController`).
    -   `Models/`: Database interactions (`User`, `Notice`, `Notification`, `Log`).
    -   `Views/`: HTML templates.
        -   `auth/`: Login pages.
        -   `dashboard/`: Role-specific dashboards.
        -   `layouts/`: Shared header/footer.
        -   `notices/`: Forms for creating/editing notices.
-   `setup.sql`: Database initialization script.
