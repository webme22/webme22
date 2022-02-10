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

			if (isset($_POST['submit'])) {

				$id = $_POST['id'];

				$title = $_POST['title'];

				$body = $_POST['body'];

				$flag = $_POST['flag'];

				if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {

					$image_name_update = $_FILES['image']['name'];
					$image_tmp_update = $_FILES['image']['tmp_name'];
					$allowed_ext = array('jpg', 'jpeg', 'gif', 'png');
					$get_image_ext = explode('.', $image_name_update);
					$image_ext = strtolower(end($get_image_ext));
					if($flag == 1){

						$image_path = "../uploads/aboutBoxes/". $id . "/" . $image_name_update . ".jpg";

						$image_database = "{$sit_url}/uploads/aboutBoxes/{$id}/{$image_name_update}.jpg";

						$update = $con->query("UPDATE `aboutBoxes` SET `title`='$title',
`body`='$body', `image`='$image_database' WHERE `id`='$id'");

					} elseif($flag == 2){

						$image_path = "../uploads/aboutPage/{$id}/" .  $image_name_update . ".jpg";

						$image_database = "uploads/aboutPage/{$id}/{$image_name_update}.jpg";

						$update = $con->query("UPDATE `aboutPage` SET 
`body`='$body', `image`='$image_database' WHERE `id`='$id'");

					}

					move_uploaded_file($image_tmp_update, $image_path);



				} else {
					$update = $con->query("UPDATE `aboutPageBoxes` SET `title`='$title',
`body`='$body' WHERE `id`='$id'");

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
                                <h4 class="page-title"><?php echo $languages[$lang]["about"];     ?></h4>
                                <ol class="breadcrumb">
                                    <li><a href="about_view.php?lang=<?php
										echo $lang;
										?>&flag=1"> <?php echo $languages[$lang]["viewAbout"];     ?></a></li>
                                    <li class="active"><?php echo $languages[$lang]["editAbout"];     ?></li>
                                </ol>
                            </div>
                        </div>

                        <div class="updateData"></div>

						<?php
						if ($_GET['id']) {

							$item = $_GET['id'];
							$flag = $_GET['flag'];

							if($flag == 1){
								$sql = "select * from  aboutBoxes where `id`='$item'";
							} elseif($flag == 2){
								$sql = "select * from  aboutPage where `id`='$item'";
							} elseif($flag == 3){
								$sql = "select * from  aboutPageBoxes where `id`='$item'";
							}

							$query_select = $con->query($sql);
							$row = mysqli_fetch_array($query_select);

							if ($query_select) {
								?>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card-box">
                                            <form method="POST" enctype="multipart/form-data" data-parsley-validate novalidate>
                                                <input type="hidden" name="id" id="id" parsley-trigger="change" required value="<?php echo $item; ?>" class="form-control">

                                                <input type="hidden" name="flag" id="flag" parsley-trigger="change" required value="<?php echo $flag; ?>" class="form-control">

												<?php if($flag != 2) { ?>
                                                    <div class="form-group col-md-4">
                                                        <label for="title"><?php echo $languages[$lang]['title'];  ?></label>
                                                        <input type="text" name="title" parsley-trigger="change" required placeholder="<?php echo $languages[$lang]['title'];  ?>" class="form-control" id="title" value="<?php echo $row['title']; ?>">
                                                    </div>
												<?php } ?>

                                                <div class="form-group col-md-6">
                                                    <label for="body"><?php echo $languages[$lang]['body'];  ?> </label>
                                                    <textarea name="body"  required placeholder="<?php echo $languages[$lang]['body'];  ?>" class="form-control" id="body"><?php echo $row['body']; ?></textarea>
                                                </div>

                                                <div class="clearfix"></div>
												<?php if($flag != 3){ ?>
                                                    <div class="form-group m-b-0">
                                                        <!--<label for="about">الصوره  </label>								-->

                                                        <div class="gal-detail thumb getImage">
                                                            <a href="<?=asset($row['image'])?>" class="image-popup" title="<?php echo $row['title']; ?>">
                                                                <img src="<?=asset($row['image'])?>" class="thumb-img" alt="<?php echo $row['title']; ?>">
                                                            </a>
                                                        </div>

                                                        <div class="form-group m-b-0">
                                                            <label class="control-label"><?php echo $languages[$lang]['image'];  ?> </label>
                                                            <input type="file" name="image" id="image_update" class="filestyle" data-buttonname="btn-primary">
                                                        </div>

                                                    </div>
												<?php } ?>
                                                <br>
                                                <div class="form-group text-right m-b-0">
                                                    <button class="btn btn-primary waves-effect waves-light" type="submit" name="submit" id="updateUser"><?php echo $languages[$lang]['edit'];  ?></button>
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
        <!-- Right Sidebar -->
        <div class="side-bar right-bar nicescroll">
			<?php include("include/rightbar.php"); ?>
        </div>
        <!-- /Right-bar -->
    </div>
</div>
<!-- END wrapper -->
<?php include("include/footer.php"); ?>

<script>
    $(document).ready(function () {
        $("#cssmenu ul>li").removeClass("active");
        $("#item55").addClass("active");
    });
</script>

</body>
</html>
