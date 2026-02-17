<?php 
session_start();
if(isset($_SESSION['email'])){
    include('../includes/connection.php');
    $uid = $_SESSION['uid'];
    
    // Check if patient has at least one APPROVED donation (status = 1)
    // Note: We check 'patient_id' here, not 'donor_id'
    $query = "SELECT * FROM donation WHERE patient_id = $uid AND status = 1 ORDER BY id DESC LIMIT 1";
    $query_run = mysqli_query($connection, $query);
    
    if(mysqli_num_rows($query_run) > 0){
        $row = mysqli_fetch_assoc($query_run);
        $name = $_SESSION['name'];
        $date = date("d-M-Y"); 
?>
<html>
<head>
    <style>
        .cert-container {
            border: 10px solid #dc3545;
            padding: 50px;
            text-align: center;
            background-color: #fff9f9;
            margin-top: 20px;
            position: relative;
        }
        .cert-title {
            font-size: 40px;
            font-weight: bold;
            color: #dc3545;
            font-family: serif;
        }
        .cert-body {
            font-size: 20px;
            margin: 20px 0;
            font-family: sans-serif;
        }
        .name-highlight {
            font-size: 30px;
            font-weight: bold;
            text-decoration: underline;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="col-md-10 m-auto">
        <div class="cert-container" id="certificate">
            <div class="cert-title">Certificate of Appreciation</div>
            <br><br>
            <p class="cert-body">This is to certify that</p>
            <p class="name-highlight"><?php echo $name; ?></p>
            <p class="cert-body">has selflessly donated blood to save a life.</p>
            <br>
            <p><strong>Blood Group:</strong> <?php echo $row['blood_group']; ?> | <strong>Units:</strong> <?php echo $row['no_units']; ?></p>
            <br><br>
            <div class="row">
                <div class="col-6 text-left">Date: <?php echo $date; ?></div>
                <div class="col-6 text-right">Signature: ________________</div>
            </div>
        </div>
        <br>
        <center>
            <button onclick="window.print()" class="btn btn-primary">Print Certificate</button>
        </center>
    </div>
</body>
</html>
<?php 
    } else {
        echo "<div class='alert alert-warning text-center mt-5'>You have no approved donations yet. Please donate first!</div>";
    }
}
?>