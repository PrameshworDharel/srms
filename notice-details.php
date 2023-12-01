<?php
error_reporting(0);
include('includes/config.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Student Result Management System</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
    <style>
        .go-to-home {
            position: fixed;
            bottom: 10px;
            left: 10px;
            z-index: 999;
        }

        .content-section {
            margin-top: 40px;

        }
    </style>
</head>

<body>
    <!-- Responsive navbar-->
    <nav class="navbar navbar-expand-lg navbar-dark bg-secondary fixed-top">
        <div class="container">
            <a class="navbar-brand " href="index.php">Student Result Management System<br>
                <p class="fs-2"> Birendra Multiple Campus</p>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>

        </div>
    </nav>
    <!-- Header - set the background image for the header in the line below-->

    <!-- Content section-->
    <section class="py-5 content-section">
        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-lg-10">

                    <?php
                    $noticeid = $_GET['nid'];
                    $sql = "SELECT * from tblnotice where id='$noticeid'";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                    $cnt = 1;
                    if ($query->rowCount() > 0) {
                        foreach ($results as $result) { ?>

                            <div class="col-lg-10">
                                <h3>
                                    <?php echo htmlentities($result->noticeTitle); ?>
                                </h3>

                                <p><strong>Notice Posting Date:</strong>
                                    <?php echo htmlentities($result->postingDate); ?>
                                </p>
                                <hr color="#000" />
                                <?php if (!empty($result->noticeFile)) : ?>
                                    <img src="admin/<?php echo $result->noticeFile; ?>" alt="Notice Image" class="w-50 h-50">
                                <?php endif; ?>
                                <p>
                                    <?php echo htmlentities($result->noticeDetails); ?>
                                </p>


                            </div>

                    <?php }
                    } ?>
                    <!-- Button to go to home page -->
                    <div>
                        <a class="btn btn-primary btn-sm go-to-home" href="index.php">Go to Home</a>
                    </div>


                </div>
            </div>
        </div>
    </section>


    <!-- Footer-->
    <!-- <footer class="py-2 bg-secondary">
        <div class="container">
            <p class="mt-10 text-center text-white">Copyright &copy; Birendra Multiple Campus <?php echo date('Y'); ?></p>
        </div>
    </footer> -->

</body>

</html>