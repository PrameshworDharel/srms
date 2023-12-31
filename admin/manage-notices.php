<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
} else {
    //For Deleting the notice
    if ($_GET['id']) {
        $id = $_GET['id'];
        $sql = "delete from tblnotice where id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->execute();
        echo '<script>alert("Notice deleted.")</script>';
        echo "<script>window.location.href ='manage-notices.php'</script>";
    }
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Admin Manage Notices</title>
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
        <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen">
        <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen">
        <link rel="stylesheet" href="css/prism/prism.css" media="screen"> <!-- USED FOR DEMO HELP - YOU CAN REMOVE IT -->
        <link rel="stylesheet" type="text/css" href="js/DataTables/datatables.min.css" />
        <link rel="stylesheet" href="css/main.css" media="screen">
        <script src="js/modernizr/modernizr.min.js"></script>
        <style>
            .errorWrap {
                padding: 10px;
                margin: 0 0 20px 0;
                background: #fff;
                border-left: 4px solid #dd3d36;
                -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
                box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            }

            .succWrap {
                padding: 10px;
                margin: 0 0 20px 0;
                background: #fff;
                border-left: 4px solid #5cb85c;
                -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
                box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            }
        </style>
    </head>

    <body class="top-navbar-fixed">
        <div class="main-wrapper">

            <!-- ========== TOP NAVBAR ========== -->
            <?php include('includes/topbar.php'); ?>
            <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
            <div class="content-wrapper">
                <div class="content-container">
                    <?php include('includes/leftbar.php'); ?>

                    <div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">Manage Notices</h2>
                                </div>
                            </div>
                            <!-- /.row -->
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
                                        <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                        <li> Classes</li>
                                        <li class="active">Manage Notices</li>
                                    </ul>
                                </div>
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.container-fluid -->

                        <section class="section">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel">
                                            <div class="panel-heading">
                                                <div class="panel-title">
                                                    <h5>View Notices Info</h5>
                                                </div>
                                            </div>
                                            <div class="panel-body p-20">
                                                <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>S.N</th>
                                                            <th>Notice Title</th>
                                                            <th>Notice Details</th>
                                                            <th>Creation Date</th>
                                                            <th>File</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $sql = "SELECT * from tblnotice";
                                                        $query = $dbh->prepare($sql);
                                                        $query->execute();
                                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                        $cnt = 1;
                                                        if ($query->rowCount() > 0) {
                                                            foreach ($results as $result) {
                                                                $noticeFile = htmlentities($result->noticeFile);
                                                        ?>
                                                                <tr>
                                                                    <td>
                                                                        <?php echo htmlentities($cnt); ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php echo htmlentities($result->noticeTitle); ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php echo htmlentities($result->noticeDetails); ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php echo htmlentities($result->postingDate); ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php
                                                                        if ($noticeFile != "" && $noticeFile != "N/A") {
                                                                            $fileExtension = strtolower(pathinfo($noticeFile, PATHINFO_EXTENSION));
                                                                            if (in_array($fileExtension, array('png', 'jpg', 'jpeg'))) {
                                                                                echo '<img src="' . $noticeFile . '" alt="Image" style="max-width: 100px; max-height: 100px;">';
                                                                            } else {
                                                                                echo '<a href="' . $noticeFile . '">' . basename($noticeFile) . '</a>';
                                                                            }
                                                                        } else {
                                                                            echo 'N/A';
                                                                        }
                                                                        ?>
                                                                    </td>

                                                                    <td>
                                                                        <a href="edit-notice.php?id=<?php echo htmlentities($result->id); ?>">
                                                                            <i class="fa fa-edit" title="Edit this Record"></i>
                                                                        </a>
                                                                        <a href="manage-notices.php?id=<?php echo htmlentities($result->id); ?>" onclick="return confirm('Do you really want to delete the notice?');">
                                                                            <i class="fa fa-trash" title="Delete this Record"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        <?php
                                                                $cnt = $cnt + 1;
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                                <!-- /.col-md-12 -->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.col-md-6 -->
                                </div>
                                <!-- /.col-md-12 -->
                            </div>
                            <!-- /.row -->
                    </div>
                    <!-- /.container-fluid -->
                    </section>
                    <!-- /.section -->
                </div>
                <!-- /.main-page -->
            </div>
            <!-- /.content-container -->
        </div>
        <!-- /.main-wrapper -->

        <script src="js/jquery/jquery-2.2.4.min.js"></script>
        <script src="js/jquery-ui/jquery-ui.min.js"></script>
        <script src="js/bootstrap/bootstrap.min.js"></script>
        <script src="js/lobipanel/lobipanel.min.js"></script>
        <script src="js/prism/prism.js"></script>
        <script src="js/datatables/datatables.min.js"></script>
        <script src="js/datatables/dataTables.buttons.min.js"></script>
        <script src="js/datatables/jszip.min.js"></script>
        <script src="js/datatables/pdfmake.min.js"></script>
        <script src="js/datatables/vfs_fonts.js"></script>
        <script src="js/datatables/buttons.html5.min.js"></script>
        <script src="js/datatables/buttons.print.min.js"></script>
        <script src="js/datatables/buttons.colVis.min.js"></script>
        <script src="js/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
        <script src="js/main.js"></script>
        <script>
            $(function() {
                $('#example').DataTable({
                    pageLength: 10,
                    "lengthMenu": [10, 25, 50, 75, 100],

                });
            });
        </script>
    </body>

    </html>


<?php } ?>