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
                $country_id = mysqli_real_escape_string($con, trim($_POST['country_id']));
                $lang_id = mysqli_real_escape_string($con, trim($_POST['lang_id']));
                $name = mysqli_real_escape_string($con, trim($_POST['name']));

                if (empty($country_id) || empty($lang_id) || empty($name)) {
                    $errors = $languages[$lang]["required"];
                }
                if (strlen($errors) > 4) {
                    echo get_error($errors);
                } else {
                    $add_group = add_group($country_id, $lang_id, $name);
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
                                <h4 class="page-title"><?php echo $languages[$lang]["group_add"];     ?></h4>
                                <ol class="breadcrumb">
                                    <li><a href="groups_view.php?lang=<?php  
                                        echo $lang;
                                        ?>"> <?php echo $languages[$lang]["groups"];     ?></a></li>
                                    <li class="active"><?php echo $languages[$lang]["groups"];     ?></li>
                                </ol>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-box">
                                    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" data-parsley-validate novalidate>
                                        <div class="form-group col-md-4">
                                            <label for="name"><?php echo $languages[$lang]['name'];  ?></label>
                                            <input class="form-control" name="name" id="name">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="country_id"><?php echo $languages[$lang]['country'];  ?></label>
                                            <select name="country_id"  required class="form-control" id="country_id">
                                                <option value=""><?= $languages[$lang]["choose"] ?></option>
                                                <?php
                                                    $countries = $con->query("select id,name_en from countries");
                                                    while($row_1 = mysqli_fetch_assoc($countries)){
                                                        echo "<option value='{$row_1['id']}'>{$row_1['name_en']}</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="lang_id"><?php echo $languages[$lang]['language'];  ?></label>
                                            <select name="lang_id"  required class="form-control" id="lang_id">
                                                <option value=""><?= $languages[$lang]["choose"] ?></option>
                                                <?php
                                                    $langs = $con->query("select id,lang from languages");
                                                    while($row_1 = mysqli_fetch_assoc($langs)){
                                                        echo "<option value='{$row_1['id']}'>{$row_1['lang']}</option>";
                                                    }
                                                ?>
                                            </select>
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
                $("#item105").addClass("active");           
            });
        </script>

    </body>
</html>
