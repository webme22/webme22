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
    <style>.custom-label{
        margin-top: 11px!important;
        }
        .pagination {
        display: inline-block;
        }
        .pagination a {
        color: black;
        float: <?php if($lang == 'ar') {echo 'right';} else {echo 'left'; }?>;
        padding: 8px 16px;
        text-decoration: none;
        cursor: pointer;
        }
        .pagination a.active {
        background-color: #4CAF50;
        color: white;
        }
        .pagination a:hover:not(.active) {background-color: #ddd;}
    </style>
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
                                <h4 class="page-title"> <?php echo $languages[$lang]["familyDetails"]; ?></h4>
                                <ol class="breadcrumb">
                                    <li><a href="families_view.php?lang=<?php echo $lang; ?>"> <?php echo $languages[$lang]["families"]; ?></a></li>
                                    <li class="active"><?php echo $languages[$lang]["families"]; ?> </li>
                                </ol>
                            </div>
                        </div>
                        <?php
                            if ($_GET['familyId']) {
                            
                                $familyId = $_GET['familyId'];
                            
                                $query_select = $con->query("SELECT * FROM `family` WHERE `id` = '{$familyId}' LIMIT 1");
                                $row_select = mysqli_fetch_array($query_select);
                            
                                $familyId = $row_select['id'];
                                $name_ar= $row_select['name_ar'];
                                $name_en = $row_select['name_en'];
                                $desc_ar= $row_select['desc_ar'];
                                $desc_en = $row_select['desc_en'];
                                $country_id= $row_select['country_id'];
                                $plan_id = $row_select['plan_id'];
                                $user_id = $row_select['user_id'];
                            
                            
                                if ($query_select) {
                                    ?>
                        <ul class="nav nav-tabs" style="text-align:center;">
                            <li class="<?php 
                                if($_GET['type']== 'Details' || ! isset($_GET['type'])){
                                    echo 'active';
                                }
                                ?>" style="float:none; display:inline-block; zoom:1;" id="item1">
                                <a href="family_details.php?familyId=<?php echo $familyId; ?>&lang=<?php echo $lang; ?>&type=Details" id="details">
                                <?php echo $languages[$lang]["details"]; ?>
                                </a>
                            </li>
                            <li class="<?php 
                                if($_GET['type']== 'Image'){
                                    echo 'active';
                                }
                                ?>" style="float:none; display:inline-block; zoom:1;" id="item2">
                                <a href="family_details.php?familyId=<?php echo $familyId; ?>&lang=<?php echo $lang; ?>&type=Image" id="images">
                                <?php echo $languages[$lang]["Images"]; ?>
                                </a>
                            </li>
                            <li style="float:none; display:inline-block; zoom:1;" class="<?php
                                if($_GET['type']== 'Video'){
                                    echo 'active';
                                }
                                
                                ?>">
                                <a href="family_details.php?familyId=<?php echo $familyId; ?>&lang=<?php echo $lang; ?>&type=Video" id="videos">
                                <?php echo $languages[$lang]["Videos"]; ?>
                                </a>
                            </li>
                            <li style="float:none; display:inline-block; zoom:1;" class="<?php
                                if($_GET['type']== 'Audio'){
                                    echo 'active';
                                }
                                
                                ?>">
                                <a href="family_details.php?familyId=<?php echo $familyId; ?>&lang=<?php echo $lang; ?>&type=Audio" id="documents">
                                <?php echo $languages[$lang]["Audios"]; ?>
                                </a>
                            </li>
                            <li style="float:none; display:inline-block; zoom:1;" class="<?php
                                if($_GET['type']== 'PDF'){
                                    echo 'active';
                                }
                                
                                ?>">
                                <a href="family_details.php?familyId=<?php echo $familyId; ?>&lang=<?php echo $lang; ?>&type=PDF" id="PDF">
                                <?php echo $languages[$lang]["PDF"]; ?>
                                </a>
                            </li>
                        </ul>
                        <div class="panel">
                            <div class="panel-body">
                                <div class="">
                                    <div class="table-responsive m-t-20">
                                    <?php if($_GET['type'] == 'Details' || ! isset($_GET['type'])){ ?>
                                        <table class="table table-striped">
                                            <tbody>
                                                <tr>
                                                    <th><?php echo $languages[$lang]["name_ar"]; ?></th>
                                                    <td>
                                                        <?php echo $name_ar; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><?php echo $languages[$lang]["name_en"]; ?></th>
                                                    <td>
                                                        <?php echo $name_en; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><?php echo $languages[$lang]["sheikh"]; ?></th>
                                                    <td>
                                                        <?php 
                                                            $parent = parentName($user_id); 
                                                            echo "<a href='user_details.php?userID={$user_id}&lang={$lang}'>{$parent}</a>";
                                                            
                                                                       ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><?php
                                                        echo $languages[$lang]["desc_ar"]; 
                                                        
                                                        ?></th>
                                                    <td>
                                                        <?php
                                                            if(! empty($desc_ar)){
                                                            echo $desc_ar; 
                                                            
                                                            } else {
                                                                echo "<span style='color: red;'>" .$languages[$lang]["notFound"]."</span>";
                                                            }
                                                            
                                                            ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><?php echo $languages[$lang]["desc_en"]; ?></th>
                                                    <td>
                                                        <?php 
                                                            if(! empty($desc_en)){
                                                             echo $desc_en; 
                                                             
                                                             } else {
                                                                 echo "<span style='color: red;'>" .$languages[$lang]["notFound"]."</span>";
                                                             }
                                                             
                                                             
                                                             ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th> <?php echo $languages[$lang]["country"]; ?></th>
                                                    <td>
                                                        <?php echo countryName($country_id, $lang); ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><?= $languages[$lang]["payments_history"] ?></th>
                                                    <td>
                                                        <table class="table table-striped">
                                                            <tr>
                                                                <th>#</th>
                                                                <th><?= $languages[$lang]["plan"] ?></th>
                                                                <th><?= $languages[$lang]["start_date"] ?></th>
                                                                <th><?= $languages[$lang]["expire_date"] ?></th>
                                                                <th><?= $languages[$lang]["subscribed_at"] ?></th>
                                                            </tr>
                                                            <?php 
                                                            $family_plans = get_family_plans($_GET['familyId'], $lang);
                                                            $x=1;
                                                            foreach($family_plans as $plan){ ?>
                                                            <tr>
                                                                <td><?= $x ?></td>
                                                                <td><?= $plan['plan'] ?></td>
                                                                <td><?= $plan['start_date'] ?></td>
                                                                <td><?= $plan['end_date'] ?></td>
                                                                <td><?= $plan['created_at'] ?></td>
                                                            </tr>
                                                            <?php $x++; } ?>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <?php 
                                        } 
                                        if($_GET['type'] != 'Details' && isset($_GET['type'])){

                                            $page = (isset($_GET['page']))? $_GET['page'] : 1;
                                            $start = ($page-1) * 6;
                                              
                                            $fileType = isset($_GET['type'])? $_GET['type']: 'Image';
                                            $id = (int) $_GET['familyId'];
                                                
                                            $sql = "select * from familyMedia where family_id='$id' and  file_type='$fileType'";
                                                
                                            $result = $con->query($sql);
                                            $data_num = mysqli_num_rows($result);
                                            $total_pages = ceil($data_num / 6); 
                                            $result = $con->query($sql . " order by id desc limit $start, 6"); 
                                                
                                            if(mysqli_num_rows($result) > 0){
                                            while($row = mysqli_fetch_assoc($result)){
                                              
                                                
                                        ?>
                                        <input type="hidden" value="<?php echo $total_pages; ?>" id="paginate">
                                        <?php if($row['file_type'] == 'Image'){
                                            ?>   
                                        <div class="col-md-3 file" style="margin-right: 60px;">
                                            <!-- <span href=""  class="deleteParent" ><i class="fa fa-trash-o"></i></span> -->
                                            <a href="<?=asset($row['file'])?>" alt=""><img src="<?=asset($row['file'])?>" style="margin-bottom: 10px;" height="100px" width = "200px" ></a>
                                        </div>
                                        <?php
                                            } elseif($row['file_type'] == 'Video'){ ?>
                                        <div class="col-md-4 file" >
                                            <!-- <span href=""  class="deleteParent" ><i class="fa fa-trash-o"></i></span> -->
                                            <video width="250" height="200"  controls style="border-top: none;">
                                                <source src="<?=asset($row['file'])?>" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>
                                        <?php } else if($row['file_type'] == 'Audio'){ ?>
                                        <div class="col-md-6 file" style="">
                                            <!-- <span href=""  class="deleteParent" ><i class="fa fa-trash-o"></i></span> -->
                                            <audio controls >
                                                <source src="<?=asset($row['file'])?>" type="audio/mpeg">
                                                Your browser does not support the audio tag.
                                            </audio>
                                        </div>
                                        <?php } else if($row['file_type'] == 'PDF'){ ?>
                                        <div class="col-md-2 file">
                                            <!-- <span href=""  class="deleteParent" ><i class="fa fa-trash-o"></i></span> -->
                                            <button style="font-size:24px" onclick="window.open('<?=asset($row['file'])?>', '_blank');"> <i class="fa fa-file"></i></button>
                                        </div>
                                        <?php } ?> 
                                        <?php } } 
                                            if($data_num == 0){
                                                echo "<p style='text-align: center; font-size: 30px;'>{$languages[$lang]['noResults']}</p>";
                                            }
                                            
                                        }
                                            ?>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <?php if($_GET['type'] != 'Details' && isset($_GET['type'])){ ?>
                            <div class='pagination' style="margin: auto;">
                                    <?php            
                                           
                                        $pagLink = "";       
                                           
                                        if($page>=2){   
                                            echo "<a id='prev'>Prev</a>";   
                                        }       
                                                        
                                        for ($i=1; $i<=$total_pages; $i++) {   
                                            if ($i == $page) {   
                                                $pagLink .= "<a class = 'paginate_link active'>".$i." </a>";   
                                            }               
                                            elseif($i < $page+4)  {   
                                                $pagLink .= "<a class = 'paginate_link'>  ".$i." </a>";     
                                            } elseif($i == $total_pages-1)  {   
                                                $pagLink .= "<a>...</a>";
                                                $pagLink .= "<a class = 'paginate_link'>  ".$i." </a>";     
                                            } elseif($i == $total_pages)  {   
                                                $pagLink .= "<a class = 'paginate_link'>  ".$i." </a>";     
                                            }   
                                        };     
                                        echo $pagLink;   
                                       
                                        if($page<$total_pages){   
                                            echo "<a id='next'>Next</a>";   
                                        } 
                                    ?>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
            }
            }
            ?>
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
                $("#item31").addClass("active");
                
                
                $('#prev').click(function(e){
            
                    let url = window.location.href;            
                    url = new URL(url);
                    page = parseInt(url.searchParams.get("page"));

                    if(page >= 2){
                             
                        url.searchParams.set("page", page-1);
                        location.href = url.href;
                   
                    }
                            
                    e.preventDefault();
                        
                })
                        
                $('#next').click(function(){
                    
                    let url = window.location.href;            
                    url = new URL(url);
                    page = parseInt(url.searchParams.get("page"));
                    let pagesNum = $('#paginate').val();
    
                    if(page < pagesNum){
            
                        url.searchParams.set("page", page + 1);
            
                        location.href = url.href;  
                                
                    }
                            
                    // e.preventDefault();
                            
                })

                $('body').on('click', '.paginate_link', function(){
                    $('.paginate_link.active').removeClass('active');
                    $(this).addClass('active');
                    let page = parseInt($(this).html());
                    
                    let url = location.href;            
                    url = new URL(url);
                    url.searchParams.set("page", page);
                    location.href = url.href;
                })
                
                // $('body').on('click', '#media', function(e){
                //     $('table.table').hide();
                //     let url = $(location).attr('href');
                //     // alert(url);
                //     url = new URL(url);
                //     url.searchParams.set("family", 2);
                //     location.href = url.href;
                    
                //     e.preventDefault();
                // })
            });
        </script>
    </body>
</html>