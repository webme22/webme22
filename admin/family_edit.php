<?php
include("config.php");
if (!loggedin()) {
    header("Location: login.php");
    exit();
}
if (($_SESSION['parent_categories'] != '1')) {
   header("Location: error.php");
   exit();
}
?>

<?php
// error_reporting(0);

if (isset($_POST['parent_categories_update'])) {

    $parent_category_id = $_POST['parent_category_id_update'];
    $name_ar = $_POST['name_ar'];
    $name_en = $_POST['name_en'];
    $description_ar = $_POST['description_ar'];
    $description_en = $_POST['description_en'];
    // $brand_id = implode(',', $_POST['brands']);
    
    if (isset($_FILES['cat_photo']['name']) && !empty($_FILES['cat_photo']['name'])) {
        $old_image = $_POST['image_ext_old'];
        // $mostafa = explode('/', $image_ext_old);
        // $image_name = $mostafa[7];
        // $full_img_path = "../api/uploads/parent_categories/{$parent_category_id}/{$image_name}";
        if (getimagesize($old_image) > 0) {
            unlink($old_image);
        }

        $cat_photo_name_update = $_FILES['cat_photo']['name'];
        $cat_photo_tmp_update = $_FILES['cat_photo']['tmp_name'];

        if (!file_exists("../api/uploads/parent_categories/" . $parent_category_id)) {
            mkdir("../api/uploads/parent_categories/" . $parent_category_id, 0777, true);
        }

        $image_path = "../api/uploads/parent_categories/" . $parent_category_id . "/";
        $image_database = "api/uploads/parent_categories/" . $parent_category_id . "/" . round(microtime(true)) . '.' . "jpg";

        $target_path = $image_path . round(microtime(true)) . '.' . "jpg";


        $update = $con->query("UPDATE `parent_categories` SET `description_ar`='$description_ar',`description_en`='$description_en',`name_ar`='$name_ar',`name_en`='$name_en',`image`='$image_database' WHERE `id`='$parent_category_id'");


        move_uploaded_file($cat_photo_tmp_update, $target_path);


    } else {
        $update = $con->query("UPDATE `parent_categories` SET `description_ar`='$description_ar',`description_en`='$description_en',`name_ar`='$name_ar',`name_en`='$name_en' WHERE `id`='$parent_category_id'");
    }

    echo get_success("تم التحديث بنجاح");
    

}

 $result = mysqli_query($con, "SELECT `id`, `title_ar` FROM `brands`");
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

            <div class="content-page">
                <div class="content">
                    <div class="container">

                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="page-title">الأقسام الرئيسية للمنتجات</h4>
                                <ol class="breadcrumb">
                                    <li><a href="cats_view.php">الأقسام الرئيسية للمنتجات</a></li>
                                    <li class="active">تعديل قسم</li>
                                </ol>
                            </div>
                        </div>

                        <div class="updateData">

                            <?php
                            if (isset($_GET['catId'])) {
                                $parent_categories_id = $_GET['catId'];
                                $query_select = $con->query("SELECT * FROM `parent_categories` WHERE `id` = '{$parent_categories_id}' LIMIT 1");
                                $row_select = mysqli_fetch_array($query_select);

                                $parent_category_id = $row_select['id'];
                                // $old_brands = $row_select['brand_id'];
                                $name_ar = $row_select['name_ar'];
                                $name_en = $row_select['name_en'];
                                $cat_image = $row_select['image'];
                                $description_ar = $row_select['description_ar'];
                                $description_en = $row_select['description_en'];
                                if ($query_select) {
                                    
                                    ?>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card-box"> 	
                                            
                                                <form method="POST" enctype="multipart/form-data" data-parsley-validate novalidate>
                                                    <input type="hidden" name="parent_category_id_update" id="parent_category_id_update" parsley-trigger="change"  value="<?php echo $parent_category_id; ?>" class="form-control">
                                                    <div class="form-group col-md-3">
                                                        <label for="name_ar">الإسم  بالعربي</label>
                                                        <input type="text" name="name_ar" id="name_ar" parsley-trigger="change" required value="<?php echo $name_ar; ?>" class="form-control">
                                                    </div>

                                                    <div class="form-group col-md-3">
                                                        <label for="name_en">الإسم  بالإنجليزي</label>
                                                        <input type="text" name="name_en" id="name_en" parsley-trigger="change" required value="<?php echo $name_en; ?>" class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="description_ar">وصف القسم بالعربي</label>
                                                        <textarea class="form-control" rows="3" name="description_ar"  minlength="3" maxlength="100"><?php echo $description_ar; ?></textarea>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="description_en">وصف القسم بالإنجليزي </label>
                                                        <textarea class="form-control" rows="3" name="description_en"  minlength="3" maxlength="100"><?php echo $description_en; ?></textarea>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <input type="hidden" name="image_ext_old" value="<?php echo $cat_image; ?>" />
                                                    <div class="form-group m-b-0">
                                                        <label for="cat_image">صورة  القسم <a class="showImg">تعديل؟</a> </label>								

                                                        <div class="gal-detail thumb getImage">
                                                            <a href="<?=asset($cat_image)?>" class="image-popup" title="<?php echo $name; ?>">
                                                                <img src="<?=asset($cat_image)?>" class="thumb-img" alt="<?php echo $name; ?>">
                                                            </a>
                                                        </div>					

                                                        <div class="form-group m-b-0">
                                                            <label class="control-label">صورة القسم</label>
                                                            <input type="file" name="cat_photo" id="cat_photo" class="filestyle" data-buttonname="btn-primary">
                                                        </div>
                                                        <br>
                                                    </div>

                                                    <br>
                                                    </div>
                                                    <div class="clearfix"></div>


                                                    <div class="form-group text-right m-b-0">
                                                        <button class="btn btn-primary waves-effect waves-light" type="submit" name="parent_categories_update" id="parent_categories_update">تحديث</button>
                                                    </div>
                                                </form>								
                                            </div>
                                        </div>
                                    </div>          

                                    <?php

                                }
                            }
                            ?>

                        </div>


                    </div>			
                </div>
                </div>
                <?php include("include/footer_text.php"); ?>

            </div>			
            </div>

            <!-- End Right content here -->


        </div>
        <!-- END wrapper -->
        <?php include("include/footer.php"); ?>
        <script>
            $(document).ready(function () {
                $("#cssmenu ul>li").removeClass("active");
                $("#item31").addClass("active");
            });
            
             $(".multi-select-account").select2();
        </script>
    </body>
</html>
