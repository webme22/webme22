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
                                <h4 class="page-title"> <?php echo $languages[$lang]["about"];   ?></h4>
                                <ol class="breadcrumb">
                                    <li><a href="about_view.php?flag=1&lang=<?php echo $lang; ?>"> <?php echo $languages[$lang]["about"];   ?> </a></li>
                                    <li class="active">  <?php echo $languages[$lang]["about"];   ?> </li>
                                </ol>
                            </div>
                        </div>
                        <input type="hidden" value="<?php echo $lang;   ?>" id="aboutLang">
                        <input type="hidden" value="<?php echo $_GET['flag'];   ?>" id="flag">
                        <ul class="nav nav-tabs" style="text-align:center;">
                            
                                    <li class="<?php 
                                    if($_GET['flag'] == 1 || ! isset($_GET['flag'])){
                                        echo 'active';
                                    }
                                    ?>" style="float:none; display:inline-block; zoom:1;" id="item1">
                                        <a href="about_view.php?flag=1&lang=<?php echo $lang; ?>" id="aboutHome">
                                            <?php echo $languages[$lang]["aboutHome"]; ?>
                                            
                                        </a>
                                    </li>
                                    <li style="float:none; display:inline-block; zoom:1;" class="<?php
                                            
                                            if($_GET['flag']== 2){
                                                echo 'active';
                                            }
                                    
                                    ?>">
                                        <a href="about_view.php?flag=2&lang=<?php echo $lang; ?>" id="upperAbout">
                                            <?php echo $languages[$lang]["upperAbout"]; ?>
                                        </a>
                                    </li>
                                    
                                    <li style="float:none; display:inline-block; zoom:1;" class="<?php
                                            
                                            if($_GET['flag']== 3){
                                                echo 'active';
                                            }
                                    
                                    ?>">
                                        <a href="about_view.php?flag=3&lang=<?php echo $lang; ?>" id="lowerAbout">
                                            <?php echo $languages[$lang]["lowerAbout"]; ?>
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
                                                <?php if($_GET['flag'] == 1 || $_GET['flag'] == 3){ ?>
                                                <th><?php  echo $languages[$lang]["title"];    ?></th>
                                                <?php } ?>
                                                <th style="width: 300px; " colspan="2"><?php echo $languages[$lang]["body"];   ?></th>
                                                <?php if($_GET['flag'] == 1 || $_GET['flag'] == 2){ ?>
                                                <th><?php  echo $languages[$lang]["image"];   ?></th>
                                                <?php } ?>
                                                
                                                <th><?php echo $languages[$lang]["status"];   ?></th>
                                                <th><?php echo $languages[$lang]["date"];   ?></th>
                                                <th></th>
                                               
                                            </tr>
                                        </thead>
                                        <tbody> <?php 
                                        $flag = $_GET['flag'];
                                        echo view_about($lang, $flag); ?> </tbody>
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
            
            <div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><?php echo $languages[$lang]['editAbout']; ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form method="post" enctype="multipart/form-data">
                      <div class="form-group">
                        <label for="title" class="col-form-label"><?php echo $languages[$lang]["title"]; ?></label>
                        <input type="text" class="form-control" id="aboutTitle" name="aboutTitle">
                      </div>
                      <div class="form-group">
                        <label for="body" class="col-form-label"><?php echo $languages[$lang]["body"]; ?></label>
                        <textarea class="form-control" id="aboutBody" name="aboutBody"></textarea>
                      </div>
                      <div class="form-group">
                        <label for="image" class="col-form-label"><?php echo $languages[$lang]["image"]; ?></label>
                        <input type="file" class="form-control" id="aboutImage" name="aboutImage">
                      </div>
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" name="firstSubmit"><?php echo $languages[$lang]["edit"]; ?></button>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="modal fade" id="modal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><?php echo $languages[$lang]['editAbout']; ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form method="post" enctype="multipart/form-data">
                      <div class="form-group">
                        <label for="body" class="col-form-label"><?php echo $languages[$lang]["body"]; ?></label>
                        <textarea class="form-control" id="aboutSecBody" name="aboutSecBody"></textarea>
                      </div>
                      <div class="form-group">
                        <label for="image" class="col-form-label"><?php echo $languages[$lang]["image"]; ?></label>
                        <input type="file" class="form-control" id="aboutSecImage" name="aboutSecImage">
                      </div>
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" name="secondSubmit"><?php echo $languages[$lang]["edit"]; ?></button>
                  </div>
                </div>
                </div>
            </div>
                        
            
            <div class="modal fade" id="modal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><?php echo $languages[$lang]['editAbout']; ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form method="post">
                      <div class="form-group">
                        <label for="title" class="col-form-label"><?php echo $languages[$lang]["title"]; ?></label>
                        <input type="text" class="form-control" id="aboutThTitle" name="aboutThTitle">
                      </div>
                      <div class="form-group">
                        <label for="body" class="col-form-label"><?php echo $languages[$lang]["body"]; ?></label>
                        <textarea class="form-control" id="aboutThBody" name="aboutThBody"></textarea>
                      </div>
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" name="ThirdSubmit"><?php echo $languages[$lang]["edit"]; ?></button>
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
                
               
                
            });
            
                // $(".editParent").click(function (e) {
                //     let item = $(this).attr('value');
                //     let lang = $('#aboutLang').val();
                //     let flag = $(this).data("id");
                    
                //     // $('#modal1').modal('show');
                //     // alert(item);
                //     $.ajax({
                //         type: "POST",
                //         url: "functions/about_functions.php",
                //         data: {
                                    
                //             item: item,
                //             lang: lang,
                //             flag: flag
                            
                //         },
                //         dataType: 'Json',
                //         cache: true,
                //         async: true,
                //         success: function (res) {
                //             console.log(res);
                //             if(flag == 1){
                
                //                 $("#aboutTitle").val(res.title);
                //                 $("#aboutBody").val(res.body);
                //                 $("aboutImage").val(res.image);
                                
                //                 $('#modal1').modal('show');
                                
                //                 // $("#aboutTitle").val());
                //                 // $("#aboutBody").val();
                //                 // $("aboutImage").val();
                                
                                
                //             } else if(flag == 2){
                //                 $('#modal2').modal('show');
                //             } else if(flag == 3){
                //                 $('#modal3').modal('show');
                //             }
                //         }
                //     });
                    
                //     e.preventDefault();
                // });
            
                $(".on-default").click(function () {
                    let item = $(this).attr('href');
                    let lang = $('#aboutLang').val();
                    let flag = $('#flag').val();
                    // alert(lang);
                    // return false;
                    $("#dialogConfirm").click(function () {
                        // var dataString = 'family=' + family;
                        $.ajax({
                            type: "POST",
                            url: "functions/about_functions.php",
                            data: {
                                item: item,
                                lang: lang,
                                flag: flag
                            },
                            dataType: 'text',
                            cache: false,
                            success: function (data) {
                                $(".deleteData").html(data);
                            }
                        });
                    });
                });
                

            $('body').on('change', '.change_cat_status_off', function () {
                var change_cat_status_off = $(this).attr('data-id');
                let lang = $('#aboutLang').val();
                let flag = $('#flag').val();
                // alert(flag);
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
                            url: "functions/about_functions.php",
                            data: {
                                change_cat_status_off: change_cat_status_off,
                                lang: lang,
                                flag: flag
                            },
                            dataType: 'text',
                            cache: false,
                            success: function (data) {
                                // console.log(data);
                                // alert(data);
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
                let lang = $('#aboutLang').val();
                let flag = $('#flag').val();
                // alert()
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
                            url: "functions/about_functions.php",
                            data: {
                                change_cat_status_on: change_cat_status_on,
                                lang: lang,
                                flag: flag
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
            
            
            
            $('#plan').change(function(){
                let plan = $(this).val();
                let lang = $('#familyLang').val();
                let familyId = $(this).attr("family");
                
                // alert(familyId);
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
                $("#item55").addClass("active");
            });
        </script>
    </body>
</html>
