<?php
include("config.php");
if (!loggedin()) {
    header("Location: login.php");
    exit();
}

if (($_SESSION['role'] != 'admin')) {
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

            <?php
            if (isset($_POST['submit'])) {

                $errors = "";
                
                $plan_id = $_POST['plan_id'];
                $title_ar = mysqli_real_escape_string($con, trim($_POST['name_ar']));
                $title_en = mysqli_real_escape_string($con, trim($_POST['name_en']));
                
                if(($_POST["price"] != '')) { $price = $_POST["price"]; } else {$price = 0;}
                if(($_POST["members"])) { $members = $_POST["members"]; } else {$members = 0;}
                if(($_POST["media"] != '')) { $media = $_POST["media"]; } else {$media = 0;}

                if (empty($title_ar) || empty($title_en)) {
                        $errors = $languages[$lang]["required"];

                }

                
                if (strlen($errors) > 4) {
                    
                    echo get_error($errors);
                        
                } else {

                    
                    $con->query("update plans set `name_ar`='$title_ar',  `name_en`='$title_en',  `price`='$price',  `members`='$members',  `media`='$media' where `id`='$plan_id'") or die(mysqli_error($con));

                    echo get_success($languages[$lang]["updateMessage"]);

                    ?>
                    
                      <meta http-equiv="refresh" content="0; URL='plan_edit.php?planId=<?php echo $plan_id; ?>&lang=<?php echo $lang;  ?>'" />
                    
                    <?php
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
                                <h4 class="page-title"><?php echo $languages[$lang]["editPlan"];     ?></h4>
                                <ol class="breadcrumb">
                                    <li><a href="plans_view.php?lang=<?php  
                                        echo $lang;
                                        ?>"> <?php echo $languages[$lang]["plans"];     ?></a></li>
                                    <li class="active"><?php echo $languages[$lang]["plans"];     ?></li>
                                </ol>
                            </div>
                        </div>
                        
                        <?php
                        
                        
                        if(isset($_GET['planId'])){
                            
                            $planId = $_GET['planId'];
                            
                            $result = $con->query("select * from plans where `id`='$planId'");
                            $row = mysqli_fetch_assoc($result);
                            
                            
                        }

                        ?>
                        
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-box">
                                    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" data-parsley-validate novalidate>

                                        <input type="hidden" name="plan_id" value="<?php echo $row['id'];  ?>">

                                        <div class="form-group col-md-3">
                                            <label for="name_ar"><?php echo $languages[$lang]['name_ar'];  ?></label>
                                            <input type="text" name="name_ar"  required placeholder="<?php echo $languages[$lang]['name_ar'];  ?>" class="form-control" id="name_ar" value="<?php echo $row['name_ar']; ?>">
                                        </div>
                                        
                                        <div class="form-group col-md-3">
                                            <label for="name_en"><?php echo $languages[$lang]['name_en'];  ?> </label>
                                            <input type="text" name="name_en"  required placeholder="<?php echo $languages[$lang]['name_en'];  ?> " class="form-control" id="name_en" value="<?php echo $row['name_en']; ?>">
                                        </div>
                                        
                                        <div class="form-group col-md-3">
                                            <label for="price"><?php echo $languages[$lang]['price'];  ?> </label>
                                            <input type="number" name="price"  placeholder="<?php echo $languages[$lang]['price'];  ?> " class="form-control" id="price" value="<?php echo $row['price']; ?>">
                                        </div>
                                       
                                        <div class="clearfix"></div>
                                        
                                        <div class="form-group col-md-3">
                                            <label for="price"><?php echo $languages[$lang]['membersNum'];  ?> </label>
                                            <input type="number" name="members"  placeholder="<?php echo $languages[$lang]['membersNum'];  ?> " class="form-control" id="membersNum" value="<?php echo $row['members']; ?>">
                                        </div>
                                        
                                        <div class="form-group col-md-3">
                                            <label for="media"><?php echo $languages[$lang]['mediaUploads'];  ?> </label>
                                            <input type="number" name="media"  placeholder="<?php echo $languages[$lang]['mediaUploads'];  ?> " class="form-control" id="media" value="<?php echo $row['media']; ?>">
                                        </div>
                                        
                                        <div class="clearfix"></div>
                                        
                                        <div class="form-group text-right m-b-0">
                                            <button class="btn btn-primary waves-effect waves-light" type="submit" name="submit"> <?php echo $languages[$lang]['update'];  ?> </button>
                                            <button type="reset" class="btn btn-default waves-effect waves-light m-l-5"> <?php echo $languages[$lang]['cancel'];  ?> </button>
                                        </div>
                                    </form>
                                </div>
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

        <script>

                
            $(document).ready(function () {
                $("#cssmenu ul>li").removeClass("active");
                $("#item3").addClass("active");


                    
            });
        </script>

    </body>
</html>
