<?php
session_start();
error_reporting(0);
include('includes/config.php');
?>

<!DOCTYPE html>
<html lang="en">

<style>
.center {
    display: block;
    margin-left: auto;
    margin-right: auto;
}

.fail-subject {
    color: red;
    font-weight: bold;
}
</style>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Result Management System</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
    <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen">
    <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen">
    <link rel="stylesheet" href="css/prism/prism.css" media="screen">
    <link rel="stylesheet" href="css/main.css" media="screen">
    <script src="js/modernizr/modernizr.min.js"></script>
</head>

<body>
    <div class="main-wrapper">
        <?php include('includes/topbar.php'); ?>
        <div class="content-wrapper">
            <div class="content-container">
                <div class="main-page">
                    <div class="container-fluid">
                        <section class="section" id="exampl">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-8 col-md-offset-2">
                                        <div class="panel">
                                            <div class="panel-heading">
                                                <div class="panel-title">
                                                    <img src="images/bmclogo.jpg" class="center"></img>
                                                    <h5 align="center">Student Marksheet</h5>
                                                    <hr />
                                                    <?php
                                                    // Fetch Student Data
                                                    $rollid = $_POST['rollid'];
                                                    $classid = $_POST['class'];
                                                    $exam = $_POST['exam'];

                                                    $_SESSION['rollid'] = $rollid;
                                                    $_SESSION['classid'] = $classid;

                                                    $query = "SELECT tblstudents.StudentName, tblstudents.RollId, tblstudents.RegDate, tblstudents.StudentId, tblstudents.StudentEmail, tblstudents.Gender, tblstudents.DOB, tblstudents.Status, tblclasses.ClassName, tblclasses.Semister, tblresult.Exam
                                                    FROM tblstudents 
                                                    JOIN tblclasses ON tblclasses.id = tblstudents.ClassId 
                                                    LEFT JOIN tblresult ON tblresult.StudentId = tblstudents.StudentId
                                                    WHERE tblstudents.RollId = :rollid 
                                                    AND tblstudents.ClassId = :classid
                                                    AND tblresult.Exam = :exam";

                                                    $stmt = $dbh->prepare($query);
                                                    $stmt->bindParam(':rollid', $rollid, PDO::PARAM_STR);
                                                    $stmt->bindParam(':classid', $classid, PDO::PARAM_STR);
                                                    $stmt->bindParam(':exam', $exam, PDO::PARAM_STR);
                                                    $stmt->execute();
                                                    $resultss = $stmt->fetchAll(PDO::FETCH_OBJ);
                                                    $cnt = 1;
                                                    if ($stmt->rowCount() > 0) {
                                                        $row = $resultss[0];
                                                        if ($row->Status == 1) {
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p><b>Student Name:</b>
                                                                <?php echo htmlentities($row->StudentName); ?>
                                                            </p>
                                                            <p><b>Roll Id:</b>
                                                                <?php echo htmlentities($row->RollId); ?>
                                                            </p>
                                                            <p><b>Exam:</b>
                                                                <?php echo htmlentities($row->Exam); ?> Terminal
                                                            </p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p><b>Batch:</b>
                                                                <?php echo htmlentities(date('Y', strtotime($row->RegDate))); ?>
                                                            </p>
                                                            <p><b>Date of Birth:</b>
                                                                <?php echo htmlentities($row->DOB); ?>
                                                            </p>
                                                            <p><b>Gender:</b>
                                                                <?php echo htmlentities($row->Gender); ?>
                                                            </p>

                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p><b>Class:</b>
                                                                <?php echo strtoupper(htmlentities($row->ClassName)); ?>
                                                                -
                                                                <?php echo htmlentities($row->Semister); ?>
                                                            </p>

                                                        </div>

                                                    </div>


                                                </div>
                                                <div class="panel-body p-20">
                                                    <table class="table table-hover table-bordered" border="1"
                                                        width="100%">
                                                        <thead>
                                                            <tr style="text-align: center">
                                                                <th style="text-align: center">S.N</th>
                                                                <th style="text-align: center">Subject</th>
                                                                <th style="text-align: center">Full Marks</th>
                                                                <th style="text-align: center">Credit Hour</th>
                                                                <th style="text-align: center">Grade Point</th>
                                                                <th style="text-align: center">Grade </th>
                                                                <th style="text-align: center">Remarks</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            // Code for result
                                                            $query = "SELECT t.StudentName, t.RollId, t.ClassId, t.marks, SubjectId, tblsubjects.SubjectName,  tblsubjects.CreditHour 
FROM (SELECT sts.StudentName, sts.RollId, sts.ClassId, tr.marks, SubjectId, tr.Exam
      FROM tblstudents AS sts 
      JOIN tblresult AS tr ON tr.StudentId = sts.StudentId) AS t 
JOIN tblsubjects ON tblsubjects.id = t.SubjectId 
WHERE (t.RollId = :rollid AND t.ClassId = :classid AND t.Exam = :exam)";
                                                            // Add the condition to filter by exam

                                                            $query = $dbh->prepare($query);
                                                            $query->bindParam(':rollid', $rollid, PDO::PARAM_STR);
                                                            $query->bindParam(':classid', $classid, PDO::PARAM_STR);
                                                            $query->bindParam(':exam', $exam, PDO::PARAM_STR);
                                                            $query->execute();

                                                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                            $cnt = 1;
                                                            $failedSubject = false;
                                                            $passedSubjectsCount = 0;
                                                            $totalGPA = 0.0;
                                                            $totalCreditHours = 0;

                                                            if ($query->rowCount() > 0) {
                                                                foreach ($results as $result) {
                                                                    $marks = $result->marks;
                                                                    $subjectName = $result->SubjectName;
                                                                    $fullMarks = 100;
                                                                    $creditHours = $result->CreditHour;

                                                                    // Calculate Grade
                                                                    if ($marks >= 90) {
                                                                        $grade = 'A';
                                                                        $gradePoints = 4.0;
                                                                    } elseif ($marks >= 80) {
                                                                        $grade = 'A-';
                                                                        $gradePoints = 3.70;
                                                                    } elseif ($marks >= 70) {
                                                                        $grade = 'B+';
                                                                        $gradePoints = 3.30;
                                                                    } elseif ($marks >= 60) {
                                                                        $grade = 'B';
                                                                        $gradePoints = 3.00;
                                                                    } elseif ($marks >= 50) {
                                                                        $grade = 'B-';
                                                                        $gradePoints = 2.70;
                                                                    } else {
                                                                        $grade = 'F';
                                                                        $gradePoints = 0.00;
                                                                        $failedSubject = true;
                                                                    }

                                                                    // Generate Remarks
                                                                    if ($marks >= 50) {
                                                                        $remarks = 'Pass';
                                                                        $passedSubjectsCount++; // Increment the counter for passed subjects
                                                                    } else {
                                                                        $remarks = '<span class="fail-subject">Fail*</span>'; // Add a red asterisk for fail subjects
                                                                    }

                                                                    // Calculate grade points and credit hours
                                                                    $subjectGradePoints = $gradePoints * $creditHours;
                                                                    $totalGPA += $subjectGradePoints;
                                                                    $totalCreditHours += $creditHours;

                                                            ?>
                                                            <tr>
                                                                <th scope="row" style="text-align: center">
                                                                    <?php echo htmlentities($cnt); ?>
                                                                </th>
                                                                <td style="text-align: center">
                                                                    <?php echo htmlentities($subjectName); ?>
                                                                </td>
                                                                <td style="text-align: center">
                                                                    <?php echo htmlentities($fullMarks); ?>
                                                                </td>
                                                                <td style="text-align: center">
                                                                    <?php echo htmlentities($creditHours); ?>
                                                                </td>
                                                                <td style="text-align: center">
                                                                    <?php echo htmlentities($gradePoints); ?>
                                                                </td>
                                                                <td style="text-align: center">
                                                                    <?php echo htmlentities($grade); ?>
                                                                </td>
                                                                <td style="text-align: center">
                                                                    <?php echo $remarks; ?>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                                    $cnt++;
                                                                }

                                                                if (!$failedSubject) {
                                                                    // Calculate GPA
                                                                    $gpa = $totalGPA / $totalCreditHours;
                                                                    $gpa = round($gpa, 2); // Round GPA to 2 decimal places

                                                                    // Determine Overall Grade based on CGPA
                                                                    $overallGrade = '';
                                                                    if ($gpa >= 3.70) {
                                                                        $overallGrade = 'A';
                                                                    } elseif ($gpa >= 3.30) {
                                                                        $overallGrade = 'A-';
                                                                    } elseif ($gpa >= 3.00) {
                                                                        $overallGrade = 'B+';
                                                                    } elseif ($gpa >= 2.70) {
                                                                        $overallGrade = 'B';
                                                                    } elseif ($gpa >= 2.00) {
                                                                        $overallGrade = 'B-';
                                                                    } elseif ($gpa < 2.00) {
                                                                        $overallGrade = 'F';
                                                                    }

                                                                    // Display total grade obtained and overall grade
                                                                    $totalGradeObtained = $totalGPA / $totalCreditHours;
                                                                ?>
                                                            <tr>
                                                                <td colspan="2" style="text-align: right"><b>GPA</b>
                                                                </td>
                                                                <td colspan="4" style="text-align: center">
                                                                    <?php echo number_format($totalGradeObtained, 2); ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td colspan="2" style="text-align: right"><b>Overall
                                                                        Grade</b></td>
                                                                <td colspan="4" style="text-align: center">
                                                                    <?php echo htmlentities($overallGrade); ?>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                                }
                                                            }
                                                            ?>

                                                        </tbody>
                                                    </table>
                                                    <tr>
                                                        <td colspan="3" align="center"><i class="fa fa-print fa-2x"
                                                                aria-hidden="true" style="cursor:pointer"
                                                                OnClick="CallPrint(this.value)"></i></td>
                                                    </tr>
                                                    <?php if ($failedSubject) : ?>

                                                    <div class="alert alert-danger" role="alert" colspan="6"
                                                        style="color: red">
                                                        Failed!
                                                    </div>

                                                    <?php endif; ?>
                                                    <?php
                                                            if ($query->rowCount() > 0) {
                                                                if (!$failedSubject) {
                                                                    echo '<div class="alert alert-success" role="alert"> Congratulations!You passed in all subjects.</div>';
                                                                }
                                                            }
                                                    ?>
                                                </div>
                                                <?php
                                                        } else {
                                                            echo '<div class="alert alert-danger" role="alert">Result Blocked. Contact Administration.</div>';
                                                        }
                                                    } else {
                                                        echo '<div class="alert alert-danger" role="alert">Class and RollId not found!</div>';
                                                    }
                                        ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <script src="js/lobipanel/lobipanel.min.js"></script>
    <script src="js/prism/prism.js"></script>
    <script src="js/main.js"></script>
    <script>
    function CallPrint(strid) {
        var prtContent = document.getElementById("exampl");
        var prtCSS =
            '<link rel="stylesheet" href="css/bootstrap.min.css" media="screen"><link rel="stylesheet" href="css/font-awesome.min.css" media="screen"><link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen"><link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen"><link rel="stylesheet" href="css/prism/prism.css" media="screen"><link rel="stylesheet" href="css/main.css" media="screen">';
        var WinPrint = window.open('', '', 'letf=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
        WinPrint.document.write(prtCSS + '<div class="content-wrapper"><div class="content-container">' + prtContent
            .innerHTML + '</div></div>');
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
        WinPrint.close();
    }
    </script>
</body>

</html>