<?php

include("languages.php");

function get_category($category_id){
	global $con;

	$result = $con->query("select * from questions_categories where id='$id'");

	return mysqli_fetch_assoc($result);
}

function view_questions($lang){
    
    global $con;
	
		$query = $con->query("SELECT * FROM `questions_and_answers` ORDER BY `id` DESC");
	
		$x = 1;
		while ($row = mysqli_fetch_assoc($query)) {
	
			$id = $row['id'];
			$category_id = $row['category_id'];
			$question_ar = $row['question_ar'];
			$question_en = $row['question_en'];
			$answer_ar = $row['answer_ar'];
			$answer_en = $row['answer_en'];
			$image = $row['image'];
			$date = $row['date'];
			
	
			?>
			<tr class="gradeX">
				<td><?php echo $x; ?></td>
				<td><?= get_category($category_id)['category_'.$lang] ?></td>
				<td><?php echo $question_ar; ?></td>
				<td><?php echo $question_en; ?></td>
                <td><?php echo $answer_ar; ?></td>
                <td><?php echo $answer_en; ?></td>
                
                <td>
                    <a href="<?=asset($image)?>" class="image-popup">
                        <img src="<?=asset($image)?>" class="thumb-img"  height="100" style="width:100px;">
                    </a>			
                </td>
				
                <td><?php echo $date;  ?></td>
				<td class="actions">
					<a href="question_edit.php?question_id=<?php echo $id ?>&lang=<?php echo $lang; ?>" class=""><i class="fa fa-pencil"></i></a>
					<a href="<?php echo $id; ?>"  class="on-default remove-row" id="deleteParent"><i class="fa fa-trash-o"></i></a>
				</td>
			</tr>		
			<?php
			$x++;
		}
	
		return mysqli_insert_id($con);
    
}

if (isset($_POST['delete_question'])) {

    include("../connection.php");

    $delete_question = $_POST['delete_question'];
    $lang = $_POST['lang'];
    $query = $con->query("DELETE FROM `questions_and_answers` WHERE `id`='$delete_question'");

    if ($query) {
        echo get_success($languages[$lang]["deleteMessage"]);
    }

}

if (isset($_POST['delete_category'])) {

    include("../connection.php");

    $delete_category = $_POST['delete_category'];
    $lang = $_POST['lang'];
    $query = $con->query("DELETE FROM `questions_categories` WHERE `id`='$delete_category'");

    if ($query) {
        echo get_success($languages[$lang]["deleteMessage"]);
    }

}

if (isset($_POST['delete_item'])) {

    include("../connection.php");

    $delete_item = $_POST['delete_item'];
    $lang = $_POST['lang'];
    $query = $con->query("DELETE FROM `how_it_works` WHERE `id`='$delete_item'");

    if ($query) {
        echo get_success($languages[$lang]["deleteMessage"]);
    }

}

function view_how_it_works($lang){
	include("languages.php");
	global $con;
	
		$query = $con->query("SELECT * FROM `how_it_works` ORDER BY `id` DESC");
	
		$x = 1;
		while ($row = mysqli_fetch_assoc($query)) {
	
			$id = $row['id'];
			$title_ar = $row['title_ar'];
			$title_en = $row['title_en'];
			$file = $row['file'];
			$date = $row['date'];
			
	
			?>
			<tr class="gradeX">
				<td><?php echo $x; ?></td>
				<td><?php echo $title_ar; ?></td>
				<td><?php echo $title_en; ?></td>                
                <td>
                    <a href="<?php echo $file; ?>" target="_blank">
						<?= $languages[$lang]["click_here"] ?>
                    </a>			
                </td>
				
                <td><?php echo $date;  ?></td>
				<td class="actions">
					<a href="how_it_works_edit.php?item_id=<?php echo $id ?>&lang=<?php echo $lang; ?>" class=""><i class="fa fa-pencil"></i></a>
					<a href="<?php echo $id; ?>"  class="on-default remove-row" id="deleteParent"><i class="fa fa-trash-o"></i></a>
				</td>
			</tr>		
			<?php
			$x++;
		}
	
		return mysqli_insert_id($con);
}

function view_questions_categories($lang){
	global $con;
	
	$query = $con->query("SELECT * FROM `questions_categories` ORDER BY `id` DESC");
	
	$x = 1;
	while ($row = mysqli_fetch_assoc($query)) {
	
			$id = $row['id'];

			$category_ar = $row['category_ar'];
			$category_en = $row['category_en'];
			
			$date = $row['date'];
			
	
			?>
			<tr class="gradeX">
				<td><?php echo $x; ?></td>
				<td><?php echo $category_ar; ?></td>
				<td><?php echo $category_en; ?></td>
				
                <td><?php echo $date;  ?></td>
				<td class="actions">
					<a href="category_edit.php?category_id=<?php echo $id ?>&lang=<?php echo $lang; ?>" class=""><i class="fa fa-pencil"></i></a>
					<a href="<?php echo $id; ?>"  class="on-default remove-row" id="deleteParent"><i class="fa fa-trash-o"></i></a>
				</td>
			</tr>		
			<?php
			$x++;
	}
	
	return mysqli_insert_id($con);	
}
