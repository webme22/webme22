<!DOCTYPE html>
<html>
	<?php include("heads.php"); ?>	
	<body class="fixed-left">
        <div id="wrapper">
            <!-- Top Bar Start -->
				<?php include("topbar.php"); ?>
            <!-- Top Bar End -->
			
            <!-- Left Sidebar Start -->
				<?php include("leftsidebar.php"); ?>
            <!-- Left Sidebar End -->

            <!-- Start right Content here -->
				<?php include("rightContent.php"); ?>
            <!-- End Right content here -->

            <!-- Right Sidebar -->
            <div class="side-bar right-bar nicescroll">
				<?php include("rightbar.php"); ?>
            </div>
            <!-- /Right-bar -->
        </div>
        <!-- END wrapper -->
		<?php include("footer.php"); ?>
    </body>
</html>