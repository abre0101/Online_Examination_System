<?php
// Final organization - Move public pages to public folder

echo "<!DOCTYPE html>
<html>
<head>
    <title>OES Final Organization</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); }
        h2 { color: #1a2b4a; border-bottom: 3px solid #d4af37; padding-bottom: 15px; margin-bottom: 30px; font-size: 2em; }
        h3 { color: #1a2b4a; margin-top: 30px; font-size: 1.5em; }
        .success { color: #28a745; padding: 5px 0; }
        .error { color: #dc3545; padding: 5px 0; }
        .info { color: #17a2b8; padding: 5px 0; }
        .summary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 25px; border-radius: 10px; margin: 30px 0; }
        .btn { display: inline-block; padding: 15px 30px; background: #1a2b4a; color: white; text-decoration: none; border-radius: 8px; margin: 10px 10px 0 0; font-weight: 600; }
        ul { list-style: none; padding: 0; }
        li { padding: 8px 0; border-bottom: 1px solid #f0f0f0; }
        .icon { font-size: 1.2em; margin-right: 10px; }
        .code-box { background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #1a2b4a; margin: 15px 0; font-family: monospace; }
    </style>
</head>
<body>
<div class='container'>";

echo "<h2>📁 OES Final Organization</h2>";
echo "<p style='font-size: 1.1em; color: #666;'>Creating the cleanest possible root folder structure...</p>";

// Create directories
$directories = [
    'public' => 'Public pages (About, Help, Schedule)',
    'database' => 'Database files'
];

echo "<h3>📂 Creating Directories:</h3>";
echo "<ul>";

foreach ($directories as $dir => $description) {
    if (!file_exists($dir)) {
        if (mkdir($dir, 0755, true)) {
            echo "<li class='success'><span class='icon'>✅</span> Created: <strong>$dir/</strong> ($description)</li>";
        } else {
            echo "<li class='error'><span class='icon'>❌</span> Failed to create: $dir/</li>";
        }
    } else {
        echo "<li class='info'><span class='icon'>ℹ️</span> Already exists: $dir/</li>";
    }
}

echo "</ul>";

// Define file movements
$fileMovements = [
    // Public pages -> public/
    'AboutUs-modern.php' => 'public/AboutUs-modern.php',
    'Help-modern.php' => 'public/Help-modern.php',
    'Shedule-modern.php' => 'public/Shedule-modern.php',
    
    // Database -> database/
    'oes.sql' => 'database/oes.sql',
];

echo "<h3>📦 Moving Files:</h3>";
echo "<ul>";

$movedFiles = 0;
$failedFiles = 0;

foreach ($fileMovements as $source => $destination) {
    if (file_exists($source)) {
        $destDir = dirname($destination);
        if (!file_exists($destDir)) {
            mkdir($destDir, 0755, true);
        }
        
        if (rename($source, $destination)) {
            echo "<li class='success'><span class='icon'>✅</span> Moved: <strong>$source</strong> → <strong>$destination</strong></li>";
            $movedFiles++;
        } else {
            echo "<li class='error'><span class='icon'>❌</span> Failed to move: $source</li>";
            $failedFiles++;
        }
    } else {
        echo "<li class='info'><span class='icon'>ℹ️</span> Not found: $source</li>";
    }
}

echo "</ul>";

// Create index.html files for security
$indexContent = "<!DOCTYPE html><html><head><title>Access Denied</title></head><body><h1>403 - Access Denied</h1></body></html>";
foreach (['public', 'database'] as $dir) {
    if (file_exists($dir) && !file_exists("$dir/index.html")) {
        file_put_contents("$dir/index.html", $indexContent);
    }
}

echo "<div class='summary'>";
echo "<h3>📊 Final Root Folder Structure</h3>";
echo "<div class='code-box'>";
echo "OES/<br>";
echo "├── 📄 <strong>index-modern.php</strong> (Main landing page)<br>";
echo "│<br>";
echo "├── 📁 <strong>public/</strong> (Public pages)<br>";
echo "│   ├── AboutUs-modern.php<br>";
echo "│   ├── Help-modern.php<br>";
echo "│   └── Shedule-modern.php<br>";
echo "│<br>";
echo "├── 📁 <strong>database/</strong> (Database files)<br>";
echo "│   └── oes.sql<br>";
echo "│<br>";
echo "├── 📁 <strong>auth/</strong> (Authentication)<br>";
echo "├── 📁 <strong>docs/</strong> (Documentation)<br>";
echo "├── 📁 <strong>utils/</strong> (Utilities)<br>";
echo "├── 📁 <strong>assets/</strong> (CSS, JS)<br>";
echo "├── 📁 <strong>images/</strong> (Images)<br>";
echo "├── 📁 <strong>Student/</strong> (Student Portal)<br>";
echo "├── 📁 <strong>Admin/</strong> (Admin Portal)<br>";
echo "├── 📁 <strong>Instructor/</strong> (Instructor Portal)<br>";
echo "└── 📁 <strong>ExamCommittee/</strong> (Exam Committee)<br>";
echo "</div>";
echo "</div>";

if ($failedFiles == 0) {
    echo "<div style='background: #d4edda; border: 2px solid #28a745; color: #155724; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
    echo "<h3 style='color: #155724; margin-top: 0;'>✅ Organization Complete!</h3>";
    echo "<p style='font-size: 1.1em;'><strong>Root folder now has only 1 file:</strong> index-modern.php</p>";
    echo "<p style='margin-bottom: 0;'>All other files are organized in proper folders!</p>";
    echo "</div>";
    
    echo "<h3>⚠️ Important: Update Links</h3>";
    echo "<p>You need to update links in your pages:</p>";
    echo "<div class='code-box'>";
    echo "Old: href='AboutUs-modern.php'<br>";
    echo "New: href='public/AboutUs-modern.php'<br><br>";
    echo "Old: href='Help-modern.php'<br>";
    echo "New: href='public/Help-modern.php'<br><br>";
    echo "Old: href='Shedule-modern.php'<br>";
    echo "New: href='public/Shedule-modern.php'<br>";
    echo "</div>";
} else {
    echo "<div style='background: #fff3cd; border: 2px solid #ffc107; color: #856404; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
    echo "<h3 style='color: #856404; margin-top: 0;'>⚠️ Organization Completed with Errors</h3>";
    echo "<p style='margin-bottom: 0;'>Some files could not be moved.</p>";
    echo "</div>";
}

echo "<a href='index-modern.php' class='btn'>🏠 Go to Home</a>";
echo "<a href='Student/index-modern.php' class='btn'>👨‍🎓 Student Dashboard</a>";

echo "</div></body></html>";
?>
