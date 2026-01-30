<?php
session_start();
if(!isset($_SESSION['Name'])){
    header("Location:../auth/institute-login.php");
    exit();
}

$con = new mysqli("localhost","root","","oes");
$pageTitle = "Approval History";

// Get filter parameters
$filterStatus = isset($_GET['status']) ? $_GET['status'] : 'all';
$filterDate = isset($_GET['date']) ? $_GET['date'] : 'all';
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Build WHERE clause
$whereConditions = [];
if($filterStatus != 'all') {
    $whereConditions[] = "qp.approval_status = '$filterStatus'";
}
if($filterDate == 'today') {
    $whereConditions[] = "DATE(qp.review_date) = CURDATE()";
} elseif($filterDate == 'week') {
    $whereConditions[] = "qp.review_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
} elseif($filterDate == 'month') {
    $whereConditions[] = "qp.review_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
}
if($searchQuery) {
    $whereConditions[] = "(qp.Question LIKE '%$searchQuery%' OR ec.exam_name LIKE '%$searchQuery%' OR qp.reviewed_by LIKE '%$searchQuery%')";
}

$whereClause = !empty($whereConditions) ? "WHERE " . implode(" AND ", $whereConditions) : "";

// Get approval history
$history = $con->query("SELECT qp.*, ec.exam_name 
    FROM question_page qp 
    LEFT JOIN exam_category ec ON qp.exam_id = ec.exam_id 
    $whereClause
    ORDER BY qp.review_date DESC 
    LIMIT 100");

// Get statistics
$stats = [];
$stats['total_reviewed'] = $con->query("SELECT COUNT(*) as count FROM question_page WHERE review_date IS NOT NULL")->fetch_assoc()['count'];
$stats['approved'] = $con->query("SELECT COUNT(*) as count FROM question_page WHERE approval_status = 'approved'")->fetch_assoc()['count'];
$stats['revision'] = $con->query("SELECT COUNT(*) as count FROM question_page WHERE approval_status = 'revision'")->fetch_assoc()['count'];
$stats['rejected'] = $con->query("SELECT COUNT(*) as count FROM question_page WHERE approval_status = 'rejected'")->fetch_assoc()['count'];
$stats['pending'] = $con->query("SELECT COUNT(*) as count FROM question_page WHERE approval_status = 'pending' OR approval_status IS NULL")->fetch_assoc()['count'];

// Get reviewer statistics
$reviewerStats = $con->query("SELECT reviewed_by, 
    COUNT(*) as total_reviews,
    SUM(CASE WHEN approval_status = 'approved' THEN 1 ELSE 0 END) as approved_count,
    SUM(CASE WHEN approval_status = 'revision' THEN 1 ELSE 0 END) as revision_count,
    SUM(CASE WHEN approval_status = 'rejected' THEN 1 ELSE 0 END) as rejected_count
    FROM question_page 
    WHERE reviewed_by IS NOT NULL 
    GROUP BY reviewed_by 
    ORDER BY total_reviews DESC 
    LIMIT 10");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approval History - Exam Committee</title>
    <link href="../assets/css/modern-v2.css" rel="stylesheet">
    <link href="../assets/css/admin-modern-v2.css" rel="stylesheet">
    <link href="../assets/css/admin-sidebar.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .history-item {
            background: white;
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--border-color);
            transition: all 0.3s;
        }
        .history-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .history-item.approved { border-left-color: var(--success-color); }
        .history-item.revision { border-left-color: #ff9800; }
        .history-item.rejected { border-left-color: #dc3545; }
        .history-item.pending { border-left-color: var(--warning-color); }
        
        .timeline {
            position: relative;
            padding-left: 2rem;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--border-color);
        }
        .timeline-item {
            position: relative;
            padding-bottom: 2rem;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -2.4rem;
            top: 0.5rem;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--primary-color);
            border: 3px solid white;
            box-shadow: 0 0 0 2px var(--primary-color);
        }
    </style>
</head>
<body class="admin-layout">
    <?php include 'sidebar-component.php'; ?>

    <div class="admin-main-content">
        <?php include 'header-component.php'; ?>

        <div class="admin-content">
            <div class="page-header">
                <h1>📜 Approval History</h1>
                <p>Complete audit trail of all question reviews and approvals</p>
            </div>

            <!-- Statistics -->
            <div class="grid grid-5" style="margin-bottom: 2rem;">
                <div style="background: white; padding: 1.5rem; border-radius: var(--radius-lg); text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div style="font-size: 2rem; font-weight: 800; color: var(--primary-color);">
                        <?php echo $stats['total_reviewed']; ?>
                    </div>
                    <div style="font-size: 0.85rem; color: var(--text-secondary);">Total Reviewed</div>
                </div>
                <div style="background: white; padding: 1.5rem; border-radius: var(--radius-lg); text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div style="font-size: 2rem; font-weight: 800; color: var(--success-color);">
                        <?php echo $stats['approved']; ?>
                    </div>
                    <div style="font-size: 0.85rem; color: var(--text-secondary);">Approved</div>
                </div>
                <div style="background: white; padding: 1.5rem; border-radius: var(--radius-lg); text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div style="font-size: 2rem; font-weight: 800; color: #ff9800;">
                        <?php echo $stats['revision']; ?>
                    </div>
                    <div style="font-size: 0.85rem; color: var(--text-secondary);">Revision</div>
                </div>
                <div style="background: white; padding: 1.5rem; border-radius: var(--radius-lg); text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div style="font-size: 2rem; font-weight: 800; color: #dc3545;">
                        <?php echo $stats['rejected']; ?>
                    </div>
                    <div style="font-size: 0.85rem; color: var(--text-secondary);">Rejected</div>
                </div>
                <div style="background: white; padding: 1.5rem; border-radius: var(--radius-lg); text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div style="font-size: 2rem; font-weight: 800; color: var(--warning-color);">
                        <?php echo $stats['pending']; ?>
                    </div>
                    <div style="font-size: 0.85rem; color: var(--text-secondary);">Pending</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card" style="margin-bottom: 2rem;">
                <div style="padding: 1.5rem;">
                    <form method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
                        <div class="form-group" style="margin: 0;">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="all" <?php echo $filterStatus == 'all' ? 'selected' : ''; ?>>All Status</option>
                                <option value="approved" <?php echo $filterStatus == 'approved' ? 'selected' : ''; ?>>Approved</option>
                                <option value="revision" <?php echo $filterStatus == 'revision' ? 'selected' : ''; ?>>Revision</option>
                                <option value="rejected" <?php echo $filterStatus == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                <option value="pending" <?php echo $filterStatus == 'pending' ? 'selected' : ''; ?>>Pending</option>
                            </select>
                        </div>

                        <div class="form-group" style="margin: 0;">
                            <label>Date Range</label>
                            <select name="date" class="form-control">
                                <option value="all" <?php echo $filterDate == 'all' ? 'selected' : ''; ?>>All Time</option>
                                <option value="today" <?php echo $filterDate == 'today' ? 'selected' : ''; ?>>Today</option>
                                <option value="week" <?php echo $filterDate == 'week' ? 'selected' : ''; ?>>Last 7 Days</option>
                                <option value="month" <?php echo $filterDate == 'month' ? 'selected' : ''; ?>>Last 30 Days</option>
                            </select>
                        </div>

                        <div class="form-group" style="margin: 0;">
                            <label>Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Question, exam, reviewer..." value="<?php echo $searchQuery; ?>">
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary">🔍 Filter</button>
                            <a href="ApprovalHistory.php" class="btn btn-secondary">Clear</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="grid grid-2">
                <!-- History List -->
                <div style="grid-column: span 2;">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">📋 Review History</h3>
                        </div>
                        <div style="padding: 2rem;">
                            <?php if($history && $history->num_rows > 0): ?>
                            <div class="timeline">
                                <?php while($item = $history->fetch_assoc()): ?>
                                <div class="timeline-item">
                                    <div class="history-item <?php echo $item['approval_status'] ?? 'pending'; ?>">
                                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                                            <div>
                                                <h4 style="margin: 0 0 0.5rem 0; color: var(--primary-color);">
                                                    <?php echo $item['exam_name'] ?? 'Exam Question'; ?>
                                                </h4>
                                                <div style="font-size: 0.9rem; color: var(--text-secondary);">
                                                    Question ID: <?php echo $item['Question_ID']; ?> | 
                                                    Course: <?php echo $item['course_name']; ?>
                                                </div>
                                            </div>
                                            <span style="padding: 0.35rem 0.75rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600; background: <?php 
                                                echo $item['approval_status'] == 'approved' ? 'var(--success-color)' : 
                                                     ($item['approval_status'] == 'revision' ? '#ff9800' : 
                                                     ($item['approval_status'] == 'rejected' ? '#dc3545' : 'var(--warning-color)')); 
                                            ?>; color: white;">
                                                <?php echo ucfirst($item['approval_status'] ?? 'pending'); ?>
                                            </span>
                                        </div>

                                        <div style="background: var(--bg-light); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1rem;">
                                            <strong>Question:</strong>
                                            <p style="margin: 0.5rem 0 0 0; color: var(--text-secondary);">
                                                <?php echo substr($item['Question'], 0, 150); ?><?php echo strlen($item['Question']) > 150 ? '...' : ''; ?>
                                            </p>
                                        </div>

                                        <?php if($item['revision_comments']): ?>
                                        <div style="background: rgba(255, 152, 0, 0.1); padding: 1rem; border-radius: var(--radius-md); border-left: 4px solid #ff9800; margin-bottom: 1rem;">
                                            <strong>Comments:</strong>
                                            <p style="margin: 0.5rem 0 0 0; color: var(--text-secondary);">
                                                <?php echo $item['revision_comments']; ?>
                                            </p>
                                        </div>
                                        <?php endif; ?>

                                        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                                            <div style="font-size: 0.85rem; color: var(--text-secondary);">
                                                <strong>Reviewed by:</strong> <?php echo $item['reviewed_by'] ?? 'N/A'; ?>
                                            </div>
                                            <div style="font-size: 0.85rem; color: var(--text-secondary);">
                                                <?php echo $item['review_date'] ? date('M d, Y H:i', strtotime($item['review_date'])) : 'Not reviewed'; ?>
                                            </div>
                                        </div>

                                        <div style="margin-top: 1rem;">
                                            <a href="ViewQuestion.php?id=<?php echo $item['Question_ID']; ?>" class="btn btn-sm btn-primary">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            </div>
                            <?php else: ?>
                            <div style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                                <div style="font-size: 3rem; margin-bottom: 1rem;">📜</div>
                                <p>No approval history found matching your filters.</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Reviewer Statistics -->
                <div style="grid-column: span 2;">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">👥 Reviewer Statistics</h3>
                        </div>
                        <div style="padding: 2rem;">
                            <?php if($reviewerStats && $reviewerStats->num_rows > 0): ?>
                            <?php while($reviewer = $reviewerStats->fetch_assoc()): ?>
                            <div style="background: var(--bg-light); padding: 1.5rem; border-radius: var(--radius-md); margin-bottom: 1rem;">
                                <h4 style="margin: 0 0 1rem 0; color: var(--primary-color);">
                                    <?php echo $reviewer['reviewed_by']; ?>
                                </h4>
                                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; text-align: center;">
                                    <div>
                                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--primary-color);">
                                            <?php echo $reviewer['total_reviews']; ?>
                                        </div>
                                        <div style="font-size: 0.75rem; color: var(--text-secondary);">Total</div>
                                    </div>
                                    <div>
                                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--success-color);">
                                            <?php echo $reviewer['approved_count']; ?>
                                        </div>
                                        <div style="font-size: 0.75rem; color: var(--text-secondary);">Approved</div>
                                    </div>
                                    <div>
                                        <div style="font-size: 1.5rem; font-weight: 700; color: #ff9800;">
                                            <?php echo $reviewer['revision_count']; ?>
                                        </div>
                                        <div style="font-size: 0.75rem; color: var(--text-secondary);">Revision</div>
                                    </div>
                                    <div>
                                        <div style="font-size: 1.5rem; font-weight: 700; color: #dc3545;">
                                            <?php echo $reviewer['rejected_count']; ?>
                                        </div>
                                        <div style="font-size: 0.75rem; color: var(--text-secondary);">Rejected</div>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                            <?php else: ?>
                            <div style="text-align: center; padding: 2rem; color: var(--text-secondary);">
                                No reviewer statistics available
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Export Options -->
            <div class="card mt-4">
                <div style="padding: 2rem;">
                    <h3 style="margin: 0 0 1rem 0; color: var(--primary-color);">📥 Export Options</h3>
                    <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">
                        Download approval history for record keeping and analysis
                    </p>
                    <div style="display: flex; gap: 1rem;">
                        <button class="btn btn-success" onclick="alert('CSV export feature - Coming soon!')">
                            📊 Export to CSV
                        </button>
                        <button class="btn btn-primary" onclick="window.print()">
                            🖨️ Print Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/admin-sidebar.js"></script>
</body>
</html>
<?php $con->close(); ?>
