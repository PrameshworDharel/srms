<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
} else {
    $msg = "";
    $error = "";

    if (isset($_POST['submit'])) {
        $marks = array();
        $class = $_POST['class'];
        $studentid = $_POST['studentid'];
        $mark = $_POST['marks'];
        $exam = $_POST['exam'];

        $stmt = $dbh->prepare("SELECT tblsubjects.SubjectName, tblsubjects.id FROM tblsubjectcombination JOIN tblsubjects ON tblsubjects.id = tblsubjectcombination.SubjectId WHERE tblsubjectcombination.ClassId = :cid ORDER BY tblsubjects.SubjectName");
        $stmt->execute(array(':cid' => $class));
        $sid1 = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($sid1, $row['id']);
        }

        $errorFlag = false;

        for ($i = 0; $i < count($mark); $i++) {
            $mar = $mark[$i];
            $sid = $sid1[$i];

            if ($mar > 100) {
                $errorFlag = true;
                break;
            }

            $checkStmt = $dbh->prepare("SELECT * FROM tblresult WHERE StudentId = :studentid AND ClassId = :class AND SubjectId = :sid AND Exam = :exam");
            $checkStmt->execute(array(':studentid' => $studentid, ':class' => $class, ':sid' => $sid, ':exam' => $exam));
            $existingResult = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if (!$existingResult) {
                $sql = "INSERT INTO tblresult(StudentId, ClassId, Exam, SubjectId, marks) VALUES(:studentid, :class, :exam, :sid, :marks)";
                $query = $dbh->prepare($sql);
                $query->bindParam(':studentid', $studentid, PDO::PARAM_STR);
                $query->bindParam(':class', $class, PDO::PARAM_STR);
                $query->bindParam(':exam', $exam, PDO::PARAM_STR);
                $query->bindParam(':sid', $sid, PDO::PARAM_STR);
                $query->bindParam(':marks', $mar, PDO::PARAM_STR);
                $query->execute();
                $lastInsertId = $dbh->lastInsertId();
                if ($lastInsertId) {
                    $msg = "Result info added successfully";
                } else {
                    $errorFlag = true;
                    break;
                }
            }
        }

        if ($errorFlag) {
            $error = "Marks should not be greater than 100";
        }
    }
?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SMS Admin| Add Result </title>
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
        <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen">
        <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen">
        <link rel="stylesheet" href="css/prism/prism.css" media="screen">
        <link rel="stylesheet" href="css/select2/select2.min.css">
        <link rel="stylesheet" href="css/main.css" media="screen">
        <script src="js/modernizr/modernizr.min.js"></script>
        <script>
            function getStudent(val) {
                $.ajax({
                    type: "POST",
                    url: "get_student.php",
                    data: 'classid=' + val,
                    success: function(data) {
                        $("#studentid").html(data);

                    }
                });
                $.ajax({
                    type: "POST",
                    url: "get_student.php",
                    data: 'classid1=' + val,
                    success: function(data) {
                        $("#subject").html(data);

                    }
                });
            }
        </script>
        <script>
            function getresult(val, clid) {

                var clid = $(".clid").val();
                var val = $(".stid").val();;
                var abh = clid + '$' + val;
                // alert(abh);
                $.ajax({
                    type: "POST",
                    url: "get_student.php",
                    data: 'studclass=' + abh,
                    success: function(data) {
                        $("#reslt").html(data);

                    }
                });
            }
        </script>


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
                                    <h2 class="title">Declare Result</h2>

                                </div>

                                <!-- /.col-md-6 text-right -->
                            </div>
                            <!-- /.row -->
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
                                        <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>

                                        <li class="active">Student Result</li>
                                    </ul>
                                </div>

                            </div>
                            <!-- /.row -->
                        </div>
                        <div class="container-fluid">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel">

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

                                                <div class="form-group">
                                                    <label for="default" class="col-sm-2 control-label">Class</label>
                                                    <div class="col-sm-10">
                                                        <select name="class" class="form-control clid" id="classid" onChange="getStudent(this.value);" required="required">
                                                            <option value="">Select Class</option>


                                                            <?php $sql = "SELECT * from tblclasses";

                                                            $query = $dbh->prepare($sql);
                                                            $query->execute();
                                                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                            if ($query->rowCount() > 0) {
                                                                foreach ($results as $result) { ?>
                                                                    <option value="<?php echo htmlentities($result->id); ?>">
                                                                        <?php echo strtoupper(htmlentities($result->ClassName)); ?>&nbsp;
                                                                        Semister-
                                                                        <?php echo htmlentities($result->Semister); ?>
                                                                    </option>
                                                            <?php }
                                                            } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="date" class="col-sm-2 control-label ">Student Name</label>
                                                    <div class="col-sm-10">
                                                        <select name="studentid" class="form-control stid" id="studentid" required="required" onChange="getresult(this.value);">
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Exam</label>
                                                    <div class="col-sm-10">
                                                        <label class="radio-inline">
                                                            <input type="radio" name="exam" value="First" required="required"> First
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="exam" value="Second" required="required"> Second
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="exam" value="Third" required="required">
                                                            Third
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="date" class="col-sm-2 control-label">Subjects</label>
                                                    <div class="col-sm-10">
                                                        <div id="subject">

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-offset-2 col-sm-10">
                                                        <button type="submit" name="submit" class="btn btn-success bg-primary">Declare Result</button>

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