<?php
    include("config.php");
    if (!loggedin()) {
        header("Location: login.php");
        exit();
    }
    if (($_SESSION['role'] != 'admin' || $_SESSION['users'] != 1)) {
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
                    <!-- ============================================================== -->
                    <!-- Start right Content here -->
                    <!-- ============================================================== -->  		
                    <?php
                        if (isset($_POST['user_update'])) {
                            $userID_update = $_POST['userID_update'];
                                   
                            $userPassword = trim($_POST['userPassword']);
                           
                            $country_id = $_POST['country_id'];
                            $nationality_id = $_POST['nationality_id'];
                            $userName = mysqli_real_escape_string($con, trim($_POST['userName']));
                            $name = mysqli_real_escape_string($con, trim($_POST['name']));
                        
                            $userEmail = mysqli_real_escape_string($con, trim($_POST['userEmail']));
                        
                            $userPhone = trim($_POST['userPhone']);
                            
                            if (isset($_POST['clients'])) {
                                $clients = 1;
                            } else {
                                $clients = 0;
                            }
                        
                            if (isset($_POST['countries'])) {
                                $countries = 1;
                            } else {
                                $countries = 0;
                            }
                        
                            if (isset($_POST['managers'])) {
                                $users = 1;
                            } else {
                                $users = 0;
                            }
                        
                            if (isset($_POST['services'])) {
                                $services = 1;
                            } else {
                                $services = 0;
                            }
                        
                        
                            if (isset($_POST['setting'])) {
                                $setting = 1;
                            } else {
                                $setting = 0;
                            }
                        
                        
                            if (isset($_POST['messages'])) {
                                $messages = 1;
                            } else {
                                $messages = 0;
                            }
                            if (isset($_POST['families'])) {
                                $families = 1;
                            } else {
                                $families = 0;
                            }
                        
                        
                            $errors = array();
                        
                        
                                if (empty($userName) || empty($userPassword)) {
                                    $errors[] = $languages[$lang]["required"];
                                }
                        
                                if (filter_var($userEmail, FILTER_VALIDATE_EMAIL) === false) {
                                    $errors[] = $languages[$lang]["invalidEmail"];
                                }
                                if (strlen($userPassword) > 255) {
                                    $errors[] = $languages[$lang]["largePassword"];
                                }
                                if (in_array($image_ext, $allowed_ext) === false) {
                                    $errors[] = $languages[$lang]["unallowedFile"];
                                }
                            
                                if (userEmailExists($userEmail, $userID_update)) {
                                    $errors[] = $languages[$lang]["emailExists"];  
                                }
                        
                                if (userNameExists($userName, $userID_update)) {
                                    $errors[] = $languages[$lang]["nameExists"];
                                }
                        
                            if (!empty($errors)) {
                                foreach ($errors as $error) {
                                    echo get_error($error);
                                }
                            } else {
                                
                                if (isset($_FILES['photo']['name']) && !empty($_FILES['photo']['name'])) {
                        
                                    $image_name_update = $_FILES['photo']['name'];
                                    $image_tmp_update = $_FILES['photo']['tmp_name'];
                                    $allowed_ext = array('jpg', 'jpeg', 'gif', 'png');
                                    $get_image_ext = explode('.', $image_name_update);
                                    $image_ext = strtolower(end($get_image_ext));
                                    
                                    if(! file_exists("../uploads/users/" . $userID_update)){
                                        mkdir("../uploads/users/" . $userID_update, 0775, true);
                                    }
                        
                                    $image_database = "uploads/users/" . $userID_update . "/" . round(microtime(true)) ."." . $image_ext;
                                    $update = $con->query("UPDATE `users` SET `countries`='$countries',`services`='$services',`families`='$families',`clients`='$clients',`users`='$users',`setting`='$setting',`messages`='$messages', `user_name`='$userName',`email`='$userEmail',
                                        `phone`='$userPhone',`name`='$name', `role`='admin',`image`='$image_database', `country_id`='$country_id', `nationality`='$nationality_id' WHERE `user_id`='$userID_update'") or die(mysqli_error($con));
                        
                                    $image_path = "../uploads/users/" . $userID_update . "/" . round(microtime(true)) . "." . $image_ext;
                        
                                    move_uploaded_file($image_tmp_update, $image_path);
                        
                                } else if(empty($_FILES['photo']['name'])){
                                    $update = $con->query("UPDATE `users` SET `countries`='$countries',`services`='$services',`families`='$families',`clients`='$clients',`users`='$users',`setting`='$setting',`messages`='$messages', `user_name`='$userName',`email`='$userEmail',
                                        `phone`='$userPhone',`name`='$name', `role`='admin', `country_id`='$country_id', `nationality`='$nationality_id'  WHERE `user_id`='$userID_update'") or die(mysqli_error($con));
                        
                        
                                }

                                if(isset($userPassword) && $userPassword != ''){
                                    $userPassword = password_hash($userPassword, PASSWORD_DEFAULT);
                                    $update = $con->query("UPDATE `users` SET `user_password`='$userPassword'  WHERE `user_id`='$userID_update'") or die(mysqli_error($con));
                                }
                                
                                if ($update) {
                                    echo get_success($languages[$lang]["updateMessage"]);
                                } else {
                                    echo get_error("هنا خطأ ما !");
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
                                        <h4 class="page-title"><?php echo $languages[$lang]["users"]; ?></h4>
                                        <ol class="breadcrumb">
                                            <li><a href="users_view.php?lang=<?php echo $lang; ?>"><?php echo $languages[$lang]["users"]; ?></a></li>
                                            <li class="active"> <?php echo $languages[$lang]["editUser"]; ?></li>
                                        </ol>
                                    </div>
                                </div>
                                <div class="updateData"></div>
                                <?php
                                    if ($_GET['userID']) {
                                    
                                        $get_user_id = $_GET['userID'];
                                    
                                        $query_select = $con->query("SELECT * FROM `users` WHERE `user_id` = '{$get_user_id}' LIMIT 1");
                                        $row_select = mysqli_fetch_array($query_select);
                                    
                                        $user_id = $row_select['user_id'];
                                        $user_name = $row_select['user_name'];
                                        $name = $row_select['name'];
                                        $user_password = $row_select['user_password'];
                                        $user_email = $row_select['email'];
                                        $user_phone = $row_select['phone'];
                                        $user_image = $row_select['image'];
                                        $cover = $row_select['cover'];
                                        $role = $row_select['role'];
                                        $users = $row_select['users'];
                                        $families = $row_select['families'];
                                        $countries = $row_select['countries'];
                                        $services = $row_select['services'];
                                        
                                        $setting = $row_select['setting'];
                                        
                                        $clients = $row_select['clients'];
                                       
                                        $messages = $row_select['messages'];
                                        $countryId = $row_select['country_id'];
                                        $nationalityId = $row_select['nationality'];
                                        
                                        if ($query_select) {
                                            ?>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card-box">
                                            <form method="POST" enctype="multipart/form-data" data-parsley-validate novalidate>
                                                <input type="hidden" name="userID_update" id="userID_update" parsley-trigger="change" required value="<?php echo $user_id; ?>" class="form-control">
                                                <input type="hidden" name="old_pass" id="old_pass" parsley-trigger="change" required class="form-control">
                                                <input type="hidden" id="role" parsley-trigger="change" required value="<?php echo $role; ?>" class="form-control">
                                                <div class="form-group col-md-3">
                                                    <label for="name"><?php    
                                                        echo  $languages[$lang]["name"];
                                                         ?></label>
                                                    <input type="text" name="name" parsley-trigger="change" required placeholder="<?php    
                                                        echo $languages[$lang]["name"];
                                                        ?>" class="form-control" id="name" value="<?php echo $name;  ?>">
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label for="userName"><?php    
                                                        echo  $languages[$lang]["username"];
                                                         ?></label>
                                                    <input type="text" name="userName" parsley-trigger="change" required placeholder="<?php    
                                                        echo $languages[$lang]["username"];
                                                        ?>" class="form-control" id="userName" value="<?php echo $user_name;  ?>">
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label for="emailAddress">
                                                    <?php    
                                                        echo $languages[$lang]["email"];
                                                        ?>    
                                                    </label>
                                                    <input type="email" name="userEmail" parsley-trigger="change" required placeholder="<?php    
                                                        echo $languages[$lang]["email"];
                                                        ?> " class="form-control" id="emailAddress" value="<?php echo $user_email;  ?>">
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label for="pass1"><?php    
                                                        echo $languages[$lang]["password"];
                                                        ?> </label>
                                                    <input id="pass1" name="userPassword" type="password" required class="form-control">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="number"> <?php    
                                                        echo $languages[$lang]["phone"];
                                                        ?></label>
                                                    <input type="number" min="0" name="userPhone" required placeholder="<?php    
                                                        echo $languages[$lang]["phone"];
                                                        ?> " class="form-control" id="userPhone" value="<?php echo $user_phone;  ?>">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="country_id"> <?php    
                                                        echo $languages[$lang]["country"];
                                                        ?></label>
                                                    <select name="country_id" required  class="form-control" id="country_id">
                                                    <?php
                                                        $result = $con->query("select id, name_$lang from countries");
                                                        while($row = mysqli_fetch_assoc($result)){
                                                            if($countryId == $row['id']){
                                                             echo "<option value='{$row["id"]}' selected>{$row['name_'.$lang]}</option>";
                                                             
                                                            } else {
                                                                
                                                            echo "<option value='{$row["id"]}'>{$row['name_'.$lang]}</option>";
                                                            
                                                            }
                                                        }
                                                        
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="nationality_id"> <?php    
                                                        echo $languages[$lang]["nationality"];
                                                        ?></label>
                                                    <select name="nationality_id" required  class="form-control" id="nationality_id">
                                                    <?php
                                                        $result = $con->query("select id, name from nationalities");
                                                        while($row = mysqli_fetch_assoc($result)){
                                                            if($nationalityId == $row['id']){
                                                             echo "<option value='{$row["id"]}' selected>{$row['name']}</option>";
                                                             
                                                            } else {
                                                                
                                                            echo "<option value='{$row["id"]}'>{$row['name']}</option>";
                                                            
                                                            }
                                                        }
                                                        
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="clearfix"></div>
                                                <br><br>
                                                <div class="clearfix"></div>
                                                <input type="hidden" name="image_ext_old" value="<?php echo $image_ext; ?>" />
                                                <div class="gal-detail thumb getImage">
                                                    <a href="<?=asset($user_image)?>" class="image-popup" title="<?php echo $user_name; ?>">
                                                    <img src="<?=asset($user_image)?>" class="thumb-img" alt="<?php echo $user_name; ?>">
                                                    </a>
                                                </div>
                                                <div class="form-group m-b-0">
                                                    <label class="control-label"><?php    
                                                        echo $languages[$lang]["image"];
                                                        ?>  </label>
                                                    <input type="file" name="photo" id="photo" class="filestyle" data-buttonname="btn-primary">
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="clearfix"></div>
                                                <div id="sectionTwo" class="getSections">
                                                    <h2><?php echo $languages[$lang]["permissions"];    ?></h2>
                                                    <div class="col-sm-3">
                                                        <div class="checkbox">
                                                            <input type="checkbox" id="families" name="families" class="form-control" <?php  
                                                                if($families == 1){
                                                                    echo "checked";
                                                                }
                                                                
                                                                ?>>
                                                            <label for="families">  <?php echo $languages[$lang]["families"];    ?></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="checkbox">
                                                            <input type="checkbox" id="managers" name="managers" class="form-control" <?php  
                                                                if($users == 1){
                                                                    echo "checked";
                                                                }
                                                                
                                                                ?>>
                                                            <label for="managers">  <?php  echo $languages[$lang]["managers"]; ?> </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="checkbox">
                                                            <input type="checkbox" id="setting" name="setting" class="form-control" <?php  
                                                                if($setting == 1){
                                                                    echo "checked";
                                                                }
                                                                
                                                                ?>>
                                                            <label for="setting">  <?php echo $languages[$lang]["setting"];
                                                                ?></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="checkbox">
                                                            <input type="checkbox" id="messages" name="messages" class="form-control" <?php  
                                                                if($messages == 1){
                                                                    echo "checked";
                                                                }
                                                                
                                                                ?>>
                                                            <label for="messages">  
                                                            <?php echo $languages[$lang]["messages"];   ?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="checkbox">
                                                            <input type="checkbox" id="clients" name="clients" class="form-control" <?php  
                                                                if($clients == 1){
                                                                    echo "checked";
                                                                }
                                                                
                                                                ?>>
                                                            <label for="clients">  
                                                            <?php echo $languages[$lang]["clients"]; ?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="checkbox">
                                                            <input type="checkbox" id="coutries" name="coutries" class="form-control" <?php  
                                                                if($countries == 1){
                                                                    echo "checked";
                                                                }
                                                                
                                                                ?>>
                                                            <label for="coutries">  
                                                            <?php  echo $languages[$lang]["countries"];  ?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="checkbox">
                                                            <input type="checkbox" id="services" name="services" class="form-control" <?php  
                                                                if($services == 1){
                                                                    echo "checked";
                                                                }
                                                                
                                                                ?>>
                                                            <label for="services">   
                                                            <?php echo $languages[$lang]["services"];   ?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="form-group text-right m-b-0">
                                                    <button class="btn btn-primary waves-effect waves-light" type="submit" name="user_update" id="updateUser"><?php echo $languages[$lang]["update"];   ?></button>
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
                let role = $('#role').val();
                if(role == 'admin'){
                    $("#cssmenu ul>li").removeClass("active");
                    $("#item12").addClass("active");
                } else {
                    $("#cssmenu ul>li").removeClass("active");
                    $("#item7").addClass("active");
                }
            });
        </script>
    </body>
</html>