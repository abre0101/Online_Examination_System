<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debre Markos University Health Campus - Online Examination System</title>
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
                    <a href="#login" class="btn btn-primary btn-sm">Login</a>
                </div>
            </div>
        </div>
        <nav class="main-nav">
            <div class="container">
                <ul class="nav-menu">
                    <li><a href="index-modern.php" class="active">Home</a></li>
                    <li><a href="AboutUs-modern.php">About Us</a></li>
                    <li><a href="Help-modern.php">Help</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Hero Section -->
            <section class="hero-section">
                <div class="hero-content">
                    <h1>Welcome to Online Examination System</h1>
                    <p>Secure, Efficient, and Modern Examination Platform</p>
                    
                    <div class="hero-features">
                        <div class="feature-card">
                            <div class="feature-icon">👨‍🎓</div>
                            <span class="stat-number">1000+</span>
                            <h3>Students</h3>
                            <p>Active learners taking exams online</p>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon">👨‍🏫</div>
                            <span class="stat-number">50+</span>
                            <h3>Instructors</h3>
                            <p>Expert faculty managing assessments</p>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon">📊</div>
                            <span class="stat-number">98%</span>
                            <h3>Success Rate</h3>
                            <p>Reliable and secure exam delivery</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Login Section -->
            <section id="login" class="login-container">
                <div class="login-card">
                    <div class="login-header">
                        <h2>Sign In</h2>
                        <p>Login to access your account</p>
                    </div>
                    <div class="login-body">
                        <form name="form1" method="post" action="login.php">
                            <!-- Hidden field for user type - defaults to Student -->
                            <input type="hidden" name="cmbType" value="Student">
                            
                            <div class="form-group">
                                <label for="txtUserName">Username</label>
                                <input type="text" 
                                       class="form-control" 
                                       name="txtUserName" 
                                       id="txtUserName" 
                                       placeholder="Enter your username"
                                       required>
                            </div>

                            <div class="form-group">
                                <label for="txtPassword">Password</label>
                                <input type="password" 
                                       class="form-control" 
                                       name="txtPassword" 
                                       id="txtPassword" 
                                       placeholder="Enter your password"
                                       required>
                            </div>

                            <div class="form-group" style="text-align: right; margin-top: -0.5rem; margin-bottom: 1.5rem;">
                                <a href="forgot-password.php" style="color: var(--secondary-color); font-weight: 600; font-size: 0.9rem;">Forgot Your Password?</a>
                            </div>

                            <button type="submit" name="logined" class="btn btn-primary btn-block">
                                Login to System
                            </button>

                            <div style="text-align: center; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 2px solid var(--border-color);">
                                <p style="color: var(--text-secondary); margin: 0;">
                                    Go to <a href="institute-login.php" style="color: var(--primary-color); font-weight: 600;">Institutes login?</a>
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
                <p>&copy; 2026 Debre Markos University Health Campus Online Examination System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Form validation
        document.querySelector('form[name="form1"]').addEventListener('submit', function(e) {
            const username = document.getElementById('txtUserName').value.trim();
            const password = document.getElementById('txtPassword').value.trim();
            const userType = document.getElementById('cmbType').value;

            if (!username) {
                alert('Please enter your username');
                e.preventDefault();
                return false;
            }

            if (!password) {
                alert('Please enter your password');
                e.preventDefault();
                return false;
            }

            if (!userType) {
                alert('Please select a user type');
                e.preventDefault();
                return false;
            }
        });

        // Smooth scroll to login
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>
