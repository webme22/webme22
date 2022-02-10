<?php
include("config.php");
if (!loggedin()) {
    header("Location: login.php");
    exit();
}
?>
<html>
    <?php include("include/heads.php"); ?>	
    <body class="fixed-left">
        <div id="wrapper">
            <!-- Top Bar Start -->
            <?php include("include/topbar.php"); ?>
            <!-- Top Bar End -->
            <div class="container-fluid p-0">

            <!-- Left Sidebar Start -->
            <?php include("include/leftsidebar.php"); ?>
            <!-- Left Sidebar End -->
                <div class="col-xs-12 col-lg-10">

            <!-- Start right Content here -->		


            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container">
                        <!-- Page-Title -->
                        <div class="innerLR">
                            <div class="note alert-error">
                                <button class="close" data-dismiss="alert" type="button">×</button>
                                <?php   echo $languages[$lang]["error"]; ?>
                            </div>
                        </div>		
                    </div>			
                </div>
                              <?php include("include/footer_text.php"); ?>
				
            </div>			

            <!-- End Right content here -->
                </div>

        </div>
        </div>
        <!-- END wrapper -->
        <?php include("include/footer.php"); ?>
    </body>
</html>
