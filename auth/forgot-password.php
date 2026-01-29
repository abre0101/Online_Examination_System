<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Debre Markos University Health Campus</title>
    <link href="assets/css/modern-v2.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header class="modern-header">
        <div class="header-top">
            <div class="container">
                <div class="university-info">
                    <img src="images/logo1.png" alt="Debre Markos University Health Campus" class="university-logo" onerror="this.style.display='none'">
                    <div class="university-name">
                        <h1>Debre Markos University Health Campus</h1>
                        <p>Online Examination System</p>
                    </div>
                </div>
                <div class="header-actions">
                    <a href="index-modern.php" class="btn btn-secondary btn-sm">Back to Login</a>
                </div>
            </div>
        </div>
        <nav class="main-nav">
            <div class="container">
                <ul class="nav-menu">
                    <li><a href="index-modern.php">Home</a></li>
                    <li><a href="AboutUs-modern.php">About Us</a></li>
                    <li><a href="Shedule-modern.php">Schedule</a></li>
                    <li><a href="Help-modern.php">Help</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Forgot Password Section -->
            <section class="login-container">
                <div class="login-card">
                    <div class="login-header">
                        <h2>Forgot Password</h2>
                        <p>Your Password Will Send to Your Mail</p>
                    </div>
                    <div class="login-body">
                        <div class="alert alert-info">
                            <strong>📧 Password Recovery:</strong> Enter your details below and we'll send your password to your registered email address.
                        </div>
                        
                        <form name="form1" method="post" action="forgot-password-process.php">
                            <div class="form-group">
                                <label for="txtStudentName">Student Name</label>
                                <input type="text" 
                                       class="form-control" 
                                       name="txtStudentName" 
                                       id="txtStudentName" 
                                       placeholder="Enter your full name"
                                       required>
                            </div>

                            <div class="form-group">
                                <label for="txtStudentEmail">Student Email</label>
                                <input type="email" 
                                       class="form-control" 
                                       name="txtStudentEmail" 
                                       id="txtStudentEmail" 
                                       placeholder="Enter your email address"
                                       required>
                            </div>

                            <button type="submit" name="recover" class="btn btn-primary btn-block">
                                Send Password to Email
                            </button>

                            <div style="text-align: center; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 2px solid var(--border-color);">
                                <p style="color: var(--text-secondary); margin: 0;">
                                    Remember your password? <a href="index-modern.php" style="color: var(--primary-color); font-weight: 600;">Go to login page?</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- Footer -->
    <footer class="modern-footer">
        <div class="container">
            <div class="footer-content">
                <p>&copy; 2022 Debre Markos University Health Campus Online Examination System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Form validation
        document.querySelector('form[name="form1"]').addEventListener('submit', function(e) {
            const name = document.getElementById('txtStudentName').value.trim();
            const email = document.getElementById('txtStudentEmail').value.trim();

            if (!name) {
                alert('Please enter your name');
                e.preventDefault();
                return false;
            }

            if (!email) {
                alert('Please enter your email address');
                e.preventDefault();
                return false;
            }

            // Basic email validation
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                alert('Please enter a valid email address');
                e.preventDefault();
                return false;
            }
        });
    </script>
</body>
</html>
