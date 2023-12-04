<?php
include '../../../includes/session.php';

$strand_id = $_GET['strand_id'];

if (isset($_POST['submit'])) {

    $strand_name = mysqli_real_escape_string($conn, $_POST['strand_name']);
    $strand_def = mysqli_real_escape_string($conn, $_POST['strand_def']);

    $check_double = mysqli_query($conn, "SELECT * FROM tbl_strand
     WHERE strand_name = '$strand_name' AND strand_def = '$strand_def'") or die(mysqli_error($conn));

    $result = mysqli_num_rows($check_double);


    if ($result == 0) {
        $insertStrand = mysqli_query($conn, "UPDATE tbl_strand SET strand_name = '$strand_name', strand_def = '$strand_def' WHERE strand_id = '$strand_id'") or die(mysqli_error($conn));
        $_SESSION['success'] = true;
        header('location: ../edit.strand.php?strand_id=' . $strand_id);
    } else {
        $_SESSION['strand_exists'] = true;
        header('location: ../edit.strand.php?strand_id=' . $strand_id);
    }
}