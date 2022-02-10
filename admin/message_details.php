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
                                <h4 class="page-title"> <?php echo $languages[$lang]["messageDetails"]; ?></h4>
                                <ol class="breadcrumb">
                                    <li><a href="messages_view.php?lang=<?php echo $lang; ?>&type=1"> <?php echo $languages[$lang]["messages"]; ?></a></li>
                                    <li class="active"><?php echo $languages[$lang]["messages"]; ?> </li>
                                </ol>
                            </div>
                        </div>
                        <?php
                        if ($_GET['messages_id']) {

                            $messageId = $_GET['messages_id'];
                            
                            $query = $con->query("update `messages` set `viewed`='1' where `id`='$messageId'");

                            $query_select = $con->query("SELECT * FROM `messages` WHERE `id` = '{$messageId}' LIMIT 1");
                            $row = mysqli_fetch_array($query_select);

                            $id = $row['id'];
                            $date = $row['date'];
                            $content = $row['content'];
                            $viewed = $row['viewed'];
                            $reply = $row['reply'];
                            $client = $row['client_name'];
                            $email = $row['client_email'];
 

                            if ($query_select) {
                                ?>
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="">
                                            <div class="table-responsive m-t-20">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <th><?php echo $languages[$lang]["client"]; ?></th>
                                                            <td>
                                                                <?php echo $client; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo $languages[$lang]["email"]; ?></th>
                                                            <td>
                                                                <?php 
                                                                
                                                            echo $email;
                                                            
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th> <?php echo $languages[$lang]["content"]; ?></th>
                                                            <td>
                                                                <?php echo $content; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo $languages[$lang]["reply"]; ?></th>
                                                            <td>
                                                                <?php 
                                                                
                                                                
                                                                                        if(! empty($reply)){
                                                
                                                    echo $reply;
                                                    
                                                } else {
                                                    echo "<span style='color: red;'>{$languages[$lang]['NotAnswered']}</span>";            
                                                }
                                                                
                                                                
                                                                
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        

                                                        <tr>
                                                            <th>  <?php echo $languages[$lang]["date"]; ?>  </th>
                                                            <td>
                                                                <?php
                                                                
                                                                    echo $date;
                                                                
                                                                ?>
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
