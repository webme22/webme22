<?php
include("config.php");
if (!loggedin()) {
    header("Location: login.php");
    exit();
}

if (($_SESSION['role'] != 'admin' || $_SESSION['setting'] != 1)) {
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

            <?php
            if (isset($_POST['submit'])) {

                $errors = "";

                $id = $_POST['id_update'];
                $category_id = mysqli_real_escape_string($con, trim($_POST['category']));
                $question_ar = mysqli_real_escape_string($con, trim($_POST['question_ar']));
                $question_en = mysqli_real_escape_string($con, trim($_POST['question_en']));
                $answer_ar = mysqli_real_escape_string($con, trim($_POST['answer_ar']));
                $answer_en = mysqli_real_escape_string($con, trim($_POST['answer_en']));

                if (empty($category_id) || empty($question_ar) || empty($question_en) || empty($answer_ar) || empty($answer_en)) {
                        $errors = $languages[$lang]["required"];

                }

                
                if (strlen($errors) > 4) {
                    
                    echo get_error($errors);
                        
                } else {
                    

                    $con->query("update questions_and_answers set category_id='$category_id', question_ar='$question_ar', question_en='$question_en', answer_ar='$answer_ar', answer_en='$answer_en' where id='$id'") or die(mysqli_error($con));
                    
                    $image_name = $_FILES['image']['name'];
                    $image_tmp = $_FILES['image']['tmp_name'];
                    
                    if(isset($image_name) && $image_name != ''){
                        
                        if(! file_exists("../uploads/questions/" . $id)){
                            mkdir("../uploads/questions/" . $id, 0775, true);
                        }
        
                        $image_database = "uploads/questions/" . $id . "/" . round(microtime(true)) .".jpg";
        
                        $image_path = "../uploads/questions/" . $id . "/" . round(microtime(true)) .".jpg";
                        
                        
                        $con->query("update `questions_and_answers` set `image`='$image_database' where `id`='".$id."' ");
    
                        move_uploaded_file($image_tmp, $image_path);
                    }
                    
                    echo get_success($languages[$lang]["updateMessage"]);

                    
                }
            }
            ?>  


            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container">
                        <!-- Page-Title -->
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="page-title"><?php echo $languages[$lang]["editQuestion"];     ?></h4>
                                <ol class="breadcrumb">
                                    <li><a href="questions_view.php?lang=<?php  
                                        echo $lang;
                                        ?>"> <?php echo $languages[$lang]["questions"];     ?></a></li>
                                    <li class="active"><?php echo $languages[$lang]["questions"];     ?></li>
                                </ol>
                            </div>
                        </div>
                        <?php
                        if(isset($_GET['question_id'])){
                            $result = $con->query("select * from questions_and_answers where id='".$_GET['question_id']."'");
                            $row = mysqli_fetch_assoc($result);
                        ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-box">
                                    <form method="POST" enctype="multipart/form-data" data-parsley-validate novalidate>

                                  
                                        <input type="hidden" name="id_update" value="<?= $row['id'] ?>">
                                        
                                        <div class="form-group col-md-4">
                                            <label for="category"><?php echo $languages[$lang]['category'];  ?></label>
                                            <select name="category" required class="form-control" id="category">
                                            
                                                <option value=""><?= $languages[$lang]["choose"] ?></option>
                                                <?php
                                                    
                                                    $result_2 = $con->query("select * from questions_categories order by id desc");
                                                    while($row_2 = mysqli_fetch_assoc($result_2)){
                                                        $selected = '';
                                                        if($row['category_id'] == $row_2['id']){
                                                            $selected = 'selected';
                                                        }
                                                        echo "<option value='{$row_2['id']}' {$selected}>{$row_2['category_'.$lang]}</option>";
                                                    }

                                                ?>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label for="question_ar"><?php echo $languages[$lang]['question_ar'];  ?></label>
                                            <input type="text" name="question_ar"  required placeholder="<?php echo $languages[$lang]['question_ar'];  ?>" class="form-control" id="question_ar" value="<?= $row['question_ar'] ?>">
                                        </div>
                                        
                                        <div class="form-group col-md-4">
                                            <label for="question_en"><?php echo $languages[$lang]['question_en'];  ?> </label>
                                            <input type="text" name="question_en"  required placeholder="<?php echo $languages[$lang]['question_en'];  ?> " class="form-control" id="question_en" value="<?= $row['question_en'] ?>">
                                        </div>
                                        
                                       
                                        <div class="clearfix"></div>
                                        
                                        <div class="form-group col-md-6">
                                            <label for="answer_ar"><?php echo $languages[$lang]['answer_ar'];  ?> </label>
                                            <textarea required name="answer_ar"  placeholder="<?php echo $languages[$lang]['answer_ar'];  ?> " class="form-control ckeditor" id="answer_ar"><?= $row['answer_ar'] ?></textarea>
                                        </div>
                                        
                                        <div class="form-group col-md-6">
                                            <label for="answer_en"><?php echo $languages[$lang]['answer_en'];  ?> </label>
                                            <textarea required name="answer_en"  placeholder="<?php echo $languages[$lang]['answer_en'];  ?> " class="form-control ckeditor" id="answer_en"><?= $row['answer_en'] ?></textarea>
                                        </div>
                                        
                                        <div class="gal-detail thumb getImage">
                                            <a href="<?=asset($row['image'])?>" class="image-popup">
                                                <img src="<?=asset($row['image'])?>" class="thumb-img">
                                            </a>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group m-b-0">
                                            <label class="control-label"><?php    
                                            echo $languages[$lang]["image"];
                                            ?>  </label>
                                            <input type="file" name="image" id="photo" class="filestyle" data-buttonname="btn-primary">
                                        </div>
                                        
                                        <div class="clearfix"></div>
                                        
                                        <div class="form-group text-right m-b-0">
                                            <button class="btn btn-primary waves-effect waves-light" type="submit" name="submit"> <?php echo $languages[$lang]['update'];  ?> </button>
                                            <button type="reset" class="btn btn-default waves-effect waves-light m-l-5"> <?php echo $languages[$lang]['cancel'];  ?> </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>   
                        <?php } ?>
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
                $("#item53").addClass("active");
            });
        </script>

    </body>
</html>
