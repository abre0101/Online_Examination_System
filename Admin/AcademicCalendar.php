<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location:../index-modern.php");
    exit();
}

$con = new mysqli("localhost","root","","oes");

// Create academic_calendar table if not exists
$createTableSQL = "CREATE TABLE IF NOT EXISTS `academic_calendar` (
    `calendar_id` INT AUTO_INCREMENT PRIMARY KEY,
    `academic_year` VARCHAR(20) NOT NULL,
    `semester` VARCHAR(20) NOT NULL,
    `semester_start` DATE NOT NULL,
    `semester_end` DATE NOT NULL,
    `registration_start` DATE,
    `registration_end` DATE,
    `exam_period_start` DATE,
    `exam_period_end` DATE,
    `holiday_name` VARCHAR(100),
    `holiday_date` DATE,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_semester` (`academic_year`, `semester`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
$con->query($createTableSQL);

$message = '';
$messageType = '';

// Handle form submissions
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['add_semester'])) {
        $academicYear = $_POST['academic_year'];
        $semester = $_POST['semester'];
        $semesterStart = $_POST['semester_start'];
        $semesterEnd = $_POST['semester_end'];
        $regStart = $_POST['registration_start'];
        $regEnd = $_POST['registration_end'];
        $examStart = $_POST['exam_period_start'];
        $examEnd = $_POST['exam_period_end'];
        
        $stmt = $con->prepare("INSERT INTO academic_calendar (academic_year, semester, semester_start, semester_end, registration_start, registration_end, exam_period_start, exam_period_end) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $academicYear, $semester, $semesterStart, $semesterEnd, $regStart, $regEnd, $examStart, $examEnd);
        
        if($stmt->execute()) {
            $message = 'Semester added successfully!';
            $messageType = 'success';
        } else {
            $message = 'Error: ' . $stmt->error;
            $messageType = 'danger';
        }
    }
    
    if(isset($_POST['delete_semester'])) {
        $calendarId = $_POST['calendar_id'];
        $con->query("DELETE FROM academic_calendar WHERE calendar_id = $calendarId");
        $message = 'Semester deleted successfully!';
        $messageType = 'success';
    }
    
    if(isset($_POST['set_active'])) {
        $calendarId = $_POST['calendar_id'];
        $con->query("UPDATE academic_calendar SET is_active = 0");
        $con->query("UPDATE academic_calendar SET is_active = 1 WHERE calendar_id = $calendarId");
        $message = 'Active semester updated!';
        $messageType = 'success';
    }
}

// Get all semesters
$semesters = $con->query("SELECT * FROM academic_calendar ORDER BY semester_start DESC");
$activeSemester = $con->query("SELECT * FROM academic_calendar WHERE is_active = 1 LIMIT 1")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Calendar - Admin</title>
    <link href="../assets/css/modern-v2.css" rel="stylesheet">
    <link href="../assets/css/admin-modern-v2.css" rel="stylesheet">
    <link href="../assets/css/admin-sidebar.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .calendar-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 2px solid var(--border-color);
            transition: all 0.3s;
        }
        .calendar-card.active {
            border-color: var(--success-color);
            background: rgba(40, 167, 69, 0.05);
        }
        .calendar-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .timeline {
            position: relative;
            padding-left: 2rem;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: var(--primary-color);
        }
        .timeline-item {
            position: relative;
            padding: 1rem 0;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -2.5rem;
            top: 1.5rem;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--primary-color);
            border: 3px solid white;
            box-shadow: 0 0 0 2px var(--primary-color);
        }
    </style>
</head>
<body class="admin-layout">
    <?php include 'sidebar-component.php'; ?>

    <div class="admin-main-content">
        <?php 
        $pageTitle = 'Academic Calendar';
        include 'header-component.php'; 
        ?>

        <div class="admin-content">
            <div class="page-header">
                <h1>📅 Academic Calendar</h1>
                <p>Manage academic year, semesters, and important dates</p>
            </div>

            <?php if($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>" style="margin-bottom: 2rem; padding: 1.25rem; border-radius: var(--radius-lg);">
                <strong><?php echo $messageType == 'success' ? '✓' : '✗'; ?></strong> <?php echo $message; ?>
            </div>
            <?php endif; ?>

            <!-- Active Semester Banner -->
            <?php if($activeSemester): ?>
            <div style="background: linear-gradient(135deg, var(--success-color) 0%, #1e7e34 100%); color: white; padding: 2rem; border-radius: var(--radius-lg); margin-bottom: 2rem;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h2 style="margin: 0 0 0.5rem 0;">🎓 Current Active Semester</h2>
                        <div style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">
                            <?php echo $activeSemester['academic_year']; ?> - <?php echo $activeSemester['semester']; ?>
                        </div>
                        <div style="opacity: 0.9;">
                            <?php echo date('M d, Y', strtotime($activeSemester['semester_start'])); ?> - 
                            <?php echo date('M d, Y', strtotime($activeSemester['semester_end'])); ?>
                        </div>
                    </div>
                    <div style="text-align: center;">
                        <div style="font-size: 3rem; font-weight: 800;">
                            <?php 
                            $today = new DateTime();
                            $end = new DateTime($activeSemester['semester_end']);
                            $diff = $today->diff($end);
                            echo $diff->days;
                            ?>
                        </div>
                        <div style="opacity: 0.9;">Days Remaining</div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="grid grid-2">
                <!-- Add New Semester -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">➕ Add New Semester</h3>
                    </div>
                    <div style="padding: 2rem;">
                        <form method="POST">
                            <div class="form-group">
                                <label>Academic Year *</label>
                                <input type="text" name="academic_year" class="form-control" placeholder="2025/2026" required>
                            </div>

                            <div class="form-group">
                                <label>Semester *</label>
                                <select name="semester" class="form-control" required>
                                    <option value="">-- Select Semester --</option>
                                    <option value="Semester 1">Semester 1</option>
                                    <option value="Semester 2">Semester 2</option>
                                    <option value="Summer">Summer</option>
                                </select>
                            </div>

                            <h4 style="margin: 1.5rem 0 1rem 0; color: var(--primary-color);">📆 Semester Dates</h4>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Semester Start Date *</label>
                                    <input type="date" name="semester_start" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Semester End Date *</label>
                                    <input type="date" name="semester_end" class="form-control" required>
                                </div>
                            </div>

                            <h4 style="margin: 1.5rem 0 1rem 0; color: var(--primary-color);">📝 Registration Period</h4>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Registration Start</label>
                                    <input type="date" name="registration_start" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Registration End</label>
                                    <input type="date" name="registration_end" class="form-control">
                                </div>
                            </div>

                            <h4 style="margin: 1.5rem 0 1rem 0; color: var(--primary-color);">📝 Exam Period</h4>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Exam Period Start</label>
                                    <input type="date" name="exam_period_start" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Exam Period End</label>
                                    <input type="date" name="exam_period_end" class="form-control">
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" name="add_semester" class="btn btn-primary">
                                    ➕ Add Semester
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                    Clear
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Timeline View -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">📊 Current Semester Timeline</h3>
                    </div>
                    <div style="padding: 2rem;">
                        <?php if($activeSemester): ?>
                        <div class="timeline">
                            <?php if($activeSemester['registration_start']): ?>
                            <div class="timeline-item">
                                <strong style="color: var(--primary-color);">Registration Period</strong>
                                <div style="color: var(--text-secondary); font-size: 0.9rem;">
                                    <?php echo date('M d', strtotime($activeSemester['registration_start'])); ?> - 
                                    <?php echo date('M d, Y', strtotime($activeSemester['registration_end'])); ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <div class="timeline-item">
                                <strong style="color: var(--primary-color);">Semester Period</strong>
                                <div style="color: var(--text-secondary); font-size: 0.9rem;">
                                    <?php echo date('M d', strtotime($activeSemester['semester_start'])); ?> - 
                                    <?php echo date('M d, Y', strtotime($activeSemester['semester_end'])); ?>
                                </div>
                            </div>

                            <?php if($activeSemester['exam_period_start']): ?>
                            <div class="timeline-item">
                                <strong style="color: var(--primary-color);">Exam Period</strong>
                                <div style="color: var(--text-secondary); font-size: 0.9rem;">
                                    <?php echo date('M d', strtotime($activeSemester['exam_period_start'])); ?> - 
                                    <?php echo date('M d, Y', strtotime($activeSemester['exam_period_end'])); ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div style="margin-top: 2rem; padding: 1rem; background: var(--bg-light); border-radius: var(--radius-md);">
                            <strong>📍 Today's Date:</strong>
                            <div style="font-size: 1.2rem; color: var(--primary-color); margin-top: 0.5rem;">
                                <?php echo date('F d, Y'); ?>
                            </div>
                        </div>
                        <?php else: ?>
                        <div style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                            <div style="font-size: 3rem; margin-bottom: 1rem;">📅</div>
                            <p>No active semester set. Add a semester and mark it as active.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- All Semesters List -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">📋 All Semesters</h3>
                </div>
                <div style="padding: 2rem;">
                    <?php if($semesters->num_rows > 0): ?>
                    <?php while($sem = $semesters->fetch_assoc()): ?>
                    <div class="calendar-card <?php echo $sem['is_active'] ? 'active' : ''; ?>">
                        <div style="display: flex; justify-content: space-between; align-items: start;">
                            <div style="flex: 1;">
                                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
                                    <h3 style="margin: 0; color: var(--primary-color);">
                                        <?php echo $sem['academic_year']; ?> - <?php echo $sem['semester']; ?>
                                    </h3>
                                    <?php if($sem['is_active']): ?>
                                    <span style="background: var(--success-color); color: white; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">
                                        ✓ Active
                                    </span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="grid grid-3" style="margin-top: 1rem; gap: 1rem;">
                                    <div>
                                        <strong style="color: var(--text-secondary); font-size: 0.85rem;">Semester Period</strong>
                                        <div style="color: var(--text-primary);">
                                            <?php echo date('M d', strtotime($sem['semester_start'])); ?> - 
                                            <?php echo date('M d, Y', strtotime($sem['semester_end'])); ?>
                                        </div>
                                    </div>
                                    <?php if($sem['exam_period_start']): ?>
                                    <div>
                                        <strong style="color: var(--text-secondary); font-size: 0.85rem;">Exam Period</strong>
                                        <div style="color: var(--text-primary);">
                                            <?php echo date('M d', strtotime($sem['exam_period_start'])); ?> - 
                                            <?php echo date('M d, Y', strtotime($sem['exam_period_end'])); ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <div>
                                        <strong style="color: var(--text-secondary); font-size: 0.85rem;">Duration</strong>
                                        <div style="color: var(--text-primary);">
                                            <?php 
                                            $start = new DateTime($sem['semester_start']);
                                            $end = new DateTime($sem['semester_end']);
                                            $diff = $start->diff($end);
                                            echo $diff->days . ' days';
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div style="display: flex; gap: 0.5rem;">
                                <?php if(!$sem['is_active']): ?>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="calendar_id" value="<?php echo $sem['calendar_id']; ?>">
                                    <button type="submit" name="set_active" class="btn btn-success btn-sm" title="Set as Active">
                                        ✓ Activate
                                    </button>
                                </form>
                                <?php endif; ?>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Delete this semester?')">
                                    <input type="hidden" name="calendar_id" value="<?php echo $sem['calendar_id']; ?>">
                                    <button type="submit" name="delete_semester" class="btn btn-danger btn-sm" title="Delete">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                    <?php else: ?>
                    <div style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">📅</div>
                        <p>No semesters configured yet. Add your first semester above.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/admin-sidebar.js"></script>
</body>
</html>
<?php $con->close(); ?>
