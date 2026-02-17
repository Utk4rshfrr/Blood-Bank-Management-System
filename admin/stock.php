<?php
session_start();
include('../includes/connection.php');
if(!isset($_SESSION['admin_id'])){ header('Location: login.php'); exit(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Blood Stock</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; font-family: 'Poppins', sans-serif; }
        .navbar { background: white !important; box-shadow: 0 2px 10px rgba(0,0,0,0.05); padding: 15px 30px; }
        .navbar-brand { color: #e63946 !important; font-weight: 800; font-size: 1.5rem; letter-spacing: 1px; }
        .nav-link { color: #555 !important; font-weight: 600; margin-left: 20px; transition: 0.3s; }
        .nav-link:hover { color: #e63946 !important; }
        
        .stock-card {
            background: white; border-radius: 15px; padding: 25px; text-align: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.03); border-bottom: 5px solid transparent; transition: 0.3s;
            margin-bottom: 25px;
        }
        .stock-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); }
        
        .blood-group { font-size: 2.5rem; font-weight: 800; color: #e63946; margin-bottom: 5px; }
        .units-badge { font-size: 1.2rem; font-weight: 600; color: #555; background: #f8f9fa; padding: 5px 15px; border-radius: 20px; }
        .critical { border-bottom-color: #e63946; }
        .healthy { border-bottom-color: #28a745; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">🩸 ADMIN PANEL</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Back to Dashboard</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <h3 class="font-weight-bold mb-4" style="border-left: 5px solid #e63946; padding-left: 15px;">Live Blood Stock</h3>
        
        <div class="row">
            <?php
            $blood_groups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
            
            foreach($blood_groups as $bg){
                // Calculate Stock (Same logic as Dashboard)
                // IN
                $in1 = mysqli_fetch_assoc(mysqli_query($connection, "SELECT SUM(no_units) as t FROM donation WHERE blood_group='$bg' AND status=1"))['t'] ?? 0;
                $in2 = mysqli_fetch_assoc(mysqli_query($connection, "SELECT SUM(available_units) as t FROM patient_donation_offers WHERE blood_group='$bg' AND status='Approved'"))['t'] ?? 0;
                // OUT
                $out1 = mysqli_fetch_assoc(mysqli_query($connection, "SELECT SUM(units_required) as t FROM patient_blood_requests WHERE blood_group='$bg' AND status='Approved'"))['t'] ?? 0;
                $out2 = mysqli_fetch_assoc(mysqli_query($connection, "SELECT SUM(units_required) as t FROM donor_blood_requests WHERE blood_group='$bg' AND status='Approved'"))['t'] ?? 0;
                
                $stock = max(0, ($in1 + $in2) - ($out1 + $out2));
                $status_class = ($stock < 5) ? 'critical' : 'healthy';
                
                echo "
                <div class='col-md-3'>
                    <div class='stock-card $status_class'>
                        <div class='blood-group'>$bg</div>
                        <div class='units-badge'>$stock Units</div>
                    </div>
                </div>";
            }
            ?>
        </div>
    </div>
</body>
</html>