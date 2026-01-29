<?php
if (!isset($_SESSION)) {
    session_start();
}

if(!isset($_SESSION['Name'])){
    header("Location:../auth/institute-login.php");
    exit();
}

$con = new mysqli("localhost","root","","oes");
$pageTitle = "Manage Questions";

// Get all questions
$questions = $con->query("SELECT qp.*, ec.exam_name 
    FROM question_page qp 
    LEFT JOIN exam_category ec ON qp.exam_id = ec.exam_id 
    ORDER BY qp.question_id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Questions - Instructor</title>
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
                <h1>📝 Manage Questions</h1>
                <p>Create, edit, and organize your exam questions</p>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
                <a href="AddQuestion.php" class="btn btn-primary">
                    ➕ Add New Question
                </a>
                <a href="QuestionBank.php" class="btn btn-success">
                    🗂️ Question Bank
                </a>
                <button class="btn btn-secondary" onclick="window.print()">
                    🖨️ Export Questions
                </button>
            </div>

            <!-- Questions List -->
            <div class="tabs-container">
                <div class="tabs-header">
                    <button class="tab-btn active" onclick="switchTab(0)">By Exam</button>
                    <button class="tab-btn" onclick="switchTab(1)">By Course</button>
                    <button class="tab-btn" onclick="switchTab(2)">By Semester</button>
                </div>

                <!-- By Exam Tab -->
                <div class="tab-content active">
                    <?php
                    // Get all exams with their questions
                    $exams_with_questions = $con->query("SELECT ec.*, COUNT(qp.question_id) as question_count 
                        FROM exam_category ec 
                        LEFT JOIN question_page qp ON ec.exam_id = qp.exam_id 
                        GROUP BY ec.exam_id 
                        ORDER BY ec.exam_id DESC");
                    
                    if($exams_with_questions && $exams_with_questions->num_rows > 0):
                        while($exam = $exams_with_questions->fetch_assoc()):
                    ?>
                    <div style="background: white; border-radius: var(--radius-lg); padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); border-left: 4px solid var(--primary-color);">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                            <div>
                                <h3 style="margin: 0; color: var(--primary-color);">
                                    <?php echo $exam['exam_name']; ?>
                                </h3>
                                <p style="margin: 0.5rem 0 0 0; color: var(--text-secondary);">
                                    <strong><?php echo $exam['question_count']; ?></strong> questions
                                </p>
                            </div>
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="ViewExam.php?id=<?php echo $exam['exam_id']; ?>" class="btn btn-primary btn-sm">
                                    👁️ View All
                                </a>
                                <a href="AddQuestion.php?exam_id=<?php echo $exam['exam_id']; ?>" class="btn btn-success btn-sm">
                                    ➕ Add Question
                                </a>
                            </div>
                        </div>
                        
                        <?php
                        // Get questions for this exam
                        $exam_questions = $con->query("SELECT * FROM question_page WHERE exam_id = '".$exam['exam_id']."' ORDER BY question_id DESC LIMIT 3");
                        if($exam_questions && $exam_questions->num_rows > 0):
                        ?>
                        <div style="border-top: 2px solid var(--border-color); padding-top: 1rem; margin-top: 1rem;">
                            <strong style="color: var(--text-secondary); font-size: 0.9rem;">Recent Questions:</strong>
                            <?php while($q = $exam_questions->fetch_assoc()): ?>
                            <div style="background: var(--bg-light); padding: 1rem; margin-top: 0.75rem; border-radius: var(--radius-md); display: flex; justify-content: space-between; align-items: center;">
                                <div style="flex: 1;">
                                    <p style="margin: 0; color: var(--text-primary);">
                                        <?php echo substr($q['question'], 0, 100); ?>...
                                    </p>
                                    <div style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 0.25rem;">
                                        Course: <?php echo $q['course_name']; ?> | Semester: <?php echo $q['semester']; ?>
                                    </div>
                                </div>
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="EditQuestion.php?id=<?php echo $q['question_id']; ?>" class="btn btn-primary btn-sm">✏️</a>
                                    <button class="btn btn-danger btn-sm" onclick="deleteQuestion(<?php echo $q['question_id']; ?>)">🗑️</button>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                        <?php else: ?>
                        <div style="text-align: center; padding: 1rem; color: var(--text-secondary); border-top: 2px solid var(--border-color); margin-top: 1rem;">
                            No questions yet. <a href="AddQuestion.php?exam_id=<?php echo $exam['exam_id']; ?>">Add your first question</a>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php 
                        endwhile;
                    else:
                    ?>
                        <div style="text-align: center; padding: 4rem;">
                            <h3 style="color: var(--text-secondary);">No exams yet</h3>
                            <p>Create an exam first, then add questions to it</p>
                            <a href="ManageExams.php" class="btn btn-primary" style="margin-top: 1rem;">Create Exam</a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- By Course Tab -->
                <div class="tab-content">
                    <?php
                    // Get questions grouped by course
                    $courses = $con->query("SELECT course_name, COUNT(*) as count FROM question_page GROUP BY course_name ORDER BY course_name");
                    
                    if($courses && $courses->num_rows > 0):
                        while($course = $courses->fetch_assoc()):
                    ?>
                    <div style="background: white; border-radius: var(--radius-lg); padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
                        <h3 style="margin: 0 0 1rem 0; color: var(--primary-color);">
                            📚 <?php echo $course['course_name']; ?>
                            <span style="font-size: 0.9rem; color: var(--text-secondary); font-weight: normal;">
                                (<?php echo $course['count']; ?> questions)
                            </span>
                        </h3>
                        <?php
                        $course_questions = $con->query("SELECT qp.*, ec.exam_name FROM question_page qp LEFT JOIN exam_category ec ON qp.exam_id = ec.exam_id WHERE qp.course_name = '".$course['course_name']."' ORDER BY qp.question_id DESC");
                        while($q = $course_questions->fetch_assoc()):
                        ?>
                        <div style="background: var(--bg-light); padding: 1rem; margin-bottom: 0.75rem; border-radius: var(--radius-md); display: flex; justify-content: space-between; align-items: center;">
                            <div style="flex: 1;">
                                <p style="margin: 0; color: var(--text-primary);">
                                    <?php echo substr($q['question'], 0, 120); ?>...
                                </p>
                                <div style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 0.25rem;">
                                    Exam: <?php echo $q['exam_name']; ?> | Semester: <?php echo $q['semester']; ?>
                                </div>
                            </div>
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="EditQuestion.php?id=<?php echo $q['question_id']; ?>" class="btn btn-primary btn-sm">✏️</a>
                                <button class="btn btn-danger btn-sm" onclick="deleteQuestion(<?php echo $q['question_id']; ?>)">🗑️</button>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <?php 
                        endwhile;
                    else:
                    ?>
                        <p style="padding: 2rem; text-align: center;">No questions available</p>
                    <?php endif; ?>
                </div>

                <!-- By Semester Tab -->
                <div class="tab-content">
                    <?php
                    // Get questions grouped by semester
                    for($sem = 1; $sem <= 2; $sem++):
                        $sem_questions = $con->query("SELECT qp.*, ec.exam_name FROM question_page qp LEFT JOIN exam_category ec ON qp.exam_id = ec.exam_id WHERE qp.semester = $sem ORDER BY qp.question_id DESC");
                        
                        if($sem_questions && $sem_questions->num_rows > 0):
                    ?>
                    <div style="background: white; border-radius: var(--radius-lg); padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
                        <h3 style="margin: 0 0 1rem 0; color: var(--primary-color);">
                            📅 Semester <?php echo $sem; ?>
                            <span style="font-size: 0.9rem; color: var(--text-secondary); font-weight: normal;">
                                (<?php echo $sem_questions->num_rows; ?> questions)
                            </span>
                        </h3>
                        <?php while($q = $sem_questions->fetch_assoc()): ?>
                        <div style="background: var(--bg-light); padding: 1rem; margin-bottom: 0.75rem; border-radius: var(--radius-md); display: flex; justify-content: space-between; align-items: center;">
                            <div style="flex: 1;">
                                <p style="margin: 0; color: var(--text-primary);">
                                    <?php echo substr($q['question'], 0, 120); ?>...
                                </p>
                                <div style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 0.25rem;">
                                    Course: <?php echo $q['course_name']; ?> | Exam: <?php echo $q['exam_name']; ?>
                                </div>
                            </div>
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="EditQuestion.php?id=<?php echo $q['question_id']; ?>" class="btn btn-primary btn-sm">✏️</a>
                                <button class="btn btn-danger btn-sm" onclick="deleteQuestion(<?php echo $q['question_id']; ?>)">🗑️</button>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <?php 
                        endif;
                    endfor;
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/admin-sidebar.js"></script>
    <script>
        function deleteQuestion(id) {
            if(confirm('Are you sure you want to delete this question?')) {
                window.location.href = 'DeleteQuestion.php?id=' + id;
            }
        }
    </script>
</body>
</html>
<?php $con->close(); ?>
