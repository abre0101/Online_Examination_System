<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Schedule - Debre Markos University Health Campus</title>
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
                    <a href="index-modern.php#login" class="btn btn-primary btn-sm">Login</a>
                </div>
            </div>
        </div>
        <nav class="main-nav">
            <div class="container">
                <ul class="nav-menu">
                    <li><a href="../index-modern.php">Home</a></li>
                    <li><a href="../AboutUs-modern.php">About Us</a></li>
                    <li><a href="Shedule-modern.php" class="active">Schedule</a></li>
                    <li><a href="../Help-modern.php">Help</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="content-wrapper">
                <h1>📅 Examination Schedule</h1>
                <p class="text-secondary">View upcoming examination schedules. Login to see your personalized exam timetable.</p>

                <div class="alert alert-info mt-4">
                    <strong>ℹ️ Note:</strong> Please login to your account to view detailed examination schedules specific to your courses and department.
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">General Examination Information</h3>
                    </div>
                    
                    <div class="table-container mt-3">
                        <table>
                            <thead>
                                <tr>
                                    <th>Semester</th>
                                    <th>Exam Period</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Semester 1 - 2026/23</strong></td>
                                    <td>January 15 - February 10, 2023</td>
                                    <td><span style="color: var(--secondary-color); font-weight: 600;">Active</span></td>
                                    <td><a href="index-modern.php#login" class="btn btn-primary btn-sm">Login to View</a></td>
                                </tr>
                                <tr>
                                    <td><strong>Semester 2 - 2026/23</strong></td>
                                    <td>June 1 - June 30, 2023</td>
                                    <td><span style="color: var(--text-light); font-weight: 600;">Upcoming</span></td>
                                    <td><a href="index-modern.php#login" class="btn btn-primary btn-sm">Login to View</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="grid grid-2 mt-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">⏰ Important Dates</h3>
                        </div>
                        <ul style="list-style: none; padding: 0;">
                            <li style="padding: 0.75rem 0; border-bottom: 1px solid var(--border-color);">
                                <strong>Registration Deadline:</strong> January 10, 2023
                            </li>
                            <li style="padding: 0.75rem 0; border-bottom: 1px solid var(--border-color);">
                                <strong>Exam Start Date:</strong> January 15, 2023
                            </li>
                            <li style="padding: 0.75rem 0; border-bottom: 1px solid var(--border-color);">
                                <strong>Exam End Date:</strong> February 10, 2023
                            </li>
                            <li style="padding: 0.75rem 0;">
                                <strong>Results Publication:</strong> February 20, 2023
                            </li>
                        </ul>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">📋 Exam Guidelines</h3>
                        </div>
                        <ul style="list-style: none; padding: 0;">
                            <li style="padding: 0.75rem 0; border-bottom: 1px solid var(--border-color);">
                                ✓ Login 15 minutes before exam time
                            </li>
                            <li style="padding: 0.75rem 0; border-bottom: 1px solid var(--border-color);">
                                ✓ Ensure stable internet connection
                            </li>
                            <li style="padding: 0.75rem 0; border-bottom: 1px solid var(--border-color);">
                                ✓ Have your student ID ready
                            </li>
                            <li style="padding: 0.75rem 0;">
                                ✓ Read all instructions carefully
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">🔔 Notifications</h3>
                    </div>
                    <div style="padding: 1rem 0;">
                        <div style="padding: 1rem; background: var(--bg-secondary); border-radius: var(--radius-md); margin-bottom: 1rem;">
                            <strong>New Schedule Posted</strong>
                            <p style="margin: 0.5rem 0 0 0; color: var(--text-secondary);">The examination schedule for Semester 1 has been updated. Please check your dashboard for details.</p>
                            <small style="color: var(--text-light);">Posted: January 5, 2023</small>
                        </div>
                        <div style="padding: 1rem; background: var(--bg-secondary); border-radius: var(--radius-md);">
                            <strong>System Maintenance</strong>
                            <p style="margin: 0.5rem 0 0 0; color: var(--text-secondary);">The system will undergo maintenance on January 12, 2023 from 10:00 PM to 2:00 AM.</p>
                            <small style="color: var(--text-light);">Posted: January 3, 2023</small>
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
                <p>&copy; 2026 Debre Markos University Health Campus Online Examination System. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
