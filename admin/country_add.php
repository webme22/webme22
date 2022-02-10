<?php
include("config.php");
if (!loggedin()) {
    header("Location: login.php");
    exit();
}

if (($_SESSION['role'] != 'admin' || $_SESSION['countries'] != '1')) {
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

                $title_ar = mysqli_real_escape_string($con, trim($_POST['name_ar']));
                $title_en = mysqli_real_escape_string($con, trim($_POST['name_en']));
                $key = mysqli_real_escape_string($con, trim($_POST['key']));
                $country_code = mysqli_real_escape_string($con, trim($_POST['country_code']));
                $imageName = $_FILES['image']['name'];
                $imageTmp = $_FILES['image']['tmp_name'];
                if (empty($title_ar) || empty($title_en)) {
                        $errors = $languages[$lang]["required"];

                }
                else if (! is_numeric($key)){
                    $errors = $languages[$lang]["key_numeric"];
                }
                if (strlen($errors) > 4) {
                    echo get_error($errors);
                } else {
                    $add_country = add_country($title_ar, $title_en, $key, $country_code);
                    if (!file_exists("../uploads/countries/" . mysqli_insert_id($con))) {
                        mkdir("../uploads/countries/" . mysqli_insert_id($con), 0777, true);
                    }
                    $image_path = "../uploads/countries/" . mysqli_insert_id($con) . "/";
                    $target_path = $image_path . round(microtime(true)) . '.' . "jpg";
                    $image_database = "uploads/countries/" . mysqli_insert_id($con) . "/" . round(microtime(true)) . '.' . "jpg";
                    $update = $con->query("UPDATE `countries` SET `image`='$image_database' WHERE `id`='" . mysqli_insert_id($con) . "'");
                    move_uploaded_file($imageTmp, $target_path);
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
                                <h4 class="page-title"><?php echo $languages[$lang]["countries"];     ?></h4>
                                <ol class="breadcrumb">
                                    <li><a href="countries_view.php?lang=<?php  
                                        echo $lang;
                                        ?>"> <?php echo $languages[$lang]["countries"];     ?></a></li>
                                    <li class="active"><?php echo $languages[$lang]["addCountry"];     ?></li>
                                </ol>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-box">
                                    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" data-parsley-validate novalidate>

                                  

                                        <div class="form-group col-md-3">
                                            <label for="name_ar"><?php echo $languages[$lang]['name_ar'];  ?></label>
                                            <input type="text" name="name_ar" parsley-trigger="change" required placeholder="<?php echo $languages[$lang]['name_ar'];  ?>" class="form-control" id="name_ar">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="name_en"><?php echo $languages[$lang]['name_en'];  ?> </label>
                                            <input type="text" name="name_en" parsley-trigger="change" required placeholder="<?php echo $languages[$lang]['name_en'];  ?> " class="form-control" id="name_en">
                                        </div>
                                        
                                        <div class="form-group col-md-3">
                                            <label for="key"><?php echo $languages[$lang]['key'];  ?> </label>
                                            <input type="number" step="1" name="key" parsley-trigger="change" required placeholder="<?php echo $languages[$lang]['key'];  ?> " class="form-control" id="key">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="country_code"><?php echo $languages[$lang]['countryCode'];  ?> </label>
                                            <input type="text" name="country_code" parsley-trigger="change" required placeholder="<?php echo $languages[$lang]['countryCode'];  ?> " class="form-control" id="country_code">
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
                $("#item5").addClass("active");


                    
            });
        </script>

    </body>
</html>
