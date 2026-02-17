<?php
session_start();
include('../includes/connection.php');
if(!isset($_SESSION['uid'])){ header('Location: login.php'); exit(); }
$uid = $_SESSION['uid'];
$name = $_SESSION['name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Patient Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Poppins', sans-serif; }
        
        .navbar { background: white !important; box-shadow: 0 4px 10px rgba(0,0,0,0.05); padding: 15px 30px; }
        .navbar-brand { color: #e63946 !important; font-weight: 800; font-size: 1.5rem; letter-spacing: 1px; }
        .nav-link { color: #555 !important; font-weight: 600; margin-left: 20px; transition: 0.3s; }
        .nav-link:hover { color: #e63946 !important; }
        
        /* RED THEME (MATCHES DONOR) */
        .welcome-card { 
            background: linear-gradient(135deg, #e63946 0%, #c0392b 100%); 
            color: white; border-radius: 20px; padding: 50px 40px; margin-bottom: 40px; 
            box-shadow: 0 15px 40px rgba(230, 57, 70, 0.3); position: relative; overflow: hidden;
        }
        .welcome-card::after { content: ''; position: absolute; top: 0; right: 0; width: 200px; height: 100%; background: rgba(255,255,255,0.1); transform: skewX(-20deg); }

        .action-btn { 
            display: flex; align-items: center; justify-content: center; height: 100%; width: 100%; 
            padding: 30px; border-radius: 15px; text-align: center; font-weight: 700; font-size: 1.3rem; 
            text-decoration: none; transition: 0.3s; box-shadow: 0 10px 20px rgba(0,0,0,0.05); background: white; 
        }
        
        .btn-primary-action { border: 2px solid #e63946; color: #e63946; }
        .btn-primary-action:hover { background: #e63946; color: white; transform: translateY(-5px); box-shadow: 0 15px 30px rgba(230, 57, 70, 0.2); }
        
        /* Darker Red Button (To replace blue) */
        .btn-secondary-action { border: 2px solid #a71d2a; color: #a71d2a; }
        .btn-secondary-action:hover { background: #a71d2a; color: white; transform: translateY(-5px); box-shadow: 0 15px 30px rgba(167, 29, 42, 0.2); }
        
        .table-section-title { font-weight: 700; margin-bottom: 20px; color: #2d3436; border-left: 5px solid #e63946; padding-left: 15px; }
        .custom-table { background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.05); margin-bottom: 40px; }
        .custom-table th { background: #2d3436; color: white; border: none; padding: 18px; font-weight: 500; letter-spacing: 0.5px; }
        .custom-table td { padding: 18px; vertical-align: middle; border-bottom: 1px solid #f1f1f1; color: #555; font-weight: 500; }
        .custom-table tr:last-child td { border-bottom: none; }

        .animate-fade { animation: fadeIn 0.8s ease-out forwards; opacity: 0; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">🏥 PATIENT PORTAL</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="welcome-card animate-fade" style="animation-delay: 0.1s;">
            <h1 class="font-weight-bold">Welcome, <?php echo $name; ?></h1>
            <p class="lead mb-0" style="opacity: 0.9;">Request blood easily or help others by offering a donation.</p>
        </div>

        <div class="row mb-5">
            <div class="col-md-6 mb-3 animate-fade" style="animation-delay: 0.2s;"><a href="request_blood.php" class="action-btn btn-primary-action">🚑 Request Blood Now</a></div>
            <div class="col-md-6 mb-3 animate-fade" style="animation-delay: 0.3s;"><a href="donation_offer.php" class="action-btn btn-secondary-action">🤝 Offer Donation</a></div>
        </div>

        <h5 class="table-section-title animate-fade" style="animation-delay: 0.4s;">🚑 My Blood Requests</h5>
        <div class="custom-table animate-fade" style="animation-delay: 0.5s;">
            <table class="table mb-0">
                <thead><tr><th>Req ID</th><th>Blood Group</th><th>Units</th><th>Reason</th><th>Status</th></tr></thead>
                <tbody>
                    <?php
                    $q = "SELECT * FROM patient_blood_requests WHERE patient_id=$uid ORDER BY request_id DESC";
                    $r = mysqli_query($connection, $q);
                    if(mysqli_num_rows($r) > 0){
                        while($row = mysqli_fetch_assoc($r)){
                             $st = $row['status'];
                             $badge = ($st == 'Approved') ? 'success' : (($st == 'Rejected') ? 'danger' : 'warning');
                             $badge_class = ($st == 'Pending') ? 'badge-warning text-white' : "badge-$badge";
                             echo "<tr>
                                <td>#{$row['request_id']}</td>
                                <td class='font-weight-bold text-danger' style='font-size:1.1rem;'>{$row['blood_group']}</td>
                                <td>{$row['units_required']}</td>
                                <td>{$row['reason']}</td>
                                <td><span class='badge $badge_class px-3 py-2'>{$st}</span></td>
                            </tr>";
                        }
                    } else { echo "<tr><td colspan='5' class='text-center text-muted py-4'>No requests made.</td></tr>"; }
                    ?>
                </tbody>
            </table>
        </div>

        <h5 class="table-section-title animate-fade" style="animation-delay: 0.6s;">🤝 My Donation Offers</h5>
        <div class="custom-table animate-fade" style="animation-delay: 0.7s;">
            <table class="table mb-0">
                <thead><tr><th>Offer ID</th><th>Blood Group</th><th>Units</th><th>Disease Info</th><th>Status</th></tr></thead>
                <tbody>
                    <?php
                    $q2 = "SELECT * FROM patient_donation_offers WHERE patient_id=$uid ORDER BY donation_id DESC";
                    $r2 = mysqli_query($connection, $q2);
                    if(mysqli_num_rows($r2) > 0){
                        while($row = mysqli_fetch_assoc($r2)){
                             $st = $row['status'];
                             $badge = ($st == 'Approved') ? 'success' : (($st == 'Rejected') ? 'danger' : 'warning');
                             $badge_class = ($st == 'Pending') ? 'badge-warning text-white' : "badge-$badge";
                             echo "<tr>
                                <td>#{$row['donation_id']}</td>
                                <td class='font-weight-bold text-success' style='font-size:1.1rem;'>{$row['blood_group']}</td>
                                <td>{$row['available_units']}</td>
                                <td>{$row['disease']}</td>
                                <td><span class='badge $badge_class px-3 py-2'>{$st}</span></td>
                            </tr>";
                        }
                    } else { echo "<tr><td colspan='5' class='text-center text-muted py-4'>No donations offered yet.</td></tr>"; }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>