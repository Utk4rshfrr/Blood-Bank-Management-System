<?php
session_start();
include('../includes/connection.php');
if(!isset($_SESSION['uid'])){ header('Location: login.php'); exit(); }

if(isset($_POST['offer'])){
    $patient_id = $_SESSION['uid'];
    $blood_group = $_POST['blood_group'];
    $units = $_POST['units'];
    $disease = $_POST['disease'];

    $query = "INSERT INTO patient_donation_offers (patient_id, blood_group, available_units, disease, status) VALUES ($patient_id, '$blood_group', '$units', '$disease', 'Pending')";
    if(mysqli_query($connection, $query)){
        echo "<script>alert('✅ Offer Sent! Thank you.'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('❌ Error: " . mysqli_error($connection) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Offer Donation</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #fff5f5 0%, #ffe3e3 100%); font-family: 'Poppins', sans-serif; min-height: 100vh; }
        .navbar { background: white !important; box-shadow: 0 4px 10px rgba(0,0,0,0.05); padding: 15px 30px; }
        .navbar-brand { color: #e63946 !important; font-weight: 800; font-size: 1.5rem; letter-spacing: 1px; }
        .nav-link { color: #555 !important; font-weight: 600; transition: 0.3s; margin-left:20px; }
        .nav-link:hover { color: #e63946 !important; }
        
        .form-card {
            background: white; padding: 40px; border-radius: 20px;
            box-shadow: 0 20px 60px rgba(230, 57, 70, 0.1); margin-top: 50px;
            max-width: 600px; margin-left: auto; margin-right: auto;
            animation: fadeIn 0.8s ease;
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        .form-title { color: #e63946; font-weight: 800; font-size: 1.8rem; margin-bottom: 5px; }
        .form-subtitle { color: #888; margin-bottom: 30px; }
        
        .form-control { 
            height: 55px; border-radius: 12px; margin-bottom: 20px; border: 2px solid #f1f1f1; 
            padding-left: 20px; transition: 0.3s; 
        }
        .form-control:focus { border-color: #e63946; box-shadow: none; background: #fffbfb; }
        
        /* UPDATED: Button is now RED */
        .btn-submit { 
            background: #e63946; color: white; height: 55px; border-radius: 12px; 
            font-weight: 600; width: 100%; border: none; transition: 0.3s; 
            box-shadow: 0 10px 20px rgba(230, 57, 70, 0.2);
        }
        .btn-submit:hover { background: #d62828; transform: translateY(-3px); box-shadow: 0 15px 30px rgba(230, 57, 70, 0.3); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">🏥 PATIENT PORTAL</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Back</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mb-5">
        <div class="form-card">
            <h2 class="form-title">Offer Donation</h2>
            <p class="form-subtitle">Patients can help too. Fill out the details.</p>
            <form method="post">
                <label class="font-weight-bold text-muted">Blood Group</label>
                <select name="blood_group" class="form-control" required>
                    <option value="" disabled selected>Select Group</option>
                    <option value="A+">A+</option><option value="A-">A-</option>
                    <option value="B+">B+</option><option value="B-">B-</option>
                    <option value="AB+">AB+</option><option value="AB-">AB-</option>
                    <option value="O+">O+</option><option value="O-">O-</option>
                </select>

                <label class="font-weight-bold text-muted">Number of Units</label>
                <input type="number" name="units" class="form-control" placeholder="e.g. 1" min="1" max="5" required>

                <label class="font-weight-bold text-muted">Any Disease?</label>
                <textarea name="disease" class="form-control" placeholder="Details or 'None'" rows="3" style="height:auto; padding-top:15px;"></textarea>

                <button type="submit" name="offer" class="btn btn-submit">Submit Offer</button>
            </form>
        </div>
    </div>
</body>
</html>