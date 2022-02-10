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

				$countryId = $_POST['country_id'];
				$title_ar = mysqli_real_escape_string($con, trim($_POST['name_ar']));
				$title_en = mysqli_real_escape_string($con, trim($_POST['name_en']));
				$key = mysqli_real_escape_string($con, trim($_POST['key']));
				$country_code = mysqli_real_escape_string($con, trim($_POST['country_code']));
				if (empty($title_ar) || empty($title_en)) {
					$errors = $languages[$lang]["required"];
				}
				else if (! is_numeric($key)){
					$errors = $languages[$lang]["key_numeric"];
				}
				if (strlen($errors) > 4) {
					echo get_error($errors);
				} else {
					$result = $con->query("select * from countries where `id`='$countryId'");
					$row = mysqli_fetch_array($result);
					if(isset($_FILES['image']['name']) && ! empty($_FILES['image']['name'])){
						$imageName = $_FILES['image']['name'];
						$imageTmp = $_FILES['image']['tmp_name'];
						if (!file_exists("../uploads/countries/" . $countryId)) {
							mkdir("../uploads/countries/" . $countryId, 0777, true);
						}
						$image_path = "../uploads/countries/" . $countryId . "/";
						$target_path = $image_path . round(microtime(true)) . '.' . "jpg";

						$image_database = "uploads/countries/" . $countryId . "/" . round(microtime(true)) . '.' . "jpg";
						$oldFile = __DIR__."/../".$row['image'];
						if(file_exists($oldFile)){
							unlink($oldFile);
						}
						$con->query("update countries set `name_ar`='$title_ar', `name_en`='$title_en', `image`='$image_database', `countryKey`='$key', `country_code`='$country_code' where `id`='$countryId'") or die(mysqli_error($con));
						move_uploaded_file($imageTmp, $target_path);
					} else {
						$con->query("update countries set `name_ar`='$title_ar', `name_en`='$title_en', `countryKey`='$key' where `id`='$countryId'") or die(mysqli_error($con));
					}
					echo get_success($languages[$lang]["updateMessage"]);
					// echo "<meta http-equiv='refresh' content='4; URL=country_edit.php?countryId={$countryId}&lang={$lang}'>";
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
                                    <li class="active"><?php echo $languages[$lang]["editCountry"];     ?></li>
                                </ol>
                            </div>
                        </div>
						<?php
						if(isset($_GET["countryId"])){
							$id = $_GET["countryId"];
							$result = $con->query("select * from countries where `id`='$id'");
							$row = mysqli_fetch_array($result);
						}
						?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-box">
                                    <form method="POST" enctype="multipart/form-data" data-parsley-validate novalidate>

                                        <input type="hidden" name="country_id" value="<?php echo $row['id']; ?>">

                                        <div class="form-group col-md-3">
                                            <label for="name_ar"><?php echo $languages[$lang]['name_ar'];  ?></label>
                                            <input type="text" name="name_ar"  required placeholder="<?php echo $languages[$lang]['name_ar'];  ?>" class="form-control" value="<?php echo $row['name_ar']; ?>">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="name_en"><?php echo $languages[$lang]['name_en'];  ?> </label>
                                            <input type="text" name="name_en" required placeholder="<?php echo $languages[$lang]['name_en'];  ?> " class="form-control" value="<?php echo $row['name_en']; ?>" class="form-control">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="key"><?php echo $languages[$lang]['key'];  ?> </label>
                                            <input type="number" step="1" name="key" required placeholder="<?php echo $languages[$lang]['key'];  ?> " value="<?php echo $row['countryKey']; ?>" class="form-control">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="country_code"><?php echo $languages[$lang]['countryCode'];  ?> </label>
                                            <input type="text" name="country_code" parsley-trigger="change" required placeholder="<?php echo $languages[$lang]['countryCode'];  ?> " value="<?php echo $row['country_code']; ?>" class="form-control" id="country_code">
                                        </div>

                                        <div class="clearfix"></div>

                                        <div class="gal-detail thumb getImage">
                                            <a href="<?=asset($row['image'])?>" class="image-popup" title="<?php echo $name_.$lang; ?>">
                                                <img src="<?=asset($row['image'])?>" class="thumb-img" alt="<?php echo $name_.$lang;  ?>">
                                            </a>
                                        </div>

                                        <div class="form-group m-b-0">
                                            <label class="control-label"> <?php echo $languages[$lang]['image'];  ?></label>
                                            <input type="file" name="image" id="image" class="filestyle" data-buttonname="btn-primary">
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
                    </div>
                </div>
				<?php include("include/footer_text.php"); ?>

            </div>

            <!-- End Right content here -->
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
