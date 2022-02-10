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

                $name_ar = mysqli_real_escape_string($con, trim($_POST['name_ar']));
                $name_en = mysqli_real_escape_string($con, trim($_POST['name_en']));

                $description_ar = mysqli_real_escape_string($con, trim($_POST['description_ar']));
                $description_en = mysqli_real_escape_string($con, trim($_POST['description_en']));

                $cat_photo_name = $_FILES['cat_photo']['name'];
                $cat_photo_tmp = $_FILES['cat_photo']['tmp_name'];
                
                // $brand_id = implode(',', $_POST['brands']);

                $errors = array();

                if (empty($name_ar)) {
                    $errors[] = "من فضلك ادخل جميع الحقول !";
                }

                if (!empty($errors)) {
                    foreach ($errors as $error) {
                        echo get_error($error);
                    }
                } else {


                    $add_parent_categories = add_parent_categories($name_ar, $name_en,$description_ar,$description_en, $cat_photo_name);
                    if (!file_exists("../api/uploads/parent_categories/" . mysqli_insert_id($con))) {
                        mkdir("../api/uploads/parent_categories/" . mysqli_insert_id($con), 0777, true);
                    }
                    $image_path = "../api/uploads/parent_categories/" . mysqli_insert_id($con) . "/";
                    $target_path = $image_path . round(microtime(true)) . '.' . "jpg";

                    $image_database = "api/uploads/parent_categories/" . mysqli_insert_id($con) . "/" . round(microtime(true)) . '.' . "jpg";
                    $update = $con->query("UPDATE `parent_categories` SET `image`='$image_database' WHERE `id`='" . mysqli_insert_id($con) . "'");


                    move_uploaded_file($cat_photo_tmp, $target_path);

                    echo get_success("تم الإضافة بنجاح");
                }
            }

            // $result = mysqli_query($con, "SELECT `id`, `title_ar` FROM `brands`");
            
            ?>	


            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container">
                        <!-- Page-Title -->
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="page-title">الأقسام الرئيسية للمنتجات</h4>
                                <ol class="breadcrumb">
                                    <li><a href="cats_view.php"> الأقسام الرئيسية للمنتجات</a></li>
                                    <li class="active">أضف قسم جديد</li>
                                </ol>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-box">
                                    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" data-parsley-validate novalidate>
                                        <div class="form-group col-md-3">
                                            <label for="name_ar">الإسم بالعربي *</label>
                                            <input type="text" name="name_ar" parsley-trigger="change" required placeholder="الإسم بالعربي" class="form-control" id="parent_cat_name">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="name_en">الإسم بالإنجليزي*</label>
                                            <input type="text" name="name_en" parsley-trigger="change" required placeholder="الإسم بالإنجليزي" class="form-control" id="name_en">
                                        </div>	
                                        <div class="form-group col-md-3">
                                            <label for="description_ar">وصف القسم بالعربي</label>
                                            <textarea class="form-control" rows="3" name="description_ar"  minlength="3" maxlength="100"></textarea>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="description_en">وصف القسم بالإنجليزي </label>
                                            <textarea class="form-control" rows="3" name="description_en"  minlength="3" maxlength="100"></textarea>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group m-b-0">
                                            <label class="control-label">صورة القسم</label>
                                            <input type="file" name="cat_photo" id="cat_photo" class="filestyle" data-buttonname="btn-primary">
                                        </div>
                                        <br />

                                        <!-- <div class="form-group m-b-0">
                                            <label>اختر الماركات</label>
                                            <select id="multiple" name="brands[]" 
                                            placeholder="اختر الماركات"
                                            class="multi-select-account select2-multiple" multiple>
                                                
                                                <?php
                                                if(mysqli_num_rows($result) > 0){

                                                    while($row = mysqli_fetch_assoc($result)){
                                                        echo "<option value='".$row['id']."'>" . $row['title_ar'] . "</option>";
                                                    }

                                                }
                                                ?>
                                            </select>
                                        </div> -->
                                        <br><br>

                                        <div class="form-group text-right m-b-0">
                                            <button class="btn btn-primary waves-effect waves-light" type="submit" name="submit"> إضافة </button>
                                            <button type="reset" class="btn btn-default waves-effect waves-light m-l-5"> إلغاء </button>
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
                $("#item31").addClass("active");
            });
            
            $(".multi-select-account").select2();

        </script>

    </body>
</html>
