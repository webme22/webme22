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
    <style>.red.btn {
            /* color: #FFFFFF; */
            background-color: #cb5a5e;
        }</style>
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
// error_reporting(0);

            if (isset($_POST['contact_update'])) {

                $address = mysqli_real_escape_string($con, trim($_POST['address']));
                $salesEmail = mysqli_real_escape_string($con, trim($_POST['salesEmail']));
                $salesNum = mysqli_real_escape_string($con, trim($_POST['salesNum']));
                $supportEmail = mysqli_real_escape_string($con, trim($_POST['supportEmail']));
                
                $error;
                
                if (! filter_var($salesEmail, FILTER_VALIDATE_EMAIL) || ! filter_var($supportEmail, FILTER_VALIDATE_EMAIL)) {
                    echo get_error($languages[$lang]["invalidEmail"]);
                } else {

                    $update = $con->query("UPDATE `contact` SET `address`='$address',`sales_email`='$salesEmail',
    `sales_num`='$salesNum',`support_email`='$supportEmail' WHERE `id`='1'");
    
                    if ($update) {
                        echo get_success($languages[$lang]["updateMessage"]);
                    } 
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
                                <h4 class="page-title"><?php echo $languages[$lang]["contact"]; ?></h4>
                                <ol class="breadcrumb">
                                    <!--<li><a href="user_add.php">المديرين</a></li>-->
                                    <!--<li class="active">تعديل مدير</li>-->
                                </ol>
                            </div>
                        </div>

                        <div class="updateData"></div>

                        <?php
                        $query_select = $con->query("SELECT * FROM `contact` where id=1 order by id desc");

                        $row = mysqli_fetch_array($query_select);
                        $id = $row['id'];
                        
                        if ($query_select) {
                            ?>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card-box"> 									
                                        <form method="POST" enctype="multipart/form-data" data-parsley-validate novalidate>
                                            <input type="hidden" name="id" id="id" parsley-trigger="change" required value="<?php echo $id; ?>" class="form-control">

                                            <div class="form-group col-md-3">
                                                <label for="address"><?php echo $languages[$lang]["title"]; ?></label>
                                                <input type="text" name="address" id="address" parsley-trigger="change" required value="<?php echo $row['address']; ?>" class="form-control">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="salesEmail"> <?php echo $languages[$lang]["salesEmail"]; ?> </label>
                                                <input type="text" name="salesEmail" id="salesEmail" parsley-trigger="change" required value="<?php echo $row['sales_email']; ?>" class="form-control">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="phone"><?php echo $languages[$lang]["salesNum"]; ?></label>
                                                <input type="text" name="salesNum" id="salesNum" parsley-trigger="change" required value="<?php echo $row['sales_num']; ?>" class="form-control">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="whatsapp"> <?php echo $languages[$lang]["supportEmail"]; ?></label>
                                                <input type="text" name="supportEmail" id="supportEmail" parsley-trigger="change" required value="<?php echo $row['support_email']; ?>" class="form-control">
                                            </div>
                                            


                                            <div class="clearfix"></div>
                                            <br>

                                            <div class="clearfix"></div>
                               

                                            <div class="form-group text-right m-b-0">
                                                <button class="btn btn-primary waves-effect waves-light" type="submit" name="contact_update" id="contact_update"><?php echo $languages[$lang]["edit"]; ?></button>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>

                </div>			
            </div>

            <?php include("include/footer_text.php"); ?>
                </div>
        </div>			
        </div>

        <!-- End Right content here -->

    <!-- END wrapper -->
    <?php include("include/footer.php"); ?>

    <script>
        $(document).ready(function () {
            $("#cssmenu ul>li").removeClass("active");
            $("#item72").addClass("active");
        });
    </script>	
</body>
</html>
