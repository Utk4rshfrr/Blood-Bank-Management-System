<?php 
session_start();
if(isset($_SESSION['uid'])){
    include('../includes/connection.php');
    // FIX: Use new table 'donor_blood_requests'
    $query = "DELETE FROM donor_blood_requests WHERE request_id = $_GET[id]";
    $query_result = mysqli_query($connection, $query);
    if($query_result){
        echo "<script>alert('✅ Request Deleted Successfully'); window.location.href = 'dashboard.php';</script>";
    } else {
        echo "<script>alert('❌ Error: Could not delete.'); window.location.href = 'dashboard.php';</script>";
    }
} else {
    header('Location:login.php');
}
?>