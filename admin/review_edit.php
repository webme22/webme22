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
                $review_id = $_POST['review_id'];
                $client = mysqli_real_escape_string($con, trim($_POST['client']));
                $position = mysqli_real_escape_string($con, trim($_POST['position']));
                $review = mysqli_real_escape_string($con, trim($_POST['review']));
                
                $imageName = $_FILES['image']['name'];
                $imageTmp = $_FILES['image']['tmp_name'];

                
                if (empty($client) || empty($review)) {
                        $errors = $languages[$lang]["required"];

                }

                
                if (strlen($errors) > 4) {
                    
                    echo get_error($errors);
                        
                } else {

                    if(isset($_FILES['image']['name']) && ! empty($_FILES['image']['name'])) {
                        if (!file_exists("../uploads/reviews/" . $review_id)) {
                            mkdir("../uploads/reviews/" . $review_id, 0777, true);
                        }
                        $image_path = "../uploads/reviews/" . $review_id . "/";
                        $target_path = $image_path . round(microtime(true)) . '.' . "jpg";
    
                        $image_database = "uploads/reviews/" . $review_id . "/" . round(microtime(true)) . '.' . "jpg";
                        $update = $con->query("UPDATE `reviews` SET `image`='$image_database', `client`='$client', `position`='$position', `review`='$review' WHERE `id`='" . $review_id . "'");
    
    
                        move_uploaded_file($imageTmp, $target_path);
                        
                    } else {
                        $update = $con->query("UPDATE `reviews` SET `client`='$client', `position`='$position', `review`='$review' WHERE `id`='" . $review_id . "'");
                    }

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
                                <h4 class="page-title"><?php echo $languages[$lang]["reviews"];     ?></h4>
                                <ol class="breadcrumb">
                                    <li><a href="reviews_view.php?lang=<?php  
                                        echo $lang;
                                        ?>"> <?php echo $languages[$lang]["reviews"];     ?></a></li>
                                    <li class="active"><?php echo $languages[$lang]["addReview"];     ?></li>
                                </ol>
                            </div>
                        </div>
                        
                        <?php
                        
                        if(isset($_GET['reviewId'])){
                            
                            $query = $con->query("SELECT * FROM `reviews` where `id`='".$_GET['reviewId']."'");
                            $row = mysqli_fetch_assoc($query);
                            
                            $id = $row['id'];
                            $client = $row['client'];
                			$position = $row['position'];
                			$image = $row['image'];
                			$review = $row['review'];
                			
                        
                        }
                        ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-box">
                                    <form method="POST"  enctype="multipart/form-data" data-parsley-validate novalidate>
                                        
                                        <input type="hidden" name="review_id" value="<?php echo $id; ?>">

                                        <div class="form-group col-md-3">
                                            <label for="client"><?php echo $languages[$lang]['client'];  ?> </label>
                                            <input type="text" name="client" parsley-trigger="change" required placeholder="<?php echo $languages[$lang]['client'];  ?> " class="form-control" id="client" value="<?php echo $client;  ?> ">
                                        </div>
                                        
                                        <div class="form-group col-md-3">
                                            <label for="position"><?php echo $languages[$lang]['position'];  ?> </label>
                                            <input type="text" name="position" parsley-trigger="change" required placeholder="<?php echo $languages[$lang]['position'];  ?> " class="form-control" id="position" value="<?php echo $position;  ?> ">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="review"><?php echo $languages[$lang]['review'];  ?></label>
                                            <textarea name="review" parsley-trigger="change" required placeholder="<?php echo $languages[$lang]['review'];  ?>" class="form-control" id="review" rows="7" cols="80"><?php echo $review;  ?></textarea>
                                        </div>
                                        

                                       <div class="clearfix"></div>
                                       
                                       <div class="gal-detail thumb getImage">
                                                            <a href="<?=asset($row['image'])?>" class="image-popup" title="<?php echo $client; ?>">
                                                                <img src="<?=asset($row['image'])?>" class="thumb-img" alt="<?php echo $client;  ?>">
                                                            </a>
                                        </div>
                                       
                                       <div class="form-group m-b-0">
                                            <label class="control-label">
                                            <?php echo $languages[$lang]['image'];  ?>    
                                            </label>
                                            <input type="file" name="image" id="image" class="filestyle" data-buttonname="btn-primary">
                                        </div>  
                                       
                                        <div class="clearfix"></div>

                                        
                                        <div class="form-group text-right m-b-0">
                                            <button class="btn btn-primary waves-effect waves-light" type="submit" name="submit"> <?php echo $languages[$lang]['edit'];  ?> </button>
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
        
            function myFunction() {
                  var x = document.getElementById("password");
                  console.log(x);
                  if (x.type === "password") {
                    x.type = "text";
                  } else {
                    x.type = "password";
                  }
            }
                
            $(document).ready(function () {
                $("#cssmenu ul>li").removeClass("active");
                $("#item51").addClass("active");


                    
            });
        </script>

    </body>
</html>
