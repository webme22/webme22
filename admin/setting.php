<?php
include("config.php");
if (!loggedin()) {
    header("Location: login.php");
    exit();
}
if (($_SESSION['role'] != 'admin') ) {
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
// error_reporting(0);

            if (isset($_POST['setting_submit'])) {
                
                // $mptitle = mysqli_real_escape_string($con, trim($_POST['mptitle']));
                // $mpheader = mysqli_real_escape_string($con, trim($_POST['mpheader']));
                // $mpbody = mysqli_real_escape_string($con, trim($_POST['mpbody']));
                // $stitle = mysqli_real_escape_string($con, trim($_POST['stitle']));
                // $sheader = mysqli_real_escape_string($con, trim($_POST['sheader']));
                // $sbody = mysqli_real_escape_string($con, trim($_POST['sbody']));
                
                // $atitle = mysqli_real_escape_string($con, trim($_POST['atitle']));
                // $firstheader = mysqli_real_escape_string($con, trim($_POST['firstheader']));
                // $secheader = mysqli_real_escape_string($con, trim($_POST['secheader']));
                // $firstbody = mysqli_real_escape_string($con, trim($_POST['firstbody']));
                // $secbody = mysqli_real_escape_string($con, trim($_POST['secbody']));
                // $ctitle = mysqli_real_escape_string($con, trim($_POST['ctitle']));
                // $ptitle = mysqli_real_escape_string($con, trim($_POST['ptitle']));
                $facebook = mysqli_real_escape_string($con, trim($_POST['facebook']));
                $twitter = mysqli_real_escape_string($con, trim($_POST['twitter']));
                $instagram = mysqli_real_escape_string($con, trim($_POST['instagram']));
                $youtube = mysqli_real_escape_string($con, trim($_POST['youtube']));
                $linkedin = mysqli_real_escape_string($con, trim($_POST['linkedin']));
                
                
                
                $update = $con->query("UPDATE `setting` SET `facebook`='$facebook', `twitter`='$twitter', `instagram`='$instagram', `youtube`='$youtube', `linkedin`='$linkedin' WHERE `id`=1 ") or die(mysqli_error($con));
                


                if ($update) {
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
                                <h4 class="page-title"><?php echo $languages[$lang]["setting"]; ?></h4>
                                <ol class="breadcrumb">
                                    <!--<li><a href="user_add.php">المديرين</a></li>-->
                                    <!--<li class="active">تعديل مدير</li>-->
                                </ol>
                            </div>
                        </div>

                        <div class="updateData"></div>

                        <?php
                        

                            $query_select = $con->query("SELECT * FROM `setting` WHERE `id` = 1 LIMIT 1");
                            $row = mysqli_fetch_array($query_select);

                            
                            if ($query_select) {
                                ?>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card-box"> 									
                                            <form method="POST" enctype="multipart/form-data" data-parsley-validate novalidate>
                                                
                                            <!-- <h3 style="text-align: center;"> <?php echo $languages[$lang]["mostpopular"]; ?></h3>
                                                <div class="form-group col-md-5">
                                                    <label for="mptitle"><?php echo $languages[$lang]["title"]; ?> </label>
                                                    <input type="text" name="mptitle" id="mptitle" parsley-trigger="change" required value="<?php echo $row['MPtitle']; ?>" class="form-control">
                                                </div>
                                                
                                                <div class="form-group col-md-5">
                                                    <label for="mpheader"><?php echo $languages[$lang]["header"]; ?> </label>
                                                    <input type="text" name="mpheader" id="mpheader" parsley-trigger="change" required value="<?php echo $row['MPheader']; ?>" class="form-control">
                                                </div>
                                                 
                                                <div class="clearfix"></div>
                                                
                                                <div class="form-group">
                                                    <label for="mpbody"><?php echo $languages[$lang]["body"]; ?> </label>
                                                    <textarea class="form-control" rows="3" name="mpbody"  minlength="3" maxlength="1000" required=""><?php echo $row['MPbody']; ?></textarea>
                                                </div>
                                                
                                                <br> -->
                                                
                                                <!-- <h3 style="text-align: center;"> <?php echo $languages[$lang]["services"]; ?></h3>
                                                
                                                <div class="form-group col-md-5">
                                                    <label for="stitle"><?php echo $languages[$lang]["title"]; ?> </label>
                                                    <input type="text" name="stitle" id="stitle" parsley-trigger="change" required value="<?php echo $row['BStitle']; ?>" class="form-control">
                                                </div>
                                                
                                                <div class="form-group col-md-5">
                                                    <label for="sheader"><?php echo $languages[$lang]["header"]; ?> </label>
                                                    <input type="text" name="sheader" id="sheader" parsley-trigger="change" required value="<?php echo $row['BSheader']; ?>" class="form-control">
                                                </div>
                                                 
                                                <div class="clearfix"></div>
                                                
                                                <div class="form-group">
                                                    <label for="sbody"><?php echo $languages[$lang]["body"]; ?> </label>
                                                    <textarea class="form-control" rows="3" name="sbody"  minlength="3" maxlength="1000" required=""><?php echo $row['BSbody']; ?></textarea>
                                                </div>
                                                
                                                <br>
                                                
                                                <h3 style="text-align: center;"> <?php echo $languages[$lang]["about"]; ?></h3>
                                                
                                                <div class="form-group col-md-4">
                                                    <label for="atitle"><?php echo $languages[$lang]["title"]; ?> </label>
                                                    <input type="text" name="atitle" id="atitle" parsley-trigger="change" required value="<?php echo $row['title']; ?>" class="form-control">
                                                </div> -->
                                                
                                                <!-- <div class="form-group col-md-4">
                                                    <label for="firstheader"><?php echo $languages[$lang]["firstheader"]; ?> </label>
                                                    <input type="text" name="firstheader" id="firstheader" parsley-trigger="change" required value="<?php echo $row['firstHeader']; ?>" class="form-control">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="secheader"><?php echo $languages[$lang]["secheader"]; ?> </label>
                                                    <input type="text" name="secheader" id="secheader" parsley-trigger="change" required value="<?php echo $row['secHeader']; ?>" class="form-control">
                                                </div>
                                                 
                                                <div class="clearfix"></div>
                                                
                                                <div class="form-group">
                                                    <label for="firstbody"><?php echo $languages[$lang]["firstbody"]; ?> </label>
                                                    <textarea class="form-control" rows="3" name="firstbody"  minlength="3" maxlength="1000" required=""><?php echo $row['firstbody']; ?></textarea>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="secbody"><?php echo $languages[$lang]["secbody"]; ?> </label>
                                                    <textarea class="form-control" rows="3" name="secbody"  minlength="3" maxlength="1000" required=""><?php echo $row['secbody']; ?></textarea>
                                                </div>
                                                
                                                <br>
                                                
                                                <h3 style="text-align: center;"> <?php echo $languages[$lang]["clients"]; ?></h3>
                                                <div class="form-group">
                                                    <label for="ctitle"><?php echo $languages[$lang]["title"]; ?> </label>
                                                    <input type="text" name="ctitle" id="ctitle" parsley-trigger="change" required value="<?php echo $row['clientsTitle']; ?>" class="form-control">
                                                </div> -->
                                                
                                                <!-- <br>
                                                
                                                <h3 style="text-align: center;"> <?php echo $languages[$lang]["plans"]; ?></h3>
                                                <div class="form-group">
                                                    <label for="ptitle"><?php echo $languages[$lang]["title"]; ?> </label>
                                                    <input type="text" name="ptitle" id="ptitle" parsley-trigger="change" required value="<?php echo $row['plansTitle']; ?>" class="form-control">
                                                </div>
                                                
                                                <br> -->
                                                
                                                <h3 style="text-align: center;"> <?php echo $languages[$lang]["social"]; ?></h3>
                                                <div class="form-group col-md-4">
                                                    <label for="facebook"><?php echo $languages[$lang]["facebook"]; ?> </label>
                                                    <input type="text" name="facebook" id="facebook" parsley-trigger="change" required value="<?php echo $row['facebook']; ?>" class="form-control">
                                                </div>
                                                
                                                <div class="form-group col-md-4">
                                                    <label for="twitter"><?php echo $languages[$lang]["twitter"]; ?> </label>
                                                    <input type="text" name="twitter" id="twitter" parsley-trigger="change" required value="<?php echo $row['twitter']; ?>" class="form-control">
                                                </div>
                                                
                                                <div class="form-group col-md-4">
                                                    <label for="instagram"><?php echo $languages[$lang]["instagram"]; ?> </label>
                                                    <input type="text" name="instagram" id="instagram" parsley-trigger="change" required value="<?php echo $row['instagram']; ?>" class="form-control">
                                                </div>
                                                
                                                <div class="form-group col-md-4">
                                                    <label for="youtube"><?php echo $languages[$lang]["youtube"]; ?> </label>
                                                    <input type="text" name="youtube" id="youtube" parsley-trigger="change" required value="<?php echo $row['youtube']; ?>" class="form-control">
                                                </div>
                                                
                                                <div class="form-group col-md-4">
                                                    <label for="linkedin"><?php echo $languages[$lang]["linkedin"]; ?> </label>
                                                    <input type="text" name="linkedin" id="linkedin" parsley-trigger="change" required value="<?php echo $row['linkedin']; ?>" class="form-control">
                                                </div>
                                                
                                                <br>
                                                <div class="clearfix"></div>
                                                <br>
                                                <div class="form-group text-right m-b-0">
                                                    <button class="btn btn-primary waves-effect waves-light" type="submit" name="setting_submit" id="updateUser"><?php echo $languages[$lang]["edit"]; ?></button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        // }
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
                $("#cssmenu ul>li").removeClass("active");
                $("#item71").addClass("active");
                
                $('#facebook').mouseleave(function(){
                    let fb = $(this).val();
                    if(fb.length > 0 && ! fb.includes("facebook.com")){
                        alert("Invalid Facebook Link .")
                    }
                })
        
                $('#twitter').mouseleave(function(){
                    let twitter = $(this).val();
                    if(twitter.length > 0 && ! twitter.includes("twitter.com")){
                        alert("Invalid Twitter Link .")
                    }
                })
        
                $('#instagram').mouseleave(function(){
                    let instagram = $(this).val();
                    if(instagram.length > 0 && ! instagram.includes("instagram.com")){
                        alert("Invalid Instagram link .")
                    }
                })
        
                $('#youtube').mouseleave(function(){
                    let youtube = $(this).val();
                    if(youtube.length > 0 && ! youtube.includes("youtube.com")){
                        alert("Invalid youtube link .")
                    }
                })
                
                $('#linkedin').mouseleave(function(){
                    let linkedin = $(this).val();
                    if(linkedin.length > 0 && ! linkedin.includes("linkedin.com")){
                        alert("Invalid linkedin link .")
                    }
                })
                
                
            });
        </script>		

    </body>
</html>
