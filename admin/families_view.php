<?php
include("config.php");
if (!loggedin()) {
    header("Location: login.php");
    exit();
}
if (($_SESSION['role'] != 'admin' || $_SESSION['families'] != '1')) {
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

            <!-- Start right Content here -->
<div class="col-xs-12 col-lg-10">

            <div class="deleteData"></div>

            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container">

                        <!-- Page-Title -->
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="page-title"> <?php echo $languages[$lang]["families"];   ?></h4>
                                <ol class="breadcrumb">
                                    <li><a href="families_view.php?lang=<?php echo $lang; ?>"> <?php echo $languages[$lang]["families"];   ?> </a></li>
                                    <li class="active">  <?php echo $languages[$lang]["families"];   ?> </li>
                                </ol>
                            </div>
                        </div>
                        <input type="hidden" value="<?php echo $lang;   ?>" id="familyLang">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="">
                                    <table class="table table-striped" id="datatable-editable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th><?php  echo $languages[$lang]["name"];    ?></th>
                                                <th><?php  echo $languages[$lang]["sheikh"];   ?></th>
                                                <th><?php  echo $languages[$lang]["country"];   ?></th>
                                                <th><?php  echo $languages[$lang]["plan"];   ?></th>
                                                <th><?php echo $languages[$lang]["expire_date"];   ?></th>                                                
                                                <th><?php  echo $languages[$lang]["mostpopular"];   ?></th>
                                                <th><?php  echo $languages[$lang]["viewStatus"];   ?></th>
                                                <th><?php echo $languages[$lang]["status"];   ?></th>
                                                <th><?php echo $languages[$lang]["date"];   ?></th>
                                                <th></th>
                                               
                                            </tr>
                                        </thead>
                                        <tbody> <?php echo view_families($lang); ?> </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

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
        <!-- END wrapper -->
        <?php include("include/footer.php"); ?>	

        <script type="text/javascript">
            $(document).ready(function () {
                
               
                
            });
            
            
             $(".on-default").click(function () {
                    var family = $(this).attr('href');
                    let lang = $('#familyLang').val();
                    // alert(category);
                    $("#dialogConfirm").click(function () {
                        // var dataString = 'family=' + family;
                        $.ajax({
                            type: "POST",
                            url: "functions/family_functions.php",
                            data: {
                                family: family,
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
                

            $('body').on('change', '.change_cat_status_off', function () {
                var change_cat_status_off = $(this).attr('data-id');
                let lang = $('#familyLang').val();
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
                            url: "functions/family_functions.php",
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
                let lang = $('#familyLang').val();
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
                            url: "functions/family_functions.php",
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
            
            
            
            $('body').on('change', '.change_cat_mostpopular_off', function () {
                var change_cat_mostpopular_off = $(this).attr('data-id');
                let lang = $('#familyLang').val();
                // var dataString = 'change_cat_mostpopular_off=' + change_cat_mostpopular_off;
                swal({
                    title: "<?php echo $languages[$lang]["changeStatus ?"];  ?>",
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
                        var dataString = 'change_cat_mostpopular_off=' + change_cat_mostpopular_off;
                        $.ajax({
                            type: "POST",
                            url: "functions/family_functions.php",
                            data: {
                                change_cat_mostpopular_off: change_cat_mostpopular_off,
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
            
            $('body').on('change', '.change_cat_mostpopular_on', function () {
                var change_cat_mostpopular_on = $(this).attr('data-id');
                let lang = $('#familyLang').val();
                // var dataString = 'change_cat_mostpopular_on=' + change_cat_mostpopular_on;
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
                        var dataString = 'change_cat_mostpopular_on=' + change_cat_mostpopular_on;
                        $.ajax({
                            type: "POST",
                            url: "functions/family_functions.php",
                            data: {
                                change_cat_mostpopular_on: change_cat_mostpopular_on,
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
            
            $('body').on('change', '.change_cat_view_off', function () {
                var change_cat_view_off = $(this).attr('data-id');
                let lang = $('#familyLang').val();
                // var dataString = 'change_cat_view_off=' + change_cat_view_off;
                swal({
                    title: "<?php echo $languages[$lang]["changeStatus ?"];  ?>",
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
                        // var dataString = 'change_cat_view_off=' + change_cat_view_off;
                        $.ajax({
                            type: "POST",
                            url: "functions/family_functions.php",
                            data: {
                                change_cat_view_off: change_cat_view_off,
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
            
            $('body').on('change', '.change_cat_view_on', function () {
                var change_cat_view_on = $(this).attr('data-id');
                let lang = $('#familyLang').val();
                // var dataString = 'change_cat_view_on=' + change_cat_view_on;
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
                        var dataString = 'change_cat_view_on=' + change_cat_view_on;
                        $.ajax({
                            type: "POST",
                            url: "functions/family_functions.php",
                            data: {
                                change_cat_view_on: change_cat_view_on,
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
            
            
            $('body').on('change', '#plan', function(){
                let plan = $(this).val();
                let lang = $('#familyLang').val();
                let familyId = $(this).attr("family");
                           
                $.ajax({
                    type: "post",
                    url: "functions/family_functions.php",
                    data: {
                        plan: plan,
                        lang: lang,
                        familyId: familyId
                    },
                    dataType: 'text',
                    cache: false
                }).done(function(data){
                    // console.log(res);
                    $(".deleteData").html(data);
                    // location.reload();
                })
            })

        </script>
        <script>
            $(document).ready(function () {
                $("#cssmenu ul>li").removeClass("active");
                $("#item31").addClass("active");
            });
        </script>
    </body>
</html>
