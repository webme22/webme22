<?php
include("config.php");
if (!loggedin()) {
    header("Location: login.php");
    exit();
}

if (($_SESSION['role'] != 'admin' || $_SESSION['setting'] != 1)) {
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

                $title_ar = mysqli_real_escape_string($con, trim($_POST['title_ar']));
                $title_en = mysqli_real_escape_string($con, trim($_POST['title_en']));
                $file = mysqli_real_escape_string($con, trim($_POST['file']));

                if (empty($title_ar) || empty($title_en)) {
                    $errors = $languages[$lang]["required"];
                }
				if(filter_var($file, FILTER_VALIDATE_URL) == false){
					$errors .= "<br> File Url is not valid";
				}


				if (strlen($errors) > 4) {
                    
                    echo get_error($errors);
                        
                } else {
                   
                    $con->query("insert into how_it_works (title_ar, title_en, file, date) values ('$title_ar','$title_en','$file','".date('Y-m-d')."')") or die(mysqli_error($con));
//                    $id = mysqli_insert_id($con);
//                    if(! file_exists("../uploads/how_it_works/" . mysqli_insert_id($con))){
//                        mkdir("../uploads/how_it_works/" . mysqli_insert_id($con), 0775, true);
//                    }
//
//                    $image_name = $_FILES['image']['name'];
//                    $image_tmp = $_FILES['image']['tmp_name'];
//
//                    $image_database = "uploads/how_it_works/" . mysqli_insert_id($con) . "/" . round(microtime(true)) . "_" . $image_name;
//
//                    $image_path = "../uploads/how_it_works/" . mysqli_insert_id($con) . "/" . round(microtime(true)) . "_" . $image_name;
//
//
//                    $con->query("update `how_it_works` set `file`='$image_database' where `id`='".mysqli_insert_id($con)."' ");
//
//                    move_uploaded_file($image_tmp, $image_path);

                    echo get_success($languages[$lang]["addMessage"]);

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
                                <h4 class="page-title"><?php echo $languages[$lang]["add_item"];     ?></h4>
                                <ol class="breadcrumb">
                                    <li><a href="how_it_works_view.php?lang=<?php  
                                        echo $lang;
                                        ?>"> <?php echo $languages[$lang]["view_items"];     ?></a></li>
                                    <li class="active"><?php echo $languages[$lang]["view_items"];     ?></li>
                                </ol>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-box">
                                    <form method="POST" enctype="multipart/form-data" data-parsley-validate novalidate>

                                        <div class="form-group col-md-6">
                                            <label for="title_ar"><?php echo $languages[$lang]['title_ar'];  ?></label>
                                            <input type="text" name="title_ar"  required placeholder="<?php echo $languages[$lang]['title_ar'];  ?>" class="form-control" id="title_ar">
                                        </div>
                                        
                                        <div class="form-group col-md-6">
                                            <label for="title_en"><?php echo $languages[$lang]['title_en'];  ?> </label>
                                            <input type="text" name="title_en"  required placeholder="<?php echo $languages[$lang]['title_en'];  ?> " class="form-control" id="title_en">
                                        </div>
                                        
                                       
                                        <div class="clearfix"></div>
                                        
                                        <div class="form-group m-b-1 col-md-12">
                                            <label class="control-label"><?php    
                                            echo $languages[$lang]["file"];
                                            ?>  </label>
                                            <input type="text" name="file" id="photo" class="form-control" placeholder="Drive Link">
                                        </div>
                                        
                                        <div class="clearfix"></div>
                                        <div class="form-group text-right m-b-0">
                                            <button class="btn btn-primary waves-effect waves-light" type="submit" name="submit"> <?php echo $languages[$lang]['add'];  ?> </button>
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
                $("#item54").addClass("active");
            });
        </script>

    </body>
</html>
