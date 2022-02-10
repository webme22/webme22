<?php include("config.php");
if(!loggedin()){
    header("Location: login.php");
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

    <div class="deleteData"></div>

    <div class="content-page">
        <div class="content">
            <div class="container">

                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="page-title"> <?php echo $languages[$lang]["payments"];   ?></h4>
                        <ol class="breadcrumb">
                            <li><a href="payments_view.php?lang=<?php echo $lang; ?>"> <?php echo $languages[$lang]["payments"];   ?> </a></li>
                            <li class="active">  <?php echo $languages[$lang]["payments"];   ?> </li>
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
                                    <th><?php  echo $languages[$lang]["family"];    ?></th>
                                    <th><?php  echo $languages[$lang]["payment_type"];    ?></th>
                                    <th><?php  echo $languages[$lang]["payment_id"];    ?></th>
                                    <th><?php  echo $languages[$lang]["payment_value"];    ?></th>
                                    <th><?php  echo $languages[$lang]["purpose"];    ?></th>
                                    <th><?php  echo $languages[$lang]["confirmed"];    ?></th>

                                </tr>
                                </thead>
                                <tbody>
                                <?php echo view_payments($lang);?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


            </div>
        </div>
        <?php include("include/footer_text.php"); ?>

    </div>
    </div>
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
    $('body').on('change', '.change_payment_confirmed_on', function () {
        var change_payment_confirmed_on = $(this).attr('data-id');
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
                var dataString = 'change_payment_confirmed_on=' + change_payment_confirmed_on;
                $.ajax({
                    type: "POST",
                    url: "functions/family_functions.php",
                    data: {
                        change_payment_confirmed_on: change_payment_confirmed_on,
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

    $('body').on('change', '.change_payment_confirmed_off', function () {
        var change_payment_confirmed_off = $(this).attr('data-id');
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
                        change_payment_confirmed_off: change_payment_confirmed_off,
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
</body>
</html>
