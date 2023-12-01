<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
} else {
    if (isset($_GET['scid']) && !empty($_GET['scid'])) {
        $scid = $_GET['scid'];

        // Delete the subject combination
        $sql = "DELETE FROM tblsubjectcombination WHERE id = :scid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':scid', $scid, PDO::PARAM_INT);
        $query->execute();

        if ($query->rowCount() > 0) {
            $msg = "Subject combination deleted successfully.";
        } else {
            $error = "Error deleting subject combination.";
        }
    } else {
        $error = "Invalid subject combination ID.";
    }
    // Redirect back to the page displaying the subjects combination
    header("Location: manage-subjectcombination.php?msg=" . urlencode($msg) . "&error=" . urlencode($error));
    exit();
}
?>