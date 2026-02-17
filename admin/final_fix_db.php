<?php
include('../includes/connection.php');

// Turn off error reporting to prevent crashes
mysqli_report(MYSQLI_REPORT_OFF);

echo "<h2>🛡️ Database Doctor: Smart Fixer</h2><hr>";

/**
 * Helper function to safely add a column only if it doesn't exist
 */
function safe_add_column($conn, $table, $column, $definition) {
    // Check if column exists
    $check = mysqli_query($conn, "SHOW COLUMNS FROM `$table` LIKE '$column'");
    
    if (mysqli_num_rows($check) > 0) {
        // Column exists
        echo "<p style='color:blue;'>ℹ️ Table <b>$table</b>: Column <b>$column</b> already exists. (Skipped)</p>";
    } else {
        // Column does not exist - Add it
        $sql = "ALTER TABLE `$table` ADD COLUMN $column $definition";
        if (mysqli_query($conn, $sql)) {
            echo "<p style='color:green;'>✅ Table <b>$table</b>: Added column <b>$column</b>.</p>";
        } else {
            echo "<p style='color:red;'>⚠️ Table <b>$table</b>: Failed to add <b>$column</b>. Error: " . mysqli_error($conn) . "</p>";
        }
    }
}

/**
 * Helper function to safely create a table if it doesn't exist
 */
function safe_create_table($conn, $table_name, $sql_content) {
    $check = mysqli_query($conn, "SHOW TABLES LIKE '$table_name'");
    
    if (mysqli_num_rows($check) > 0) {
        echo "<p style='color:blue;'>ℹ️ Table <b>$table_name</b> already exists. (Skipped)</p>";
    } else {
        if (mysqli_query($conn, $sql_content)) {
            echo "<p style='color:green;'>✅ Created Table <b>$table_name</b>.</p>";
        } else {
            echo "<p style='color:red;'>⚠️ Failed to create <b>$table_name</b>. Error: " . mysqli_error($conn) . "</p>";
        }
    }
}

// ====================================================
// 1. FIX 'donors' TABLE
// ====================================================
safe_add_column($connection, 'donors', 'address', 'TEXT');
safe_add_column($connection, 'donors', 'blood_group', 'VARCHAR(10)');
// Fix mobile length just in case
mysqli_query($connection, "ALTER TABLE donors MODIFY COLUMN mobile VARCHAR(20)");

// ====================================================
// 2. FIX 'patients' TABLE
// ====================================================
safe_add_column($connection, 'patients', 'address', 'TEXT');
safe_add_column($connection, 'patients', 'blood_group', 'VARCHAR(10)');
safe_add_column($connection, 'patients', 'age', 'INT');
mysqli_query($connection, "ALTER TABLE patients MODIFY COLUMN mobile VARCHAR(20)");

// ====================================================
// 3. FIX 'donation' TABLE
// ====================================================
safe_add_column($connection, 'donation', 'date', 'DATETIME DEFAULT CURRENT_TIMESTAMP');
// Note: 'disease' usually exists, but we check just in case you need it
safe_add_column($connection, 'donation', 'disease', "VARCHAR(255) DEFAULT 'None'");

// ====================================================
// 4. CREATE MISSING TABLES
// ====================================================

// Patient Requests
$sql_pbr = "CREATE TABLE patient_blood_requests (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    blood_group VARCHAR(10) NOT NULL,
    units_required INT NOT NULL,
    reason TEXT NOT NULL,
    status VARCHAR(20) DEFAULT 'Pending',
    request_date DATETIME DEFAULT CURRENT_TIMESTAMP
)";
safe_create_table($connection, 'patient_blood_requests', $sql_pbr);

// Donor Requests
$sql_dbr = "CREATE TABLE donor_blood_requests (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    donor_id INT NOT NULL,
    blood_group VARCHAR(10) NOT NULL,
    units_required INT NOT NULL,
    reason TEXT NOT NULL,
    status VARCHAR(20) DEFAULT 'Pending',
    request_date DATETIME DEFAULT CURRENT_TIMESTAMP
)";
safe_create_table($connection, 'donor_blood_requests', $sql_dbr);

// Patient Offers
$sql_pdo = "CREATE TABLE patient_donation_offers (
    donation_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    blood_group VARCHAR(10) NOT NULL,
    available_units INT NOT NULL,
    disease VARCHAR(255) DEFAULT 'None',
    status VARCHAR(20) DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
safe_create_table($connection, 'patient_donation_offers', $sql_pdo);

echo "<hr><h3>🎉 Database Repairs Complete!</h3>";
echo "<p>You can now go back to the dashboard. All errors should be gone.</p>";
echo "<a href='dashboard.php' style='background:#e63946; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>Go to Admin Dashboard</a>";
?>