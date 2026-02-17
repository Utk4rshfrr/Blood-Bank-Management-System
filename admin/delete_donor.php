<?php 
session_start();
if(isset($_SESSION['admin_id'])){
    include('../includes/connection.php');
    $query = "DELETE FROM donors WHERE id = $_GET[id]";
    $query_result = mysqli_query($connection, $query);
    if($query_result){
        echo "<script>alert('✅ Donor deleted successfully'); window.location.href = 'manage_users.php?tab=donors';</script>";
    } else {
        echo "<script>alert('❌ Error: Could not delete.'); window.location.href = 'manage_users.php?tab=donors';</script>";
    }
} else {
    header('Location:login.php');
}
?>