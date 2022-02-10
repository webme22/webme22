<?php

    include("languages.php");

	function add_country($title_ar, $title_en, $key, $country_code) {

	    global $con;
	    $con->query("INSERT INTO `countries` (`name_ar`, `name_en`, `countryKey`, `country_code`, `display`, `date`) VALUES ('$title_ar','$title_en', '$key', '$country_code', '1', '".date('Y-m-d')."')") or die(mysqli_error($con));

	    return mysqli_insert_id($con);
    
	}


    function countryName($id, $lang){
        global $con;
        
        $query = $con->query("select name_$lang from countries where `id`='$id'");
        $row = mysqli_fetch_array($query);
        
        return $row['name_'.$lang];
	}
	function get_nationality($id){
		global $con;
        
        $query = $con->query("select * from nationalities where `id`='$id'");
        $row = mysqli_fetch_array($query);
        
        return $row;
	}
	function get_country($id){
        global $con;
        
        $query = $con->query("select * from countries where `id`='$id'");
        $row = mysqli_fetch_array($query);
        
        return $row;
	}
	
	function get_language($id){
        global $con;
        
        $query = $con->query("select * from languages where `id`='$id'");
        $row = mysqli_fetch_array($query);
        
        return $row;
    }

	if (isset($_POST['catId'])) {
		require('../connection.php');

		$catId = $_POST['catId'];

		$result = $con->query("SELECT `id`, `name_ar` FROM `sub_category` WHERE `cat_id`='$catId'");

		$response = [];

		while ($row = mysqli_fetch_assoc($result)) {
			$data = [];

			$data['id'] = $row['id'];
			$data['name_ar'] = $row['name_ar'];
			array_push($response, $data);

		}

		echo json_encode($response);

	}


	function view_countries($lang) {

		global $con;
	
		$query = $con->query("SELECT * FROM `countries` ORDER BY `id` DESC");
	
		$x = 1;
		while ($row = mysqli_fetch_assoc($query)) {
	
			$id = $row['id'];

			$name_ar = $row['name_ar'];
			$name_en = $row['name_en'];
			$date = $row['date'];
			$image = $row['image'];
			$key = $row['countryKey'];
			$display = $row['display'];
	
			?>
			<tr class="gradeX">
				<td><?php echo $x; ?></td>
				<td><?php echo $name_ar; ?></td>
				<td><?php echo $name_en; ?></td>
				<td><?php echo $key; ?></td>
                <td>
                    <a href="<?=asset($image)?>" class="image-popup" title="<?php echo $name_en; ?>">
                        <img src="<?=asset($image)?>" class="thumb-img" alt="<?php echo $name_en; ?>" height="100" style="width:100px;">
                    </a>			
                </td>
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
					<a href="country_edit.php?countryId=<?php echo $id ?>&lang=<?php echo $lang; ?>" class=""><i class="fa fa-pencil"></i></a>
					<a href="<?php echo $id; ?>"  class="on-default remove-row" id="deleteParent"><i class="fa fa-trash-o"></i></a>
				</td>
			</tr>		
			<?php
			$x++;
		}
	
		return mysqli_insert_id($con);
	}
	

if (isset($_POST['delete_country'])) {
    
    include("../connection.php");

    $delete_id = $_POST['delete_country'];
    $lang = $_POST['lang'];

    $query = $con->query("DELETE FROM `countries` WHERE `id`='$delete_id'");

    if ($query) {

        echo get_success($languages[$lang]["deleteMessage"]);
    }

}

if (isset($_POST['change_cat_status_on'])) {

    include("../connection.php");
    $lang = $_POST['lang'];

    $change_status = $_POST['change_cat_status_on'];

    $query = $con->query("UPDATE `countries` SET `display`=1 WHERE `id`='$change_status'");

    if ($query) {
        echo get_success($languages[$lang]["changeStatus"]);
    }
}
if (isset($_POST['change_cat_status_off'])) {

    include("../connection.php");
    $lang = $_POST['lang'];

    $change_status = $_POST['change_cat_status_off'];

    $query = $con->query("UPDATE `countries` SET `display`=0 WHERE `id`='$change_status'");

    if ($query) {
        echo get_success($languages[$lang]["changeStatus"]);
    }
}
