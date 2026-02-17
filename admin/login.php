<?php
session_start();
include('../includes/connection.php');

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // FIX: Table name is 'admins' (plural), not 'admin'
    $query = "SELECT * FROM admins WHERE email = '$email' AND password = '$password'";
    
    $result = mysqli_query($connection, $query);
    
    // Check if query failed (e.g., table missing)
    if(!$result) {
        die("Query Failed: " . mysqli_error($connection)); 
    }

    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);
        $_SESSION['admin_id'] = $row['id'];
        $_SESSION['admin_name'] = $row['name'];
        header('Location: dashboard.php');
        exit();
    } else {
        echo "<script>alert('❌ Invalid Admin Credentials');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Login</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #f4f6f9;
            background-image: radial-gradient(#e63946 0.5px, #f4f6f9 0.5px);
            background-size: 20px 20px;
            height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center;
        }

        .login-card {
            background: white; 
            padding: 50px 40px; 
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.08); 
            width: 100%; 
            max-width: 420px;
            text-align: center; 
            border-top: 5px solid #e63946;
            animation: fadeIn 0.8s ease;
        }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        
        .brand-icon { font-size: 3rem; margin-bottom: 10px; display: block; }
        .brand-text { color: #333; font-weight: 800; font-size: 1.8rem; letter-spacing: 0.5px; margin-bottom: 5px; }
        .subtitle { color: #777; font-size: 0.95rem; margin-bottom: 30px; font-weight: 500; }
        
        .form-control { 
            background: #f8f9fa; 
            border: 2px solid #eee; 
            color: #333; 
            height: 55px; 
            border-radius: 10px; 
            padding-left: 20px; 
            margin-bottom: 20px; 
            transition: 0.3s;
        }
        .form-control:focus { 
            background: white; 
            border-color: #e63946; 
            box-shadow: 0 0 0 4px rgba(230, 57, 70, 0.1); 
        }
        
        .btn-custom { 
            background: #333; 
            color: white; 
            height: 55px; 
            border-radius: 10px; 
            font-weight: 600; 
            width: 100%; 
            border: none; 
            transition: 0.3s; 
            font-size: 1.1rem;
            margin-top: 10px;
        }
        .btn-custom:hover { 
            background: #e63946; 
            transform: translateY(-2px); 
            box-shadow: 0 10px 20px rgba(230, 57, 70, 0.2); 
        }
        
        .back-link { 
            color: #999; 
            text-decoration: none; 
            font-size: 0.9rem; 
            margin-top: 25px; 
            display: inline-block; 
            transition: 0.3s;
        }
        .back-link:hover { color: #e63946; }
    </style>
</head>
<body>
    <div class="login-card">
        <span class="brand-icon">🛡️</span>
        <h2 class="brand-text">ADMIN PORTAL</h2>
        <p class="subtitle">Authorized Access Only</p>
        
        <form method="post">
            <input type="email" name="email" class="form-control" placeholder="Admin Email" required>
            <input type="password" name="password" class="form-control" placeholder="Secure Password" required>
            <button type="submit" name="login" class="btn btn-custom">Login</button>
        </form>
        
        <a href="../index.php" class="back-link">← Back to Homepage</a>
    </div>
</body>
</html>