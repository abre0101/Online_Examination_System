<?php
if (!isset($_SESSION)) {
    session_start();
}

if(!isset($_SESSION['Name'])){
    header("Location: ../index-modern.php");
    exit();
}

$con = new mysqli("localhost","root","","oes");
$studentId = $_SESSION['ID'];
$resultId = $_GET['result_id'] ?? 0;

// Get result details
$resultQuery = $con->prepare("SELECT r.*, ec.exam_name 
    FROM result r
    LEFT JOIN exam_category ec ON r.Exam_ID = ec.exam_id
    WHERE r.Result_ID = ? AND r.Stud_ID = ?");
$resultQuery->bind_param("is", $resultId, $studentId);
$resultQuery->execute();
$result = $resultQuery->get_result()->fetch_assoc();
$resultQuery->close();

if(!$result) {
    header("Location: Result-modern.php");
    exit();
}

// Get course name from result
$courseQuery = $con->prepare("SELECT course_name FROM course WHERE course_id = ?");
$courseQuery->bind_param("i", $result['Course_ID']);
$courseQuery->execute();
$courseData = $courseQuery->get_result()->fetch_assoc();
$courseQuery->close();
$courseName = $courseData['course_name'] ?? 'N/A';

// Get all questions and student's answers
$questionsQuery = $con->prepare("SELECT qp.*, sa.selected_answer, sa.is_correct
    FROM question_page qp
    LEFT JOIN student_answers sa ON qp.question_id = sa.question_id AND sa.result_id = ?
    WHERE qp.exam_id = ?
    ORDER BY qp.question_id");
$questionsQuery->bind_param("ii", $resultId, $result['Exam_ID']);
$questionsQuery->execute();
$questions = $questionsQuery->get_result();
$questionsQuery->close();

$totalQuestions = $questions->num_rows;
$correctAnswers = 0;
$incorrectAnswers = 0;
$unanswered = 0;

// Count answers
$questions->data_seek(0);
while($q = $questions->fetch_assoc()) {
    if($q['selected_answer']) {
        if($q['is_correct']) {
            $correctAnswers++;
        } else {
            $incorrectAnswers++;
        }
    } else {
        $unanswered++;
    }
}

$con->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Answers - Student Portal</title>
    <link href="../assets/css/modern-v2.css" rel="stylesheet">
    <link href="../assets/css/student-modern.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .review-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 2rem;
            border-radius: var(--radius-lg);
            margin-bottom: 2rem;
        }
        
        .review-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .review-stat {
            background: rgba(255, 255, 255, 0.15);
            padding: 1rem;
            border-radius: var(--radius-md);
            text-align: center;
        }
        
        .review-stat-value {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }
        
        .review-stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .question-review {
            background: white;
            border-radius: var(--radius-lg);
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .question-review.correct {
            border-left: 5px solid var(--success-color);
        }
        
        .question-review.incorrect {
            border-left: 5px solid #dc3545;
        }
        
        .question-review.unanswered {
            border-left: 5px solid #ffc107;
        }
        
        .option-review {
            padding: 1rem;
            margin: 0.75rem 0;
            border-radius: var(--radius-md);
            border: 2px solid #e0e0e0;
            background: var(--bg-light);
        }
        
        .option-review.correct-answer {
            background: rgba(40, 167, 69, 0.1);
            border-color: var(--success-color);
        }
        
        .option-review.student-answer {
            background: rgba(0, 123, 255, 0.1);
            border-color: #007bff;
        }
        
        .option-review.wrong-answer {
            background: rgba(220, 53, 69, 0.1);
            border-color: #dc3545;
        }
        
        .answer-indicator {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: var(--radius-sm);
            font-size: 0.85rem;
            font-weight: 700;
            margin-left: 1rem;
        }
        
        .indicator-correct {
            background: var(--success-color);
            color: white;
        }
        
        .indicator-wrong {
            background: #dc3545;
            color: white;
        }
        
        .indicator-unanswered {
            background: #ffc107;
            color: var(--primary-dark);
        }
    </style>
</head>
<body>
    <header class="modern-header">
        <div class="header-top">
            <div class="container">
                <div class="university-info">
                    <img src="../images/logo1.png" alt="Logo" class="university-logo" onerror="this.style.display='none'">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <nav class="main-nav">
            <div class="container">
                <ul class="nav-menu">
                    <li><a href="index-modern.php">Dashboard</a></li>
                    <li><a href="StartExam-modern.php">Take Exam</a></li>
                    <li><a href="Result-modern.php" class="active">Results</a></li>
                    <li><a href="Profile-modern.php">Profile</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="main-content">
        <div class="container">
            <div class="review-header">
                <h1 style="margin: 0 0 0.5rem 0;">📝 Review Your Answers</h1>
                <p style="margin: 0; opacity: 0.9;"><?php echo htmlspecialchars($result['exam_name']); ?> - <?php echo htmlspecialchars($courseName); ?></p>
                
                <div class="review-stats">
                    <div class="review-stat">
                        <div class="review-stat-value"><?php echo $result['Result']; ?>%</div>
                        <div class="review-stat-label">Your Score</div>
                    </div>
                    <div class="review-stat">
                        <div class="review-stat-value" style="color: #4ade80;"><?php echo $correctAnswers; ?></div>
                        <div class="review-stat-label">Correct Answers</div>
                    </div>
                    <div class="review-stat">
                        <div class="review-stat-value" style="color: #f87171;"><?php echo $incorrectAnswers; ?></div>
                        <div class="review-stat-label">Incorrect Answers</div>
                    </div>
                    <div class="review-stat">
                        <div class="review-stat-value" style="color: #fbbf24;"><?php echo $unanswered; ?></div>
                        <div class="review-stat-label">Unanswered</div>
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 2rem;">
                <a href="Result-modern.php" class="btn btn-secondary">← Back to Results</a>
                <button onclick="window.print()" class="btn btn-primary">🖨️ Print Review</button>
            </div>

            <?php 
            $questions->data_seek(0);
            $qNum = 1;
            while($q = $questions->fetch_assoc()): 
                $studentAnswer = $q['selected_answer'];
                $correctAnswer = $q['Answer'];
                $isCorrect = $q['is_correct'];
                
                $questionClass = 'unanswered';
                if($studentAnswer) {
                    $questionClass = $isCorrect ? 'correct' : 'incorrect';
                }
            ?>
            <div class="question-review <?php echo $questionClass; ?>">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                    <h3 style="margin: 0; color: var(--primary-color);">Question <?php echo $qNum++; ?></h3>
                    <?php if(!$studentAnswer): ?>
                        <span class="answer-indicator indicator-unanswered">⚠️ Not Answered</span>
                    <?php elseif($isCorrect): ?>
                        <span class="answer-indicator indicator-correct">✓ Correct</span>
                    <?php else: ?>
                        <span class="answer-indicator indicator-wrong">✗ Incorrect</span>
                    <?php endif; ?>
                </div>
                
                <p style="font-size: 1.1rem; line-height: 1.6; margin: 1rem 0;">
                    <?php echo htmlspecialchars($q['question']); ?>
                </p>
                
                <div style="margin-top: 1.5rem;">
                    <?php
                    $options = ['A' => $q['Option1'], 'B' => $q['Option2'], 'C' => $q['Option3'], 'D' => $q['Option4']];
                    foreach($options as $letter => $text):
                        $optionClass = '';
                        $indicator = '';
                        
                        if($letter == $correctAnswer) {
                            $optionClass = 'correct-answer';
                            $indicator = '<span style="color: var(--success-color); font-weight: 700; margin-left: 1rem;">✓ Correct Answer</span>';
                        }
                        
                        if($letter == $studentAnswer && $letter != $correctAnswer) {
                            $optionClass = 'wrong-answer';
                            $indicator = '<span style="color: #dc3545; font-weight: 700; margin-left: 1rem;">✗ Your Answer</span>';
                        } elseif($letter == $studentAnswer && $letter == $correctAnswer) {
                            $indicator = '<span style="color: var(--success-color); font-weight: 700; margin-left: 1rem;">✓ Your Answer (Correct)</span>';
                        }
                    ?>
                    <div class="option-review <?php echo $optionClass; ?>">
                        <strong><?php echo $letter; ?>.</strong> <?php echo htmlspecialchars($text); ?>
                        <?php echo $indicator; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </main>

    <footer class="modern-footer">
        <div class="container">
            <div class="footer-content">
                <p>&copy; 2026 Debre Markos University. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        const userDropdown = document.querySelector('.user-dropdown');
        if(userDropdown) {
            const userInfo = userDropdown.querySelector('.user-info');
            userInfo.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdown.classList.toggle('active');
            });
        }
    </script>
</body>
</html>
