<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location:../index-modern.php");
    exit();
}

$con = new mysqli("localhost","root","","oes");
$message = '';
$messageType = '';
$importResults = [];

// Handle CSV upload
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['csv_file'])) {
    $userType = $_POST['import_type'];
    $file = $_FILES['csv_file'];
    
    if($file['error'] == 0) {
        $filename = $file['tmp_name'];
        $handle = fopen($filename, 'r');
        
        // Skip header row
        $header = fgetcsv($handle);
        
        $successCount = 0;
        $errorCount = 0;
        $errors = [];
        
        while(($data = fgetcsv($handle)) !== FALSE) {
            try {
                switch($userType) {
                    case 'student':
                        // Expected: ID, Name, Email, Password, Department, Semester, Status
                        if(count($data) >= 7) {
                            $stmt = $con->prepare("INSERT INTO student (Id, Name, Email, Password, dept_name, Semister, Status) VALUES (?, ?, ?, ?, ?, ?, ?)");
                            $stmt->bind_param("sssssss", $data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6]);
                            if($stmt->execute()) {
                                $successCount++;
                            } else {
                                $errorCount++;
                                $errors[] = "Row: {$data[0]} - " . $stmt->error;
                            }
                        }
                        break;
                        
                    case 'instructor':
                        // Expected: ID, Name, Email, Password, Department, Status
                        if(count($data) >= 6) {
                            $stmt = $con->prepare("INSERT INTO instructor (Inst_ID, Name, Email, Password, dept_name, Status) VALUES (?, ?, ?, ?, ?, ?)");
                            $stmt->bind_param("ssssss", $data[0], $data[1], $data[2], $data[3], $data[4], $data[5]);
                            if($stmt->execute()) {
                                $successCount++;
                            } else {
                                $errorCount++;
                                $errors[] = "Row: {$data[0]} - " . $stmt->error;
                            }
                        }
                        break;
                        
                    case 'exam_committee':
                        // Expected: ID, Name, Email, Password, Department, Status
                        if(count($data) >= 6) {
                            $stmt = $con->prepare("INSERT INTO exam_committee (committee_id, Name, Email, Password, dept_name, Status) VALUES (?, ?, ?, ?, ?, ?)");
                            $stmt->bind_param("ssssss", $data[0], $data[1], $data[2], $data[3], $data[4], $data[5]);
                            if($stmt->execute()) {
                                $successCount++;
                            } else {
                                $errorCount++;
                                $errors[] = "Row: {$data[0]} - " . $stmt->error;
                            }
                        }
                        break;
                }
            } catch(Exception $e) {
                $errorCount++;
                $errors[] = "Row error: " . $e->getMessage();
            }
        }
        
        fclose($handle);
        
        $importResults = [
            'success' => $successCount,
            'errors' => $errorCount,
            'error_details' => $errors
        ];
        
        if($successCount > 0) {
            $message = "Successfully imported {$successCount} users!";
            $messageType = 'success';
        }
        if($errorCount > 0) {
            $message .= " {$errorCount} errors occurred.";
            $messageType = $successCount > 0 ? 'warning' : 'danger';
        }
    } else {
        $message = 'Error uploading file. Please try again.';
        $messageType = 'danger';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Import - Admin</title>
    <link href="../assets/css/modern-v2.css" rel="stylesheet">
    <link href="../assets/css/admin-modern-v2.css" rel="stylesheet">
    <link href="../assets/css/admin-sidebar.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .template-card {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 2rem;
            border-radius: var(--radius-lg);
            margin-bottom: 2rem;
        }
        .upload-zone {
            border: 3px dashed var(--border-color);
            border-radius: var(--radius-lg);
            padding: 3rem;
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
        }
        .upload-zone:hover {
            border-color: var(--primary-color);
            background: rgba(0, 123, 255, 0.05);
        }
        .upload-zone.dragover {
            border-color: var(--success-color);
            background: rgba(40, 167, 69, 0.1);
        }
        .result-box {
            background: white;
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            margin-top: 2rem;
            border-left: 4px solid var(--primary-color);
        }
    </style>
</head>
<body class="admin-layout">
    <?php include 'sidebar-component.php'; ?>

    <div class="admin-main-content">
        <?php 
        $pageTitle = 'Bulk Import';
        include 'header-component.php'; 
        ?>

        <div class="admin-content">
            <div class="page-header">
                <h1>📥 Bulk User Import</h1>
                <p>Import multiple users at once using CSV files</p>
            </div>

            <?php if($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>" style="margin-bottom: 2rem; padding: 1.25rem; border-radius: var(--radius-lg);">
                <strong><?php echo $messageType == 'success' ? '✓' : ($messageType == 'warning' ? '⚠' : '✗'); ?></strong> <?php echo $message; ?>
            </div>
            <?php endif; ?>

            <?php if(!empty($importResults)): ?>
            <div class="result-box">
                <h3 style="margin-bottom: 1rem; color: var(--primary-color);">📊 Import Results</h3>
                <div class="grid grid-2" style="margin-bottom: 1rem;">
                    <div style="text-align: center; padding: 1rem; background: rgba(40, 167, 69, 0.1); border-radius: var(--radius-md);">
                        <div style="font-size: 2.5rem; font-weight: 800; color: var(--success-color);"><?php echo $importResults['success']; ?></div>
                        <div style="color: var(--text-secondary);">Successfully Imported</div>
                    </div>
                    <div style="text-align: center; padding: 1rem; background: rgba(220, 53, 69, 0.1); border-radius: var(--radius-md);">
                        <div style="font-size: 2.5rem; font-weight: 800; color: #dc3545;"><?php echo $importResults['errors']; ?></div>
                        <div style="color: var(--text-secondary);">Errors</div>
                    </div>
                </div>
                
                <?php if(!empty($importResults['error_details'])): ?>
                <details style="margin-top: 1rem;">
                    <summary style="cursor: pointer; font-weight: 600; color: var(--primary-color);">View Error Details</summary>
                    <div style="margin-top: 1rem; padding: 1rem; background: var(--bg-light); border-radius: var(--radius-md); max-height: 300px; overflow-y: auto;">
                        <?php foreach($importResults['error_details'] as $error): ?>
                        <div style="padding: 0.5rem; border-bottom: 1px solid var(--border-color); color: #dc3545;">
                            • <?php echo htmlspecialchars($error); ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </details>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <div class="grid grid-2">
                <!-- Upload Form -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">📤 Upload CSV File</h3>
                    </div>
                    <div style="padding: 2rem;">
                        <form method="POST" enctype="multipart/form-data" id="uploadForm">
                            <div class="form-group">
                                <label>Select Import Type *</label>
                                <select name="import_type" id="importType" class="form-control" required onchange="updateTemplate()">
                                    <option value="">-- Select Type --</option>
                                    <option value="student">Students</option>
                                    <option value="instructor">Instructors</option>
                                    <option value="exam_committee">Exam Committee</option>
                                </select>
                            </div>

                            <div class="upload-zone" id="uploadZone" onclick="document.getElementById('csvFile').click()">
                                <div style="font-size: 3rem; margin-bottom: 1rem;">📁</div>
                                <h3 style="margin-bottom: 0.5rem;">Drop CSV file here or click to browse</h3>
                                <p style="color: var(--text-secondary); margin-bottom: 1rem;">Maximum file size: 5MB</p>
                                <input type="file" name="csv_file" id="csvFile" accept=".csv" required style="display: none;" onchange="handleFileSelect(event)">
                                <div id="fileName" style="font-weight: 600; color: var(--primary-color);"></div>
                            </div>

                            <div style="background: var(--bg-light); padding: 1rem; border-radius: var(--radius-md); margin: 1.5rem 0;">
                                <strong>📋 CSV Format Requirements:</strong>
                                <ul style="margin: 0.5rem 0 0 1.5rem; color: var(--text-secondary);">
                                    <li>First row must be headers</li>
                                    <li>Use comma (,) as delimiter</li>
                                    <li>Ensure all required fields are present</li>
                                    <li>Download template below for correct format</li>
                                </ul>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                    📥 Import Users
                                </button>
                                <button type="reset" class="btn btn-secondary" onclick="resetForm()">
                                    Clear
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Templates -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">📄 CSV Templates</h3>
                    </div>
                    <div style="padding: 2rem;">
                        <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">
                            Download the appropriate template for your import type. Fill in the data and upload.
                        </p>

                        <!-- Student Template -->
                        <div class="template-card">
                            <h4 style="margin: 0 0 1rem 0;">👨‍🎓 Student Template</h4>
                            <p style="opacity: 0.9; margin-bottom: 1rem;">Required columns: ID, Name, Email, Password, Department, Semester, Status</p>
                            <button onclick="downloadTemplate('student')" class="btn btn-light">
                                ⬇️ Download Student Template
                            </button>
                        </div>

                        <!-- Instructor Template -->
                        <div class="template-card" style="background: linear-gradient(135deg, var(--success-color) 0%, #1e7e34 100%);">
                            <h4 style="margin: 0 0 1rem 0;">👨‍🏫 Instructor Template</h4>
                            <p style="opacity: 0.9; margin-bottom: 1rem;">Required columns: ID, Name, Email, Password, Department, Status</p>
                            <button onclick="downloadTemplate('instructor')" class="btn btn-light">
                                ⬇️ Download Instructor Template
                            </button>
                        </div>

                        <!-- Exam Committee Template -->
                        <div class="template-card" style="background: linear-gradient(135deg, var(--warning-color) 0%, #e0a800 100%);">
                            <h4 style="margin: 0 0 1rem 0;">👥 Exam Committee Template</h4>
                            <p style="opacity: 0.9; margin-bottom: 1rem;">Required columns: ID, Name, Email, Password, Department, Status</p>
                            <button onclick="downloadTemplate('exam_committee')" class="btn btn-light">
                                ⬇️ Download Committee Template
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">📚 Import Instructions</h3>
                </div>
                <div style="padding: 2rem;">
                    <div class="grid grid-3">
                        <div>
                            <h4 style="color: var(--primary-color); margin-bottom: 1rem;">1️⃣ Prepare Your Data</h4>
                            <ul style="color: var(--text-secondary); line-height: 1.8;">
                                <li>Download the appropriate CSV template</li>
                                <li>Fill in all required fields</li>
                                <li>Ensure IDs are unique</li>
                                <li>Use valid email addresses</li>
                                <li>Status: Active or Inactive</li>
                            </ul>
                        </div>
                        <div>
                            <h4 style="color: var(--primary-color); margin-bottom: 1rem;">2️⃣ Upload & Import</h4>
                            <ul style="color: var(--text-secondary); line-height: 1.8;">
                                <li>Select the import type</li>
                                <li>Upload your CSV file</li>
                                <li>Review the file name</li>
                                <li>Click "Import Users"</li>
                                <li>Wait for processing</li>
                            </ul>
                        </div>
                        <div>
                            <h4 style="color: var(--primary-color); margin-bottom: 1rem;">3️⃣ Review Results</h4>
                            <ul style="color: var(--text-secondary); line-height: 1.8;">
                                <li>Check success count</li>
                                <li>Review any errors</li>
                                <li>Fix errors in CSV if needed</li>
                                <li>Re-import failed records</li>
                                <li>Verify imported users</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/admin-sidebar.js"></script>
    <script>
        const templates = {
            student: {
                headers: ['ID', 'Name', 'Email', 'Password', 'Department', 'Semester', 'Status'],
                sample: ['STU001', 'John Doe', 'john@example.com', 'password123', 'Computer Science', '1', 'Active']
            },
            instructor: {
                headers: ['ID', 'Name', 'Email', 'Password', 'Department', 'Status'],
                sample: ['INS001', 'Dr. Jane Smith', 'jane@example.com', 'password123', 'Computer Science', 'Active']
            },
            exam_committee: {
                headers: ['ID', 'Name', 'Email', 'Password', 'Department', 'Status'],
                sample: ['COM001', 'Prof. Mike Johnson', 'mike@example.com', 'password123', 'Computer Science', 'Active']
            }
        };

        function downloadTemplate(type) {
            const template = templates[type];
            let csv = template.headers.join(',') + '\n';
            csv += template.sample.join(',') + '\n';
            
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `${type}_import_template.csv`;
            a.click();
            window.URL.revokeObjectURL(url);
        }

        function handleFileSelect(event) {
            const file = event.target.files[0];
            if(file) {
                document.getElementById('fileName').textContent = `Selected: ${file.name}`;
                document.getElementById('submitBtn').disabled = false;
            }
        }

        function resetForm() {
            document.getElementById('fileName').textContent = '';
            document.getElementById('submitBtn').disabled = true;
        }

        // Drag and drop
        const uploadZone = document.getElementById('uploadZone');
        
        uploadZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadZone.classList.add('dragover');
        });
        
        uploadZone.addEventListener('dragleave', () => {
            uploadZone.classList.remove('dragover');
        });
        
        uploadZone.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadZone.classList.remove('dragover');
            
            const file = e.dataTransfer.files[0];
            if(file && file.name.endsWith('.csv')) {
                document.getElementById('csvFile').files = e.dataTransfer.files;
                handleFileSelect({ target: { files: [file] } });
            } else {
                alert('Please drop a CSV file');
            }
        });

        function updateTemplate() {
            const type = document.getElementById('importType').value;
            // Could highlight the relevant template
        }
    </script>
</body>
</html>
<?php $con->close(); ?>
