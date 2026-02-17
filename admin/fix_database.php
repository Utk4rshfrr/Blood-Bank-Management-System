<?php
include('../includes/connection.php');

echo "<h2>🛠️ Database Auto-Fixer</h2>";

// 1. Array of SQL commands to create missing tables
$queries = [
    "CREATE TABLE IF NOT EXISTS patient_donation_offers (
        donation_id INT AUTO_INCREMENT PRIMARY KEY,
        patient_id INT NOT NULL,
        blood_group VARCHAR(10) NOT NULL,
        available_units INT NOT NULL,
        disease VARCHAR(255) DEFAULT 'None',
        status VARCHAR(20) DEFAULT 'Pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS patient_blood_requests (
        request_id INT AUTO_INCREMENT PRIMARY KEY,
        patient_id INT NOT NULL,
        blood_group VARCHAR(10) NOT NULL,
        units_required INT NOT NULL,
        reason TEXT,
        status VARCHAR(20) DEFAULT 'Pending',
        request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS donor_blood_requests (
        request_id INT AUTO_INCREMENT PRIMARY KEY,
        donor_id INT NOT NULL,
        blood_group VARCHAR(10) NOT NULL,
        units_required INT NOT NULL,
        reason TEXT,
        status VARCHAR(20) DEFAULT 'Pending',
        request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )"
];

// 2. Loop through and execute each query
foreach ($queries as $sql) {
    if (mysqli_query($connection, $sql)) {
        echo "<p style='color:green;'>✅ Table Checked/Created Successfully.</p>";
    } else {
        echo "<p style='color:red;'>❌ Error: " . mysqli_error($connection) . "</p>";
    }
}

echo "<h3>🎉 Fix Complete! <a href='dashboard.php'>Click here to go to Dashboard</a></h3>";
?>