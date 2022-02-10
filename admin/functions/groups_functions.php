<?php

include("languages.php");

function add_group($country_id, $lang_id, $name) {
    global $con;
    
    $con->query("INSERT INTO `groups` (country_id, lang_id, date, name) VALUES ('$country_id','$lang_id','" . date("Y-m-d H:i:s") . "','$name')") or die(mysqli_error($con));

    
    return mysqli_insert_id($con);
}

function view_groups(){
    
    global $con;
	
		$query = $con->query("SELECT * FROM `groups` ORDER BY `id` DESC");
	
		$x = 1;
		while ($row = mysqli_fetch_assoc($query)) {
	
			$id = $row['id'];
			$country = get_country($row['country_id'])['name_en'];
			$lang = get_language($row['lang_id'])['lang'];
			$date = $row['date'];
	
			?>
			<tr class="gradeX">
				<td><?php echo $x; ?></td>
				<td><?php echo $row['name']; ?></td>
				<td><?php echo $country; ?></td>
				<td><?php echo $lang; ?></td>
                <td><?php echo $date;  ?></td>
				<td class="actions">
					<a href="group_edit.php?groupId=<?php echo $id ?>" class=""><i class="fa fa-pencil"></i></a>
					<a href="<?php echo $id; ?>"  class="on-default remove-row" id="deleteParent"><i class="fa fa-trash-o"></i></a>
					<a href="groups_emails.php?groupId=<?php echo $id ?>"><i class="fa fa-envelope-o" aria-hidden="true"></i></a>
				</td>
			</tr>		
			<?php
			$x++;
		}
	
		return mysqli_insert_id($con);
    
}

function view_groups_emails($get){
    
    global $con;
	
		$query = $con->query("SELECT * FROM `group_emails` WHERE `group_id`='".$get['groupId']."' ORDER BY `id` DESC");
	
		$x = 1;
		while ($row = mysqli_fetch_assoc($query)) {
	
			$id = $row['id'];
			$email = $row['email'];
			$date = $row['date'];
	
			?>
			<tr class="gradeX">
				<td><?php echo $x; ?></td>
				<td><?php echo $email; ?></td>
                <td><?php echo $date;  ?></td>
				<td class="actions">
					<a href="<?php echo $id; ?>"  class="on-default remove-row" id="deleteParent"><i class="fa fa-trash-o"></i></a>
				</td>
			</tr>		
			<?php
			$x++;
		}
	
		return mysqli_insert_id($con);
    
}


function get_group($id){
    global $con;
        
    $query = $con->query("select * from group where `id`='$id'");
    $row = mysqli_fetch_array($query);
        
    return $row;
}

if (isset($_POST['delete_group'])) {

    include("../connection.php");
    include("languages.php");

    $group_id = $_POST['delete_group'];
    $lang = $_POST['lang'];
    $delete_group_emails = $con->query("DELETE FROM `groups_emails` WHERE `group_id`='$group_id'");
    $delete_group = $con->query("DELETE FROM `groups` WHERE `id`='$group_id'");

    if ($delete_group) {
        echo get_success($languages[$lang]["deleteMessage"]);
    }
    else {
        echo mysqli_error($con);
    }
}

if (isset($_POST['delete_group_email'])) {

    include("../connection.php");
    include("languages.php");

    $email_id = $_POST['delete_group_email'];
    $lang = $_POST['lang'];
    $delete_email = $con->query("DELETE FROM `group_emails` WHERE `id`='$email_id'");

    if ($delete_email) {
        echo get_success($languages[$lang]["deleteMessage"]);
    }
}


?>
