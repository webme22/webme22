<?php
include("config.php");
if (!loggedin()) {
    header("Location: login.php");
    exit();
}

if (($_SESSION['messages'] != '1')) {
    header("Location: error.php");
    exit();
}
include_once(__DIR__."/../lib/Mailer.php");

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
                
                $content = mysqli_real_escape_string($con, $_POST['content']);
                
                $con->query("insert into messages (`type`, `content`, `viewed`, `date`) values ('2', '$content', '0', '".date('Y-m-d')."') ") or die(mysqli_error($con));
                
                $result = $con->query("select * from `subscriptions` order by id asc");
            
                if(mysqli_num_rows($result) > 0){
                    while($row = mysqli_fetch_assoc($result)){
                        $mailer = new Mailer();
                        $mailer->setVars(['user_name'=>$row['name'], 'content'=>$content]);
                        $mailer->sendMail([$row['email']], "Alhmayel newsletter", 'new_message.html', 'new_message.txt');
                    }
                }
                
                echo get_success($languages[$lang]["sentSuccessfully"]);
                
            }
            ?>	

            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container">

                        <!-- Page-Title -->
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="page-title"> <?php echo $languages[$lang]["viewMessages"];   ?>  </h4>
                                <ol class="breadcrumb">
                                    <li><a href="messages_view.php?lang=<?php echo $lang; ?>&type=2"><?php echo $languages[$lang]["viewMessages"];   ?> </a></li>
                                    <li class="active"><?php echo $languages[$lang]["newMessage"];   ?> </li>
                                </ol>
                            </div>
                        </div>
                        <form id="client_address_add" method="POST" enctype="multipart/form-data" data-parsley-validate novalidate >

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card-box">
                                      
                                        <div class="form-group">
                                            <label for="content">  <?php echo $languages[$lang]["content"];   ?></label>
                                            <textarea class="form-control" name="content"  minlength="3" required=""></textarea>
                                        </div>
                                        <div class="form-group text-right m-b-0">
                                            <button class="btn btn-primary waves-effect waves-light" id="submit" name="submit"> <?php echo $languages[$lang]["add"];   ?> </button>
                                            <button type="reset" class="btn btn-default waves-effect waves-light m-l-5"> <?php echo $languages[$lang]["cancel"];   ?> </button>
                                        </div>
                                    </div>
                                </div>
                            </div>	
                        </form>

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
                $("#item103").addClass("active");
            });
        </script>

    </body>
</html>
