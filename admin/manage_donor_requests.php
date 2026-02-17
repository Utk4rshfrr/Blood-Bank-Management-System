<?php 
session_start();
include('../includes/connection.php'); 
?>
<div class="row">
    <div class="col-md-10 m-auto">
        <br><center><h4><u>Manage Blood Requests (From Donors)</u></h4><br></center>
        <table class="table">
            <thead>
                <th>S.No</th>
                <th>Req ID</th>
                <th>Donor Name</th>
                <th>Mobile</th>
                <th>Blood Group</th>
                <th>Units</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Action</th>
            </thead>
            <tbody>
            <?php 
                // DIAGNOSTIC QUERY: uses LEFT JOIN to show rows even if donor_id is broken
                $query = "
                    SELECT requests.id as rid, requests.blood_group, requests.no_units, requests.reason, requests.donor_id,
                           donors.name, donors.mobile 
                    FROM requests 
                    LEFT JOIN donors ON requests.donor_id = donors.id 
                    WHERE requests.status = 0 AND requests.patient_id IS NULL
                ";
                
                $query_run = mysqli_query($connection, $query);
                $sno = 1;
                
                if(mysqli_num_rows($query_run) > 0){
                    while($row = mysqli_fetch_assoc($query_run)){
                        // Fallback if name is missing
                        $name = $row['name'] ? $row['name'] : "<span class='text-danger'>Unknown (ID: ".$row['donor_id'].")</span>";
                        $mobile = $row['mobile'] ? $row['mobile'] : "N/A";
                        
                        $bg = $row['blood_group'];
                        // URL Encoding
                        $bg_url = $bg;
                        if($bg == 'A+') $bg_url = 'AP'; elseif($bg == 'B+') $bg_url = 'BP'; elseif($bg == 'AB+') $bg_url = 'ABP'; elseif($bg == 'O+') $bg_url = 'OP'; elseif($bg == 'A-') $bg_url = 'AM'; elseif($bg == 'B-') $bg_url = 'BM'; elseif($bg == 'AB-') $bg_url = 'ABM'; elseif($bg == 'O-') $bg_url = 'OM';
            ?>
                    <tr>
                        <td><?php echo $sno; ?></td>
                        <td><?php echo $row['rid']; ?></td>
                        <td><?php echo $name; ?></td>
                        <td><?php echo $mobile; ?></td>
                        <td><?php echo $row['blood_group']; ?></td>
                        <td><?php echo $row['no_units']; ?></td>
                        <td><?php echo $row['reason']; ?></td>
                        <td><span class="badge bg-warning">Pending</span></td>
                        <td>
                            <a class="btn btn-sm btn-success" href="accept_req.php?rid=<?php echo $row['rid']; ?>&bg=<?php echo $bg_url; ?>&nu=<?php echo $row['no_units']; ?>">Approve</a>
                            <a class="btn btn-sm btn-danger" href="reject_req.php?rid=<?php echo $row['rid']; ?>">Reject</a>
                        </td>
                    </tr>
            <?php
                    $sno++;
                    }
                } else {
                    echo "<tr><td colspan='9' class='text-center'>No Pending Donor Requests Found</td></tr>";
                }
            ?>
            </tbody>
        </table> 
    </div>
</div>