<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
} else {
    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $noticeTitle = $_POST['noticeTitle'];
        $noticeDetails = $_POST['noticeDetails'];


        // File Upload
        $file = $_FILES['file'];
        $filename = $file['name'];
        $filetmp = $file['tmp_name'];
        $filetype = $file['type'];


        // Check if a new file is uploaded
        if (!empty($filename)) {
            $allowedExtensions = array('jpg', 'jpeg', 'png');
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedExtensions)) {
                echo '<script>alert("Invalid file format. Only JPG, JPEG, and PNG files are allowed.")</script>';
                echo "<script>window.location.href ='manage-notices.php'</script>";
                exit();
            }

            $folder = "images/"; // Folder where the file will be stored
            $filepath = $folder . $filename;
            move_uploaded_file($filetmp, $filepath);
        } else {
            // No new file uploaded, retain the original file path
            $old_image = $_POST['old_image'];
            $filepath = $old_image;
        }

        // Update the notice in the database
        $sql = "UPDATE tblnotice SET noticeTitle=:noticeTitle, noticeDetails=:noticeDetails, noticeFile=:noticeFile WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':noticeTitle', $noticeTitle, PDO::PARAM_STR);
        $query->bindParam(':noticeDetails', $noticeDetails, PDO::PARAM_STR);
        $query->bindParam(':noticeFile', $filepath, PDO::PARAM_STR);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->execute();

        echo '<script>alert("Notice updated successfully.")</script>';
        echo "<script>window.location.href ='manage-notices.php'</script>";
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SRMS Admin | Edit Notice</title>
        <link rel="stylesheet" href="css/bootstrap.css" media="screen">
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
        <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen">
        <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen">
        <link rel="stylesheet" href="css/prism/prism.css" media="screen">
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

            <div class="content-wrapper">
                <div class="content-container">
                    <?php include('includes/leftbar.php'); ?>

                    <div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">Edit Notice</h2>
                                </div>
                            </div>

                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
                                        <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                        <li><a href="#">Notices</a></li>
                                        <li class="active">Edit Notice</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <section class="section">
                            <div class="container-fluid">
                                <?php
                                $id = $_GET['id'];
                                $sql = "SELECT * from tblnotice where id=:id";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':id', $id, PDO::PARAM_STR);
                                $query->execute();
                                $result = $query->fetch(PDO::FETCH_ASSOC);
                                ?>

                                <div class="row">
                                    <div class="col-md-8 col-md-offset-2">
                                        <div class="panel">
                                            <div class="panel-heading">
                                                <div class="panel-title">
                                                    <h5>Edit Notice</h5>
                                                </div>
                                            </div>

                                            <div class="panel-body">
                                                <form method="post" enctype="multipart/form-data">
                                                    <div class="form-group has-success">
                                                        <label for="success" class="control-label">Notice Title</label>
                                                        <div class="">
                                                            <input type="text" name="noticeTitle" class="form-control"
                                                                required="required" id="noticeTitle"
                                                                value="<?php echo htmlentities($result['noticeTitle']); ?>">
                                                        </div>
                                                    </div>

                                                    <div class="form-group has-success">
                                                        <label for="success" class="control-label">Notice Details</label>
                                                        <div class="">
                                                            <textarea class="form-control" name="noticeDetails" required
                                                                rows="5"><?php echo htmlentities($result['noticeDetails']); ?></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="form-group has-success">
                                                        <label for="file" class="control-label">Choose File</label>
                                                        <div>
                                                            <input type="file" name="file">
                                                            <?php if (!empty($result['noticeFile']) && file_exists($result['noticeFile'])) { ?>
                                                                <img src="<?php echo $result['noticeFile']; ?>" height="150px">
                                                                <input type="hidden" name="old_image"
                                                                    value="<?php echo $result['noticeFile']; ?>">
                                                            <?php } ?>
                                                        </div>
                                                        <br><br>
                                                    </div>




                                                    <div class="form-group has-success">
                                                        <div class="">
                                                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                                                            <button type="submit" name="update"
                                                                class="btn btn-success bg-primary">Update</button>
                                                        </div>
                                                    </div>
                                                </form>
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

        <!-- ========== COMMON JS FILES ========== -->
        <script src="js/jquery/jquery-2.2.4.min.js"></script>
        <script src="js/jquery-ui/jquery-ui.min.js"></script>
        <script src="js/bootstrap/bootstrap.min.js"></script>
        <script src="js/pace/pace.min.js"></script>
        <script src="js/lobipanel/lobipanel.min.js"></script>
        <script src="js/iscroll/iscroll.js"></script>

        <!-- ========== PAGE JS FILES ========== -->
        <script src="js/prism/prism.js"></script>

        <!-- ========== THEME JS ========== -->
        <script src="js/main.js"></script>

        <!-- ========== ADD custom.js FILE BELOW WITH YOUR CHANGES ========== -->

    </body>

    </html>
<?php } ?>