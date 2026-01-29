<?php
if (!isset($_SESSION)) {
    session_start();
}

if(!isset($_SESSION['Name'])){
    header("Location:../auth/institute-login.php");
    exit();
}

$con = new mysqli("localhost","root","","oes");
$pageTitle = "View Exam";

$exam_id = $_GET['id'] ?? 0;

// Get exam details
$exam = $con->query("SELECT * FROM exam_category WHERE exam_id = '$exam_id'")->fetch_assoc();

if(!$exam) {
    header("Location: ManageExams.php");
    exit();
}

// Get questions for this exam
$questions = $con->query("SELECT * FROM question_page WHERE exam_id = '$exam_id' ORDER BY question_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Exam - Instructor</title>
    <link href="../assets/css/modern-v2.css" rel="stylesheet">
    <link href="../assets/css/admin-modern-v2.css" rel="stylesheet">
    <link href="../assets/css/admin-sidebar.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .question-preview {
            background: white;
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-left: 4px solid var(--primary-color);
        }
        
        .option-preview {
            padding: 0.75rem;
            margin: 0.5rem 0;
            background: var(--bg-light);
            border-radius: var(--radius-md);
            border-left: 3px solid #e0e0e0;
        }
        
        .option-preview.correct {
            border-left-color: var(--success-color);
            background: rgba(40, 167, 69, 0.1);
        }
    </style>
</head>
<body class="admin-layout">
    <?php include 'sidebar-component.php'; ?>

    <div class="admin-main-content">
        <?php include 'header-component.php'; ?>

        <div class="admin-content">
            <div class="page-header">
                <h1>👁️ View Exam</h1>
                <p>Preview exam details and questions</p>
            </div>

            <!-- Exam Header -->
            <div style="background: white; border: 2px solid var(--primary-color); padding: 2rem; border-radius: var(--radius-lg); margin-bottom: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
                <h2 style="margin: 0 0 1rem 0; color: var(--primary-color);"><?php echo $exam['exam_name']; ?></h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-top: 1.5rem;">
                    <div>
                        <strong style="color: var(--text-secondary);">Exam ID:</strong>
                        <p style="margin: 0.5rem 0 0 0; font-size: 1.2rem; color: var(--text-primary); font-weight: 600;"><?php echo $exam['exam_id']; ?></p>
                    </div>
                    <div>
                        <strong style="color: var(--text-secondary);">Total Questions:</strong>
                        <p style="margin: 0.5rem 0 0 0; font-size: 1.2rem; color: var(--text-primary); font-weight: 600;"><?php echo $questions ? $questions->num_rows : 0; ?></p>
                    </div>
                    <div>
                        <strong style="color: var(--text-secondary);">Status:</strong>
                        <p style="margin: 0.5rem 0 0 0; font-size: 1.2rem;">
                            <span style="background: var(--success-color); color: white; padding: 0.25rem 0.75rem; border-radius: var(--radius-sm); font-weight: 600;">Active</span>
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 1rem; margin-top: 2rem; padding-top: 2rem; border-top: 2px solid var(--border-color);">
                    <a href="EditExam.php?id=<?php echo $exam_id; ?>" class="btn btn-primary">
                        ✏️ Edit Exam
                    </a>
                    <button class="btn btn-secondary" onclick="window.print()">
                        🖨️ Print Exam
                    </button>
                    <a href="ManageExams.php" class="btn btn-secondary">
                        ← Back to Exams
                    </a>
                </div>
            </div>

            <!-- Questions List -->
            <div>
                <h3 style="margin-bottom: 1.5rem; color: var(--primary-color);">📝 Exam Questions</h3>
                
                <?php if($questions && $questions->num_rows > 0): ?>
                    <?php $qnum = 1; while($q = $questions->fetch_assoc()): ?>
                    <div class="question-preview">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                            <h4 style="margin: 0; color: var(--primary-color);">
                                Question <?php echo $qnum++; ?>
                            </h4>
                            <div style="display: flex; gap: 0.5rem;">
                                <span style="background: var(--bg-light); padding: 0.25rem 0.75rem; border-radius: var(--radius-sm); font-size: 0.85rem; font-weight: 600;">
                                    Semester <?php echo $q['semester']; ?>
                                </span>
                                <span style="background: var(--bg-light); padding: 0.25rem 0.75rem; border-radius: var(--radius-sm); font-size: 0.85rem; font-weight: 600;">
                                    <?php echo $q['course_name']; ?>
                                </span>
                            </div>
                        </div>
                        
                        <p style="font-size: 1.1rem; line-height: 1.6; margin: 1rem 0; color: var(--text-primary);">
                            <?php echo htmlspecialchars($q['question']); ?>
                        </p>
                        
                        <div style="margin-top: 1rem;">
                            <strong style="display: block; margin-bottom: 0.75rem; color: var(--primary-color);">Options:</strong>
                            
                            <div class="option-preview <?php echo ($q['Answer'] == 'A') ? 'correct' : ''; ?>">
                                <strong>A.</strong> <?php echo htmlspecialchars($q['Option1']); ?>
                                <?php if($q['Answer'] == 'A'): ?>
                                    <span style="float: right; color: var(--success-color); font-weight: 700;">✓ Correct Answer</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="option-preview <?php echo ($q['Answer'] == 'B') ? 'correct' : ''; ?>">
                                <strong>B.</strong> <?php echo htmlspecialchars($q['Option2']); ?>
                                <?php if($q['Answer'] == 'B'): ?>
                                    <span style="float: right; color: var(--success-color); font-weight: 700;">✓ Correct Answer</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="option-preview <?php echo ($q['Answer'] == 'C') ? 'correct' : ''; ?>">
                                <strong>C.</strong> <?php echo htmlspecialchars($q['Option3']); ?>
                                <?php if($q['Answer'] == 'C'): ?>
                                    <span style="float: right; color: var(--success-color); font-weight: 700;">✓ Correct Answer</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="option-preview <?php echo ($q['Answer'] == 'D') ? 'correct' : ''; ?>">
                                <strong>D.</strong> <?php echo htmlspecialchars($q['Option4']); ?>
                                <?php if($q['Answer'] == 'D'): ?>
                                    <span style="float: right; color: var(--success-color); font-weight: 700;">✓ Correct Answer</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div style="display: flex; gap: 0.5rem; margin-top: 1rem; padding-top: 1rem; border-top: 2px solid var(--border-color);">
                            <button class="btn btn-primary btn-sm" onclick="editQuestion(<?php echo $q['question_id']; ?>)">
                                ✏️ Edit Question
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="deleteQuestion(<?php echo $q['question_id']; ?>)">
                                🗑️ Delete
                            </button>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div style="text-align: center; padding: 4rem; background: white; border-radius: var(--radius-lg);">
                        <h3 style="color: var(--text-secondary);">No questions in this exam</h3>
                        <p>Add questions to this exam to get started</p>
                        <a href="AddQuestion.php?exam_id=<?php echo $exam_id; ?>" class="btn btn-primary" style="margin-top: 1rem;">
                            ➕ Add Questions
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Exam Summary -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">📊 Exam Summary</h3>
                </div>
                <div style="padding: 2rem;">
                    <div class="overview-list">
                        <div class="overview-item">
                            <span>Total Questions</span>
                            <strong><?php echo $questions ? $questions->num_rows : 0; ?></strong>
                        </div>
                        <div class="overview-item">
                            <span>Exam Type</span>
                            <strong><?php echo $exam['exam_name']; ?></strong>
                        </div>
                        <div class="overview-item">
                            <span>Status</span>
                            <strong style="color: var(--success-color);">Active</strong>
                        </div>
                        <div class="overview-item">
                            <span>Created By</span>
                            <strong><?php echo $_SESSION['Name']; ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/admin-sidebar.js"></script>
    <script>
        function editQuestion(id) {
            window.location.href = 'EditQuestion.php?id=' + id;
        }
        
        function deleteQuestion(id) {
            if(confirm('Are you sure you want to delete this question?')) {
                window.location.href = 'DeleteQuestion.php?id=' + id;
            }
        }
    </script>
</body>
</html>
<?php $con->close(); ?>
