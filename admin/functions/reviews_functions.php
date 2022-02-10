<?php


    include("languages.php");

    function add_review($client, $position, $review, $imageName){
        global $con;
        
        $con->query("insert into reviews ('review', 'client', 'position', 'image', 'display', 'date') values (null, '$review', '$client', '$position', '$imageName', '1', '".date('Y-m-d')."')") or die(mysqli_error($con));
        
        return mysqli_insert_id($con);
    }
    
    function view_reviews($lang){
        global $con;
	
		$query = $con->query("SELECT * FROM `reviews` ORDER BY `id` DESC");
	
		$x = 1;
		while ($row = mysqli_fetch_assoc($query)) {
	
			$id = $row['id'];

			$client = $row['client'];
			$position = $row['position'];
			$date = $row['date'];
			$image = $row['image'];
			$review = $row['review'];
			$display = $row['display'];
	
			?>
			<tr class="gradeX">
				<td><?php echo $x; ?></td>
				<td><?php echo $client; ?></td>
				<td><?php echo $position; ?></td>
				<td style="width: 30%;"><?php echo $review; ?></td>
                <td>
                    <a href="<?=asset($image)?>" class="image-popup" title="<?php echo $client; ?>">
                        <img src="<?=asset($image)?>" class="thumb-img" alt="<?php echo $client; ?>" height="100" style="width:100px;">
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
					<a href="review_edit.php?reviewId=<?php echo $id ?>&lang=<?php echo $lang; ?>" class=""><i class="fa fa-pencil"></i></a>
					<a href="<?php echo $id; ?>"  class="on-default remove-row" id="deleteParent"><i class="fa fa-trash-o"></i></a>
				</td>
			</tr>		
			<?php
			$x++;
		}
	
		return mysqli_insert_id($con);
    }
    
    
if (isset($_POST['delete_review'])) {
    
    include("../connection.php");

    $delete_id = $_POST['delete_review'];
    $lang = $_POST['lang'];

    $query = $con->query("DELETE FROM `reviews` WHERE `id`='$delete_id'");

    if ($query) {

        echo get_success($languages[$lang]["deleteMessage"]);
    }

}

if (isset($_POST['change_cat_status_on'])) {

    include("../connection.php");
    $lang = $_POST['lang'];

    $change_status = $_POST['change_cat_status_on'];

    $query = $con->query("UPDATE `reviews` SET `display`=1 WHERE `id`='$change_status'");

    if ($query) {
        echo get_success($languages[$lang]["changeStatus"]);
    }
}
if (isset($_POST['change_cat_status_off'])) {

    include("../connection.php");
    $lang = $_POST['lang'];

    $change_status = $_POST['change_cat_status_off'];

    $query = $con->query("UPDATE `reviews` SET `display`=0 WHERE `id`='$change_status'");

    if ($query) {
        echo get_success($languages[$lang]["changeStatus"]);
    }
}
    
