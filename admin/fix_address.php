<?php
include('../includes/connection.php');

echo "<h2>🛠️ Fixing Missing Address Columns...</h2>";

// 1. Add 'address' to Donors table
$sql1 = "ALTER TABLE donors ADD COLUMN address TEXT";
if(mysqli_query($connection, $sql1)){
    echo "<p style='color:green;'>✅ Added 'address' column to Donors.</p>";
} else {
    echo "<p style='color:blue;'>ℹ️ Donors: " . mysqli_error($connection) . "</p>";
}

// 2. Add 'address' to Patients table (Just in case)
$sql2 = "ALTER TABLE patients ADD COLUMN address TEXT";
if(mysqli_query($connection, $sql2)){
    echo "<p style='color:green;'>✅ Added 'address' column to Patients.</p>";
} else {
    echo "<p style='color:blue;'>ℹ️ Patients: " . mysqli_error($connection) . "</p>";
}

echo "<h3>🎉 Database Patched! <a href='manage_users.php'>Go back to Manage Users</a></h3>";
?>