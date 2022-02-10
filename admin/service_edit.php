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

            if (isset($_POST['service_submit'])) {
                
                // var_dump($_POST); die();
                
                $Gtitle = mysqli_real_escape_string($con, trim($_POST['Gtitle']));
                $Gbody = mysqli_real_escape_string($con, trim($_POST['Gdesc']));
                
                $Ttitle = mysqli_real_escape_string($con, trim($_POST['Ttitle']));
                $Tbody = mysqli_real_escape_string($con, trim($_POST['Tdesc']));
                
                $Mtitle = mysqli_real_escape_string($con, trim($_POST['Mtitle']));
                $Mbody = mysqli_real_escape_string($con, trim($_POST['Mdesc']));
                
                $Dtitle = mysqli_real_escape_string($con, trim($_POST['Dtitle']));
                $Dbody = mysqli_real_escape_string($con, trim($_POST['Ddesc']));
                
                $TvideoName = $_FILES['Timage']['name'];
                $TvideoTmp = $_FILES['Timage']['tmp_name'];
                $Textension = pathinfo($_FILES['Timage']['name'], PATHINFO_EXTENSION);

                
                $GvideoName = $_FILES['Gimage']['name'];
                $GvideoTmp = $_FILES['Gimage']['tmp_name'];
                $Gextension = pathinfo($_FILES['Gimage']['name'], PATHINFO_EXTENSION);

                
                $MvideoName = $_FILES['Mimage']['name'];
                $MvideoTmp = $_FILES['Mimage']['tmp_name'];
                $Mextension = pathinfo($_FILES['Mimage']['name'], PATHINFO_EXTENSION);

                
                $DvideoName = $_FILES['Dimage']['name'];
                $DvideoTmp = $_FILES['Dimage']['tmp_name'];
                $Dextension = pathinfo($_FILES['Dimage']['name'], PATHINFO_EXTENSION);

                $allowedExt = ["mp4", "m4a", "m4v", "f4v", "f4a", "m4b", "m4r", "f4b", "mov"];
                
                if(empty($TvideoName) || empty($GvideoName) || empty($MvideoName) || empty($DvideoName)){
                    
                    echo get_error($languages[$lang]["required"]);
                    
                } elseif(! in_array($Textension, $allowedExt) || !  in_array($Gextension, $allowedExt) || !  in_array($Mextension, $allowedExt) || !  in_array($Dextension, $allowedExt)){
                
                    echo get_error($languages[$lang]["invalidFile"]);
                
                } else {
                
                    if(! file_exists("../uploads/services/")){
                        mkdir("../uploads/services/", 0775, true);
                    }
                
                    $GvideoPath = "../uploads/services/G{$GvideoName}.mp4";
                    $Gvideodb = "uploads/services/G{$GvideoName}";
                    move_uploaded_file($GvideoTmp, $GvideoPath);
                    
                    $TvideoPath = "../uploads/services/T{$TvideoName}.mp4";
                    $Tvideodb = "uploads/services/T{$TvideoName}";
                    move_uploaded_file($TvideoTmp, $TvideoPath);
                    
                    $MvideoPath = "../uploads/services/M{$MvideoName}.mp4";
                    $Mvideodb = "uploads/services/M{$MvideoName}";
                    move_uploaded_file($MvideoTmp, $MvideoPath);
                    
                    $DvideoPath = "../uploads/services/D{$DvideoName}.mp4";
                    $Dvideodb = "uploads/services/D{$DvideoName}";
                    move_uploaded_file($DvideoTmp, $DvideoPath);
    
                    $update = $con->query("UPDATE `services` SET `Gtitle`='$Gtitle',`Gdesc`='$Gbody', `Gmedia`='$Gvideodb', `Ttitle`='$Ttitle',`Tdesc`='$Tbody', `Tmedia`='$Tvideodb', `Mtitle`='$Mtitle',`Mdesc`='$Mbody', `Mmedia`='$Mvideodb', `Dtitle`='$Dtitle',`Ddesc`='$Dbody', `Dmedia`='$Dvideodb' WHERE `id`=1 ") or die(mysqli_error($con));
                
                }
                


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
                                <h4 class="page-title"><?php echo $languages[$lang]["services"]; ?></h4>
                                <ol class="breadcrumb">
                                    <li><a href="service_view.php?lang<?php echo $lang; ?>"><?php echo $languages[$lang]["viewService"]; ?></a></li>
                                    <li class="active"> <?php echo $languages[$lang]["editService"]; ?></li>
                                </ol>
                            </div>
                        </div>

                        <div class="updateData"></div>

                        <?php
                        

                            $query_select = $con->query("SELECT * FROM `services` WHERE `id` = 1 LIMIT 1");
                            $row = mysqli_fetch_array($query_select);

                            
                            if ($query_select) {
                                ?>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card-box"> 									
                                            <form method="POST" enctype="multipart/form-data" data-parsley-validate novalidate>
                                                
                                        <h3 style="text-align: center;"> 
                                        Family Gallery
                                        </h3>
                                                <div class="form-group col-md-4">
                                                    <label for="Gtitle"><?php echo $languages[$lang]["title"]; ?> </label>
                                                    <input type="text" name="Gtitle" id="Gtitle" parsley-trigger="change" required value="<?php echo $row['Gtitle']; ?>" class="form-control">
                                                </div>
                                                
                                                 
                                                
                                                <div class="form-group col-md-8">
                                                    <label for="Gdesc"><?php echo $languages[$lang]["body"]; ?> </label>
                                                    <textarea class="form-control" rows="3" name="Gdesc"  minlength="3" maxlength="1000" required=""><?php echo $row['Gdesc']; ?></textarea>
                                                </div>
                                                
                                                <div class="clearfix"></div>
                                                
                                                <div class="form-group m-b-0">
                                                    <label class="control-label">
                                                    <?php echo $languages[$lang]['video'];  ?>    
                                                    </label>
                                                    <input type="file" name="Gimage" id="Gimage" class="filestyle" data-buttonname="btn-primary">
                                                </div> 
                                                
                                                <br>
                                                
                                        <h3 style="text-align: center;">Family Tree </h3>
                                                
                                                <div class="form-group col-md-4">
                                                    <label for="Ttitle"><?php echo $languages[$lang]["title"]; ?> </label>
                                                    <input type="text" name="Ttitle" id="Ttitle" parsley-trigger="change" required value="<?php echo $row['Ttitle']; ?>" class="form-control">
                                                </div>
                                                
                                                <div class="form-group col-md-8">
                                                    <label for="Tdesc"><?php echo $languages[$lang]["body"]; ?> </label>
                                                    <textarea class="form-control" rows="3" name="Tdesc"  minlength="3" maxlength="1000" required=""><?php echo $row['Tdesc']; ?></textarea>
                                                </div>
                                                
                                                <div class="clearfix"></div>
                                                
                                                <div class="form-group m-b-0">
                                                    <label class="control-label">
                                                    <?php echo $languages[$lang]['video'];  ?>    
                                                    </label>
                                                    <input type="file" name="Timage" id="Timage" class="filestyle" data-buttonname="btn-primary">
                                                </div> 
                                                
                                                <br>
                                                
                                                <h3 style="text-align: center;"> Family Museum</h3>
                                                
                                                <div class="form-group col-md-4">
                                                    <label for="Mtitle"><?php echo $languages[$lang]["title"]; ?> </label>
                                                    <input type="text" name="Mtitle" id="Mtitle" parsley-trigger="change" required value="<?php echo $row['Mtitle']; ?>" class="form-control">
                                                </div>
                                                
                                                <div class="form-group col-md-8">
                                                    <label for="Mdesc"><?php echo $languages[$lang]["body"]; ?> </label>
                                                    <textarea class="form-control" rows="3" name="Mdesc"  minlength="3" maxlength="1000" required=""><?php echo $row['Mdesc']; ?></textarea>
                                                </div>
                                                
                                                <div class="clearfix"></div>
                                                
                                                <div class="form-group m-b-0">
                                                    <label class="control-label">
                                                    <?php echo $languages[$lang]['video'];  ?>    
                                                    </label>
                                                    <input type="file" name="Mimage" id="Mimage" class="filestyle" data-buttonname="btn-primary">
                                                </div> 
                                                
                                                <br>
                                                
                                                <h3 style="text-align: center;"> Family Documents</h3>
                                                
                                                <div class="form-group col-md-4">
                                                    <label for="Dtitle"><?php echo $languages[$lang]["title"]; ?> </label>
                                                    <input type="text" name="Dtitle" id="Dtitle" parsley-trigger="change" required value="<?php echo $row['Dtitle']; ?>" class="form-control">
                                                </div>
                                                
                                                <div class="form-group col-md-8">
                                                    <label for="Ddesc"><?php echo $languages[$lang]["body"]; ?> </label>
                                                    <textarea class="form-control" rows="3" name="Ddesc"  minlength="3" maxlength="1000" required=""><?php echo $row['Ddesc']; ?></textarea>
                                                </div>
                                                
                                                <div class="clearfix"></div>
                                                
                                                <div class="form-group m-b-0">
                                                    <label class="control-label">
                                                    <?php echo $languages[$lang]['video'];  ?>    
                                                    </label>
                                                    <input type="file" name="Dimage" id="Dimage" class="filestyle" data-buttonname="btn-primary">
                                                </div> 
                                                
                                                <br>
                                                
                                                <div class="form-group text-right m-b-0">
                                                    <button class="btn btn-primary waves-effect waves-light" type="submit" name="service_submit" id="updateUser"><?php echo $languages[$lang]["edit"]; ?></button>
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
                $("#item50").addClass("active");
            });
        </script>		

    </body>
</html>
