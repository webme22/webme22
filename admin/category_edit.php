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

                $id = $_POST['id'];
                $category_ar = mysqli_real_escape_string($con, trim($_POST['category_ar']));
                $category_en = mysqli_real_escape_string($con, trim($_POST['category_en']));

                if (empty($category_ar) || empty($category_en)) {
                    $errors = $languages[$lang]["required"];
                }

                
                if (strlen($errors) > 4) {
                    
                    echo get_error($errors);
                        
                } else {
                   
                    $con->query("update questions_categories set category_ar='$category_ar', category_en='$category_en' where id='$id'") or die(mysqli_error($con));
                    
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
                                <h4 class="page-title"><?php echo $languages[$lang]["edit_category"];     ?></h4>
                                <ol class="breadcrumb">
                                    <li><a href="categories_view.php?lang=<?php  
                                        echo $lang;
                                        ?>"> <?php echo $languages[$lang]["categories"];     ?></a></li>
                                    <li class="active"><?php echo $languages[$lang]["categories"];     ?></li>
                                </ol>
                            </div>
                        </div>
                        <?php
                        if(isset($_GET['category_id'])){
                            $result = $con->query("select * from questions_categories where id='".$_GET['category_id']."'");
                            $row = mysqli_fetch_assoc($result);
                        ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-box">
                                    <form method="POST" enctype="multipart/form-data" data-parsley-validate novalidate>
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <div class="form-group col-md-6">
                                            <label for="category_ar"><?php echo $languages[$lang]['category_ar'];  ?></label>
                                            <input type="text" name="category_ar"  required placeholder="<?php echo $languages[$lang]['category_ar'];  ?>" class="form-control" id="category_ar" value="<?= $row['category_ar'] ?>">
                                        </div>
                                        
                                        <div class="form-group col-md-6">
                                            <label for="category_en"><?php echo $languages[$lang]['category_en'];  ?> </label>
                                            <input type="text" name="category_en"  required placeholder="<?php echo $languages[$lang]['category_en'];  ?> " class="form-control" id="category_en" value="<?= $row['category_en'] ?>">
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
                        <?php } ?>       
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
                $("#item53").addClass("active");
            });
        </script>

    </body>
</html>
