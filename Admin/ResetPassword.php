<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location:../index-modern.php");
    exit();
}

$con = new mysqli("localhost","root","","oes");
$message = '';
$messageType = '';

// Handle password reset
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset_password'])) {
    $userType = $_POST['user_type'];
    $userId = $_POST['user_id'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    
    if($newPassword !== $confirmPassword) {
        $message = 'Passwords do not match!';
        $messageType = 'danger';
    } elseif(strlen($newPassword) < 6) {
        $message = 'Password must be at least 6 characters long!';
        $messageType = 'danger';
    } else {
        // Hash password for security
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // Update password based on user type
        $updated = false;
        switch($userType) {
            case 'student':
                $stmt = $con->prepare("UPDATE student SET Password = ? WHERE Id = ?");
                $stmt->bind_param("ss", $newPassword, $userId);
                $updated = $stmt->execute();
                break;
            case 'instructor':
                $stmt = $con->prepare("UPDATE instructor SET Password = ? WHERE Inst_ID = ?");
                $stmt->bind_param("ss", $newPassword, $userId);
                $updated = $stmt->execute();
                break;
            case 'exam_committee':
                $stmt = $con->prepare("UPDATE exam_committee SET Password = ? WHERE committee_id = ?");
                $stmt->bind_param("ss", $newPassword, $userId);
                $updated = $stmt->execute();
                break;
            case 'admin':
                $stmt = $con->prepare("UPDATE admin SET Password = ? WHERE username = ?");
                $stmt->bind_param("ss", $newPassword, $userId);
                $updated = $stmt->execute();
                break;
        }
        
        if($updated) {
            $message = 'Password reset successfully! User can now login with the new password.';
            $messageType = 'success';
        } else {
            $message = 'Failed to reset password. Please try again.';
            $messageType = 'danger';
        }
    }
}

// Get all users for dropdown
$students = $con->query("SELECT Id, Name, Email FROM student ORDER BY Name");
$instructors = $con->query("SELECT Inst_ID, Name, Email FROM instructor ORDER BY Name");
$committees = $con->query("SELECT committee_id, Name, Email FROM exam_committee ORDER BY Name");
$admins = $con->query("SELECT username, username as Name FROM admin ORDER BY username");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Admin</title>
    <link href="../assets/css/modern-v2.css" rel="stylesheet">
    <link href="../assets/css/admin-modern-v2.css" rel="stylesheet">
    <link href="../assets/css/admin-sidebar.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .password-strength {
            height: 5px;
            border-radius: 3px;
            margin-top: 0.5rem;
            transition: all 0.3s;
        }
        .strength-weak { background: #dc3545; width: 33%; }
        .strength-medium { background: #ffc107; width: 66%; }
        .strength-strong { background: #28a745; width: 100%; }
        
        .user-card {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s;
        }
        .user-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .user-card.selected {
            border-color: var(--primary-color);
            background: rgba(0, 123, 255, 0.05);
        }
    </style>
</head>
<body class="admin-layout">
    <?php include 'sidebar-component.php'; ?>

    <div class="admin-main-content">
        <?php 
        $pageTitle = 'Reset Password';
        include 'header-component.php'; 
        ?>

        <div class="admin-content">
            <div class="page-header">
                <h1>🔐 Reset User Password</h1>
                <p>Reset password for any user in the system</p>
            </div>

            <?php if($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>" style="margin-bottom: 2rem; padding: 1.25rem; border-radius: var(--radius-lg); background: <?php echo $messageType == 'success' ? 'rgba(40, 167, 69, 0.1)' : 'rgba(220, 53, 69, 0.1)'; ?>; border-left: 4px solid <?php echo $messageType == 'success' ? 'var(--success-color)' : '#dc3545'; ?>;">
                <strong><?php echo $messageType == 'success' ? '✓' : '✗'; ?></strong> <?php echo $message; ?>
            </div>
            <?php endif; ?>

            <div class="grid grid-2">
                <!-- Reset Password Form -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">🔑 Password Reset Form</h3>
                    </div>
                    <div style="padding: 2rem;">
                        <form method="POST" id="resetForm">
                            <div class="form-group">
                                <label>Select User Type *</label>
                                <select name="user_type" id="userType" class="form-control" required onchange="loadUsers()">
                                    <option value="">-- Select User Type --</option>
                                    <option value="student">Student</option>
                                    <option value="instructor">Instructor</option>
                                    <option value="exam_committee">Exam Committee</option>
                                    <option value="admin">Administrator</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Select User *</label>
                                <select name="user_id" id="userId" class="form-control" required>
                                    <option value="">-- First select user type --</option>
                                </select>
                                <small style="color: var(--text-secondary);">Select the user whose password you want to reset</small>
                            </div>

                            <div class="form-group">
                                <label>New Password *</label>
                                <input type="password" name="new_password" id="newPassword" class="form-control" required minlength="6" oninput="checkPasswordStrength()">
                                <div id="passwordStrength" class="password-strength"></div>
                                <small id="strengthText" style="color: var(--text-secondary);"></small>
                            </div>

                            <div class="form-group">
                                <label>Confirm Password *</label>
                                <input type="password" name="confirm_password" id="confirmPassword" class="form-control" required minlength="6">
                                <small style="color: var(--text-secondary);">Re-enter the new password</small>
                            </div>

                            <div style="background: var(--bg-light); padding: 1rem; border-radius: var(--radius-md); margin: 1.5rem 0; border-left: 4px solid var(--warning-color);">
                                <strong>⚠️ Important:</strong>
                                <ul style="margin: 0.5rem 0 0 1.5rem; color: var(--text-secondary);">
                                    <li>Password must be at least 6 characters</li>
                                    <li>User will be able to login immediately with new password</li>
                                    <li>Inform the user about their new password securely</li>
                                    <li>Recommend user to change password after first login</li>
                                </ul>
                            </div>

                            <div class="form-actions">
                                <button type="submit" name="reset_password" class="btn btn-primary">
                                    🔐 Reset Password
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                    Clear Form
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Quick Access User Lists -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">👥 Quick User Selection</h3>
                    </div>
                    <div style="padding: 2rem;">
                        <div class="tabs" style="margin-bottom: 1.5rem;">
                            <button class="tab-btn active" onclick="showTab('students')">Students</button>
                            <button class="tab-btn" onclick="showTab('instructors')">Instructors</button>
                            <button class="tab-btn" onclick="showTab('committees')">Committees</button>
                        </div>

                        <!-- Students Tab -->
                        <div id="students-tab" class="tab-content" style="max-height: 500px; overflow-y: auto;">
                            <?php while($student = $students->fetch_assoc()): ?>
                            <div class="user-card" onclick="selectUser('student', '<?php echo $student['Id']; ?>', '<?php echo htmlspecialchars($student['Name']); ?>')">
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                        <?php echo strtoupper(substr($student['Name'], 0, 1)); ?>
                                    </div>
                                    <div style="flex: 1;">
                                        <div style="font-weight: 600; color: var(--text-primary);"><?php echo $student['Name']; ?></div>
                                        <div style="font-size: 0.85rem; color: var(--text-secondary);">ID: <?php echo $student['Id']; ?></div>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>

                        <!-- Instructors Tab -->
                        <div id="instructors-tab" class="tab-content" style="display: none; max-height: 500px; overflow-y: auto;">
                            <?php while($instructor = $instructors->fetch_assoc()): ?>
                            <div class="user-card" onclick="selectUser('instructor', '<?php echo $instructor['Inst_ID']; ?>', '<?php echo htmlspecialchars($instructor['Name']); ?>')">
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--success-color); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                        <?php echo strtoupper(substr($instructor['Name'], 0, 1)); ?>
                                    </div>
                                    <div style="flex: 1;">
                                        <div style="font-weight: 600; color: var(--text-primary);"><?php echo $instructor['Name']; ?></div>
                                        <div style="font-size: 0.85rem; color: var(--text-secondary);">ID: <?php echo $instructor['Inst_ID']; ?></div>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>

                        <!-- Committees Tab -->
                        <div id="committees-tab" class="tab-content" style="display: none; max-height: 500px; overflow-y: auto;">
                            <?php while($committee = $committees->fetch_assoc()): ?>
                            <div class="user-card" onclick="selectUser('exam_committee', '<?php echo $committee['committee_id']; ?>', '<?php echo htmlspecialchars($committee['Name']); ?>')">
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--warning-color); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                        <?php echo strtoupper(substr($committee['Name'], 0, 1)); ?>
                                    </div>
                                    <div style="flex: 1;">
                                        <div style="font-weight: 600; color: var(--text-primary);"><?php echo $committee['Name']; ?></div>
                                        <div style="font-size: 0.85rem; color: var(--text-secondary);">ID: <?php echo $committee['committee_id']; ?></div>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Password Reset Guidelines -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">📚 Password Reset Guidelines</h3>
                </div>
                <div style="padding: 2rem;">
                    <div class="grid grid-3">
                        <div>
                            <h4 style="color: var(--primary-color); margin-bottom: 1rem;">🔒 Security Best Practices</h4>
                            <ul style="color: var(--text-secondary); line-height: 1.8;">
                                <li>Use strong passwords (mix of letters, numbers, symbols)</li>
                                <li>Never share passwords via email or unsecured channels</li>
                                <li>Recommend users change password after reset</li>
                                <li>Keep a secure log of password reset activities</li>
                            </ul>
                        </div>
                        <div>
                            <h4 style="color: var(--primary-color); margin-bottom: 1rem;">✅ When to Reset</h4>
                            <ul style="color: var(--text-secondary); line-height: 1.8;">
                                <li>User forgot their password</li>
                                <li>Account security has been compromised</li>
                                <li>User is locked out of their account</li>
                                <li>New user needs initial password</li>
                            </ul>
                        </div>
                        <div>
                            <h4 style="color: var(--primary-color); margin-bottom: 1rem;">📞 User Communication</h4>
                            <ul style="color: var(--text-secondary); line-height: 1.8;">
                                <li>Inform user via phone or in-person</li>
                                <li>Provide temporary password securely</li>
                                <li>Instruct to change password immediately</li>
                                <li>Verify user identity before resetting</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/admin-sidebar.js"></script>
    <script>
        const userData = {
            student: <?php 
                $students = $con->query("SELECT Id, Name FROM student ORDER BY Name");
                $arr = [];
                while($s = $students->fetch_assoc()) $arr[] = $s;
                echo json_encode($arr);
            ?>,
            instructor: <?php 
                $instructors = $con->query("SELECT Inst_ID as Id, Name FROM instructor ORDER BY Name");
                $arr = [];
                while($i = $instructors->fetch_assoc()) $arr[] = $i;
                echo json_encode($arr);
            ?>,
            exam_committee: <?php 
                $committees = $con->query("SELECT committee_id as Id, Name FROM exam_committee ORDER BY Name");
                $arr = [];
                while($c = $committees->fetch_assoc()) $arr[] = $c;
                echo json_encode($arr);
            ?>,
            admin: <?php 
                $admins = $con->query("SELECT username as Id, username as Name FROM admin ORDER BY username");
                $arr = [];
                while($a = $admins->fetch_assoc()) $arr[] = $a;
                echo json_encode($arr);
            ?>
        };

        function loadUsers() {
            const userType = document.getElementById('userType').value;
            const userSelect = document.getElementById('userId');
            
            userSelect.innerHTML = '<option value="">-- Select User --</option>';
            
            if(userType && userData[userType]) {
                userData[userType].forEach(user => {
                    const option = document.createElement('option');
                    option.value = user.Id;
                    option.textContent = `${user.Name} (ID: ${user.Id})`;
                    userSelect.appendChild(option);
                });
            }
        }

        function selectUser(type, id, name) {
            document.getElementById('userType').value = type;
            loadUsers();
            setTimeout(() => {
                document.getElementById('userId').value = id;
            }, 100);
            
            // Visual feedback
            document.querySelectorAll('.user-card').forEach(card => card.classList.remove('selected'));
            event.currentTarget.classList.add('selected');
        }

        function checkPasswordStrength() {
            const password = document.getElementById('newPassword').value;
            const strengthBar = document.getElementById('passwordStrength');
            const strengthText = document.getElementById('strengthText');
            
            let strength = 0;
            if(password.length >= 6) strength++;
            if(password.length >= 10) strength++;
            if(/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if(/\d/.test(password)) strength++;
            if(/[^a-zA-Z0-9]/.test(password)) strength++;
            
            if(strength <= 2) {
                strengthBar.className = 'password-strength strength-weak';
                strengthText.textContent = 'Weak password';
                strengthText.style.color = '#dc3545';
            } else if(strength <= 3) {
                strengthBar.className = 'password-strength strength-medium';
                strengthText.textContent = 'Medium strength';
                strengthText.style.color = '#ffc107';
            } else {
                strengthBar.className = 'password-strength strength-strong';
                strengthText.textContent = 'Strong password';
                strengthText.style.color = '#28a745';
            }
        }

        function showTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(tab => tab.style.display = 'none');
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            
            document.getElementById(tabName + '-tab').style.display = 'block';
            event.target.classList.add('active');
        }
    </script>
    <style>
        .tabs {
            display: flex;
            gap: 0.5rem;
            border-bottom: 2px solid var(--border-color);
        }
        .tab-btn {
            padding: 0.75rem 1.5rem;
            border: none;
            background: transparent;
            cursor: pointer;
            font-weight: 600;
            color: var(--text-secondary);
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }
        .tab-btn:hover {
            color: var(--primary-color);
        }
        .tab-btn.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
        }
    </style>
</body>
</html>
<?php $con->close(); ?>
