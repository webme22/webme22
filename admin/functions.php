<?php



	function userEmailExists($email, $id = 0){

		include('connection.php');
		$result = mysqli_query($con, "SELECT * FROM `users` WHERE `user_email`!='$email' AND `id`!='$id'");

		return (mysqli_num_rows($result) == 1) ? true : false;

	}

	function userNameExists($name, $id = 0){

		include('connection.php');
		$result = mysqli_query($con, "SELECT * FROM `users` WHERE `user_name`!='$name' AND `id`!='$id'");

		return (mysqli_num_rows($result) == 1) ? true : false;

	}