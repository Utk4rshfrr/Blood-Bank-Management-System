<?php
session_start();
include('../includes/connection.php');
if(!isset($_SESSION['admin_id'])){ header('Location: login.php'); exit(); }

$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'donors';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Users</title>
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
            <h3 class="page-title">Registered Users</h3>

            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($active_tab == 'donors') ? 'active-tab' : ''; ?>" href="manage_users.php?tab=donors">🩸 Donors</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($active_tab == 'patients') ? 'active-tab' : ''; ?>" href="manage_users.php?tab=patients">🏥 Patients</a>
                </li>
            </ul>

            <div class="table-responsive">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $table = ($active_tab == 'donors') ? 'donors' : 'patients';
                        $query = "SELECT * FROM $table";
                        $result = mysqli_query($connection, $query);
                        
                        while($row = mysqli_fetch_assoc($result)){
                            echo "<tr>
                                <td>#{$row['id']}</td>
                                <td class='font-weight-bold'>{$row['name']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['mobile']}</td>
                                <td>{$row['address']}</td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>