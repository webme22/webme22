<?php

include("languages.php");

function add_plan($title_ar, $title_en, $price, $members, $media) {
    global $con;
    
    $con->query("insert into plans ('name_ar', 'name_en', 'members', 'media', 'price', 'display', 'date') values ('$title_ar', '$title_en', '$members', '$media', '$price', '1', '".date('Y-m-d')."')") or die(mysqli_error($con));
    
    return mysqli_insert_id($con);
}

function view_plans($lang){
    
    global $con;
	
		$query = $con->query("SELECT * FROM `plans` ORDER BY `id` DESC");
	
		$x = 1;
		while ($row = mysqli_fetch_assoc($query)) {
	
			$id = $row['id'];

			$name_ar = $row['name_ar'];
			$name_en = $row['name_en'];
			$members = $row['members'];
			$media = $row['media'];
			$price = $row['price'];
			$date = $row['date'];
			$display = $row['display'];
	
			?>
			<tr class="gradeX">
				<td><?php echo $x; ?></td>
				<td><?php echo $name_ar; ?></td>
				<td><?php echo $name_en; ?></td>
                <td><?php echo $price; ?></td>
                <td><?php echo $members; ?></td>
                <td><?php echo $media; ?></td>
				<td>
					<?php if ($display == 1) { ?>
						<input class="change_cat_status_off" data-id="<?php echo $id; ?>" type="checkbox" 
							   checked
							   data-plugin="switchery" data-color="#81c868"/>
						   <?php } else if ($display == 0) {
							   ?>
						<input class="change_cat_status_on" data-id="<?php echo $id; ?>" type="checkbox" 
							   data-plugin="switchery" data-color="#81c868"/>
						   <?php }
						   ?>
				</td> 
                <td><?php echo $date;  ?></td>
				<td class="actions">
					<a href="plan_edit.php?planId=<?php echo $id ?>&lang=<?php echo $lang; ?>" class=""><i class="fa fa-pencil"></i></a>
					<a href="<?php echo $id; ?>"  class="on-default remove-row" id="deleteParent"><i class="fa fa-trash-o"></i></a>
				</td>
			</tr>		
			<?php
			$x++;
		}
	
		return mysqli_insert_id($con);
    
}


if (isset($_POST['del_size_id'])) {
    include("../connection.php");
    $size_id = $_POST['del_size_id'];
    $query_select = $con->query("SELECT * FROM `cart` WHERE `size_id`='" . $size_id . "' ORDER BY `cart_id`  ");
    $cart_count = mysqli_num_rows($query_select);

    if ($cart_count > 0) {
        echo 1;
    } else {
        echo 0;
    }
}
if (isset($_POST['delete_subcat_size_id'])) {
    include("../connection.php");
    $size_id = $_POST['delete_subcat_size_id'];
    $delete = $con->query("DELETE FROM `sub_category_size_prices` WHERE `sub_category_size_price_id` ='$size_id'");

    if ($delete) {
        echo 1;
    } else {
        echo 0;
    }
}

function planName($id, $lang){
    global $con;
        
    $query = $con->query("select name_$lang from plans where `id`='$id'");
    $row = mysqli_fetch_array($query);
        
    return $row['name_'.$lang];
}

if (isset($_POST['comment_id'])) {

    include("../connection.php");

    $comment_id = $_POST['comment_id'];
    $delete_comment = $con->query("DELETE FROM `sub_category_comments` WHERE `comment_id`='$comment_id'");

    if ($delete_comment) {
        echo get_success("تم الحذف بنجاح");
    }
}

function view_subcat_comments($aStart = 0, $aLimit = 0, $get) {

    global $con;
    $subcat_comments = array();
    $sql = " SELECT * FROM `sub_category_comments`  ";
    if (isset($get['sub_category_id']) && $get['sub_category_id'] != '') {
        $sql .= " where `sub_category_id` = '" . $get['sub_category_id'] . "'  ";
    }
    if (isset($get['client_id']) && $get['client_id'] != '') {
        $sql .= " where `client_id` = '" . $get['client_id'] . "'  ";
    }
    $sql.= " ORDER BY `comment_id` DESC ";
    $sql.= $aLimit ? "LIMIT {$aStart},{$aLimit}" : "";
    $query_select = $con->query($sql);
    $x = 1;

    while ($row = mysqli_fetch_assoc($query_select)) {
        array_push($subcat_comments, $row);

        $x++;
    }
    return $subcat_comments;
}

function subcat_comments_count($get) {

    global $con;
    $sql = " SELECT * FROM `sub_category_comments`  ";
    if (isset($get['sub_category_id']) && $get['sub_category_id'] != '') {
        $sql .= " where `sub_category_id` = '" . $get['sub_category_id'] . "'  ";
    }
    if (isset($get['client_id']) && $get['client_id'] != '') {
        $sql .= " where `client_id` = '" . $get['client_id'] . "'  ";
    }
    $query = $con->query($sql);

    $subcat_comments_count = mysqli_num_rows($query);

    return $subcat_comments_count;
}

function sub_cat_comments_count($sub_category_id) {

    global $con;
    $sql = " SELECT * FROM `sub_category_comments`  ";
    if ($sub_category_id) {
        $sql .= " where `sub_category_id` = '$sub_category_id'  ";
    }
    if ($client_id) {
        $sql .= " where `client_id` = '$client_id'  ";
    }
    $query = $con->query($sql);

    $subcat_comments_count = mysqli_num_rows($query);

    return $subcat_comments_count;
}

function sub_cat_client_comments_count($client_id) {

    global $con;
    $sql = " SELECT * FROM `sub_category_comments`  ";
    if ($client_id) {
        $sql .= " where `client_id` = '$client_id'  ";
    }
    $query = $con->query($sql);

    $subcat_comments_count = mysqli_num_rows($query);

    return $subcat_comments_count;
}

// Add Sub Category
function add_sub_cat($sub_cat_name, $sub_cat_name_ar, $sub_cat_desc, $sub_cat_desc_ar, $parent_category_id, $sub_cat_image, $display) {

    global $con;

    $con->query("INSERT INTO `sub_category` VALUES (null, '$parent_category_id', '$sub_cat_name','$sub_cat_name_ar','$sub_cat_desc_ar','$sub_cat_desc','$sub_category_image','$display','" . date("Y-m-d H:i:s") . "')");
    global $sub_category_id;

    $sub_category_id = mysqli_insert_id($con);

    return mysqli_insert_id($con);
}

// Add Sub Category Sizes Name And Price
// function add_sub_cat_size_prices($sub_cat_size_name, $sub_cat_size_name_ar, $sub_cat_size_price) {

//     global $con;

//     global $sub_category_id;

//     $sub_cat_size_name_ar = $_POST['size_ar'];
//     $sub_cat_size_name = $_POST['size'];
//     $sub_cat_size_price = $_POST['size_price'];

//     foreach ($sub_cat_size_name as $key => $n) {
//         $con->query("INSERT INTO `sub_category_size_prices` VALUES (null,'" . $n . "','" . $sub_cat_size_name_ar[$key] . "','" . $sub_cat_size_price[$key] . "','" . $sub_category_id . "','" . date("Y-m-d H:i:s") . "')");
//     }

//     return mysqli_insert_id($con);
// }

function sub_cat_size_prices_update($temp) {
    global $con;
    $sub_category_id = $temp['sub_cat_id_update'];
    $itr = $temp['itr'];
    for ($i = 0; $i <= $itr; $i++) {
        if (isset($temp['size_' . $i . '']) && $temp['size_price_' . $i . ''] != '') {
            $query_size = $con->query("SELECT * FROM `sub_category_size_prices` where `sub_category_size_price_id`='" . $temp['size_id_' . $i . ''] . "' ");
            $size_count = mysqli_num_rows($query_size);
            if ($size_count == 0) {
                $con->query("INSERT INTO `sub_category_size_prices` VALUES (null,'" . $temp['size_' . $i . ''] . "','" . $temp['size_ar_' . $i . ''] . "','" . $temp['size_price_' . $i . ''] . "','" . $sub_category_id . "','" . date("Y-m-d H:i:s") . "')");
            }else{
                                $con->query("UPDATE  `sub_category_size_prices` SET  `sub_category_size_name_ar`='" . $temp['size_ar_' . $i . ''] . "' , `sub_category_size_name`='" . $temp['size_' . $i . ''] . "' , `sub_category_size_price`='" . $temp['size_price_' . $i . ''] . "'  WHERE `sub_category_size_price_id`='" . $temp['size_id_' . $i . ''] . "' AND `sub_category_id`='$sub_category_id' ");
            }
        }
    }
}

// Add Sub Category Additions Name & Price For Each Size
function add_sub_cat_addition_prices($sub_cat_addition_name, $sub_cat_addition_name_ar, $sub_cat_addition_price,$parent_category_id) {

    global $con;

    global $sub_category_id_cus;
    global $sub_category_size_id_cus;

    $sub_cat_addition_name = $_POST['addition'];
    $sub_cat_addition_name_ar = $_POST['addition_ar'];
    $sub_cat_addition_price = $_POST['addition_price'];

    foreach ($sub_cat_addition_name as $key => $m) {
        $con->query("INSERT INTO `sub_category_addition_prices` VALUES (null,'" . $m . "','" . $sub_cat_addition_name_ar[$key] . "','" . $sub_cat_addition_price[$key] . "','$parent_category_id','" . date("Y-m-d H:i:s") . "')");
    }

    return mysqli_insert_id($con);
}

// Add Sub Category Images
function add_sub_cat_images($sub_cat_images) {

    global $con;

    global $sub_category_id;

    if (!file_exists(dirname(__FILE__) . "/../uploads/sub_category/{$sub_category_id}")) {
        mkdir(dirname(__FILE__) . "/../uploads/sub_category/{$sub_category_id}", 0777, true);
    }

    //Loop through each file
    for ($i = 0; $i < count($sub_cat_images); $i++) {

        // $tmpFilePath = $_FILES['upload']['tmp_name'][$i];

        $sub_cat_photo_name = $_FILES['sub_cat_photo']['name'][$i];
        $sub_cat_photo_tmp = $_FILES['sub_cat_photo']['tmp_name'][$i];
        $allowed_ext = array('jpg', 'jpeg', 'gif', 'png');
        $get_image_ext = explode('.', $sub_cat_photo_name);
        $image_ext = strtolower(end($get_image_ext));

        $image_path = dirname(__FILE__) . "/../uploads/sub_category/{$sub_category_id}/" . $sub_cat_photo_name;

        if (move_uploaded_file($sub_cat_photo_tmp, $image_path)) {

            $con->query("INSERT INTO `sub_category_images` VALUES (null,'$sub_cat_photo_name','" . $sub_category_id . "','" . date("Y-m-d H:i:s") . "')");
            ;
        }
    }

    return mysqli_insert_id($con);
}

// Get Parent Categories Name And ID
function parent_category_name($parent_category_id) {

    global $con;

    $queryB = $con->query("SELECT * FROM `parent_categories` WHERE `id`='$parent_category_id' ORDER BY `id` ASC limit 1");
    $row_select = mysqli_fetch_array($queryB);
    $parent_category_name = $row_select['name_ar'];
    return $parent_category_name;
}

function sub_category_name($sub_category_id) {

    global $con;

    $queryB = $con->query("SELECT * FROM `sub_category` WHERE `sub_category_id`='$sub_category_id' ORDER BY `sub_category_id` ASC limit 1");
    $row_select = mysqli_fetch_array($queryB);
    $sub_category_name = $row_select['sub_category_name'];
    return $sub_category_name;
}

function sub_parent_category($parent_category_id) {

    global $con;

    $queryB = $con->query("SELECT * FROM `parent_categories` WHERE `id`='$parent_category_id' ORDER BY `id` ASC");

    while ($row = mysqli_fetch_assoc($queryB)) {

        echo $parent_category_name = $row['name_ar'];
    }
}

// Count Number Of Sub Categories
function sub_cat_count() {

    global $con;

    $query = $con->query("SELECT * FROM `sub_category` ORDER BY `sub_category_id` ASC");

    $sub_cat_count = mysqli_num_rows($query);

    return $sub_cat_count;
}

//Delete Sub Category By Sub Category ID
if (isset($_POST['sub_category_delete'])) {

    include("../connection.php");

    $sub_category = $_POST['sub_category_delete'];
    $querya = $con->query("SELECT * FROM `sub_category` WHERE `id`='$sub_category' limit 1");
    $row_select = mysqli_fetch_array($querya);
    $sub_category_image = $row_select['image'];
    // $mostafa = explode('/', $sub_category_image);

    // $image_name = $mostafa[8];

    // $full_img_path = dirname(__FILE__) . "/../api/uploads/sub_category/{$sub_category}/{$image_name}";

    $folder_full_img_path = dirname(_FILE_) . "/../api/uploads/sub_category/{$sub_category}";

    if (file_exists($sub_category_image)) {
        @unlink($sub_category_image);
    }

    rmdir($folder_full_img_path);

    $query = $con->query("DELETE FROM `sub_category` WHERE `id`='$sub_category'");

    if ($query) {
        echo get_success("تم الحذف بنجاح");
    }
}

if (isset($_POST['delete_sub_category_addition_price_id'])) {

    include("../connection.php");

    $sub_category_addition_price_id = $_POST['delete_sub_category_addition_price_id'];


    $query = $con->query("DELETE FROM `sub_category_addition_prices` WHERE `sub_category_addition_price_id`='$sub_category_addition_price_id'");

    if ($query) {
        echo get_success("تم الحذف بنجاح");
    }
}


//Update Sub Category Form 
if (isset($_POST['sub_cat_id'])) {

    global $con;

    include("../connection.php");

    $get_sub_cat_id = $_POST['sub_cat_id'];

    $query_select = $con->query("SELECT * FROM `sub_category` WHERE `sub_category_id` = '{$get_sub_cat_id}' LIMIT 1");
    $row_select = mysqli_fetch_array($query_select);

    $sub_category_id = $row_select['sub_category_id'];
    $sub_category_name = $row_select['sub_category_name'];
    $get_parent_category_id = $row_select['parent_category_id'];

    $sub_category_image = $row_select['sub_category_image'];
    $get_image_ext = explode('.', $sub_category_image);
    $image_ext = strtolower(end($get_image_ext));

    if ($query_select) {
        ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="card-box"> 									
                    <form method="POST" enctype="multipart/form-data" data-parsley-validate novalidate>
                        <input type="hidden" name="sub_cat_id_update" id="sub_cat_id_update" parsley-trigger="change" required value="<?php echo $sub_category_id; ?>" class="form-control">
                        <div class="form-group">
                            <label for="userName">الإسم*</label>
                            <input type="text" name="sub_cat_name_update" id="sub_cat_name_update" parsley-trigger="change" required value="<?php echo $sub_category_name; ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="userName">الصنف الرئيسى*</label>
                            <select class="form-control" name="parent_category_id_update" id="parent_category_id_update" required parsley-trigger="change">
                                <?php
                                $query = $con->query("SELECT * FROM `parent_categories` ORDER BY `parent_category_id` ASC");
                                while ($row = mysqli_fetch_assoc($query)) {
                                    $parent_category_id = $row['parent_category_id'];
                                    $parent_category_name = $row['parent_category_name'];
                                    if ($get_parent_category_id == $parent_category_id) {
                                        echo "<option value='{$parent_category_id}' selected='selected'>{$parent_category_name}</option>";
                                    } else {
                                        echo "<option value='{$parent_category_id}'>{$parent_category_name}</option>";
                                    }
                                }
                                ?>
                            </select>					
                        </div>				
                        <div class="form-group text-right m-b-0">
                            <button class="btn btn-primary waves-effect waves-light" type="submit" name="sub_cat_update" id="sub_cat_update">تحديث</button>
                        </div>
                    </form>								
                </div>
            </div>
        </div>          
        <script type="text/javascript">
            $(document).ready(function () {
                $('.image-popup').magnificPopup({
                    type: 'image',
                    closeOnContentClick: true,
                    mainClass: 'mfp-fade',
                    gallery: {
                        enabled: true,
                        navigateByImgClick: true,
                        preload: [0, 1] // Will preload 0 - before current, and 1 after the current image
                    }
                });
            });
        </script>
        <?php
    }
}


// Get Sub Category Name And ID By Parent Category ID
if (isset($_POST['get_sub_category_by_parent_category_id'])) {

    global $con;

    include("../connection.php");

    $parent_category_id = $_POST['get_sub_category_by_parent_category_id'];

    $query = $con->query("SELECT * FROM `sub_category` WHERE `parent_category_id`='$parent_category_id'");

    echo "<option value=''>اختر المنتج</option>";
    while ($row = mysqli_fetch_assoc($query)) {
        $sub_category_id = $row['sub_category_id'];
        $sub_category_name = $row['sub_category_name'];
        echo "<option value='{$sub_category_id}'>{$sub_category_name}</option>";
    }
}

// Get Sub Category Images By Sub Category ID
function get_sub_category_images_by_id($sub_category_id) {

    global $con;

    $query = $con->query("SELECT * FROM `sub_category_images` WHERE `sub_category_id`='$sub_category_id' ORDER BY `sub_category_image_id` DESC");

    $sub_category_images_name = array();

    while ($row = mysqli_fetch_assoc($query)) {

        $sub_category_images_name[] = $row['sub_category_image_name'];
    }

    // $sub_category_images = array_sum ($sub_category_images_name);

    return $sub_category_images_name;
}

// Get Sub Category Size Name And Price By Parent Category ID
if (isset($_POST['get_sizes_by_sub_category_id'])) {

    global $con;

    include("../connection.php");

    $sub_category_id = $_POST['get_sizes_by_sub_category_id'];

    $query = $con->query("SELECT * FROM `sub_category_size_prices` WHERE `sub_category_id`='$sub_category_id'");
    echo "<option value=''>إختر حجم المنتج</option>";

    while ($row = mysqli_fetch_assoc($query)) {
        $sub_category_size_price_id = $row['sub_category_size_price_id'];
        $sub_category_size_name = $row['sub_category_size_name'];
        $sub_category_size_price = $row['sub_category_size_price'];
        echo "<option value='{$sub_category_size_price_id}'>الحجم: {$sub_category_size_name} =>    السعر: {$sub_category_size_price}    د.ب</option>";
    }
}
if (isset($_POST['addition_sub_category_id'])) {

    global $con;

    include("../connection.php");

    $sub_category_id = $_POST['addition_sub_category_id'];

    $query = $con->query("SELECT * FROM `sub_category_addition_prices` WHERE `sub_category_id`='$sub_category_id'");
    echo "<option value=''>إختر الإضافة</option>";
    while ($row = mysqli_fetch_assoc($query)) {
        $sub_category_addition_price_id = $row['sub_category_addition_price_id'];
        $sub_category_addition_name = $row['sub_category_addition_name'];
        echo "<option value='{$sub_category_addition_price_id}'>{$sub_category_addition_name}</option>";
    }
}

function count_client_fav_sub($client_id) {


    global $con;
    $query = $con->query("SELECT * FROM `client_fav` where `client_id`='$client_id' ORDER BY `fav_id` DESC");

    $sub_cat_count = mysqli_num_rows($query);

    return $sub_cat_count;
}

// View Sub Category Table
function view_client_fav_sub($client_id) {

    global $con;

    $query_1 = $con->query("SELECT * FROM `client_fav` where `client_id`='$client_id' ORDER BY `fav_id` DESC");

    while ($row_1 = mysqli_fetch_assoc($query_1)) {
        $sub_category_id = $row_1['sub_category_id'];

        $query = $con->query("SELECT * FROM `sub_category` where `sub_category_id`='$sub_category_id' ORDER BY `sub_category_id` DESC");

        $x = 1;

        while ($row = mysqli_fetch_assoc($query)) {
            $sub_category_name = $row['sub_category_name'];
            $parent_category_id = $row['parent_category_id'];
            $date = $row['date'];

            $image = $row['sub_category_image'];
            $get_image_ext = explode('.', $image);
            $image_ext = strtolower(end($get_image_ext));
            ?>
            <tr class="gradeX">
                <td><?php echo $x; ?></td>
                <td><?php echo $sub_category_name; ?></td>
                <td><?php echo sub_parent_category($parent_category_id); ?></td>
                <td>
                    <a href="<?=asset($image)?>" class="image-popup" title="<?php echo $sub_category_name; ?>">
                        <img src="<?=asset($image) ?>" class="thumb-img" alt="<?php echo $sub_category_name; ?>" height="100" style="width:100px;">
                    </a>			
                </td>
                <td><?php echo $date; ?></td>
            </tr>		
            <?php
            $x++;
        }
    }

    return mysqli_insert_id($con);
}

function sub_category_count($sub_ct_name) {

    global $con;

    $sql = " SELECT * FROM `sub_category`  ";
    if (isset($sub_ct_name) && $sub_ct_name != '') {
        $sql .= " where `sub_category_name`LIKE '%{$sub_ct_name}%'  ";
    }
    $query = $con->query($sql);

    $sub_category_count = mysqli_num_rows($query);

    return $sub_category_count;
}

function view_sub_cat($aStart = 0, $aLimit = 0, $sub_ct_name) {

    global $con;
    $sub_category = array();
    $sql = " SELECT * FROM `sub_category`  ";
    if (isset($sub_ct_name) && $sub_ct_name != '') {
        $sql .= " where `name_en` LIKE '%{$sub_ct_name}%'  ";
    }
    $sql.= " ORDER BY `id` DESC ";
    $sql.= $aLimit ? "LIMIT {$aStart},{$aLimit}" : "";
    $query_select = $con->query($sql);
    $x = 1;
    while ($row = mysqli_fetch_assoc($query_select)) {
        array_push($sub_category, $row);

        $x++;
    }
    return $sub_category;
}

function sub_category_customize() {

    global $con;

    $query = $con->query("SELECT * FROM `sub_category_addition_prices` ORDER BY `sub_category_addition_price_id` DESC");

    $x = 1;

    while ($row = mysqli_fetch_assoc($query)) {
        $sub_category_addition_price_id = $row['sub_category_addition_price_id'];
        $sub_category_addition_name = $row['sub_category_addition_name'];
        $sub_category_addition_name_ar = $row['sub_category_addition_name_ar'];
        $sub_category_addition_price = $row['sub_category_addition_price'];
        $parent_cat_id=$row['parent_category_id'];
        $date = $row['date'];
        
        ?>
        <tr class="gradeX">
            <td><?php echo $x; ?></td>
            <td><?php echo $sub_category_addition_name; ?></td>
            <td><?php echo $sub_category_addition_name_ar; ?></td>
            <td><?php echo get_parent_cat_by_id($parent_cat_id)["parent_category_name_ar"]; ?></td>
            <td><?php echo $sub_category_addition_price; ?></td>
            <td class="actions">
                <a href="#" class="hidden on-editing save-row"><i class="fa fa-save"></i></a>
                <a href="#" class="hidden on-editing cancel-row"><i class="fa fa-times"></i></a>
                <a href="sub_category_customize_edit.php?addition_id=<?php echo $sub_category_addition_price_id; ?>" class="on-default"><i class="fa fa-pencil"></i></a>
                <a href="<?php echo $sub_category_addition_price_id; ?>" class="on-default remove-row" id="deleteParent"><i class="fa fa-trash-o"></i></a>
            </td>
        </tr>		
        <?php
        $x++;
    }

    return mysqli_insert_id($con);
}

function subCategory($sub_category_id) {

    global $con;

    $queryB = $con->query("SELECT * FROM `sub_category` WHERE `sub_category_id`='$sub_category_id' ORDER BY `sub_category_id` ASC");

    while ($row = mysqli_fetch_assoc($queryB)) {

        echo $sub_category_name = $row['sub_category_name'];
    }
}

function getSizeById($size_id) {

    global $con;

    $queryB = $con->query("SELECT * FROM `sub_category_size_prices` WHERE `sub_category_size_price_id`='$size_id' limit 1");
    $row_select = mysqli_fetch_array($queryB);

    return $sub_category_size_name = $row_select['sub_category_size_name'];
}

function parentCatIdBySubId($sub_category_id) {
    global $con;

    $query = $con->query("SELECT * FROM `sub_category` WHERE `sub_category_id`='$sub_category_id' limit 1");
    $row_select = mysqli_fetch_array($query);

    $parent_category_id = $row_select['parent_category_id'];
    return $parent_category_id;
}

if (isset($_POST['delete_plan'])) {
    
    include("../connection.php");

    $delete_id = $_POST['delete_plan'];
    $lang = $_POST['lang'];

    $query = $con->query("DELETE FROM `plans` WHERE `id`='$delete_id'");

    if ($query) {

        echo get_success($languages[$lang]["deleteMessage"]);
    }

}

if (isset($_POST['change_cat_status_on'])) {

    include("../connection.php");
    $lang = $_POST['lang'];

    $change_status = $_POST['change_cat_status_on'];

    $query = $con->query("UPDATE `plans` SET `display`=1 WHERE `id`='$change_status'");

    if ($query) {
        echo get_success($languages[$lang]["changeStatus"]);
    }
}
if (isset($_POST['change_cat_status_off'])) {

    include("../connection.php");
    $lang = $_POST['lang'];

    $change_status = $_POST['change_cat_status_off'];

    $query = $con->query("UPDATE `plans` SET `display`=0 WHERE `id`='$change_status'");

    if ($query) {
        echo get_success($languages[$lang]["changeStatus"]);
    }
}
?>
