<?php
session_start();
include('../includes/connection.php');
if(!isset($_SESSION['admin_id'])){ header('Location: login.php'); exit(); }

if(isset($_POST['update'])){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $bg = $_POST['blood_group'];

    $q = "UPDATE donors SET name='$name', email='$email', mobile='$mobile', address='$address', blood_group='$bg' WHERE id='$id'";
    if(mysqli_query($connection, $q)){
        echo "<script>alert('✅ Donor Updated'); window.location.href='manage_users.php?tab=donors';</script>";
    } else {
        echo "<script>alert('❌ Error');</script>";
    }
}

$id = $_GET['id'];
$row = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM donors WHERE id='$id'"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Donor</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { background: #f4f6f9; font-family: 'Poppins', sans-serif; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .form-card { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); width: 100%; max-width: 500px; border-top: 5px solid #e63946; }
        .btn-custom { background: #e63946; color: white; height: 50px; border-radius: 8px; font-weight: 600; width: 100%; border: none; transition: 0.3s; }
        .btn-custom:hover { background: #d62828; transform: translateY(-2px); }
    </style>
</head>
<body>
    <div class="form-card">
        <h3 class="font-weight-bold mb-4 text-center">Edit Donor</h3>
        <form method="post">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            
            <div class="form-group"><label>Name</label><input type="text" name="name" class="form-control" value="<?php echo $row['name']; ?>" required></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" value="<?php echo $row['email']; ?>" required></div>
            <div class="form-group"><label>Mobile</label><input type="text" name="mobile" class="form-control" value="<?php echo $row['mobile']; ?>" required></div>
            <div class="form-group"><label>Blood Group</label>
            <select name="blood_group" class="form-control">
                <option value="<?php echo $row['blood_group']; ?>"><?php echo $row['blood_group']; ?> (Current)</option>
                <option value="A+">A+</option><option value="A-">A-</option><option value="B+">B+</option><option value="B-">B-</option>
                <option value="AB+">AB+</option><option value="AB-">AB-</option><option value="O+">O+</option><option value="O-">O-</option>
            </select></div>
            <div class="form-group"><label>Address</label><textarea name="address" class="form-control"><?php echo $row['address']; ?></textarea></div>
            
            <button type="submit" name="update" class="btn btn-custom mt-3">Update Donor</button>
            <a href="manage_users.php" class="btn btn-link btn-block mt-2 text-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>