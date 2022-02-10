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

if(isset($_POST['submit'])){
    $file = $_FILES['upload_emails']['tmp_name'];
    $group = (int) $_POST['group_id'];
    if (($handle = fopen($file, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            for ($c=0; $c < count($data); $c++) {
                $con->query("INSERT INTO `group_emails` ('group_id', 'email', 'date') VALUES ('$group', '" . $data[$c] . "', '".date('Y-m-d H:i:s')."')") or die(mysqli_error($con));
            }
        }
        fclose($handle);
        echo get_success($languages[$lang]["addMessage"]);
    }
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

            <div class="deleteData"></div>

            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container">

                        <!-- Page-Title -->
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="page-title"> <?php echo $languages[$lang]["groups_emails"];   ?></h4>
                                <ol class="breadcrumb">
                                    <li><a href="groups_view.php?lang=<?php echo $lang; ?>"> <?php echo $languages[$lang]["groups"];   ?> </a></li>
                                    <li class="active">  <?php echo $languages[$lang]["groups"];   ?> </li>
                                </ol>
                            </div>
                        </div>
                        <input type="hidden" value="<?php echo $lang;   ?>" id="groupLang">
                        <?php if(isset($_GET['groupId'])){ ?>
                        
                        <div class="panel">
                            <div class="panel-body">
                                <div class="d-flex flex-row">
                                    <form method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="group_id" value="<?= $_GET['groupId'] ?>">
                                        <div class="">
                                            <input type="file" name="upload_emails" class="">
                                        </div>
                                        <div class="">
                                            <button type="submit" name="submit" class="btn btn-primary"><?= $languages[$lang]["add"] ?></button>
                                        </div>
                                    </form>
                                </div>
                                <div class="">
                                    <table class="table table-striped" id="datatable-editable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th><?php  echo $languages[$lang]["email"];    ?></th>
                                                <th><?php echo $languages[$lang]["date"];   ?></th>
                                                <th></th>
                                               
                                            </tr>
                                        </thead>
                                        <tbody> <?php echo view_groups_emails($_GET); ?> </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>			
                </div>
                <?php include("include/footer_text.php"); ?>

            </div>			
                </div>
            <!-- MODAL -->
            <div id="dialog" class="modal-block mfp-hide">
                <section class="panel panel-info panel-color">
                    <header class="panel-heading">
                        <h2 class="panel-title"><?php echo $languages[$lang]["sure ?"];   ?></h2>
                    </header>
                    <div class="panel-body">
                        <div class="modal-wrapper">
                            <div class="modal-text">
                                <p><?php echo $languages[$lang]["delete ?"];   ?></p>
                            </div>
                        </div>
                        <div class="row m-t-20">
                            <div class="col-md-12 text-right">
                                <button id="dialogConfirm" class="btn btn-primary waves-effect waves-light"><?php echo $languages[$lang]["confirm"];   ?></button>
                                <button id="dialogCancel" class="btn btn-default waves-effect"><?php echo $languages[$lang]["cancel"];   ?></button>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <!-- end Modal -->

            <!-- End Right content here -->


        </div>
        </div>
        <!-- END wrapper -->
        <?php include("include/footer.php"); ?>	

        <script type="text/javascript">
            $(document).ready(function () {
                
               $('.buttonText').text('<?= $languages[$lang]["choose_file"] ?>');
                
            });
            
            
             $(".on-default").click(function () {
                    var email = $(this).attr('href');
                    let lang = $('#groupLang').val();
                    // alert(category);
                    $("#dialogConfirm").click(function () {
                        // var dataString = 'family=' + family;
                        $.ajax({
                            type: "POST",
                            url: "functions/groups_functions.php",
                            data: {
                                delete_group_email: email,
                                lang: lang
                            },
                            dataType: 'text',
                            cache: false,
                            success: function (data) {
                                $(".deleteData").html(data);
                                //alert(category);
                            }
                        });
                    });
                });

        </script>
        <script>
            $(document).ready(function () {
                $("#cssmenu ul>li").removeClass("active");
                $("#item105").addClass("active");
            });
        </script>
    </body>
</html>
