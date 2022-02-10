<?php


    include("languages.php");
    
    // if(isset($_POST['item']) && isset($_POST['lang']) && isset($_POST['flag'])){
    //     include("../connection.php");
        
    //     $item = isset($_POST['item']);
    //     $flag = isset($_POST['flag']);
    //     $lang = isset($_POST['lang']);
        
    //     if($flag == 1){
    //         $sql = "select * from  aboutBoxes where `id`='$item'";
    //     } elseif($flag == 2){
    //         $sql = "select * from  aboutPage where `id`='$item'";
    //     } elseif($flag == 3){
    //         $sql = "select * from  aboutPageBoxes where `id`='$item'";
    //     }
        
    //     $result = $con->query($sql);
    //     $row = mysqli_fetch_assoc($result);
        
    //     echo json_encode($row);
        
    // }


    
    
    function add_box($title, $body, $imageName){
        global $con;

        $con->query("insert into `aboutBoxes` ('title', 'body', 'image', 'display', 'date') values ('$title', '$body', '$imageName', '1', '".date('Y-m-d')."')") or die(mysqli_error($con));
        
        return mysqli_insert_id($con);
    }
    
    function add_about($body, $imageName){
        global $con;

        $con->query("insert into `aboutPage` ('body', 'image', 'display', 'date') values ('$body', '$imageName', '1', '".date('Y-m-d')."')") or die(mysqli_error($con));
        
        return mysqli_insert_id($con);
    }
    
    function add_aboutBox($body, $title){
        global $con;

        $con->query("insert into `aboutPageBoxes` ('title', 'body', 'display', 'date') values ('$title', '$body', '1', '".date('Y-m-d')."')") or die(mysqli_error($con));
        
        return mysqli_insert_id($con);
    }
    
    function view_about($lang, $flag){
        global $con;
        
        if($flag == 1){
            $sql = "select * from aboutBoxes order by id desc";
        } elseif($flag == 2){
            $sql = "select * from aboutPage order by id desc";
        } elseif($flag == 3){
            $sql = "select * from aboutPageBoxes order by id desc";
        }
        
        $x = 1;
        $result = $con->query($sql);
        while($row = mysqli_fetch_assoc($result)){
            $id = $row['id'];
            ?>
            
            <tr class="gradeX">
				<td><?php echo $x; ?></td>
				<?php if($_GET['flag'] == 1 || $_GET['flag'] == 3){ ?>
				<td><?php echo $row['title']; ?></td>
				<?php } ?>
				<td style="width: 250px; margin: 50px; " colspan="2"><?php echo $row['body']; ?></td>
				<?php if($_GET['flag'] == 1 || $_GET['flag'] == 2){ ?>
				<td>
                    <a href="<?php echo $row['image']; ?>" class="image-popup" title="">
                        <img src="<?=asset($row['image'])?>" class="thumb-img" alt="" height="100" style="width:100px;">
                    </a>			
                </td>
				<?php } ?>
                
				<td>
					<?php if ($row['display'] == 1) { ?>
						<input class="change_cat_status_off" data-id="<?php echo $id; ?>" type="checkbox" 
							   checked
							   data-plugin="switchery" data-color="#81c868"/>
						   <?php } else if ($row['display'] == 0) {
							   ?>
						<input class="change_cat_status_on" data-id="<?php echo $id; ?>" type="checkbox" 
							   data-plugin="switchery" data-color="#81c868"/>
						   <?php }
						   ?>
				</td> 
                <td><?php echo $row['date'];  ?></td>
				<td class="actions">
					<a href="about_edit.php?lang=<?php echo $lang; ?>&flag=<?php echo $flag; ?>&id=<?php echo $id; ?>" value="<?php echo $id; ?>" class="editParent" id="" data-id="<?php echo $flag; ?>"><i class="fa fa-pencil"></i></a>
					<a href="<?php echo $id; ?>"  class="on-default remove-row" id="deleteParent" data-id="<?php echo $flag; ?>"><i class="fa fa-trash-o"></i></a>
				</td>
			</tr>		
            
            
            <?php
        $x++; }
        
    }
    
if(isset($_POST['item']) && isset($_POST['lang']) && isset($_POST['flag'])){
        include("../connection.php");
        
        $item = $_POST['item'];
        $flag = $_POST['flag'];
        $lang = $_POST['lang'];
        
        if($flag == 1){
            $sql = "delete from aboutBoxes where `id`='$item'";
        } elseif($flag == 2){
            $sql = "delete from aboutPage where `id`='$item'";
        } elseif($flag == 3){
            $sql = "delete from aboutPageBoxes where `id`='$item'";
        }
        
        $query = $con->query($sql);

    if ($query) {

        echo get_success($languages[$lang]["deleteMessage"]);
    }

}

if (isset($_POST['change_cat_status_on'])) {

    include("../connection.php");
    $lang = $_POST['lang'];
    $flag = $_POST['flag'];
    $change_status = $_POST['change_cat_status_on'];
    
    if($flag == 1){
        $sql = "UPDATE `aboutBoxes` SET `display`=1 WHERE `id`='$change_status'";
    } elseif($flag == 2){
        $sql = "UPDATE `aboutPage` SET `display`=1 WHERE `id`='$change_status'";
    } elseif($flag == 3){
        $sql = "UPDATE `aboutPageBoxes` SET `display`=1 WHERE `id`='$change_status'";
    }

    $query = $con->query($sql);

    if ($query) {
        echo get_success($languages[$lang]["changeStatus"]);
    }
}
if (isset($_POST['change_cat_status_off'])) {

    include("../connection.php");
    
    $lang = $_POST['lang'];
    $flag = $_POST['flag'];
    $change_status = $_POST['change_cat_status_off'];
    // echo $flag; die();
    if($flag == 1){
        $sql = "UPDATE `aboutBoxes` SET `display`=0 WHERE `id`='$change_status'";
    } elseif($flag == 2){
        $sql = "UPDATE `aboutPage` SET `display`=0 WHERE `id`='$change_status'";
    } elseif($flag == 3){
        $sql = "UPDATE `aboutPageBoxes` SET `display`=0 WHERE `id`='$change_status'";
    }

    $query = $con->query($sql) or die(mysqli_error($con));

    if ($query) {
        echo get_success($languages[$lang]["changeStatus"]);
    }
}
