<?php
include('../includes/connection.php');

// 1. Turn off strict error crashing for this script only
mysqli_report(MYSQLI_REPORT_OFF);

echo "<h2>🛡️ Robust Database Updater</h2>";

$queries = [
    // 1. Add 'disease' column
    "ALTER TABLE donation ADD COLUMN disease VARCHAR(255) DEFAULT 'None'",
    
    // 2. Add 'blood_group' column
    "ALTER TABLE donation ADD COLUMN blood_group VARCHAR(10) AFTER donor_id",
    
    // 3. Update 'donors' table
    "ALTER TABLE donors MODIFY COLUMN mobile VARCHAR(20)",
    
    // 4. Create 'patient_donation_offers'
    "CREATE TABLE IF NOT EXISTS patient_donation_offers (
        donation_id INT AUTO_INCREMENT PRIMARY KEY,
        patient_id INT NOT NULL,
        blood_group VARCHAR(10) NOT NULL,
        available_units INT NOT NULL,
        disease VARCHAR(255) DEFAULT 'None',
        status VARCHAR(20) DEFAULT 'Pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )"
];

foreach ($queries as $sql) {
    // Try to run the query
    $result = mysqli_query($connection, $sql);
    
    if ($result) {
        echo "<p style='color:green;'>✅ Success: Query executed.</p>";
    } else {
        $error = mysqli_error($connection);
        // If error is "Duplicate column", that's actually GOOD (it means it's already there)
        if (strpos($error, "Duplicate") !== false) {
            echo "<p style='color:blue;'>ℹ️ Skipped: Column already exists (This is good!).</p>";
        } else {
            echo "<p style='color:red;'>⚠️ Error: $error</p>";
        }
    }
}

echo "<h3>🎉 Database Update Complete! <a href='../donor/dashboard.php'>Go to Donor Dashboard</a></h3>";
?>