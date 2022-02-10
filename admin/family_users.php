<?php
include("config.php");
if (!loggedin()) {
    header("Location: login.php");
    exit();
}
if (($_SESSION['role'] != 'admin' || $_SESSION['families'] != 1)) {
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

            <!-- Left Sidebar Start -->
            <?php include("include/leftsidebar.php"); ?>
            <!-- Left Sidebar End -->

            <!-- Start right Content here -->

            <div class="deleteData"></div>

            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container">

                        <!-- Page-Title -->
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="page-title"><?php echo $languages[$lang]["familyUsers"]; ?></h4>
                                <ol class="breadcrumb">
                                    <li><a href="families_view.php?lang=<?php echo $lang; ?>"><?php echo $languages[$lang]["families"]; ?></a></li>
                                    <li class="active"><?php echo $languages[$lang]["families"]; ?></li>
                                </ol>
                            </div>
                        </div>

                        <div class="panel">
                            <div class="panel-body">
                                <div class="">
                                    <table class="table table-striped" id="datatable-editable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th><?php echo $languages[$lang]["name"]; ?></th>
                                                <th><?php echo $languages[$lang]["parent"]; ?></th>
                                                <th><?php echo $languages[$lang]["family"]; ?></th>
                                                <th><?php echo $languages[$lang]["username"]; ?></th>
                                                <th><?php echo $languages[$lang]["email"]; ?></th>
                                                <th><?php echo $languages[$lang]["phone"]; ?></th>
                                                <th><?php echo $languages[$lang]["image"]; ?></th>
                                                
                                                <th><?php echo $languages[$lang]["date"]; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody> <?php 
                                        
                                        $familyId = $_GET['familyId'];
                                        
                                        echo family_users($familyId, $lang); ?> </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>			
                </div>
                <?php include("include/footer_text.php"); ?>

            </div>			

            <!-- MODAL -->
            <div id="dialog" class="modal-block mfp-hide">
                <section class="panel panel-info panel-color">
                    <header class="panel-heading">
                        <h2 class="panel-title"><?php echo $languages[$lang]["sure ?"]; ?></h2>
                    </header>
                    <div class="panel-body">
                        <div class="modal-wrapper">
                            <div class="modal-text">
                                <p><?php   echo $languages[$lang]["delete ?"];  ?></p>
                            </div>
                        </div>
                        <div class="row m-t-20">
                            <div class="col-md-12 text-right">
                                <button id="dialogConfirm" class="btn btn-primary waves-effect waves-light"><?php   echo $languages[$lang]["confirm"];  ?></button>
                                <button id="dialogCancel" class="btn btn-default waves-effect"><?php   echo $languages[$lang]["cancel"];  ?></button>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <!-- end Modal -->

            <!-- End Right content here -->


        </div>
        <!-- END wrapper -->
        <?php include("include/footer.php"); ?>	

        <script type="text/javascript">
            $(document).ready(function () {
                // $(".editItem").click(function (event) {
                //     event.preventDefault();
                //     var region_id = $(this).attr('href');
                //     var dataString = 'region_id=' + region_id;
                //     $.ajax({
                //         type: "POST",
                //         url: "functions/region_functions.php",
                //         data: dataString,
                //         dataType: 'text',
                //         cache: false,
                //         success: function (data) {
                //             $(".deleteData").html(data);
                //             //alert(category);
                //         }
                //     });
                // });
                $(".on-default").click(function () {
                    var user_id = $(this).attr('href');
                    // alert(category);
                    $("#dialogConfirm").click(function () {
                        var dataString = 'user_id=' + user_id;
                        $.ajax({
                            type: "POST",
                            url: "functions/users_functions.php",
                            data: dataString,
                            dataType: 'text',
                            cache: false,
                            success: function (data) {
                                $(".deleteData").html(data);
                                //alert(category);
                            }
                        });
                    });
                });
            });
        </script>
        <script>
            $(document).ready(function () {
                $("#cssmenu ul>li").removeClass("active");
                $("#item31").addClass("active");
            });
        </script>
    </body>
</html>
