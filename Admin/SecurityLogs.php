<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location:../index-modern.php");
    exit();
}

$con = new mysqli("localhost","root","","oes");

// Create security_logs table if it doesn't exist
$createTableSQL = "CREATE TABLE IF NOT EXISTS `security_logs` (
    `log_id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` VARCHAR(50),
    `user_type` VARCHAR(20),
    `action` VARCHAR(100),
    `ip_address` VARCHAR(45),
    `user_agent` TEXT,
    `status` VARCHAR(20),
    `details` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_user` (`user_id`),
    INDEX `idx_action` (`action`),
    INDEX `idx_date` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
$con->query($createTableSQL);

// Get filter parameters
$filterType = $_GET['type'] ?? 'all';
$filterStatus = $_GET['status'] ?? 'all';
$filterDate = $_GET['date'] ?? '';
$searchUser = $_GET['search'] ?? '';

// Build query
$whereConditions = array();
if($filterType != 'all') {
    $whereConditions[] = "user_type = '" . $con->real_escape_string($filterType) . "'";
}
if($filterStatus != 'all') {
    $whereConditions[] = "status = '" . $con->real_escape_string($filterStatus) . "'";
}
if($filterDate) {
    $whereConditions[] = "DATE(created_at) = '" . $con->real_escape_string($filterDate) . "'";
}
if($searchUser) {
    $whereConditions[] = "user_id LIKE '%" . $con->real_escape_string($searchUser) . "%'";
}

$whereClause = count($whereConditions) > 0 ? "WHERE " . implode(" AND ", $whereConditions) : "";

// Get logs with pagination
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$perPage = 50;
$offset = ($page - 1) * $perPage;

$logsQuery = "SELECT * FROM security_logs $whereClause ORDER BY created_at DESC LIMIT $perPage OFFSET $offset";
$logs = $con->query($logsQuery);

// Get total count
$countQuery = "SELECT COUNT(*) as total FROM security_logs $whereClause";
$totalResult = $con->query($countQuery);
$totalLogs = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalLogs / $perPage);

// Get statistics
$stats = array(
    'total' => $con->query("SELECT COUNT(*) as count FROM security_logs")->fetch_assoc()['count'],
    'today' => $con->query("SELECT COUNT(*) as count FROM security_logs WHERE DATE(created_at) = CURDATE()")->fetch_assoc()['count'],
    'failed' => $con->query("SELECT COUNT(*) as count FROM security_logs WHERE status = 'failed'")->fetch_assoc()['count'],
    'success' => $con->query("SELECT COUNT(*) as count FROM security_logs WHERE status = 'success'")->fetch_assoc()['count']
);

// Get recent suspicious activities
$suspiciousQuery = "SELECT * FROM security_logs 
    WHERE status = 'failed' OR action LIKE '%failed%' 
    ORDER BY created_at DESC LIMIT 10";
$suspicious = $con->query($suspiciousQuery);

$con->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Logs & Monitoring - Admin</title>
    <link href="../assets/css/modern-v2.css" rel="stylesheet">
    <link href="../assets/css/admin-modern-v2.css" rel="stylesheet">
    <link href="../assets/css/admin-sidebar.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .log-entry {
            padding: 1rem;
            border-left: 4px solid #e0e0e0;
            margin-bottom: 0.5rem;
            background: white;
            border-radius: var(--radius-md);
            transition: all 0.3s ease;
        }
        
        .log-entry:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transform: translateX(2px);
        }
        
        .log-entry.success {
            border-left-color: var(--success-color);
        }
        
        .log-entry.failed {
            border-left-color: #dc3545;
            background: rgba(220, 53, 69, 0.05);
        }
        
        .log-entry.warning {
            border-left-color: #ffc107;
        }
        
        .log-meta {
            display: flex;
            gap: 1.5rem;
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-top: 0.5rem;
        }
        
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: var(--radius-sm);
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
        }
        
        .status-success {
            background: var(--success-color);
            color: white;
        }
        
        .status-failed {
            background: #dc3545;
            color: white;
        }
        
        .status-warning {
            background: #ffc107;
            color: var(--primary-dark);
        }
    </style>
</head>
<body class="admin-layout">
    <?php include 'sidebar-component.php'; ?>

    <div class="admin-main-content">
        <?php include 'header-component.php'; ?>

        <div class="admin-content">
            <div class="page-header">
                <h1>🔒 Security Logs & Monitoring</h1>
                <p>Monitor system security and user activities</p>
            </div>

            <!-- Statistics -->
            <div class="stats-grid">
                <div class="stat-card stat-primary">
                    <div class="stat-icon">📊</div>
                    <div class="stat-details">
                        <div class="stat-value"><?php echo number_format($stats['total']); ?></div>
                        <div class="stat-label">Total Logs</div>
                    </div>
                </div>

                <div class="stat-card stat-info">
                    <div class="stat-icon">📅</div>
                    <div class="stat-details">
                        <div class="stat-value"><?php echo number_format($stats['today']); ?></div>
                        <div class="stat-label">Today's Activities</div>
                    </div>
                </div>

                <div class="stat-card stat-success">
                    <div class="stat-icon">✅</div>
                    <div class="stat-details">
                        <div class="stat-value"><?php echo number_format($stats['success']); ?></div>
                        <div class="stat-label">Successful Actions</div>
                    </div>
                </div>

                <div class="stat-card stat-danger">
                    <div class="stat-icon">⚠️</div>
                    <div class="stat-details">
                        <div class="stat-value"><?php echo number_format($stats['failed']); ?></div>
                        <div class="stat-label">Failed Attempts</div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">🔍 Filter Logs</h3>
                </div>
                <div style="padding: 1.5rem;">
                    <form method="GET" class="form-row">
                        <div class="form-group">
                            <label>User Type</label>
                            <select name="type" class="form-control">
                                <option value="all" <?php echo $filterType == 'all' ? 'selected' : ''; ?>>All Types</option>
                                <option value="admin" <?php echo $filterType == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                <option value="instructor" <?php echo $filterType == 'instructor' ? 'selected' : ''; ?>>Instructor</option>
                                <option value="student" <?php echo $filterType == 'student' ? 'selected' : ''; ?>>Student</option>
                                <option value="exam_committee" <?php echo $filterType == 'exam_committee' ? 'selected' : ''; ?>>Exam Committee</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="all" <?php echo $filterStatus == 'all' ? 'selected' : ''; ?>>All Status</option>
                                <option value="success" <?php echo $filterStatus == 'success' ? 'selected' : ''; ?>>Success</option>
                                <option value="failed" <?php echo $filterStatus == 'failed' ? 'selected' : ''; ?>>Failed</option>
                                <option value="warning" <?php echo $filterStatus == 'warning' ? 'selected' : ''; ?>>Warning</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Date</label>
                            <input type="date" name="date" class="form-control" value="<?php echo $filterDate; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Search User</label>
                            <input type="text" name="search" class="form-control" placeholder="User ID..." value="<?php echo htmlspecialchars($searchUser); ?>">
                        </div>
                        
                        <div class="form-group" style="display: flex; align-items: flex-end;">
                            <button type="submit" class="btn btn-primary">🔍 Filter</button>
                            <a href="SecurityLogs.php" class="btn btn-secondary" style="margin-left: 0.5rem;">Clear</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="grid grid-2 mt-4">
                <!-- Security Logs -->
                <div class="card" style="grid-column: 1 / -1;">
                    <div class="card-header">
                        <h3 class="card-title">📋 Security Logs</h3>
                    </div>
                    <div style="padding: 1.5rem;">
                        <?php if($logs && $logs->num_rows > 0): ?>
                            <?php while($log = $logs->fetch_assoc()): ?>
                            <div class="log-entry <?php echo $log['status']; ?>">
                                <div style="display: flex; justify-content: space-between; align-items: start;">
                                    <div style="flex: 1;">
                                        <strong style="color: var(--primary-color);"><?php echo htmlspecialchars($log['action']); ?></strong>
                                        <div class="log-meta">
                                            <span>👤 <?php echo htmlspecialchars($log['user_id'] ?? 'Unknown'); ?></span>
                                            <span>🏷️ <?php echo htmlspecialchars($log['user_type']); ?></span>
                                            <span>🌐 <?php echo htmlspecialchars($log['ip_address']); ?></span>
                                            <span>🕐 <?php echo date('M j, Y - g:i A', strtotime($log['created_at'])); ?></span>
                                        </div>
                                        <?php if($log['details']): ?>
                                        <div style="margin-top: 0.5rem; font-size: 0.9rem; color: var(--text-secondary);">
                                            <?php echo htmlspecialchars($log['details']); ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <span class="status-badge status-<?php echo $log['status']; ?>">
                                        <?php echo $log['status']; ?>
                                    </span>
                                </div>
                            </div>
                            <?php endwhile; ?>
                            
                            <!-- Pagination -->
                            <?php if($totalPages > 1): ?>
                            <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 2rem;">
                                <?php if($page > 1): ?>
                                <a href="?page=<?php echo $page-1; ?>&type=<?php echo $filterType; ?>&status=<?php echo $filterStatus; ?>&date=<?php echo $filterDate; ?>&search=<?php echo $searchUser; ?>" class="btn btn-secondary">← Previous</a>
                                <?php endif; ?>
                                
                                <span style="padding: 0.5rem 1rem; background: var(--bg-light); border-radius: var(--radius-md);">
                                    Page <?php echo $page; ?> of <?php echo $totalPages; ?>
                                </span>
                                
                                <?php if($page < $totalPages): ?>
                                <a href="?page=<?php echo $page+1; ?>&type=<?php echo $filterType; ?>&status=<?php echo $filterStatus; ?>&date=<?php echo $filterDate; ?>&search=<?php echo $searchUser; ?>" class="btn btn-secondary">Next →</a>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div style="text-align: center; padding: 4rem 2rem;">
                                <div style="font-size: 4rem; margin-bottom: 1rem;">📋</div>
                                <h3 style="color: var(--text-secondary);">No Logs Found</h3>
                                <p>No security logs match your filters</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Suspicious Activities -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">⚠️ Recent Suspicious Activities</h3>
                    </div>
                    <div style="padding: 1.5rem;">
                        <?php if($suspicious && $suspicious->num_rows > 0): ?>
                            <?php while($sus = $suspicious->fetch_assoc()): ?>
                            <div style="padding: 1rem; background: rgba(220, 53, 69, 0.05); border-left: 3px solid #dc3545; border-radius: var(--radius-md); margin-bottom: 0.75rem;">
                                <strong style="color: #dc3545;"><?php echo htmlspecialchars($sus['action']); ?></strong>
                                <div style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 0.25rem;">
                                    <?php echo htmlspecialchars($sus['user_id']); ?> - <?php echo date('M j, g:i A', strtotime($sus['created_at'])); ?>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div style="text-align: center; padding: 2rem;">
                                <div style="font-size: 3rem; margin-bottom: 0.5rem;">✅</div>
                                <p style="color: var(--text-secondary);">No suspicious activities detected</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Security Tips -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">💡 Security Tips</h3>
                    </div>
                    <div style="padding: 1.5rem;">
                        <ul style="margin: 0; padding-left: 1.5rem; color: var(--text-secondary); line-height: 1.8;">
                            <li>Monitor failed login attempts regularly</li>
                            <li>Review suspicious IP addresses</li>
                            <li>Check for unusual activity patterns</li>
                            <li>Block accounts with multiple failed attempts</li>
                            <li>Keep security logs for audit purposes</li>
                            <li>Export logs periodically for backup</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/admin-sidebar.js"></script>
</body>
</html>
