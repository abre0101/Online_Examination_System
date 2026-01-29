<?php
if (!isset($_SESSION)) {
    session_start();
}

if(!isset($_SESSION['Name'])){
    header("Location: ../index-modern.php");
    exit();
}

// Get student stats
$con = new mysqli("localhost","root","","oes");
$studentId = $_SESSION['ID'];

// Get student stats and year information
$stmt = $con->prepare("SELECT year FROM student WHERE Id = ?");
$stmt->bind_param("s", $studentId);
$stmt->execute();
$studentData = $stmt->get_result()->fetch_assoc();
$enrollmentYear = $studentData['year'] ?? '';
$stmt->close();

// Calculate student year level (1st, 2nd, 3rd, 4th year)
$currentYear = date('Y');
$yearLevel = '';
if ($enrollmentYear) {
    $yearsStudied = $currentYear - intval($enrollmentYear);
    $yearNumber = $yearsStudied + 1; // +1 because first year is year 1, not year 0
    
    // Limit to 1-5 years (typical undergraduate program)
    if ($yearNumber < 1) $yearNumber = 1;
    if ($yearNumber > 5) $yearNumber = 5;
    
    // Add ordinal suffix (1st, 2nd, 3rd, 4th, 5th)
    $suffix = 'th';
    if ($yearNumber == 1) $suffix = 'st';
    elseif ($yearNumber == 2) $suffix = 'nd';
    elseif ($yearNumber == 3) $suffix = 'rd';
    
    $yearLevel = $yearNumber . $suffix . ' Year';
} else {
    $yearLevel = 'N/A';
}

// Count available exams for student's semester
$studentSemester = $_SESSION['Sem'];
$examCountQuery = $con->prepare("SELECT COUNT(*) as count FROM schedule WHERE semister = ?");
$examCountQuery->bind_param("i", $studentSemester);
$examCountQuery->execute();
$examCount = $examCountQuery->get_result()->fetch_assoc()['count'];
$examCountQuery->close();

// Count completed exams
$completedCount = $con->query("SELECT COUNT(*) as count FROM result WHERE Stud_ID='$studentId'")->fetch_assoc()['count'];

// Get average score
$avgResult = $con->query("SELECT AVG(Result) as avg FROM result WHERE Stud_ID='$studentId'")->fetch_assoc();
$avgScore = $avgResult['avg'] ? round($avgResult['avg'], 1) : 0;

$con->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Debre Markos University Health Campus</title>
    <link href="../assets/css/modern-v2.css" rel="stylesheet">
    <link href="../assets/css/student-modern.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header class="modern-header">
        <div class="header-top">
            <div class="container">
                <div class="university-info">
                    <img src="../images/logo1.png" alt="Debre Markos University Health Campus" class="university-logo" onerror="this.style.display='none'">
                    <div class="university-name">
                        <h1>Debre Markos University Health Campus</h1>
                        <p>Online Examination System - Student Portal</p>
                    </div>
                </div>
                <div class="header-actions">
                    <div class="user-dropdown">
                        <div class="user-info">
                            <div class="user-avatar">
                                <?php echo strtoupper(substr($_SESSION['Name'], 0, 1)); ?>
                            </div>
                            <div>
                                <div style="font-weight: 600;"><?php echo $_SESSION['Name']; ?></div>
                                <div style="font-size: 0.75rem; opacity: 0.8;">Student</div>
                            </div>
                            <svg style="width: 20px; height: 20px; margin-left: 0.5rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="dropdown-menu">
                            <a href="Profile-modern.php" class="dropdown-item">
                                <span class="dropdown-icon">👤</span>
                                <span>My Profile</span>
                            </a>
                            <a href="EditProfile-modern.php?Id=<?php echo $_SESSION['ID']; ?>" class="dropdown-item">
                                <span class="dropdown-icon">⚙️</span>
                                <span>Account Settings</span>
                            </a>
                            <a href="../Help-modern.php" class="dropdown-item">
                                <span class="dropdown-icon">❓</span>
                                <span>Help</span>
                            </a>
                            <a href="../AboutUs-modern.php" class="dropdown-item">
                                <span class="dropdown-icon">ℹ️</span>
                                <span>About</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="Logout.php" class="dropdown-item logout">
                                <span class="dropdown-icon">🚪</span>
                                <span>Log Out</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <nav class="main-nav">
            <div class="container">
                <ul class="nav-menu">
                    <li><a href="index-modern.php" class="active">Dashboard</a></li>
                    <li><a href="StartExam-modern.php">Take Exam</a></li>
                    <li><a href="Result-modern.php">Results</a></li>
                    <li><a href="practice-selection.php">Practice</a></li>
                    <li><a href="Profile-modern.php">Profile</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Welcome Banner -->
            <div class="welcome-banner">
                <div class="welcome-content">
                    <h1 style="color: #FFFFFF !important; text-shadow: 3px 3px 8px rgba(0, 0, 0, 0.8), 0 0 20px rgba(0, 0, 0, 0.5) !important; font-weight: 800 !important;">👋 Welcome, <?php echo $_SESSION['Name']; ?>!</h1>
                    <p style="color: #FFFFFF !important; text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.8), 0 0 15px rgba(0, 0, 0, 0.5) !important; font-weight: 700 !important;">Ready to take your exams? Access your dashboard below.</p>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="stats-grid">
                <div class="stat-card stat-primary">
                    <div class="stat-icon">📚</div>
                    <div class="stat-details">
                        <div class="stat-value"><?php echo $examCount; ?></div>
                        <div class="stat-label">Available Exams</div>
                    </div>
                </div>

                <div class="stat-card stat-success">
                    <div class="stat-icon">✅</div>
                    <div class="stat-details">
                        <div class="stat-value"><?php echo $completedCount; ?></div>
                        <div class="stat-label">Completed Exams</div>
                    </div>
                </div>

                <div class="stat-card stat-warning">
                    <div class="stat-icon">📊</div>
                    <div class="stat-details">
                        <div class="stat-value"><?php echo $avgScore; ?>%</div>
                        <div class="stat-label">Average Score</div>
                    </div>
                </div>

                <div class="stat-card stat-info">
                    <div class="stat-icon">🎓</div>
                    <div class="stat-details">
                        <div class="stat-value"><?php echo $_SESSION['Dept']; ?></div>
                        <div class="stat-label">Department</div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="content-wrapper mt-4">
                <h2>⚡ Quick Actions</h2>
                <div class="quick-actions-grid">
                    <a href="StartExam-modern.php" class="action-card">
                        <div class="action-icon">📝</div>
                        <div class="action-title">Take Exam</div>
                        <div class="action-desc">Start a new examination</div>
                    </a>
                    <a href="Result-modern.php" class="action-card">
                        <div class="action-icon">📊</div>
                        <div class="action-title">View Results</div>
                        <div class="action-desc">Check your exam scores</div>
                    </a>
                    <a href="Profile-modern.php" class="action-card">
                        <div class="action-icon">👤</div>
                        <div class="action-title">My Profile</div>
                        <div class="action-desc">Update your information</div>
                    </a>
                    <a href="practice-selection.php" class="action-card">
                        <div class="action-icon">✏️</div>
                        <div class="action-title">Practice</div>
                        <div class="action-desc">Practice with sample questions</div>
                    </a>
                </div>
            </div>

            <!-- Student Info -->
            <div class="grid grid-2 mt-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">📋 Student Information</h3>
                    </div>
                    <div class="info-list">
                        <div class="info-item">
                            <span class="info-label">Student ID:</span>
                            <span class="info-value"><?php echo $_SESSION['ID']; ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Name:</span>
                            <span class="info-value"><?php echo $_SESSION['Name']; ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Department:</span>
                            <span class="info-value"><?php echo $_SESSION['Dept']; ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Year:</span>
                            <span class="info-value"><?php echo $yearLevel; ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Semester:</span>
                            <span class="info-value"><?php echo $_SESSION['Sem']; ?></span>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">📢 Announcements</h3>
                    </div>
                    <div class="announcement-list">
                        <div class="announcement-item">
                            <div class="announcement-icon">📅</div>
                            <div class="announcement-content">
                                <div class="announcement-title">Exam Schedule Updated</div>
                                <div class="announcement-time">Check the schedule page for new dates</div>
                            </div>
                        </div>
                        <div class="announcement-item">
                            <div class="announcement-icon">✅</div>
                            <div class="announcement-content">
                                <div class="announcement-title">System Ready</div>
                                <div class="announcement-time">All systems operational</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="modern-footer">
        <div class="container">
            <div class="footer-content">
                <p>&copy;  2026 Debre Markos University Health Campus Online Examination System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Dropdown menu functionality
        const userDropdown = document.querySelector('.user-dropdown');
        const userInfo = userDropdown.querySelector('.user-info');
        const dropdownMenu = userDropdown.querySelector('.dropdown-menu');

        userInfo.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('active');
        });

        document.addEventListener('click', function(e) {
            if (!userDropdown.contains(e.target)) {
                userDropdown.classList.remove('active');
            }
        });

        dropdownMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    </script>
</body>
</html>
