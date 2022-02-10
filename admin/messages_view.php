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
                                <h4 class="page-title"> <?php echo $languages[$lang]["viewMessages"];   ?></h4>
                                <ol class="breadcrumb">
                                    <li><a href="messages_view.php?lang=<?php echo $lang; ?>&type=1"> <?php echo $languages[$lang]["messages"];   ?> </a></li>
                                    <li class="active">  <?php echo $languages[$lang]["messages"];   ?> </li>
                                </ol>
                            </div>
                        </div>
                        <input type="hidden" value="<?php echo $lang;   ?>" id="messageLang">
                        
                        <ul class="nav nav-tabs" style="text-align:center;">
                            
                            <li class="<?php 
                            if($_GET['type'] == 1){
                                echo 'active';
                            }
                            ?>" style="float:none; display:inline-block; zoom:1;" id="item1">
                                <a href="messages_view.php?lang=<?php echo $lang; ?>&type=1">
                                    <?php echo $languages[$lang]['comming']; ?>
                                    <!--data-toggle="tab" -->
                                </a>
                            </li>
                            <li style="float:none; display:inline-block; zoom:1;" class="<?php
                                    
                                    if($_GET['type']== 2){
                                        echo 'active';
                                    }
                            
                            ?>">
                                <a href="messages_view.php?lang=<?php echo $lang; ?>&type=2">
                                    <?php echo $languages[$lang]['outgoing']; ?>
                                </a>
                            </li>
                            
                           
                        </ul>   
                        <div class="panel">
                            <div class="panel-body">
                                <div class="">
                                    <table class="table table-striped" id="datatable-editable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <?php if($_GET['type'] == 1){ ?>
                                                <th><?php  echo $languages[$lang]["client"];    ?></th>
                                                <?php } ?>
                                                <th><?php echo $languages[$lang]["content"];   ?></th>
                                                <?php if($_GET['type'] == 1){ ?>
                                                <th><?php echo $languages[$lang]["MessageStatus"];   ?></th>
                                                <th><?php echo $languages[$lang]["reply"];   ?></th>
                                                <?php } ?> 
                                                <th><?php echo $languages[$lang]["date"];   ?></th>
                                                <th></th>
                                               
                                            </tr>
                                        </thead>
                                        <tbody> <?php echo view_messages($lang, $_GET['type']); ?> </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>			
                </div>
                <?php include("include/footer_text.php"); ?>

            </div>			
                </div>
             <div class="modal fade" id="send_message" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form method="post">
                            

                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="content"><?php echo $languages[$lang]["reply"];   ?></label>
                                    <textarea class="form-control content" rows="3" id="content" name="content"  minlength="3" maxlength="2500" required=""></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $languages[$lang]["close"];   ?></button>
                                <button type="button" name="submit_send_message"  class="btn btn-success submit_send_message"><?php echo $languages[$lang]["send"]; ?></button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

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

            <!-- End Right content here -->


        </div>
        </div>
        <!-- END wrapper -->
        <?php include("include/footer.php"); ?>	

        <script type="text/javascript">
            $(document).ready(function () {
                
                $('.remove-row').on('click', function(e){
                    $('#dialogConfirm').attr('deleted_message', $(this).attr('href'));
                    $('#dialog').modal('show');
                    e.preventDefault();
                })

                $("#dialogConfirm").click(function () {
                    $('#dialog').modal('hide');
                    var message_id = $(this).attr('deleted_message');
                    let lang = $('#messageLang').val();
                    
                    $.ajax({
                        type: "POST",
                        url: "functions/messages_functions.php",
                        data: {
                            message_id: message_id,
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
            
            
                $(".on-default").click(function (e) {
                    var message = $(this).attr('href');
                    let lang = $('#messageLang').val();
                    

                    // alert(category);
                    $("#dialogConfirm").click(function () {
                        // var dataString = 'family=' + family;
                        $.ajax({
                            type: "POST",
                            url: "functions/messages_functions.php",
                            data: {
                                delete_message: message,
                                lang: lang
                            },
                            dataType: 'text',
                            cache: false,
                            success: function (data) {
                                // alert(data);
                                $(".deleteData").html(data);
                                //alert(category);
                            }
                        });
                    });
                    
                    e.preventDefault();
                    
                });
                
                
                $('body').on('click', '.sendmsg', function () {
                    let email = $(this).attr('data-client');
                    let message = $(this).attr('data-id');
                    let lang = $('#messageLang').val();
                    
                    $('#send_message').modal('show');
                    $('body').on('click', '.submit_send_message', function () {
                        let content = $("textarea#content").val();
                        
                        if (content != '') {
                            $.ajax({
                                type: "POST",
                                url: "functions/messages_functions.php",
                                data: {
                                    email: email,
                                    message: message,
                                    content: content,
                                    lang: lang
                                },
                                dataType: 'text',
                                cache: false,
                                success: function (data) {
                                    $(".deleteData").html(data);
                                }
                            });
                            $('#send_message').modal('hide');

                        } else {
                            let required = "Please Enter The Message";
                            if(lang.includes('ar')){
                                required = "من  فضلك ادخل الرساله";
                            }
                            alert(required);
                        }
                    });
                });
                

            $('body').on('change', '.change_cat_status_off', function () {
                var change_cat_status_off = $(this).attr('data-id');
                let lang = $('#messageLang').val();
                // var dataString = 'change_cat_status_off=' + change_cat_status_off;
                swal({
                    title: "<?php echo $languages[$lang]["confirmHidding"];  ?>",
                    text: "",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "<?php echo $languages[$lang]["yes"];  ?>",
                    cancelButtonText: "<?php echo $languages[$lang]["cancel"];  ?>",
                    closeOnConfirm: false,
                    closeOnCancel: true
                }, function (isConfirm) {
                    if (isConfirm) {
                        swal("<?php echo $languages[$lang]["changed"];  ?>", "", "success");
                        // var dataString = 'change_cat_status_off=' + change_cat_status_off;
                        $.ajax({
                            type: "POST",
                            url: "functions/messages_functions.php",
                            data: {
                                change_cat_status_off: change_cat_status_off,
                                lang: lang
                            },
                            dataType: 'text',
                            cache: false,
                            success: function (data) {
                                $(".deleteData").html(data);
                            }
                        });
                    } else {
                        swal("<?php echo $languages[$lang]["changed"];  ?>", "<?php echo $languages[$lang]["changed"];  ?> :)", "error");
                    }
                });
            });
            
            $('body').on('change', '.change_cat_status_on', function () {
                var change_cat_status_on = $(this).attr('data-id');
                let lang = $('#messageLang').val();
                // var dataString = 'change_cat_status_on=' + change_cat_status_on;
                swal({
                    title: "<?php echo $languages[$lang]["changeStatus ?"]    ?>",
                    text: "",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "<?php echo $languages[$lang]["yes"]    ?>",
                    cancelButtonText: "<?php echo $languages[$lang]["cancel"]    ?>",
                    closeOnConfirm: false,
                    closeOnCancel: true
                }, function (isConfirm) {
                    if (isConfirm) {
                        swal("<?php echo $languages[$lang]["changed"];  ?>", "", "success");
                        // var dataString = 'change_cat_status_on=' + change_cat_status_on;
                        $.ajax({
                            type: "POST",
                            url: "functions/messages_functions.php",
                            data: {
                                change_cat_status_on: change_cat_status_on,
                                lang: lang
                            },
                            dataType: 'text',
                            cache: false,
                            success: function (data) {
                                $(".deleteData").html(data);
                            }
                        });
                    } else {
                        swal("<?php echo $languages[$lang]["changed"];  ?>", "<?php echo $languages[$lang]["changed"];  ?> :)", "error");
                    }
                });
            });
            


        </script>
        <script>
            $(document).ready(function () {
                $("#cssmenu ul>li").removeClass("active");
                $("#item103").addClass("active");
            });
        </script>
    </body>
</html>
