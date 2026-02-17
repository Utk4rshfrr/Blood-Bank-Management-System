<?php
include('../includes/connection.php');

// Disable strict error reporting for this script to handle "duplicate column" warnings gracefully
mysqli_report(MYSQLI_REPORT_OFF);

echo "<h2>🚀 Upgrading Database to Premium Version...</h2><hr>";

function run_query($conn, $sql, $message) {
    try {
        if(mysqli_query($conn, $sql)){
            echo "<p style='color:green;'>✅ $message: Success</p>";
        } else {
            // Check if error is simply "Duplicate column" or "Table exists"
            if(strpos(mysqli_error($conn), "Duplicate") !== false || strpos(mysqli_error($conn), "already exists") !== false) {
                echo "<p style='color:blue;'>ℹ️ $message: Already exists (Skipped)</p>";
            } else {
                echo "<p style='color:red;'>⚠️ $message: " . mysqli_error($conn) . "</p>";
            }
        }
    } catch (Exception $e) {
        echo "<p style='color:blue;'>ℹ️ $message: Already done.</p>";
    }
}

// 1. UPGRADE 'donors' TABLE
run_query($connection, "ALTER TABLE donors ADD COLUMN address TEXT", "Adding 'address' to Donors");
run_query($connection, "ALTER TABLE donors ADD COLUMN blood_group VARCHAR(10)", "Adding 'blood_group' to Donors");
run_query($connection, "ALTER TABLE donors MODIFY COLUMN mobile VARCHAR(15)", "Fixing 'mobile' format in Donors");

// 2. UPGRADE 'patients' TABLE
run_query($connection, "ALTER TABLE patients ADD COLUMN address TEXT", "Adding 'address' to Patients");
run_query($connection, "ALTER TABLE patients ADD COLUMN blood_group VARCHAR(10)", "Adding 'blood_group' to Patients");
run_query($connection, "ALTER TABLE patients ADD COLUMN age INT", "Adding 'age' to Patients");
run_query($connection, "ALTER TABLE patients MODIFY COLUMN mobile VARCHAR(15)", "Fixing 'mobile' format in Patients");

// 3. UPGRADE 'donation' TABLE
run_query($connection, "ALTER TABLE donation ADD COLUMN date DATETIME DEFAULT CURRENT_TIMESTAMP", "Adding 'date' to Donation");

// 4. CREATE NEW 'patient_blood_requests' TABLE
// (The old 'requests' table is incompatible, we make a new one)
$sql_pbr = "CREATE TABLE IF NOT EXISTS patient_blood_requests (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    blood_group VARCHAR(10) NOT NULL,
    units_required INT NOT NULL,
    reason TEXT NOT NULL,
    status VARCHAR(20) DEFAULT 'Pending',
    request_date DATETIME DEFAULT CURRENT_TIMESTAMP
)";
run_query($connection, $sql_pbr, "Creating 'patient_blood_requests' table");

// 5. CREATE NEW 'donor_blood_requests' TABLE
$sql_dbr = "CREATE TABLE IF NOT EXISTS donor_blood_requests (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    donor_id INT NOT NULL,
    blood_group VARCHAR(10) NOT NULL,
    units_required INT NOT NULL,
    reason TEXT NOT NULL,
    status VARCHAR(20) DEFAULT 'Pending',
    request_date DATETIME DEFAULT CURRENT_TIMESTAMP
)";
run_query($connection, $sql_dbr, "Creating 'donor_blood_requests' table");

// 6. CREATE NEW 'patient_donation_offers' TABLE
$sql_pdo = "CREATE TABLE IF NOT EXISTS patient_donation_offers (
    donation_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    blood_group VARCHAR(10) NOT NULL,
    available_units INT NOT NULL,
    disease VARCHAR(255) DEFAULT 'None',
    status VARCHAR(20) DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
run_query($connection, $sql_pdo, "Creating 'patient_donation_offers' table");

echo "<hr><h3>🎉 Database Upgrade Complete!</h3>";
echo "<a href='dashboard.php' style='background:#e63946; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>Go to Admin Dashboard</a>";
?>