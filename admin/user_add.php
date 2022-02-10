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
            if (isset($_POST['submit'])) {
                $userName = mysqli_real_escape_string($con, trim($_POST['userName']));
                $name = mysqli_real_escape_string($con, trim($_POST['name']));

                $userEmail = mysqli_real_escape_string($con, trim($_POST['userEmail']));

                $userPassword = trim($_POST['userPassword']);

                $userPhone = trim($_POST['userPhone']);
                $countryId = $_POST['country_id'];
                $nationalityId = $_POST['nationality_id'];


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

                $gender = $_POST['gender'];
                $photo_name = $_FILES['photo']['name'];
                $photo_tmp = $_FILES['photo']['tmp_name'];
                $allowed_ext = array('jpg', 'jpeg', 'gif', 'png');
                $get_image_ext = explode('.', $photo_name);
                $image_ext = strtolower(end($get_image_ext));

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
                
                    if (userEmailExists($userEmail)) {
                        $errors[] = $languages[$lang]["emailExists"];  
                    }

                    if (userNameExists($userName)) {
                        $errors[] = $languages[$lang]["nameExists"];
                    }



                if (!empty($errors)) {
                    foreach ($errors as $error) {
                        echo get_error($error);
                    }
                } else {
                    $add_user = add_user($users, $countries, $services,  $families, $setting, $messages, $clients, $name, $userName, $userEmail, $userPassword, $userPhone, $countryId, $gender, $nationalityId);
                    
                    
                    if(! file_exists("../uploads/users/" . mysqli_insert_id($con))){
                        mkdir("../uploads/users/" . mysqli_insert_id($con), 0775, true);
                    }
    
                    $image_database = "uploads/users/" . mysqli_insert_id($con) . "/" . round(microtime(true)) ."." . $image_ext;
    
                    $image_path = "../uploads/users/" . mysqli_insert_id($con) . "/" . round(microtime(true)) ."." . $image_ext;
                    
                    $con->query("update `users` set `image`='$image_database' where `user_id`='".mysqli_insert_id($con)."' ");

                    move_uploaded_file($photo_tmp, $image_path);

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
                                <h4 class="page-title"><?php echo $languages[$lang]["managers"]; ?></h4>
                                <ol class="breadcrumb">
                                    <li><a href="users_view.php?lang=<?php echo $lang; ?>"><?php echo $languages[$lang]["managers"]; ?></a></li>
                                    <li class="active"><?php echo $languages[$lang]["addManager"]; ?></li>
                                </ol>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-box">


                                    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" data-parsley-validate novalidate>
                                        
                                        <div class="form-group col-md-3">
                                            <label for="name"><?php    
                                           echo  $languages[$lang]["name"];
                                            ?></label>
                                            <input type="text" name="name" parsley-trigger="change" required placeholder="<?php    
                                            echo $languages[$lang]["name"];
                                            ?>" class="form-control" id="name">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="userName"><?php    
                                           echo  $languages[$lang]["username"];
                                            ?></label>
                                            <input type="text" name="userName" parsley-trigger="change" required placeholder="<?php    
                                            echo $languages[$lang]["username"];
                                            ?>" class="form-control" id="userName">
                                        </div>
                                        
                                        <div class="form-group col-md-3">
                                            <label for="emailAddress">
                                            <?php    
                                            echo $languages[$lang]["email"];
                                            ?>    
                                             </label>
                                            <input type="email" name="userEmail" parsley-trigger="change" required placeholder="<?php    
                                            echo $languages[$lang]["email"];
                                            ?> " class="form-control" id="emailAddress">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="pass1"><?php    
                                            echo $languages[$lang]["password"];
                                            ?> </label>
                                            <input id="pass1" name="userPassword" type="password" placeholder="<?php    
                                            echo $languages[$lang]["password"];
                                            ?> " required class="form-control">
                                            <input type='checkbox' id='toggle'>&nbsp; <span id='toggleText'><?= $languages[$lang]["show_password"] ?></span></td>

                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="number"> <?php    
                                            echo $languages[$lang]["phone"];
                                            ?></label>
                                            <input type="number" min="0" name="userPhone" required placeholder="<?php    
                                            echo $languages[$lang]["phone"];
                                            ?> " class="form-control" id="userPhone">
                                        </div>
                                        
                                        <div class="form-group col-md-4">
                                            <label for="country_id"> <?php    
                                            echo $languages[$lang]["country"];
                                            ?></label>
                                            <select name="country_id" required  class="form-control" id="country_id">
                                                
                                                <?php
                                                
                                                    $result = $con->query("select id, name_$lang from countries");
                                                    while($row = mysqli_fetch_assoc($result)){
                                                        echo "<option value='{$row["id"]}'>{$row['name_'.$lang]}</option>";
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
                                                        echo "<option value='{$row["id"]}'>{$row['name']}</option>";
                                                    }
                                                
                                                ?>
                                                
                                            </select>
                                        </div>
                                        
                                        <div class="clearfix"></div>
                                        
                                        <div class="form-group">
                                        <label for="Gender"><?php echo $languages[$lang]["gender"]; ?></label><br>
                                            <input type="radio" name="gender" required  class=""  value="Male"> <?php echo $languages[$lang]["male"]; ?> <br>
                                            <input type="radio" name="gender"  required  class="" id="gender" value="Female"> <?php echo $languages[$lang]["female"]; ?>
                                        </div>															
                                        <div class="clearfix"></div>
                                        
                                        <div class="form-group m-b-0">
                                            <label class="control-label"><?php    
                                            echo $languages[$lang]["image"];
                                            ?>  </label>
                                            <input type="file" name="photo" id="photo" class="filestyle" data-buttonname="btn-primary">
                                        </div>	
                                        
                                        <div class="clearfix"></div>

                                        <div id="sectionTwo" class="getSections">
                                            <h2><?php echo $languages[$lang]["permissions"];    ?></h2>	

                                        
                                            <div class="col-sm-3">
                                                <div class="checkbox">
                                                    <input type="checkbox" id="families" name="families" class="form-control">
                                                    <label for="families">  <?php echo $languages[$lang]["families"];    ?></label>
                                                </div>										
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="checkbox">
                                                    <input type="checkbox" id="managers" name="managers" class="form-control">
                                                    <label for="managers">  <?php  echo $languages[$lang]["managers"]; ?> </label>
                                                </div>										
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="checkbox">
                                                    <input type="checkbox" id="setting" name="setting" class="form-control">
                                                    <label for="setting">  <?php echo $languages[$lang]["setting"];
                                                    ?></label>
                                                </div>                                      
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="checkbox">
                                                    <input type="checkbox" id="messages" name="messages" class="form-control">
                                                    <label for="messages">  
                                                    <?php echo $languages[$lang]["messages"];   ?>
                                                    </label>
                                                </div>                                      
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="checkbox">
                                                    <input type="checkbox" id="clients" name="clients" class="form-control">
                                                    <label for="clients">  
                                                    
                                                    <?php echo $languages[$lang]["clients"]; ?>
                                                    
                                                    </label>
                                                </div>                                      
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="checkbox">
                                                    <input type="checkbox" id="coutries" name="coutries" class="form-control">
                                                    <label for="coutries">  
                                                    <?php  echo $languages[$lang]["countries"];  ?>
                                                    </label>
                                                </div>                                      
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="checkbox">
                                                    <input type="checkbox" id="services" name="services" class="form-control">
                                                    <label for="services">   
                                                    <?php echo $languages[$lang]["services"];   ?>
                                                    
                                                    </label>
                                                </div>                                      
                                            </div>


                                        </div>	

                                        <div class="form-group text-right m-b-0">
                                            <button class="btn btn-primary waves-effect waves-light" type="submit" name="submit">
                                                <?php echo $languages[$lang]["add"];    ?>
                                            </button>
                                            <button type="reset" class="btn btn-default waves-effect waves-light m-l-5">
                                                <?php echo $languages[$lang]["cancel"];    ?>
                                            </button>
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
                $("#item12").addClass("active");

                $("#toggle").change(function(){
  
                    if($(this).is(':checked')){

                        $("#pass1").attr("type","text");
                        
                        $("#toggleText").text("<?= $languages[$lang]["hide_password"] ?>");
                    } else {
                        $("#pass1").attr("type","password");
                        
                        $("#toggleText").text("<?= $languages[$lang]["show_password"] ?>");
                    }
                
                });
            });
        </script>
    </body>
</html>
