<?php
include('../includes/connection.php');
echo "<h2>🛠️ Fixing Date & ID Columns...</h2>";

// 1. Add 'date' column to donation table
$sql1 = "ALTER TABLE donation ADD COLUMN date DATETIME DEFAULT CURRENT_TIMESTAMP";
if(@mysqli_query($connection, $sql1)){
    echo "<p style='color:green;'>✅ Added 'date' column.</p>";
} else {
    echo "<p style='color:blue;'>ℹ️ 'date' column exists or skipped.</p>";
}

// 2. Add 'date' column to donors table (if needed for registration date)
$sql2 = "ALTER TABLE donors ADD COLUMN date DATETIME DEFAULT CURRENT_TIMESTAMP";
@mysqli_query($connection, $sql2);

echo "<h3>🎉 Database Patched! <a href='../donor/dashboard.php'>Go to Donor Dashboard</a></h3>";
?>