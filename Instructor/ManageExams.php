<?php
if (!isset($_SESSION)) {
    session_start();
}

if(!isset($_SESSION['Name'])){
    header("Location:../auth/institute-login.php");
    exit();
}

$con = new mysqli("localhost","root","","oes");
$pageTitle = "Manage Exams";

// Get all exams
$exams = $con->query("SELECT * FROM exam_category ORDER BY exam_id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Exams - Instructor</title>
    <link href="../assets/css/modern-v2.css" rel="stylesheet">
    <link href="../assets/css/admin-modern-v2.css" rel="stylesheet">
    <link href="../assets/css/admin-sidebar.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="admin-layout">
    <?php include 'sidebar-component.php'; ?>

    <div class="admin-main-content">
        <?php include 'header-component.php'; ?>

        <div class="admin-content">
            <div class="page-header">
                <h1>📋 Manage Exams</h1>
                <p>Create and organize your exams</p>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
                <button class="btn btn-primary" onclick="showCreateExamForm()">
                    ➕ Create New Exam
                </button>
                <button class="btn btn-success">
                    📑 Exam Templates
                </button>
                <button class="btn btn-secondary">
                    📤 Export Exams
                </button>
            </div>

            <!-- Create Exam Form (Hidden by default) -->
            <div id="createExamForm" style="display: none; background: white; padding: 2rem; border-radius: var(--radius-lg); margin-bottom: 2rem; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
                <h3 style="margin-bottom: 1.5rem;">Create New Exam</h3>
                <form method="POST" action="SaveExam.php">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Exam Title *</label>
                            <input type="text" name="exam_name" class="form-control" required placeholder="e.g., Midterm Exam">
                        </div>
                        <div class="form-group">
                            <label>Exam Type</label>
                            <select name="exam_type" class="form-control">
                                <option value="Midterm">Midterm Exam</option>
                                <option value="Final">Final Exam</option>
                                <option value="Quiz">Quiz</option>
                                <option value="Assignment">Assignment</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Total Time (minutes)</label>
                            <input type="number" name="duration" class="form-control" placeholder="60">
                        </div>
                        <div class="form-group">
                            <label>Passing Score (%)</label>
                            <input type="number" name="passing_score" class="form-control" placeholder="50">
                        </div>
                        <div class="form-group">
                            <label>Attempt Limit</label>
                            <input type="number" name="attempts" class="form-control" placeholder="1">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Exam description..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" name="randomize">
                            <span>Randomize question order</span>
                        </label>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Create Exam</button>
                        <button type="button" class="btn btn-secondary" onclick="hideCreateExamForm()">Cancel</button>
                    </div>
                </form>
            </div>

            <!-- Exams List -->
            <div class="tabs-container">
                <div class="tabs-header">
                    <button class="tab-btn active" onclick="switchTab(0)">By Course</button>
                    <button class="tab-btn" onclick="switchTab(1)">All Exams</button>
                    <button class="tab-btn" onclick="switchTab(2)">Draft</button>
                    <button class="tab-btn" onclick="switchTab(3)">Pending Approval</button>
                    <button class="tab-btn" onclick="switchTab(4)">Approved</button>
                    <button class="tab-btn" onclick="switchTab(5)">Live</button>
                    <button class="tab-btn" onclick="switchTab(6)">Completed</button>
                </div>

                <!-- By Course Tab -->
                <div class="tab-content active">
                    <?php
                    // Get distinct courses that have questions
                    $courses_query = $con->query("SELECT DISTINCT course_name FROM question_page ORDER BY course_name");
                    
                    if($courses_query && $courses_query->num_rows > 0):
                        while($course = $courses_query->fetch_assoc()):
                            $course_name = $course['course_name'];
                            
                            // Get exams for this course
                            $course_exams = $con->query("SELECT DISTINCT ec.exam_id, ec.exam_name, 
                                COUNT(qp.question_id) as question_count 
                                FROM exam_category ec 
                                INNER JOIN question_page qp ON ec.exam_id = qp.exam_id 
                                WHERE qp.course_name = '".$con->real_escape_string($course_name)."'
                                GROUP BY ec.exam_id, ec.exam_name
                                ORDER BY ec.exam_id DESC");
                            
                            if($course_exams && $course_exams->num_rows > 0):
                    ?>
                    <div style="background: white; border-radius: var(--radius-lg); padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); border-left: 4px solid var(--secondary-color);">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 2px solid var(--border-color);">
                            <div>
                                <h3 style="margin: 0; color: var(--primary-color);">
                                    📚 <?php echo htmlspecialchars($course_name); ?>
                                </h3>
                                <p style="margin: 0.5rem 0 0 0; color: var(--text-secondary);">
                                    <?php echo $course_exams->num_rows; ?> exam(s)
                                </p>
                            </div>
                        </div>
                        
                        <?php while($exam = $course_exams->fetch_assoc()): ?>
                        <div style="background: var(--bg-light); border-radius: var(--radius-md); padding: 1.25rem; margin-bottom: 1rem; border-left: 4px solid var(--primary-color);">
                            <div style="display: flex; justify-content: space-between; align-items: start;">
                                <div style="flex: 1;">
                                    <h4 style="margin: 0 0 0.5rem 0; color: var(--primary-color);">
                                        <?php echo htmlspecialchars($exam['exam_name']); ?>
                                    </h4>
                                    <div style="display: flex; gap: 2rem; font-size: 0.9rem; color: var(--text-secondary);">
                                        <div>
                                            <strong>Exam ID:</strong> <?php echo $exam['exam_id']; ?>
                                        </div>
                                        <div>
                                            <strong>Questions:</strong> <?php echo $exam['question_count']; ?>
                                        </div>
                                        <div>
                                            <strong>Status:</strong> 
                                            <span style="color: var(--success-color); font-weight: 700;">Active</span>
                                        </div>
                                    </div>
                                </div>
                                <div style="display: flex; gap: 0.5rem;">
                                    <button class="btn btn-primary btn-sm" onclick="viewExam(<?php echo $exam['exam_id']; ?>)">
                                        👁️ View
                                    </button>
                                    <button class="btn btn-success btn-sm" onclick="editExam(<?php echo $exam['exam_id']; ?>)">
                                        ✏️ Edit
                                    </button>
                                    <button class="btn btn-secondary btn-sm" onclick="cloneExam(<?php echo $exam['exam_id']; ?>)">
                                        📋 Clone
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <?php 
                            endif;
                        endwhile;
                    else:
                    ?>
                        <div style="text-align: center; padding: 4rem;">
                            <h3 style="color: var(--text-secondary);">No exams yet</h3>
                            <p>Create your first exam to get started</p>
                            <button class="btn btn-primary" style="margin-top: 1rem;" onclick="showCreateExamForm()">
                                ➕ Create Exam
                            </button>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- All Exams Tab -->
                <div class="tab-content">
                    <?php
                    // Get all exams with question count
                    $all_exams = $con->query("SELECT ec.exam_id, ec.exam_name, 
                        COUNT(qp.question_id) as question_count,
                        GROUP_CONCAT(DISTINCT qp.course_name SEPARATOR ', ') as courses
                        FROM exam_category ec 
                        LEFT JOIN question_page qp ON ec.exam_id = qp.exam_id 
                        GROUP BY ec.exam_id, ec.exam_name
                        ORDER BY ec.exam_id DESC");
                    
                    if($all_exams && $all_exams->num_rows > 0):
                        while($exam = $all_exams->fetch_assoc()):
                    ?>
                    <div style="background: white; border-radius: var(--radius-lg); padding: 1.5rem; margin-bottom: 1rem; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); border-left: 4px solid var(--primary-color);">
                        <div style="display: flex; justify-content: space-between; align-items: start;">
                            <div style="flex: 1;">
                                <h4 style="margin: 0 0 0.5rem 0; color: var(--primary-color);">
                                    <?php echo htmlspecialchars($exam['exam_name']); ?>
                                </h4>
                                <div style="display: flex; gap: 2rem; font-size: 0.9rem; color: var(--text-secondary);">
                                    <div>
                                        <strong>Exam ID:</strong> <?php echo $exam['exam_id']; ?>
                                    </div>
                                    <div>
                                        <strong>Questions:</strong> <?php echo $exam['question_count']; ?>
                                    </div>
                                    <div>
                                        <strong>Courses:</strong> <?php echo $exam['courses'] ? htmlspecialchars($exam['courses']) : 'None'; ?>
                                    </div>
                                </div>
                            </div>
                            <div style="display: flex; gap: 0.5rem;">
                                <button class="btn btn-primary btn-sm" onclick="viewExam(<?php echo $exam['exam_id']; ?>)">
                                    👁️ View
                                </button>
                                <button class="btn btn-success btn-sm" onclick="editExam(<?php echo $exam['exam_id']; ?>)">
                                    ✏️ Edit
                                </button>
                                <button class="btn btn-secondary btn-sm" onclick="cloneExam(<?php echo $exam['exam_id']; ?>)">
                                    📋 Clone
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php 
                        endwhile;
                    else:
                    ?>
                        <div style="text-align: center; padding: 4rem;">
                            <h3 style="color: var(--text-secondary);">No exams yet</h3>
                            <p>Create your first exam to get started</p>
                            <button class="btn btn-primary" style="margin-top: 1rem;" onclick="showCreateExamForm()">
                                ➕ Create Exam
                            </button>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="tab-content">
                    <p style="padding: 2rem; text-align: center;">Draft exams will appear here</p>
                </div>

                <div class="tab-content">
                    <p style="padding: 2rem; text-align: center;">Exams pending approval will appear here</p>
                </div>

                <div class="tab-content">
                    <p style="padding: 2rem; text-align: center;">Approved exams will appear here</p>
                </div>

                <div class="tab-content">
                    <p style="padding: 2rem; text-align: center;">Live exams will appear here</p>
                </div>

                <div class="tab-content">
                    <p style="padding: 2rem; text-align: center;">Completed exams will appear here</p>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/admin-sidebar.js"></script>
    <script>
        function showCreateExamForm() {
            document.getElementById('createExamForm').style.display = 'block';
        }
        
        function hideCreateExamForm() {
            document.getElementById('createExamForm').style.display = 'none';
        }
        
        function viewExam(id) {
            window.location.href = 'ViewExam.php?id=' + id;
        }
        
        function editExam(id) {
            window.location.href = 'EditExam.php?id=' + id;
        }
        
        function cloneExam(id) {
            if(confirm('Clone this exam?')) {
                window.location.href = 'CloneExam.php?id=' + id;
            }
        }
        
        function deleteExam(id) {
            if(confirm('Are you sure you want to delete this exam?')) {
                window.location.href = 'DeleteExam.php?id=' + id;
            }
        }
        
        function switchTab(index) {
            // Remove active class from all tabs and content
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');
            
            tabBtns.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Add active class to selected tab and content
            tabBtns[index].classList.add('active');
            tabContents[index].classList.add('active');
        }
    </script>
</body>
</html>
<?php $con->close(); ?>
