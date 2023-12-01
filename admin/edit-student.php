<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
} else {

    $stid = intval($_GET['stid']);

    if (isset($_POST['submit'])) {
        $studentname = $_POST['fullanme'];
        $roolid = $_POST['rollid'];
        $studentemail = $_POST['emailid'];
        $gender = $_POST['gender'];
        $dob = $_POST['dob'];
        $status = $_POST['status'];
        $class = $_POST['class'];
        // Check if the roll number already exists in the same class
        $checkSql = "SELECT * FROM tblstudents WHERE RollId = :roolid AND StudentId != :stid";
        $checkQuery = $dbh->prepare($checkSql);
        $checkQuery->bindParam(':roolid', $roolid, PDO::PARAM_STR);
        $checkQuery->bindParam(':stid', $stid, PDO::PARAM_STR);
        $checkQuery->execute();

        if ($checkQuery->rowCount() > 0) {
            $error = "Roll number already exists";
        } else {
            $sql = "UPDATE tblstudents SET StudentName=:studentname, RollId=:roolid, StudentEmail=:studentemail, Gender=:gender, DOB=:dob, Status=:status  WHERE StudentId=:stid"; //, ClassId=:class
            $query = $dbh->prepare($sql);
            $query->bindParam(':studentname', $studentname, PDO::PARAM_STR);
            $query->bindParam(':roolid', $roolid, PDO::PARAM_STR);
            $query->bindParam(':studentemail', $studentemail, PDO::PARAM_STR);
            $query->bindParam(':gender', $gender, PDO::PARAM_STR);
            $query->bindParam(':dob', $dob, PDO::PARAM_STR);
            $query->bindParam(':status', $status, PDO::PARAM_STR);
            // $query->bindParam(':class', $class, PDO::PARAM_INT);
            $query->bindParam(':stid', $stid, PDO::PARAM_STR);
            $query->execute();

            $msg = "Student info updated successfully";
        }
    }

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMS Admin| Edit Student < </title>
            <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
            <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
            <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen">
            <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen">
            <link rel="stylesheet" href="css/prism/prism.css" media="screen">
            <link rel="stylesheet" href="css/select2/select2.min.css">
            <link rel="stylesheet" href="css/main.css" media="screen">
            <script src="js/modernizr/modernizr.min.js"></script>
</head>

<body class="top-navbar-fixed">
    <div class="main-wrapper">

        <!-- ========== TOP NAVBAR ========== -->
        <?php include('includes/topbar.php'); ?>
        <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
        <div class="content-wrapper">
            <div class="content-container">

                <!-- ========== LEFT SIDEBAR ========== -->
                <?php include('includes/leftbar.php'); ?>
                <!-- /.left-sidebar -->

                <div class="main-page">

                    <div class="container-fluid">
                        <div class="row page-title-div">
                            <div class="col-md-6">
                                <h2 class="title">Student Admission</h2>

                            </div>

                            <!-- /.col-md-6 text-right -->
                        </div>
                        <!-- /.row -->
                        <div class="row breadcrumb-div">
                            <div class="col-md-6">
                                <ul class="breadcrumb">
                                    <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>

                                    <li class="active">Student Admission</li>
                                </ul>
                            </div>

                        </div>
                        <!-- /.row -->
                    </div>
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel">
                                    <div class="panel-heading">
                                        <div class="panel-title">
                                            <h5>Fill the Student info</h5>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <?php if ($msg) { ?>
                                        <div class="alert alert-success left-icon-alert" role="alert">
                                            <!-- <strong>Well done!</strong> -->
                                            <?php echo htmlentities($msg); ?>
                                        </div>
                                        <?php } else if ($error) { ?>
                                        <div class="alert alert-danger left-icon-alert" role="alert">
                                            <strong>Error!</strong>
                                            <?php echo htmlentities($error); ?>
                                        </div>
                                        <?php } ?>
                                        <form class="form-horizontal" method="post">
                                            <?php


                                                $sql = "SELECT tblstudents.StudentName,tblstudents.RollId,tblstudents.RegDate,tblstudents.StudentId,tblstudents.Status,tblstudents.StudentEmail,tblstudents.Gender,tblstudents.DOB,tblclasses.ClassName,tblclasses.Semister from tblstudents join tblclasses on tblclasses.id=tblstudents.ClassId where tblstudents.StudentId=:stid";
                                                $query = $dbh->prepare($sql);
                                                $query->bindParam(':stid', $stid, PDO::PARAM_STR);
                                                $query->execute();
                                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                $cnt = 1;
                                                if ($query->rowCount() > 0) {
                                                    foreach ($results as $result) { ?>


                                            <div class="form-group">
                                                <label for="default" class="col-sm-2 control-label">Full Name</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="fullanme" class="form-control"
                                                        id="fullanme"
                                                        value="<?php echo htmlentities($result->StudentName) ?>"
                                                        required="required" autocomplete="off" pattern="[A-Za-z\s]+">
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label for="default" class="col-sm-2 control-label">Roll Id </label>
                                                <div class="col-sm-10">
                                                    <input type="number" name="rollid" class="form-control" id="rollid"
                                                        value="<?php echo htmlentities($result->RollId) ?>" min="1"
                                                        max="999" required="required" autocomplete="off">
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label for="default" class="col-sm-2 control-label">Email id</label>
                                                <div class="col-sm-10">
                                                    <input type="email" name="emailid" class="form-control" id="email"
                                                        value="<?php echo htmlentities($result->StudentEmail) ?>"
                                                        required="required" autocomplete="off">
                                                    <span style="color: red;" id="emailValidationMessage"></span>
                                                </div>
                                            </div>

                                            <script>
                                            function validateEmail() {
                                                var emailInput = document.getElementById("email");
                                                var emailValidationMessage = document.getElementById(
                                                    "emailValidationMessage");

                                                // Regular expression for a valid email address format
                                                var emailPattern =
                                                    /^[a-zA-Z][a-zA-Z0-9._%+-]*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

                                                if (!emailInput.value.match(emailPattern)) {
                                                    emailValidationMessage.textContent = "Invalid email address format";
                                                    emailInput.setCustomValidity("Invalid email address format");
                                                } else {
                                                    emailValidationMessage.textContent = "";
                                                    emailInput.setCustomValidity("");
                                                }
                                            }

                                            var emailInput = document.getElementById("email");
                                            emailInput.addEventListener("input", validateEmail);
                                            </script>



                                            <div class="form-group">
                                                <label for="default" class="col-sm-2 control-label">Gender</label>
                                                <div class="col-sm-10">
                                                    <?php $gndr = $result->Gender;
                                                                if ($gndr == "Male") {
                                                                ?>
                                                    <input type="radio" name="gender" value="Male" required="required"
                                                        checked>Male <input type="radio" name="gender" value="Female"
                                                        required="required">Female <input type="radio" name="gender"
                                                        value="Other" required="required">Other
                                                    <?php } ?>
                                                    <?php
                                                                if ($gndr == "Female") {
                                                                ?>
                                                    <input type="radio" name="gender" value="Male"
                                                        required="required">Male <input type="radio" name="gender"
                                                        value="Female" required="required" checked>Female <input
                                                        type="radio" name="gender" value="Other"
                                                        required="required">Other
                                                    <?php } ?>
                                                    <?php
                                                                if ($gndr == "Other") {
                                                                ?>
                                                    <input type="radio" name="gender" value="Male"
                                                        required="required">Male <input type="radio" name="gender"
                                                        value="Female" required="required">Female <input type="radio"
                                                        name="gender" value="Other" required="required" checked>Other
                                                    <?php } ?>


                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label for="default" class="col-sm-2 control-label">Class</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="classname" class="form-control"
                                                        id="classname"
                                                        value="<?php echo htmlentities($result->ClassName) ?>-<?php echo htmlentities($result->Semister) ?>"
                                                        readonly>
                                                </div>
                                            </div>


                                            <!-- <div class="form-group">
                                                <label for="class" class="col-sm-2 control-label">Class</label>
                                                <div class="col-sm-10">
                                                    <select name="class" class="form-control" id="class"
                                                        required="required">
                                                        <?php
                                                        // Fetch and populate the class options from the database
                                                        $classSql = "SELECT id, ClassName, Semister FROM tblclasses";
                                                        $classQuery = $dbh->prepare($classSql);
                                                        $classQuery->execute();
                                                        $classResults = $classQuery->fetchAll(PDO::FETCH_ASSOC);

                                                        foreach ($classResults as $classResult) {
                                                            $classId = $classResult['id'];
                                                            $className = $classResult['ClassName'];
                                                            $semister = $classResult['Semister'];
                                                            $selected = ($classId == $result->ClassId) ? 'selected' : '';
                                                            echo "<option value='$classId' $selected>$className - $semister</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div> -->
                                            <div class=" form-group">
                                                <label for="dob" class="col-sm-2 control-label">Date of
                                                    Birth</label>
                                                <div class="col-sm-10">
                                                    <input type="date" name="dob" class="form-control" id="dob"
                                                        required="required" max="<?php echo date('Y-m-d'); ?>"
                                                        value="<?php echo htmlentities($result->DOB); ?>">
                                                </div>
                                            </div>













                                            <div class="form-group">
                                                <label for="default" class="col-sm-2 control-label">Reg Date: </label>
                                                <div class="col-sm-10">
                                                    <?php echo htmlentities($result->RegDate) ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="default" class="col-sm-2 control-label">Status</label>
                                                <div class="col-sm-10">
                                                    <?php $stats = $result->Status;
                                                                if ($stats == "1") {
                                                                ?>
                                                    <input type="radio" name="status" value="1" required="required"
                                                        checked>Active
                                                    <input type="radio" name="status" value="0" required="required">Hide
                                                    <?php } ?>
                                                    <?php
                                                                if ($stats == "0") {
                                                                ?>
                                                    <input type="radio" name="status" value="1"
                                                        required="required">Active <input type="radio" name="status"
                                                        value="0" required="required" checked>Hide
                                                    <?php } ?>



                                                </div>
                                            </div>

                                            <?php }
                                                } ?>


                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10">
                                                    <button type="submit" name="submit"
                                                        class="btn btn-success bg-primary">Update</button>
                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                            <!-- /.col-md-12 -->
                        </div>
                    </div>
                </div>
                <!-- /.content-container -->
            </div>
            <!-- /.content-wrapper -->
        </div>
        <!-- /.main-wrapper -->
        <script src="js/jquery/jquery-2.2.4.min.js"></script>
        <script src="js/bootstrap/bootstrap.min.js"></script>
        <script src="js/pace/pace.min.js"></script>
        <script src="js/lobipanel/lobipanel.min.js"></script>
        <script src="js/iscroll/iscroll.js"></script>
        <script src="js/prism/prism.js"></script>
        <script src="js/select2/select2.min.js"></script>
        <script src="js/main.js"></script>
        <script>
        $(function($) {
            $(".js-states").select2();
            $(".js-states-limit").select2({
                maximumSelectionLength: 2
            });
            $(".js-states-hide").select2({
                minimumResultsForSearch: Infinity
            });
        });
        </script>
</body>

</html>
<?PHP } ?>