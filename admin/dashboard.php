<?php
session_start();
include('../includes/connection.php');

// 1. Check Admin Login
if(!isset($_SESSION['admin_id'])){
    header('Location: login.php');
    exit();
}

// --- DATA CALCULATION LOGIC ---
$blood_groups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
$zero_stock_groups = [];
$stock_data = [];
$monthly_stats = [];

// Calculate Stock & Monthly Stats for each blood group
foreach($blood_groups as $bg){
    // INCOMING (Donations Approved)
    $q_in1 = "SELECT SUM(no_units) as t FROM donation WHERE blood_group='$bg' AND status=1";
    $q_in2 = "SELECT SUM(available_units) as t FROM patient_donation_offers WHERE blood_group='$bg' AND status='Approved'";
    $in1 = mysqli_fetch_assoc(mysqli_query($connection, $q_in1))['t'] ?? 0;
    $in2 = mysqli_fetch_assoc(mysqli_query($connection, $q_in2))['t'] ?? 0;
    
    // OUTGOING (Requests Approved)
    $q_out1 = "SELECT SUM(units_required) as t FROM patient_blood_requests WHERE blood_group='$bg' AND status='Approved'";
    $q_out2 = "SELECT SUM(units_required) as t FROM donor_blood_requests WHERE blood_group='$bg' AND status='Approved'";
    $out1 = mysqli_fetch_assoc(mysqli_query($connection, $q_out1))['t'] ?? 0;
    $out2 = mysqli_fetch_assoc(mysqli_query($connection, $q_out2))['t'] ?? 0;
    
    // Net Current Stock
    $current = max(0, ($in1 + $in2) - ($out1 + $out2));
    $stock_data[$bg] = $current;
    
    // Check for Zero Stock
    if($current == 0) {
        $zero_stock_groups[] = $bg;
    }

    // Monthly Logic (Current Month Only)
    $m_in = mysqli_fetch_assoc(mysqli_query($connection, "SELECT SUM(available_units) as t FROM patient_donation_offers WHERE blood_group='$bg' AND status='Approved' AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())"))['t'] ?? 0;
    
    $m_out_p = mysqli_fetch_assoc(mysqli_query($connection, "SELECT SUM(units_required) as t FROM patient_blood_requests WHERE blood_group='$bg' AND status='Approved' AND MONTH(request_date) = MONTH(CURRENT_DATE()) AND YEAR(request_date) = YEAR(CURRENT_DATE())"))['t'] ?? 0;
    $m_out_d = mysqli_fetch_assoc(mysqli_query($connection, "SELECT SUM(units_required) as t FROM donor_blood_requests WHERE blood_group='$bg' AND status='Approved' AND MONTH(request_date) = MONTH(CURRENT_DATE()) AND YEAR(request_date) = YEAR(CURRENT_DATE())"))['t'] ?? 0;
    
    $monthly_stats[$bg] = ['in' => $m_in, 'out' => ($m_out_p + $m_out_d)];
}

// Global Totals for Cards
$t_donors = mysqli_fetch_assoc(mysqli_query($connection, "SELECT count(*) as t FROM donors"))['t'] ?? 0;
$t_patients = mysqli_fetch_assoc(mysqli_query($connection, "SELECT count(*) as t FROM patients"))['t'] ?? 0;
$t_req = (mysqli_fetch_assoc(mysqli_query($connection, "SELECT count(*) as t FROM patient_blood_requests"))['t'] ?? 0) + (mysqli_fetch_assoc(mysqli_query($connection, "SELECT count(*) as t FROM donor_blood_requests"))['t'] ?? 0);
$t_don = (mysqli_fetch_assoc(mysqli_query($connection, "SELECT count(*) as t FROM donation"))['t'] ?? 0) + (mysqli_fetch_assoc(mysqli_query($connection, "SELECT count(*) as t FROM patient_donation_offers"))['t'] ?? 0);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
    
    <style>
        body { background-color: #f4f7f6; font-family: 'Poppins', sans-serif; }
        
        /* NAVBAR STYLES */
        .navbar { background: white !important; box-shadow: 0 2px 10px rgba(0,0,0,0.05); padding: 15px 30px; }
        .navbar-brand { color: #e63946 !important; font-weight: 800; font-size: 1.5rem; letter-spacing: 1px; }
        .nav-link { color: #555 !important; font-weight: 600; margin-left: 20px; transition: 0.3s; }
        .nav-link:hover, .nav-item.active .nav-link { color: #e63946 !important; transform: translateY(-2px); }
        .btn-logout { background: #e63946; color: white !important; padding: 8px 20px; border-radius: 50px; transition:0.3s;}
        .btn-logout:hover { background: #d62828; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(230, 57, 70, 0.2); }

        /* DASHBOARD CARDS */
        .stat-card {
            background: white; border-radius: 15px; padding: 30px; text-align: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.03); border-bottom: 4px solid transparent; transition: 0.3s;
            height: 100%; display: flex; flex-direction: column; justify-content: center; text-decoration: none !important;
        }
        .stat-card:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(230, 57, 70, 0.1); border-bottom: 4px solid #e63946; }
        .stat-icon { font-size: 2.5rem; margin-bottom: 15px; color: #e63946; }
        .stat-num { font-size: 2.5rem; font-weight: 800; color: #333; line-height: 1; }
        .stat-label { color: #888; font-weight: 500; font-size: 1rem; margin-top: 5px; }

        /* ALERT BOX */
        .alert-custom { 
            background: #fff5f5; border-left: 4px solid #e63946; color: #d62828; 
            padding: 15px 20px; border-radius: 8px; margin-bottom: 30px; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.05); animation: fadeIn 0.5s ease;
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

        /* TABLE STYLES */
        .custom-table { background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.03); }
        .custom-table th { background: #343a40; color: white; padding: 15px; border: none; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; font-size: 0.9rem; }
        .custom-table td { padding: 15px; border-bottom: 1px solid #f1f1f1; vertical-align: middle; font-weight: 500; }
        .badge-empty { background: #ffebee; color: #c62828; padding: 5px 10px; border-radius: 5px; font-size: 0.85rem; font-weight: 700; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">🩸 ADMIN PANEL</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#adminNav">
                <span class="navbar-toggler-icon" style="background-color: #eee; border-radius: 3px;"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNav">
                <ul class="navbar-nav ml-auto align-items-center">
                    <li class="nav-item active"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage_donations.php">Donations</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage_requests.php">Requests</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage_users.php">Users</a></li>
                    <li class="nav-item"><a class="nav-link btn-logout ml-3" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        
        <?php if(!empty($zero_stock_groups)): ?>
        <div class="alert-custom d-flex align-items-center">
            <span style="font-size: 1.5rem; margin-right: 15px;">⚠️</span>
            <div>
                <strong>Critical Low Stock:</strong> We have 0 units available for: 
                <u><?php echo implode(', ', $zero_stock_groups); ?></u>.
            </div>
        </div>
        <?php endif; ?>

        <div class="row mb-5">
            <div class="col-md-4 mb-4">
                <a href="manage_donations.php" class="stat-card">
                    <div class="stat-icon">🩸</div>
                    <div class="stat-num"><?php echo $t_don; ?></div>
                    <div class="stat-label">Total Donations</div>
                </a>
            </div>
            <div class="col-md-4 mb-4">
                <a href="manage_requests.php" class="stat-card">
                    <div class="stat-icon">🚑</div>
                    <div class="stat-num"><?php echo $t_req; ?></div>
                    <div class="stat-label">Total Requests</div>
                </a>
            </div>
            <div class="col-md-4 mb-4">
                <a href="manage_users.php" class="stat-card">
                    <div class="stat-icon">👥</div>
                    <div class="stat-num"><?php echo $t_donors + $t_patients; ?></div>
                    <div class="stat-label">Active Users</div>
                </a>
            </div>
        </div>

        <h4 class="mb-4 font-weight-bold text-dark">📊 Monthly Stock Report (<?php echo date('F Y'); ?>)</h4>
        <div class="custom-table">
            <table class="table mb-0 text-center">
                <thead>
                    <tr>
                        <th>Blood Group</th>
                        <th>Incoming (This Month)</th>
                        <th>Outgoing (This Month)</th>
                        <th>Current Live Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($blood_groups as $bg): ?>
                    <tr>
                        <td class="font-weight-bold text-danger" style="font-size: 1.1rem;"><?php echo $bg; ?></td>
                        
                        <td class="text-success font-weight-bold">
                            <?php echo $monthly_stats[$bg]['in'] > 0 ? '&uarr; +'.$monthly_stats[$bg]['in'] : '-'; ?>
                        </td>
                        
                        <td class="text-warning font-weight-bold">
                            <?php echo $monthly_stats[$bg]['out'] > 0 ? '&darr; -'.$monthly_stats[$bg]['out'] : '-'; ?>
                        </td>
                        
                        <td>
                            <?php 
                            if($stock_data[$bg] == 0) {
                                echo '<span class="badge-empty">Out of Stock</span>';
                            } else {
                                echo '<span style="font-size:1.1rem; font-weight:800; color:#333;">' . $stock_data[$bg] . ' Units</span>';
                            }
                            ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script src="../includes/juqery_latest.js"></script>
    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>