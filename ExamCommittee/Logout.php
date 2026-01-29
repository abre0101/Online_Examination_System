<?php
session_start();

// Store session info before destroying
$user_name = $_SESSION['Name'] ?? 'User';
$session_duration = isset($_SESSION['login_time']) ? time() - $_SESSION['login_time'] : 0;

// Clear session
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - Exam Committee</title>
    <link href="../assets/css/modern-v2.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #003366 0%, #001a33 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
        }
        
        .logout-container {
            background: white;
            border-radius: 16px;
            padding: 3rem;
            max-width: 500px;
            width: 90%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .logout-icon {
            font-size: 5rem;
            margin-bottom: 1.5rem;
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.5); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <div class="logout-icon">👋</div>
        <h1 style="color: #003366; font-size: 2rem; font-weight: 800; margin-bottom: 1rem;">
            Goodbye, <?php echo htmlspecialchars($user_name); ?>!
        </h1>
        <p style="color: #666; margin-bottom: 2rem; line-height: 1.6;">
            You have been successfully logged out. Thank you for using the Online Examination System.
        </p>
        
        <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem; text-align: left;">
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #e0e0e0;">
                <span><strong>Session Duration:</strong></span>
                <span><?php echo gmdate("H:i:s", $session_duration); ?></span>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #e0e0e0;">
                <span><strong>Logout Time:</strong></span>
                <span><?php echo date('h:i A'); ?></span>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0;">
                <span><strong>Date:</strong></span>
                <span><?php echo date('M d, Y'); ?></span>
            </div>
        </div>
        
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <a href="../auth/institute-login.php" class="btn btn-primary btn-block">
                🔐 Login Again
            </a>
            <a href="../index-modern.php" class="btn btn-secondary btn-block">
                🏠 Go to Homepage
            </a>
        </div>
        
        <div style="font-size: 0.9rem; color: #666; margin-top: 1rem;">
            <p>Redirecting to login page in <span id="countdown">10</span> seconds...</p>
        </div>
        
        <div style="margin-top: 2rem; padding-top: 2rem; border-top: 2px solid #e0e0e0;">
            <p style="font-size: 0.85rem; color: #666;">
                For security reasons, please close your browser if you're on a shared computer.
            </p>
        </div>
    </div>

    <script>
        let seconds = 10;
        const countdownElement = document.getElementById('countdown');
        
        const interval = setInterval(() => {
            seconds--;
            countdownElement.textContent = seconds;
            
            if(seconds <= 0) {
                clearInterval(interval);
                window.location.href = '../auth/institute-login.php';
            }
        }, 1000);
    </script>
</body>
</html>
