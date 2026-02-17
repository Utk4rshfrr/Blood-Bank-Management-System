<?php
session_start();
include('../includes/connection.php');
if(!isset($_SESSION['uid'])){ header('Location: login.php'); exit(); }
$name = $_SESSION['name'];
$date = date('F j, Y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Certificate of Appreciation</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        body { background: #f0f2f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; font-family: 'Poppins', sans-serif; margin: 0; }
        
        .certificate-container {
            background: white; width: 800px; padding: 40px; text-align: center;
            border: 20px solid #e63946; position: relative; box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .inner-border { border: 5px solid #1d3557; padding: 40px; height: 100%; }
        
        h1 { font-family: 'Playfair Display', serif; font-size: 3.5rem; color: #1d3557; margin-bottom: 10px; }
        h4 { color: #e63946; text-transform: uppercase; letter-spacing: 2px; font-weight: 600; margin-top: 0; }
        
        .recipient { font-size: 2.5rem; font-weight: 700; color: #333; border-bottom: 2px solid #ddd; display: inline-block; padding: 10px 40px; margin: 20px 0; }
        
        .text { font-size: 1.2rem; color: #555; line-height: 1.6; margin-bottom: 40px; }
        
        .footer { display: flex; justify-content: space-between; margin-top: 50px; padding: 0 50px; }
        .signature { border-top: 2px solid #333; padding-top: 10px; width: 200px; font-weight: 600; }
        
        .btn-print {
            background: #1d3557; color: white; border: none; padding: 15px 30px; 
            font-size: 1rem; cursor: pointer; border-radius: 5px; margin-top: 20px;
            font-weight: 600; transition: 0.3s;
        }
        .btn-print:hover { background: #e63946; }

        @media print {
            body { background: white; }
            .certificate-container { box-shadow: none; width: 100%; border-width: 10px; }
            .btn-print, .back-link { display: none; }
        }
    </style>
</head>
<body>
    <div>
        <div class="certificate-container">
            <div class="inner-border">
                <h4>Blood Bank Management System</h4>
                <h1>Certificate of Appreciation</h1>
                <p class="text">This certificate is proudly presented to</p>
                
                <div class="recipient"><?php echo $name; ?></div>
                
                <p class="text">
                    For your selfless act of donating blood.<br>
                    Your generosity has saved a life and inspired hope.
                </p>
                
                <div class="footer">
                    <div class="signature"><?php echo $date; ?><br><small>Date</small></div>
                    <div class="signature">Admin<br><small>Authority Signature</small></div>
                </div>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 20px;">
            <button onclick="window.print()" class="btn-print">🖨️ Print Certificate</button>
            <br><br>
            <a href="dashboard.php" class="back-link" style="color: #555; text-decoration: none;">← Back to Dashboard</a>
        </div>
    </div>
</body>
</html>