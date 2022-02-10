<?php
include("config.php");
if (!loggedin()) {
    header("Location: login.php");
    exit();
}
if (($_SESSION['role'] != 'admin') ) {
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
            <div class="container-fluid p-0">

            <!-- Left Sidebar Start -->
            <?php include("include/leftsidebar.php"); ?>
            <!-- Left Sidebar End -->
                <div class="col-xs-12 col-lg-10">

            <!-- Start right Content here -->

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->  		

            <?php

            if (isset($_POST['setting_submit'])) {
                // var_dump($_POST); die();
                $first_title = mysqli_real_escape_string($con, trim($_POST['first_title']));
                $first_title_ar = mysqli_real_escape_string($con, trim($_POST['first_title_ar']));
                $first_header_ar = mysqli_real_escape_string($con, trim($_POST['first_header_ar']));
                $first_header = mysqli_real_escape_string($con, trim($_POST['first_header']));
                $terms = mysqli_real_escape_string($con, trim($_POST['terms']));
                $terms_ar = mysqli_real_escape_string($con, trim($_POST['terms_ar']));
                
                // echo "<h1>" . $title . "</h1>"; die();
                
                $update = $con->query("UPDATE `terms` SET `first_title`='$first_title', `first_title_ar`='$first_title_ar', `first_header`='$first_header', `first_header_ar`='$first_header_ar', `terms`='$terms', `terms_ar`='$terms_ar' WHERE `id`=1") or die(mysqli_error($con));
                

                if ($update) {
                    echo get_success($languages[$lang]["updateMessage"]);
                } 
            }
            ?>


            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container">

                        <!-- Page-Title -->
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="page-title"><?php echo $languages[$lang]["terms"]; ?></h4>
                                <ol class="breadcrumb">
                                    <!--<li><a href="user_add.php">المديرين</a></li>-->
                                    <!--<li class="active">تعديل مدير</li>-->
                                </ol>
                            </div>
                        </div>

                        <div class="updateData"></div>

                        <?php
                        

                            $query_select = $con->query("SELECT * FROM `terms` WHERE `id` = 1 LIMIT 1");
                            $row = mysqli_fetch_array($query_select);

                            
                            if ($query_select) {
                                ?>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card-box"> 									
                                            <form method="POST" enctype="multipart/form-data" data-parsley-validate novalidate>
                                                
                                            <div class="form-group col-md-12">
                                                <label><?php echo $languages[$lang]["title_en"]; ?></label>
                                                <textarea class=" form-control" name="first_title" rows="2"><?php echo $row['first_title']; ?></textarea>

                                            </div>
                                            
                                            <div class="form-group col-md-12">
                                                <label><?php echo $languages[$lang]["title_ar"]; ?></label>
                                                 <textarea class="form-control" name="first_title_ar" rows="2"><?php echo $row['first_title_ar']; ?></textarea>
                                            </div>
                                            
                                            
                                            <div class="form-group col-md-12">
                                                <label><?php echo $languages[$lang]["header_en"]; ?></label>
                                                <textarea class=" form-control" name="first_header" rows="2"><?php echo $row['first_header']; ?></textarea>

                                            </div>
                                            
                                            <div class="form-group col-md-12">
                                               <label><?php echo $languages[$lang]["header_ar"]; ?></label>
                                               <textarea class=" form-control" name="first_header_ar" rows="2"><?php echo $row['first_header_ar']; ?></textarea>
                                            </div>
                                            
                                            <div class="form-group col-md-12">
                                                <label><?php echo $languages[$lang]["terms_en"]; ?></label>
                                                <textarea class="ckeditor form-control" name="terms"><?php echo $row['terms']; ?></textarea>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label><?php echo $languages[$lang]["terms_ar"]; ?></label>
                                                <textarea class="ckeditor form-control" name="terms_ar"><?php echo $row['terms_ar']; ?></textarea>
                                            </div>
                                                
                                                <br>
                                                <div class="clearfix"></div>
                                                <br>
                                                <div class="form-group text-right m-b-0">
                                                    <button class="btn btn-primary waves-effect waves-light" type="submit" name="setting_submit" id="updateUser"><?php echo $languages[$lang]["edit"]; ?></button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        // }
                        ?>

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

        <script>
            $(document).ready(function () {
                $("#cssmenu ul>li").removeClass("active");
                $("#item75").addClass("active");
                
                
                
                
            });
        </script>		

    </body>
</html>
