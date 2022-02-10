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
            if (isset($_POST['firstSubmit'])) {

                $errors = "";

                $title = mysqli_real_escape_string($con, trim($_POST['title']));
                $body = mysqli_real_escape_string($con, trim($_POST['body']));
                
                $imageName = $_FILES['image']['name'];
                $imageTmp = $_FILES['image']['tmp_name'];

                
                if (empty($title) || empty($body)) {
                    $errors = $languages[$lang]["required"];

                }

                
                if (strlen($errors) > 4) {
                    
                    echo get_error($errors);
                        
                } else {


                    $add_box = add_box($title, $body, $imageName);
                    
                    if (!file_exists("../uploads/aboutBoxes/" . mysqli_insert_id($con))) {
                        mkdir("../uploads/aboutBoxes/" . mysqli_insert_id($con), 0777, true);
                    }
                    $image_path = "../uploads/aboutBoxes/" . mysqli_insert_id($con) . "/";
                    $target_path = $image_path . round(microtime(true)) . '.' . "jpg";

                    $image_database = "uploads/aboutBoxes/" . mysqli_insert_id($con) . "/" . round(microtime(true)) . '.' . "jpg";
                    $update = $con->query("UPDATE `aboutBoxes` SET `image`='$image_database' WHERE `id`='" . mysqli_insert_id($con) . "'");


                    move_uploaded_file($imageTmp, $target_path);

                    echo get_success($languages[$lang]["addMessage"]);

                    
                }
            }
            
            if (isset($_POST['secondSubmit'])) {

                $errors = "";

                $body = mysqli_real_escape_string($con, trim($_POST['body']));
                
                $imageName = $_FILES['secimage']['name'];
                $imageTmp = $_FILES['secimage']['tmp_name'];

                
                if (empty($body)) {
                    $errors = $languages[$lang]["required"];

                }

                
                if (strlen($errors) > 4) {
                    
                    echo get_error($errors);
                        
                } else {


                    $add_about = add_about($body, $imageName);
                    
                    if (!file_exists("../uploads/aboutPage/" . mysqli_insert_id($con))) {
                        mkdir("../uploads/aboutPage/" . mysqli_insert_id($con), 0777, true);
                    }
                    $image_path = "../uploads/aboutPage/" . mysqli_insert_id($con) . "/";
                    $target_path = $image_path . round(microtime(true)) . '.' . "jpg";

                    $image_database = "uploads/aboutPage/" . mysqli_insert_id($con) . "/" . round(microtime(true)) . '.' . "jpg";
                    $update = $con->query("UPDATE `aboutPage` SET `image`='$image_database' WHERE `id`='" . mysqli_insert_id($con) . "'");


                    move_uploaded_file($imageTmp, $target_path);

                    echo get_success($languages[$lang]["addMessage"]);

                    
                }
            }
            
            if (isset($_POST['thirdSubmit'])) {

                $errors = "";
                
                $title = mysqli_real_escape_string($con, trim($_POST['title']));
                $body = mysqli_real_escape_string($con, trim($_POST['body']));

                
                if (empty($body)) {
                    $errors = $languages[$lang]["required"];

                }

                
                if (strlen($errors) > 4) {
                    
                    echo get_error($errors);
                        
                } else {


                    $add_aboutBox = add_aboutBox($body, $title);
                    

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
                                <h4 class="page-title"><?php echo $languages[$lang]["about"];     ?></h4>
                                <ol class="breadcrumb">
                                    <li><a href="about_view.php?lang=<?php  
                                        echo $lang;
                                        ?>&flag=1"> <?php echo $languages[$lang]["viewAbout"];     ?></a></li>
                                    <li class="active"><?php echo $languages[$lang]["addAbout"];     ?></li>
                                </ol>
                            </div>
                        </div>
                        <h3 style="text-align: center;">About Boxes On the Home Page</h3>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-box">
                                    <form method="POST" enctype="multipart/form-data" data-parsley-validate novalidate>

                                  
                                        <div class="form-group col-md-4">
                                            <label for="title"><?php echo $languages[$lang]['title'];  ?></label>
                                            <input type="text" name="title" parsley-trigger="change" required placeholder="<?php echo $languages[$lang]['title'];  ?>" class="form-control" id="title">
                                        </div>
                                        
                                        <div class="form-group col-md-6">
                                            <label for="body"><?php echo $languages[$lang]['body'];  ?> </label>
                                            <textarea name="body"  required placeholder="<?php echo $languages[$lang]['body'];  ?>" class="form-control" id="body"></textarea>
                                        </div>
                                        

                                       <div class="clearfix"></div>
                                       
                                       <div class="form-group m-b-0">
                                            <label class="control-label">
                                            <?php echo $languages[$lang]['image'];  ?>    
                                            </label>
                                            <input type="file" name="image" id="image" class="filestyle" data-buttonname="btn-primary">
                                        </div>  
                                       
                                        <div class="clearfix"></div>

                                        
                                        <div class="form-group text-right m-b-0">
                                            <button class="btn btn-primary waves-effect waves-light" type="submit" name="firstSubmit"> <?php echo $languages[$lang]['add'];  ?> </button>
                                            <button type="reset" class="btn btn-default waves-effect waves-light m-l-5"> <?php echo $languages[$lang]['cancel'];  ?> </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>  
                        <br>
                        
                        <h3 style="text-align: center;">Upper Section on About Page</h3>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-box">
                                    <form method="POST" enctype="multipart/form-data" data-parsley-validate novalidate>

                                        
                                        <div class="form-group">
                                            <label for="body"><?php echo $languages[$lang]['body'];  ?> </label>
                                            <textarea name="body"  required placeholder="<?php echo $languages[$lang]['body'];  ?>" class="form-control" id="body"></textarea>
                                        </div>
                                        

                                       <div class="clearfix"></div>
                                       
                                       <div class="form-group m-b-0">
                                            <label class="control-label">
                                            <?php echo $languages[$lang]['image'];  ?>    
                                            </label>
                                            <input type="file" name="secimage" id="secimage" class="filestyle" data-buttonname="btn-primary">
                                        </div>  
                                       
                                        <div class="clearfix"></div>

                                        
                                        <div class="form-group text-right m-b-0">
                                            <button class="btn btn-primary waves-effect waves-light" type="submit" name="secondSubmit"> <?php echo $languages[$lang]['add'];  ?> </button>
                                            <button type="reset" class="btn btn-default waves-effect waves-light m-l-5"> <?php echo $languages[$lang]['cancel'];  ?> </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>  
                        <br>
                        
                        <h3 style="text-align: center;">Lower Section on About Page</h3>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-box">
                                    <form method="POST" enctype="multipart/form-data" data-parsley-validate novalidate>

                                  
                                        <div class="form-group col-md-4">
                                            <label for="title"><?php echo $languages[$lang]['title'];  ?></label>
                                            <input type="text" name="title" parsley-trigger="change" required placeholder="<?php echo $languages[$lang]['title'];  ?>" class="form-control" id="title">
                                        </div>
                                        
                                        <div class="form-group col-md-6">
                                            <label for="body"><?php echo $languages[$lang]['body'];  ?> </label>
                                            <textarea name="body"  required placeholder="<?php echo $languages[$lang]['body'];  ?>" class="form-control" id="body"></textarea>
                                        </div>
                                        
                                       
                                        <div class="clearfix"></div>
                                        <br>
                                        
                                        <div class="form-group text-right m-b-0">
                                            <button class="btn btn-primary waves-effect waves-light" type="submit" name="thirdSubmit"> <?php echo $languages[$lang]['add'];  ?> </button>
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
            </div>
        </div>
            <!-- End Right content here -->


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
                $("#item55").addClass("active");


                    
            });
        </script>

    </body>
</html>
