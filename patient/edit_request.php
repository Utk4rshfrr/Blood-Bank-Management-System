<?php
session_start();
include('../includes/connection.php');
if(!isset($_SESSION['uid'])){ header('Location: login.php'); exit(); }

if(isset($_POST['update'])){
    $id = $_POST['request_id'];
    $bg = $_POST['blood_group'];
    $units = $_POST['units'];
    $reason = $_POST['reason'];
    
    $q = "UPDATE patient_blood_requests SET blood_group='$bg', units_required='$units', reason='$reason' WHERE request_id='$id'";
    if(mysqli_query($connection, $q)){
        echo "<script>alert('✅ Updated Successfully'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('❌ Error');</script>";
    }
}

// Get Data
$id = $_GET['id'];
$row = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM patient_blood_requests WHERE request_id='$id'"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Request</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 100%); font-family: 'Poppins', sans-serif; min-height: 100vh; display:flex; align-items:center; justify-content:center; }
        .form-card { background: white; padding: 40px; border-radius: 20px; box-shadow: 0 15px 40px rgba(29, 53, 87, 0.08); width: 100%; max-width: 500px; }
        .btn-custom { background: #1d3557; color: white; border-radius: 10px; font-weight: 600; width: 100%; height: 50px; border:none; transition:0.3s; }
        .btn-custom:hover { background: #457b9d; transform:translateY(-2px); }
    </style>
</head>
<body>
    <div class="form-card">
        <h3 class="font-weight-bold text-center mb-4" style="color:#1d3557;">Edit Request</h3>
        <form method="post">
            <input type="hidden" name="request_id" value="<?php echo $row['request_id']; ?>">
            
            <label>Blood Group</label>
            <select name="blood_group" class="form-control mb-3">
                <option value="<?php echo $row['blood_group']; ?>" selected><?php echo $row['blood_group']; ?> (Current)</option>
                <option value="A+">A+</option><option value="A-">A-</option><option value="B+">B+</option><option value="B-">B-</option>
                <option value="AB+">AB+</option><option value="AB-">AB-</option><option value="O+">O+</option><option value="O-">O-</option>
            </select>
            
            <label>Units</label>
            <input type="number" name="units" class="form-control mb-3" value="<?php echo $row['units_required']; ?>">
            
            <label>Reason</label>
            <textarea name="reason" class="form-control mb-4" rows="3"><?php echo $row['reason']; ?></textarea>
            
            <button type="submit" name="update" class="btn btn-custom">Update Request</button>
            <a href="dashboard.php" class="btn btn-link btn-block mt-2 text-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>