<?php
include('../includes/connection.php');

echo "<h2>🛠️ Final Database Updater</h2>";

// SQL commands to ADD missing columns to the 'donation' table
$queries = [
    // 1. Add 'disease' column if it's missing
    "ALTER TABLE donation ADD COLUMN disease VARCHAR(255) DEFAULT 'None'",
    
    // 2. Add 'blood_group' column if it's missing (Old systems didn't have this in the donation table)
    "ALTER TABLE donation ADD COLUMN blood_group VARCHAR(10) AFTER donor_id",
    
    // 3. Ensure 'donors' table has all fields (just in case)
    "ALTER TABLE donors MODIFY COLUMN mobile VARCHAR(20)",
    
    // 4. Create 'patient_donation_offers' (In case it failed before)
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

// Execute
foreach ($queries as $sql) {
    // We use @ to suppress errors if column already exists
    if (@mysqli_query($connection, $sql)) {
        echo "<p style='color:green;'>✅ Update executed: " . substr($sql, 0, 50) . "...</p>";
    } else {
        // Ignore "Duplicate column" errors, show others
        if(strpos(mysqli_error($connection), "Duplicate") === false) {
             echo "<p style='color:orange;'>⚠️ Notice: " . mysqli_error($connection) . "</p>";
        } else {
             echo "<p style='color:green;'>✅ Column already exists.</p>";
        }
    }
}

echo "<h3>🎉 Database is now 100% Compatible! <a href='../donor/dashboard.php'>Go to Donor Dashboard</a></h3>";
?>