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
    <style>.custom-label{
            margin-top: 11px!important;

        }</style>
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

            <div class="deleteData"></div>

            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container">

                        <!-- Page-Title -->
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="page-title"> <?php echo $languages[$lang]["viewParent"]; ?></h4>
                                <ol class="breadcrumb">
                                    <li><a href="clients_view.php?lang=<?php echo $lang; ?>"> <?php echo $languages[$lang]["clients"]; ?></a></li>
                                    <li class="active"><?php echo $languages[$lang]["clients"]; ?> </li>
                                </ol>
                            </div>
                        </div>
                        <?php
                        if ($_GET['userID']) {

                            $userId = $_GET['userID'];

                            $query_select = $con->query("SELECT * FROM `users` WHERE `user_id` = '{$userId}' LIMIT 1");
                            $row_select = mysqli_fetch_array($query_select);

                            $userId = $row_select['user_id'];
                            $name= $row_select['name'];
                            $userName = $row_select['user_name'];
                            $phone = $row_select['phone'];
                            $email = $row_select['email'];
                            $image = $row_select['image'];
                            $cover = $row_select['cover'];
                            $country_id = $row_select['country_id'];
                            $familyId = $row_select['family_id'];
                            $nationality_id = $row_select['nationality'];
                            // $get_cat_id = $row_select['cat_id'];
                            // $get_cat_name = cat_name_ar($get_cat_id);

                            $sub_cats_image = $row_select['image'];

                            if ($query_select) {
                                ?>
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="">
                                            <div class="table-responsive m-t-20">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <th><?php echo $languages[$lang]["name"]; ?></th>
                                                            <td>
                                                                <?php echo $name; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo $languages[$lang]["family"]; ?></th>
                                                            <td>
                                                                <?php


                                                                                        if($familyId != 0){
                                                    $family = familyName($familyId);
                                                    echo "<a href='family_details.php?familyId={$familyId}'>{$family}</a>";
                                                } else {
                                                    echo "<span style='color: red;'>{$languages[$lang]['notFound']}</span>";
                                                }



                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th> <?php echo $languages[$lang]["username"]; ?></th>
                                                            <td>
                                                                <?php echo $userName; ?>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <th> <?php echo $languages[$lang]["phone"]; ?> </th>
                                                            <td>
                                                                <?php echo $phone;


                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th> <?php echo $languages[$lang]["email"]; ?> </th>
                                                            <td>
                                                                <?php echo $email;

                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo $languages[$lang]["country"]; ?></th>
                                                            <td><?= countryName($country_id, $lang) ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo $languages[$lang]["nationality"]; ?></th>
                                                            <td><?= get_nationality($nationality_id)['name'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>  <?php echo $languages[$lang]["image"]; ?>  </th>
                                                            <td>
                                                                <div class="thumb">
                                                            <img src="<?=asset($image)?>" style="height: 200px;width: 200px;margin-left: 10px;">
                                                        </div>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                </table>

                                            </div>


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
        <?php include("include/footer_text.php"); ?>
                </div>

    </div>			
    </div>


    <!-- End Right content here -->
</div>
<!-- END wrapper -->
<?php include("include/footer.php"); ?>	

<script>
    $(document).ready(function () {
        $("#cssmenu ul>li").removeClass("active");
        $("#item7").addClass("active");
    });
</script>

</body>
</html>
