<?php
session_start();
include('../includes/connection.php');

// check if admin is logged in
if(!isset($_SESSION['admin_id'])){
    header('Location: login.php');
    exit();
}

// Check if ID, Type, and Action are set in the URL
if(isset($_GET['id']) && isset($_GET['type']) && isset($_GET['action'])){
    
    $id = $_GET['id'];
    $type = $_GET['type'];
    $action = $_GET['action'];

    // Initialize query variable
    $query = "";

    // ---------------------------------------------------
    // 1. HANDLE DONOR DONATION (Legacy 'donation' table)
    // ---------------------------------------------------
    if($type == 'donor_donation'){
        // Legacy system usually uses 1 for Approve, 2 for Reject
        $status = ($action == 'approve') ? 1 : 2; 
        
        // FIX: Changed 'donation_id' to 'id' to match your database column
        $query = "UPDATE donation SET status = '$status' WHERE id = $id";
        $redirect_page = "manage_donations.php?tab=donors";
    }

    // ---------------------------------------------------
    // 2. HANDLE PATIENT DONATION (New 'patient_donation_offers' table)
    // ---------------------------------------------------
    elseif($type == 'patient_donation'){
        $status = ($action == 'approve') ? 'Approved' : 'Rejected';
        $query = "UPDATE patient_donation_offers SET status = '$status' WHERE donation_id = $id";
        $redirect_page = "manage_donations.php?tab=patients";
    }

    // ---------------------------------------------------
    // 3. HANDLE PATIENT REQUEST (Table: 'patient_blood_requests')
    // ---------------------------------------------------
    elseif($type == 'patient_request'){
        $status = ($action == 'approve') ? 'Approved' : 'Rejected';
        $query = "UPDATE patient_blood_requests SET status = '$status' WHERE request_id = $id";
        $redirect_page = "manage_requests.php?tab=patients";
    }

    // ---------------------------------------------------
    // 4. HANDLE DONOR REQUEST (New 'donor_blood_requests' table)
    // ---------------------------------------------------
    elseif($type == 'donor_request'){
        $status = ($action == 'approve') ? 'Approved' : 'Rejected';
        $query = "UPDATE donor_blood_requests SET status = '$status' WHERE request_id = $id";
        $redirect_page = "manage_requests.php?tab=donors";
    }

    // ---------------------------------------------------
    // EXECUTE QUERY
    // ---------------------------------------------------
    if($query != ""){
        if(mysqli_query($connection, $query)){
            echo "<script>
                alert('Action Performed Successfully: " . ucfirst($action) . "');
                window.location.href = '$redirect_page';
            </script>";
        } else {
            echo "<script>
                alert('Error: " . mysqli_error($connection) . "');
                window.location.href = '$redirect_page';
            </script>";
        }
    } else {
        echo "<script>alert('Invalid Request Type'); window.history.back();</script>";
    }

} else {
    echo "Invalid Parameters.";
}
?>