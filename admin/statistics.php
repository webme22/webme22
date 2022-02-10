<?php
include("config.php");
if (!loggedin()) {
    header("Location: login.php");
    exit();
}if (($_SESSION['statics'] != '1' || $_SESSION['user_type'] != '1')) {
    header("Location: error.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
    <?php include("include/heads.php"); ?>	
    <body class="fixed-left">
        <div id="wrapper">
            <!-- Top Bar Start -->
            <?php include("include/topbar.php"); ?>
            <!-- Top Bar End -->

            <!-- Left Sidebar Start -->
            <?php include("include/leftsidebar.php"); ?>
            <!-- Left Sidebar End -->

            <!-- Start right Content here -->

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->  		



            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container">

                        <!-- Page-Title -->
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="page-title">الاحصائيات</h4>
                                <ol class="breadcrumb">
                                    <li><a href="statistics_view.php">الاحصائيات</a></li>
                                    <li class="active"> الاحصائيات عرض</li>
                                </ol>
                            </div>
                        </div>

                        <?php include("include/statics.php"); ?>

                    </div>			
                </div>
                <?php include("include/footer_text.php"); ?>
            </div>			

            <!-- End Right content here -->


        </div>
        <!-- END wrapper -->
        <?php include("include/footer.php"); ?>


        <script>
            $(document).ready(function () {
                $("#cssmenu ul>li").removeClass("active");
                $("#item11").addClass("active");
            });
        </script>

    </body>
</html>
