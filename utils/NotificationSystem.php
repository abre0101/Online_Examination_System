<?php
/**
 * Notification System
 * Handles in-app and email notifications
 */

class NotificationSystem {
    private $con;
    
    public function __construct($connection) {
        $this->con = $connection;
        $this->initializeTables();
    }
    
    private function initializeTables() {
        // Create notifications table if not exists
        $this->con->query("CREATE TABLE IF NOT EXISTS `notifications` (
            `notification_id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` VARCHAR(100) NOT NULL,
            `user_type` ENUM('student', 'instructor', 'exam_committee', 'admin') NOT NULL,
            `notification_type` VARCHAR(50) NOT NULL,
            `title` VARCHAR(200) NOT NULL,
            `message` TEXT NOT NULL,
            `link` VARCHAR(255),
            `is_read` TINYINT(1) DEFAULT 0,
            `is_emailed` TINYINT(1) DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX `idx_user` (`user_id`, `user_type`),
            INDEX `idx_read` (`is_read`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }
    
    /**
     * Create a new notification
     */
    public function createNotification($userId, $userType, $type, $title, $message, $link = null) {
        $stmt = $this->con->prepare("INSERT INTO notifications (user_id, user_type, notification_type, title, message, link) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $userId, $userType, $type, $title, $message, $link);
        return $stmt->execute();
    }
    
    /**
     * Get unread notifications for a user
     */
    public function getUnreadNotifications($userId, $userType, $limit = 10) {
        $stmt = $this->con->prepare("SELECT * FROM notifications WHERE user_id = ? AND user_type = ? AND is_read = 0 ORDER BY created_at DESC LIMIT ?");
        $stmt->bind_param("ssi", $userId, $userType, $limit);
        $stmt->execute();
        return $stmt->get_result();
    }
    
    /**
     * Get unread count
     */
    public function getUnreadCount($userId, $userType) {
        $stmt = $this->con->prepare("SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND user_type = ? AND is_read = 0");
        $stmt->bind_param("ss", $userId, $userType);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['count'];
    }
    
    /**
     * Mark notification as read
     */
    public function markAsRead($notificationId) {
        $stmt = $this->con->prepare("UPDATE notifications SET is_read = 1 WHERE notification_id = ?");
        $stmt->bind_param("i", $notificationId);
        return $stmt->execute();
    }
    
    /**
     * Mark all as read for a user
     */
    public function markAllAsRead($userId, $userType) {
        $stmt = $this->con->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ? AND user_type = ?");
        $stmt->bind_param("ss", $userId, $userType);
        return $stmt->execute();
    }
    
    /**
     * Notify when exam is scheduled
     */
    public function notifyExamScheduled($studentId, $examName, $examDate, $examTime) {
        $title = "New Exam Scheduled";
        $message = "Exam '{$examName}' has been scheduled for {$examDate} at {$examTime}. Please prepare accordingly.";
        $link = "../Student/Shedule-modern.php";
        return $this->createNotification($studentId, 'student', 'exam_scheduled', $title, $message, $link);
    }
    
    /**
     * Notify when results are ready
     */
    public function notifyResultsReady($studentId, $examName, $score) {
        $title = "Exam Results Available";
        $message = "Your results for '{$examName}' are now available. You scored {$score}%.";
        $link = "../Student/Result-modern.php";
        return $this->createNotification($studentId, 'student', 'results_ready', $title, $message, $link);
    }
    
    /**
     * Notify instructor when revision is requested
     */
    public function notifyRevisionRequested($instructorId, $questionId, $comments) {
        $title = "Revision Requested";
        $message = "Exam committee has requested revision for Question #{$questionId}. Comments: {$comments}";
        $link = "../Instructor/EditQuestion.php?id={$questionId}";
        return $this->createNotification($instructorId, 'instructor', 'revision_requested', $title, $message, $link);
    }
    
    /**
     * Notify instructor when question is approved
     */
    public function notifyQuestionApproved($instructorId, $questionId, $examName) {
        $title = "Question Approved";
        $message = "Your question for '{$examName}' (ID: {$questionId}) has been approved and is now available for exams.";
        $link = "../Instructor/ManageQuestions.php";
        return $this->createNotification($instructorId, 'instructor', 'question_approved', $title, $message, $link);
    }
    
    /**
     * Notify exam committee of new questions pending approval
     */
    public function notifyPendingApproval($committeeId, $questionCount) {
        $title = "Questions Pending Approval";
        $message = "There are {$questionCount} questions waiting for your review and approval.";
        $link = "../ExamCommittee/CheckQuestions.php?status=pending";
        return $this->createNotification($committeeId, 'exam_committee', 'pending_approval', $title, $message, $link);
    }
    
    /**
     * Send email notification (placeholder - requires SMTP configuration)
     */
    public function sendEmail($to, $subject, $body) {
        // This is a placeholder. In production, use PHPMailer or similar
        // For now, we'll just log that an email would be sent
        
        // Example with mail() function (requires server configuration):
        // $headers = "From: noreply@dmu.edu\r\n";
        // $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        // return mail($to, $subject, $body, $headers);
        
        return true; // Simulated success
    }
    
    /**
     * Broadcast notification to all users of a type
     */
    public function broadcastNotification($userType, $type, $title, $message, $link = null) {
        $users = [];
        
        switch($userType) {
            case 'student':
                $result = $this->con->query("SELECT Id as user_id FROM student WHERE Status='Active'");
                break;
            case 'instructor':
                $result = $this->con->query("SELECT Inst_ID as user_id FROM instructor WHERE Status='Active'");
                break;
            case 'exam_committee':
                $result = $this->con->query("SELECT committee_id as user_id FROM exam_committee WHERE Status='Active'");
                break;
            default:
                return false;
        }
        
        $count = 0;
        while($user = $result->fetch_assoc()) {
            if($this->createNotification($user['user_id'], $userType, $type, $title, $message, $link)) {
                $count++;
            }
        }
        
        return $count;
    }
}
?>
