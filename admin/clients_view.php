<?php
include("config.php");
if (!loggedin()) {
    header("Location: login.php");
    exit();
}
if (($_SESSION['role'] != 'admin' || $_SESSION['clients'] != 1)) {
    header("Location: error.php");
    exit();

}
?>

<!DOCTYPE html>
<html>
    <style>
        .pagination {
        display: inline-block;
    }

    .pagination a {
        color: black;
        float: left;
        padding: 8px 16px;
        text-decoration: none;
    }

    .pagination a.active {
        background-color: #4CAF50;
        color: white;
    }

    .pagination a:hover:not(.active) {background-color: #ddd;}
    .info-box {
        -webkit-text-size-adjust: 100%;
        -webkit-tap-highlight-color: rgba(0,0,0,0);
        font-weight: 400;
        font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
        font-size: 14px;
        line-height: 1.42857143;
        color: #333;
        box-sizing: border-box;
        display: block;
        min-height: 90px;
        background: #fff;
        width: 100%;
        box-shadow: 0 1px 1px rgba(0,0,0,0.1);
        border-radius: 2px;
        margin-bottom: 15px;
    }
    </style>
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
            <input type="hidden" id="pageLang" value="<?= $lang ?>">
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container">

                        <!-- Page-Title -->
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="page-title"><?php echo $languages[$lang]["viewClients"]; ?></h4>
                                <ol class="breadcrumb">
                                    <li><a href="users_view.php?lang=<?php echo $lang; ?>"><?php echo $languages[$lang]["clients"]; ?></a></li>
                                    <li class="active"><?php echo $languages[$lang]["viewClients"]; ?></li>
                                </ol>
                            </div>
                        </div>
                        <?php
                            $per_page_record = 20;
                            $page = (isset($_GET['page']))? $_GET['page'] : 1;
                            $start = ($page-1) * $per_page_record;

                        ?>
                        <div class="panel">
                            <div class="panel-body">
                                <div class="">
                                    <table class="table table-striped" id="">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th><?php echo $languages[$lang]["name"]; ?></th>
                                                <th><?php echo $languages[$lang]["family"]; ?></th>
                                                <th><?php echo $languages[$lang]["username"]; ?></th>
                                                <th><?php echo $languages[$lang]["email"]; ?></th>
                                                <th><?php echo $languages[$lang]["phone"]; ?></th>
                                                <th><?php echo $languages[$lang]["image"]; ?></th>
                                                
                                                <th><?php echo $languages[$lang]["date"]; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody> <?php echo view_users(0, $lang, $start); ?> </tbody>
                                    </table>
                                </div>
                                <div class="pagination">
									<?php
									$total_pages = ceil(count_clients() / $per_page_record);
									$pagLink = "";

									if($page>=2){
										echo "<a href='clients_view.php?page=".($page-1)."'>Prev</a>";
									}

									for ($i=1; $i<=$total_pages; $i++) {
										if ($i == $page) {
											$pagLink .= "<a class = 'active' href='clients_view.php?page="
													.$i."'>".$i." </a>";
										}
                                        elseif($i < $page+4)  {
											$pagLink .= "<a href='clients_view.php?page=".$i."'>   
                                                                                     ".$i." </a>";
										} elseif($i == $total_pages-1)  {
											$pagLink .= "<a>...</a>";
											$pagLink .= "<a href='clients_view.php?page=".$i."'>  
                                                                                     ".$i." </a>";
										} elseif($i == $total_pages)  {
											$pagLink .= "<a href='clients_view.php?page=".$i."'>  
                                                                                     ".$i." </a>";
										}
									};
									echo $pagLink;

									if($page<$total_pages){
										echo "<a href='clients_view.php?page=".($page+1)."'>Next</a>";
									}
									?>

                                </div>
                            </div>
                        </div>

                    </div>			
                </div>
                <?php include("include/footer_text.php"); ?>

            </div>			
            </div>
            <!-- MODAL -->
            <div id="dialog" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="panel-title"><?php echo $languages[$lang]["sure ?"]; ?></h4>
                    </div>
                    
                    <div class="modal-footer">
                        <button id="dialogConfirm" class="btn btn-primary waves-effect waves-light"><?php   echo $languages[$lang]["confirm"];  ?></button>
                        <button id="dialogCancel" class="btn btn-default waves-effect" data-dismiss="modal"><?php   echo $languages[$lang]["cancel"];  ?></button>
                    </div>
                    </div>

                </div>
            </div>
            <!-- end Modal -->

            <!-- End Right content here -->


        </div>
        </div>
        <!-- END wrapper -->
        <?php include("include/footer.php"); ?>	

        <script type="text/javascript">
            $(document).ready(function () {

                $('.remove-row').on('click', function(e){
                    $('#dialogConfirm').attr('deleted_user', $(this).attr('href'));
                    $('#dialog').modal('show');
                    e.preventDefault();
                })
                $("#dialogConfirm").click(function () {
                    $('#dialog').modal('hide');
                    var user_id = $(this).attr('deleted_user');
                    let lang = $('#pageLang').val();
                    
                    $.ajax({
                        type: "POST",
                        url: "functions/users_functions.php",
                        data: {
                            user_id: user_id,
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
                $("#item7").addClass("active");
            });
        </script>
    </body>
</html>
