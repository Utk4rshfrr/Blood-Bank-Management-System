<?php
session_start();
include('../includes/connection.php');
if(!isset($_SESSION['admin_id'])){ header('Location: login.php'); exit(); }

$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'patients';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Requests</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; font-family: 'Poppins', sans-serif; }
        .navbar { background: white !important; box-shadow: 0 2px 10px rgba(0,0,0,0.05); padding: 15px 30px; }
        .navbar-brand { color: #e63946 !important; font-weight: 800; font-size: 1.5rem; letter-spacing: 1px; }
        .nav-link { color: #555 !important; font-weight: 600; margin-left: 20px; transition: 0.3s; }
        .nav-link:hover { color: #e63946 !important; }
        
        .main-card { background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.03); padding: 30px; }
        .page-title { font-weight: 800; color: #333; margin-bottom: 20px; border-left: 5px solid #e63946; padding-left: 15px; }

        .nav-tabs { border-bottom: 2px solid #eee; margin-bottom: 30px; }
        .nav-tabs .nav-link { border: none; color: #777; font-weight: 600; padding: 15px 25px; transition: 0.3s; background: transparent; }
        .nav-tabs .nav-link.active-tab { color: #e63946; border-bottom: 3px solid #e63946; background: transparent; }

        .custom-table th { background: #f8f9fa; color: #555; font-weight: 700; border: none; padding: 15px; text-transform: uppercase; font-size: 0.85rem; }
        .custom-table td { padding: 15px; vertical-align: middle; border-bottom: 1px solid #f1f1f1; }
        .badge-custom { padding: 8px 12px; border-radius: 30px; font-size: 0.8rem; font-weight: 600; }
        .badge-pending { background: #fff3cd; color: #856404; }
        .badge-approved { background: #d4edda; color: #155724; }
        .badge-rejected { background: #f8d7da; color: #721c24; }
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
        <div class="main-card">
            <h3 class="page-title">Manage Requests</h3>

            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($active_tab == 'patients') ? 'active-tab' : ''; ?>" href="manage_requests.php?tab=patients">🚑 By Patients</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($active_tab == 'donors') ? 'active-tab' : ''; ?>" href="manage_requests.php?tab=donors">🩸 By Donors</a>
                </li>
            </ul>

            <div class="table-responsive">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User ID</th>
                            <th>Blood Group</th>
                            <th>Units</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if($active_tab == 'patients'){
                            $query = "SELECT * FROM patient_blood_requests ORDER BY request_id DESC";
                            $id_col = 'patient_id';
                            $type = 'patient_request';
                        } else {
                            $query = "SELECT * FROM donor_blood_requests ORDER BY request_id DESC";
                            $id_col = 'donor_id';
                            $type = 'donor_request';
                        }

                        $result = mysqli_query($connection, $query);
                        if(mysqli_num_rows($result) > 0){
                            while($row = mysqli_fetch_assoc($result)){
                                $st = $row['status'];
                                $badge_class = ($st == 'Approved') ? 'badge-approved' : (($st == 'Rejected') ? 'badge-rejected' : 'badge-pending');

                                echo "<tr>
                                    <td>#{$row['request_id']}</td>
                                    <td>User #{$row[$id_col]}</td>
                                    <td class='font-weight-bold text-danger'>{$row['blood_group']}</td>
                                    <td>{$row['units_required']}</td>
                                    <td><span class='badge-custom {$badge_class}'>{$st}</span></td>
                                    <td class='text-center'>";
                                    
                                if($st == 'Pending'){
                                    echo "<a href='action_request.php?id={$row['request_id']}&type={$type}&action=approve' class='btn btn-success btn-sm' style='border-radius:50px;'>Approve</a> ";
                                    echo "<a href='action_request.php?id={$row['request_id']}&type={$type}&action=reject' class='btn btn-light text-danger btn-sm' style='border-radius:50px;'>Reject</a>";
                                } else {
                                    echo "<span class='text-muted small'>Completed</span>";
                                }
                                echo "</td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center py-4 text-muted'>No requests found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>