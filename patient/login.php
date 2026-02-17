<?php
session_start();
include('../includes/connection.php');

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $query = "SELECT * FROM patients WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($connection, $query);
    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);
        $_SESSION['uid'] = $row['id'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['email'] = $row['email'];
        header('Location: dashboard.php');
    } else {
        echo "<script>alert('❌ Invalid Credentials');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Patient Login</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background: linear-gradient(135deg, #fff5f5 0%, #ffe3e3 100%);
            height: 100vh; display: flex; align-items: center; justify-content: center;
        }
        .login-card {
            background: white; padding: 50px 40px; border-radius: 20px;
            box-shadow: 0 20px 50px rgba(230, 57, 70, 0.15); width: 100%; max-width: 450px;
            text-align: center; animation: slideUp 0.8s ease;
        }
        @keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        
        .brand-text { color: #e63946; font-weight: 800; font-size: 1.8rem; margin-bottom: 5px; }
        .subtitle { color: #777; font-size: 0.9rem; margin-bottom: 30px; }
        
        .form-control { 
            height: 55px; border-radius: 12px; border: 2px solid #f1f1f1; 
            padding-left: 20px; margin-bottom: 20px; transition: 0.3s;
        }
        .form-control:focus { border-color: #e63946; box-shadow: none; background: #fffbfb; }
        
        .btn-custom { 
            background: #e63946; color: white; height: 55px; border-radius: 12px; 
            font-weight: 600; width: 100%; border: none; transition: 0.3s; 
            box-shadow: 0 5px 15px rgba(230, 57, 70, 0.3);
        }
        .btn-custom:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(230, 57, 70, 0.4); background: #d62828; }
        
        .footer-links { margin-top: 25px; font-size: 0.9rem; }
        .footer-links a { color: #555; text-decoration: none; font-weight: 600; transition: 0.3s; }
        .footer-links a:hover { color: #e63946; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2 class="brand-text">🏥 Patient Login</h2>
        <p class="subtitle">Welcome back. We are here to help.</p>
        
        <form method="post">
            <input type="email" name="email" class="form-control" placeholder="Email Address" required>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <button type="submit" name="login" class="btn btn-custom">Login</button>
        </form>
        
        <div class="footer-links">
            <a href="register.php">Create Account</a> <span style="color:#ddd; margin:0 10px;">|</span> <a href="../index.php">Home</a>
        </div>
    </div>
</body>
</html>