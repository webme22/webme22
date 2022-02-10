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
                                <h4 class="page-title"> <?php echo $languages[$lang]["services"]; ?></h4>
                                <ol class="breadcrumb">
                                    <li><a href="service_view.php?lang=<?php echo $lang; ?>"> <?php echo $languages[$lang]["viewService"]; ?></a></li>
                                    <li class="active"><?php echo $languages[$lang]["viewService"]; ?> </li>
                                </ol>
                            </div>
                        </div>
                        <?php


                            $query_select = $con->query("SELECT * FROM `services` WHERE `id` = 1");
                            $row = mysqli_fetch_array($query_select);

                            $id = $row_select['id'];


                            if ($query_select) {
                                ?>

                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="">
                                            <div class="table-responsive m-t-20">
                                                <table class="table">
                                                    <tbody>

                                                        <tr>
                                                            <th><?php echo $languages[$lang]["title"]; ?></th>
                                                            <td style="font-size: 20px; font-weight: 300;">
                                                                <?php echo $row['Gtitle']; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo $languages[$lang]["body"]; ?></th>
                                                            <td>
                                                                <?php echo $row['Gdesc']; ?>
                                                            </td>
                                                        </tr>
                                                        
                                                        
                                                        <tr>
                                                            <th><?php echo $languages[$lang]["video"]; ?></th>
                                                            <td>
                                                                                    <video width="320" height="240" controls>
                                                  <source src="<?php echo $row['Gmedia']; ?>" type="video/mp4">
                                                  <!--<source src="movie.ogg" type="video/ogg">-->
                                                  Your browser does not support the video tag.
                                            </video>
                                                            </td>
                                                        </tr>
                                                        <br>
                                                        <tr>
                                                            <th><?php echo $languages[$lang]["title"]; ?></th>
                                                            <td style="font-size: 20px; font-weight: 300;">
                                                                <?php echo $row['Ttitle']; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo $languages[$lang]["body"]; ?></th>
                                                            <td>
                                                                <?php echo $row['Tdesc']; ?>
                                                            </td>
                                                        </tr>
                                                        
                                                        
                                                        <tr>
                                                            <th><?php echo $languages[$lang]["video"]; ?></th>
                                                            <td>
                                                                                    <video width="320" height="240" controls>
                                                  <source src="<?php echo $row['Tmedia']; ?>" type="video/mp4">
                                                  <!--<source src="movie.ogg" type="video/ogg">-->
                                                  Your browser does not support the video tag.
                                            </video>
                                                            </td>
                                                        </tr>
                                                        
                                                        
                                                        <tr>
                                                            <th><?php echo $languages[$lang]["title"]; ?></th>
                                                            <td style="font-size: 20px; font-weight: 300;">
                                                                <?php echo $row['Mtitle']; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo $languages[$lang]["body"]; ?></th>
                                                            <td>
                                                                <?php echo $row['Mdesc']; ?>
                                                            </td>
                                                        </tr>
                                                        
                                                        
                                                        <tr>
                                                            <th><?php echo $languages[$lang]["video"]; ?></th>
                                                            <td>
                                                                                    <video width="320" height="240" controls>
                                                  <source src="<?php echo $row['Mmedia']; ?>" type="video/mp4">
                                                  <!--<source src="movie.ogg" type="video/ogg">-->
                                                  Your browser does not support the video tag.
                                            </video>
                                                            </td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <th><?php echo $languages[$lang]["title"]; ?></th>
                                                            <td style="font-size: 20px; font-weight: 300;">
                                                                <?php echo $row['Dtitle']; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo $languages[$lang]["body"]; ?></th>
                                                            <td>
                                                                <?php echo $row['Ddesc']; ?>
                                                            </td>
                                                        </tr>
                                                        
                                                        
                                                        <tr>
                                                            <th><?php echo $languages[$lang]["video"]; ?></th>
                                                            <td>
                                                                                    <video width="320" height="240" controls>
                                                  <source src="<?php echo $row['Dmedia']; ?>" type="video/mp4">
                                                  <!--<source src="movie.ogg" type="video/ogg">-->
                                                  Your browser does not support the video tag.
                                            </video>
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

        ?>
                    </div>
                </div>

            </div>
        <?php include("include/footer_text.php"); ?>

            </div>

    <!-- End Right content here -->

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
        $("#item50").addClass("active");
        
        // $('body').on('click', '#media', function(e){
        //     $('table.table').hide();
        //     let url = $(location).attr('href');
        //     // alert(url);
        //     url = new URL(url);
        //     url.searchParams.set("family", 2);
        //     location.href = url.href;
            
        //     e.preventDefault();
        // })
    });
</script>

</body>
</html>
