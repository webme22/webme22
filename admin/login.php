<?ob_start(); ?>
<?php
include_once("config.php");
if (loggedin()) {
    header("Location: index.php?lang=".$lang);
    exit();
}
?>

<!DOCTYPE html>
<html>
    <?php include("include/heads.php"); ?>
    <body>

        <?php
        // error_reporting(0);
        if (isset($_POST['submit'])) {

            $username = mysqli_real_escape_string($con, $_POST['username']);
            $password = $_POST['password'];

            // check that username & password entered !!
            if ($username && $password) {
                $login = $con->query("SELECT * FROM `users` WHERE `user_name`='$username'");
                if (mysqli_num_rows($login) == 0) {
                    echo get_error($languages[$lang]["invalidUserName"]);
                } else {
                    while ($row = mysqli_fetch_assoc($login)) {
                        // Check Password
                        if (password_verify($password, $row['user_password']) && $row["role"] == "admin") {
                            $_SESSION['user_id'] = $row['user_id'];
                            $_SESSION['role'] = $row['role'];
                            $_SESSION['user_name'] = $row['user_name'];
                            $_SESSION['clients'] = $row['clients'];
                            $_SESSION['setting'] = $row['setting'];
                            $_SESSION['families'] = $row['families'];
                            $_SESSION['countries'] = $row['countries'];
                            $_SESSION['users'] = $row['users'];
                            $_SESSION['services'] = $row['services'];
                            $_SESSION['messages'] = $row['messages'];

                            header("Location: index.php?lang=".$lang);
                        } else {
                            echo get_error($languages[$lang]["invalidPass"]);
                        }
                    }
                }
            }
        }
        ?>

        <div class="account-pages"></div>
        <div class="clearfix"></div>
        <div class="wrapper-page">
            <div class=" card-box">
                <div class="panel-heading"> 
                    <h3 class="text-center"> <?php 
                    echo $languages[$lang]["login"];
                    ?><strong class="text-custom"><?php echo $languages[$lang]["website"];  ?></strong> </h3>
                </div> 
                <div class="panel-body">
                    <form class="form-horizontal m-t-20" action="" method="POST">

                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input class="form-control" type="text" name="username" required="" placeholder="<?php      echo $languages[$lang]["username"]; 
                                ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control" type="password" name="password" required="" placeholder="<?php 
                                
                                    echo $languages[$lang]["password"];
                                    
                                ?>">
                            </div>
                        </div>

                        <div class="form-group ">
                            <div class="col-xs-12">
                                <div class="checkbox checkbox-primary">
                                    <input id="checkbox-signup" type="checkbox">
                                    <label for="checkbox-signup">
                                        <?php  echo $languages[$lang]["remember"];      ?>
                                    </label>
                                </div>

                            </div>
                        </div>

                        <div class="form-group text-center m-t-40">
                            <div class="col-xs-12">
                                <button class="btn btn-pink btn-block text-uppercase waves-effect waves-light" type="submit" name="submit"><?php echo $languages[$lang]["login"];    ?></button>
                            </div>
                        </div>
                        <div class="form-group m-t-30 m-b-0">
                            <div class="col-sm-12">
                                <a  href="email_recover.php" class="text-dark"><i class="fa fa-lock m-r-5"></i> <?php   
                                
                                    echo $languages[$lang]["forgetPassword"];
                                
                                ?></a>
                            </div>
                        </div>
                    </form>					
                </div>   
            </div>                              
            <div class="row">
                <div class="col-sm-12 text-center">
                    <p><a href="<?php echo $_SERVER['PHP_SELF'] . "?lang=" . (($lang == 'en')? 'ar' : 'en'); ?>" class="text-primary m-l-5"><span> <?php 
                    
                    if($lang == "en"){
                        echo "العربيه";
                    } else {
                        echo "English";
                    }
                    
                    ?></span></a></p>
                </div>
            </div>	
        </div>
        <?php include("include/footer.php"); ?>
    </body>
</html>
<?ob_flush(); ?>
