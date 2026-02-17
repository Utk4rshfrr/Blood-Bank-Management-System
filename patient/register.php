<?php
session_start();
include('../includes/connection.php');

if(isset($_POST['register'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $age = $_POST['age']; 
    $blood_group = $_POST['blood_group'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];

    if (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        echo "<script>alert('❌ Error: Name must contain only letters and spaces.');</script>";
    } elseif (!preg_match("/^[0-9]{10}$/", $mobile)) {
        echo "<script>alert('❌ Error: Mobile number must be exactly 10 digits.');</script>";
    } else {
        $query = "INSERT INTO patients (name, email, password, age, blood_group, mobile, address) VALUES ('$name', '$email', '$password', '$age', '$blood_group', '$mobile', '$address')";
        if(mysqli_query($connection, $query)){
            echo "<script>alert('✅ Patient Registration Successful! Please Login.'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('❌ Error: " . mysqli_error($connection) . "');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Patient Registration</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background: linear-gradient(135deg, #fff5f5 0%, #ffe3e3 100%);
            min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px;
        }
        .reg-card {
            background: white; padding: 50px; border-radius: 20px;
            box-shadow: 0 20px 60px rgba(230, 57, 70, 0.15); width: 100%; max-width: 700px;
            animation: fadeIn 0.8s ease;
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        .brand-text { color: #e63946; font-weight: 800; font-size: 2rem; text-align: center; margin-bottom: 10px; }
        .subtitle { text-align: center; color: #777; margin-bottom: 40px; }
        
        .form-control { 
            height: 55px; border-radius: 12px; border: 2px solid #f1f1f1; 
            margin-bottom: 20px; transition: 0.3s; padding-left: 20px;
        }
        .form-control:focus { border-color: #e63946; box-shadow: none; background: #fffbfb; }
        
        .btn-custom { 
            background: #e63946; color: white; height: 60px; border-radius: 12px; 
            font-weight: 600; width: 100%; border: none; transition: 0.3s; 
            font-size: 1.1rem; box-shadow: 0 10px 20px rgba(230, 57, 70, 0.2);
            margin-top: 20px;
        }
        .btn-custom:hover { transform: translateY(-3px); background: #d62828; box-shadow: 0 15px 30px rgba(230, 57, 70, 0.3); }
    </style>
    <script>
        function validateForm() {
            var name = document.forms["regForm"]["name"].value;
            var mobile = document.forms["regForm"]["mobile"].value;
            if (!/^[A-Za-z\s]+$/.test(name)) { alert("Name must contain only alphabets."); return false; }
            if (!/^[0-9]{10}$/.test(mobile)) { alert("Mobile number must be exactly 10 digits."); return false; }
            return true;
        }
    </script>
</head>
<body>
    <div class="reg-card">
        <h2 class="brand-text">Join as a Patient 🏥</h2>
        <p class="subtitle">Register to request blood assistance.</p>
        
        <form name="regForm" method="post" onsubmit="return validateForm()">
            <div class="row">
                <div class="col-md-6"><input type="text" name="name" class="form-control" placeholder="Full Name" required></div>
                <div class="col-md-6"><input type="email" name="email" class="form-control" placeholder="Email Address" required></div>
            </div>
            <div class="row">
                <div class="col-md-6"><input type="password" name="password" class="form-control" placeholder="Password" required></div>
                <div class="col-md-6"><input type="number" name="age" class="form-control" placeholder="Age" required></div>
            </div>
            <div class="form-group">
                <select name="blood_group" class="form-control" required>
                    <option value="" disabled selected>Blood Group</option>
                    <option value="A+">A+</option><option value="A-">A-</option>
                    <option value="B+">B+</option><option value="B-">B-</option>
                    <option value="AB+">AB+</option><option value="AB-">AB-</option>
                    <option value="O+">O+</option><option value="O-">O-</option>
                </select>
            </div>
            <input type="text" name="mobile" class="form-control" placeholder="Mobile Number" required>
            <textarea name="address" class="form-control" placeholder="Full Address" rows="2" style="height: auto; padding-top:15px;" required></textarea>
            
            <button type="submit" name="register" class="btn btn-custom">Register Now</button>
        </form>
        <div class="text-center mt-4">
            <a href="login.php" style="color: #777; text-decoration: none;">Already have an account? <b>Login here</b></a>
        </div>
    </div>
</body>
</html>