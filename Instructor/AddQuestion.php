<?php
if (!isset($_SESSION)) {
    session_start();
}

if(!isset($_SESSION['Name'])){
    header("Location:../auth/institute-login.php");
    exit();
}

$con = new mysqli("localhost","root","","oes");
$pageTitle = "Add New Question";

// Ensure topic columns exist
$con->query("ALTER TABLE question_page ADD COLUMN IF NOT EXISTS topic_id INT AFTER course_name");
$con->query("ALTER TABLE question_page ADD COLUMN IF NOT EXISTS topic_name VARCHAR(100) AFTER topic_id");

// Get exam categories
$exams = $con->query("SELECT * FROM exam_category");

// Get all courses
$courses = $con->query("SELECT * FROM course ORDER BY course_name");

// Get all topics
$topics = $con->query("SELECT * FROM question_topics ORDER BY course_name, chapter_number");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Question - Instructor</title>
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
                <h1>➕ Add New Question</h1>
                <p>Create a new exam question</p>
            </div>

            <div class="form-wrapper">
                <form method="POST" action="SaveQuestion.php">
                    <div class="form-section">
                        <h3 class="form-section-title">Question Details</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Exam Type *</label>
                                <select name="exam_id" class="form-control" required>
                                    <option value="">Select Exam</option>
                                    <?php while($exam = $exams->fetch_assoc()): ?>
                                    <option value="<?php echo $exam['exam_id']; ?>">
                                        <?php echo $exam['exam_name']; ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Course Code *</label>
                                <select name="course_name" class="form-control" required>
                                    <option value="">Select Course</option>
                                    <?php while($course = $courses->fetch_assoc()): ?>
                                    <option value="<?php echo $course['course_name']; ?>">
                                        <?php echo $course['course_name']; ?> - <?php echo $course['course_id']; ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Semester *</label>
                                <select name="semester" class="form-control" required>
                                    <option value="1">Semester 1</option>
                                    <option value="2">Semester 2</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Topic/Chapter (Optional)</label>
                                <select name="topic_id" class="form-control" id="topicSelect">
                                    <option value="">-- No Topic --</option>
                                    <?php while($topic = $topics->fetch_assoc()): ?>
                                    <option value="<?php echo $topic['topic_id']; ?>" data-course="<?php echo $topic['course_name']; ?>">
                                        <?php echo $topic['course_name']; ?> - Ch.<?php echo $topic['chapter_number']; ?>: <?php echo $topic['topic_name']; ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                                <small style="color: var(--text-secondary);">Organize questions by chapter/topic</small>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Question Text *</label>
                            <textarea name="question" class="form-control" rows="4" required placeholder="Enter your question here..."></textarea>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="form-section-title">Answer Options</h3>
                        
                        <div class="form-group">
                            <label>Option A *</label>
                            <input type="text" name="option1" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Option B *</label>
                            <input type="text" name="option2" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Option C *</label>
                            <input type="text" name="option3" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Option D *</label>
                            <input type="text" name="option4" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Correct Answer *</label>
                            <select name="answer" class="form-control" required>
                                <option value="">Select Correct Answer</option>
                                <option value="A">Option A</option>
                                <option value="B">Option B</option>
                                <option value="C">Option C</option>
                                <option value="D">Option D</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Point Value *</label>
                            <input type="number" name="point_value" class="form-control" min="1" max="100" value="1" required>
                            <small style="color: var(--text-secondary);">Points awarded for correct answer (1-100)</small>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            💾 Save Question
                        </button>
                        <a href="ManageQuestions.php" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../assets/js/admin-sidebar.js"></script>
</body>
</html>
<?php $con->close(); ?>
