<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
} else {
    if (isset($_GET['subjectid'])) {
        $subjectId = $_GET['subjectid'];

        // Delete subject from the database
        $sql = "DELETE FROM tblsubjects WHERE id = :subjectid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':subjectid', $subjectId, PDO::PARAM_INT);
        $query->execute();

        // Check if the deletion was successful
        if ($query->rowCount() > 0) {
            $msg = "Subject deleted successfully.";
        } else {
            $error = "Error deleting subject. Please try again.";
        }
    }

    // Redirect back to the "Manage Subjects" page
    header("Location: manage-subjects.php?msg=" . urlencode($msg) . "&error=" . urlencode($error));
    exit();
}
?>
