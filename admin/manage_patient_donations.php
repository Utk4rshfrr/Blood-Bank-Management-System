<?php
session_start();
include('../includes/connection.php');
if(!isset($_SESSION['admin_id'])){ header('Location: login.php'); exit(); }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Patient Requests</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h3 class="text-danger">Manage Patient Blood Requests</h3>
    <table class="table table-bordered table-striped mt-3">
        <thead class="bg-danger text-white">
            <tr>
                <th>Req ID</th>
                <th>Patient ID</th>
                <th>Group</th>
                <th>Units</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch requests from the new table
            $query = "SELECT * FROM patient_blood_requests";
            $result = mysqli_query($connection, $query);
            
            while($row = mysqli_fetch_assoc($result)){
                echo "<tr>
                    <td>{$row['request_id']}</td>
                    <td>{$row['patient_id']}</td>
                    <td>{$row['blood_group']}</td>
                    <td>{$row['units_required']}</td>
                    <td>{$row['reason']}</td>
                    <td><b>{$row['status']}</b></td>
                    <td>";
                    
                if($row['status'] == 'Pending'){
                    // We reuse the existing action script, but we need to update it slightly to handle this new type
                    echo "<a href='action_request.php?id={$row['request_id']}&type=patient_req&action=approve' class='btn btn-success btn-sm'>Approve</a> ";
                    echo "<a href='action_request.php?id={$row['request_id']}&type=patient_req&action=reject' class='btn btn-danger btn-sm'>Reject</a>";
                } else {
                    echo "Completed";
                }
                echo "</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <center><a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a></center>
</div>
</body>
</html>