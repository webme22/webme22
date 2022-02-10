<?php
define('_DEFVAR', 1);
include("languages.php");
include_once("translation.php");
if(isset($_GET['type']) && isset($_GET['logged_family']) && isset($_GET['file_type'])){
    include("../db_class.php");

    $service = $_GET['type'];
    $family = $_GET['logged_family'];
    $file_type = $_GET['file_type'];
    $media = [];
    $media['data'] = [];

    $sql = "select * from familyMedia where family_type='$service' and file_type='$file_type' and family_id='$family'";
    $result1 = $con->query($sql);
    $media['count'] = mysqli_num_rows($result1);

    if($file_type == "PDF"){
        $result = $con->query($sql . " limit 6");
    } else {
        $result = $con->query($sql . " limit 4");
    }
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            array_push($media['data'], $row);
        }
    }

    echo json_encode($media);
}
if(isset($_GET['page']) && isset($_GET['pagination_service']) && isset($_GET['pagination_type']) && isset($_GET['logged_family_id'])){
    include("../db_class.php");
    include_once(__DIR__."/helpers.php");
    $service = $_GET['pagination_service'];
    $file_type = $_GET['pagination_type'];
    $family = $_GET['logged_family_id'];
    $page = $_GET['page'];
    $per_page = 8;
    $start = ($page-1) * $per_page;
    $media = [];
    $media['data'] = [];
    $sql = "select * from familyMedia where family_type='$service' and file_type='$file_type' and family_id='$family'";
    $count_sql = "select count(*) from familyMedia where family_type='$service' and file_type='$file_type' and family_id='$family'";
    $count_q = $con->query($count_sql);
    $row = $count_q->fetch_row();
    $media['all'] = $row[0] + 0;
    $media['page'] = $page + 0;
    $media['pages'] = ceil($row[0] / $per_page);
    $result = $con->query($sql . " limit $start, $per_page") or die(mysqli_error($con));
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            array_push($media['data'], $row);
        }
    }
    foreach ($media['data'] as $key => $item){
        $item['file'] = asset($item['file']);
        $item['name'] = db_trans($item, 'name');
        $item['description'] = db_trans($item, 'description');
        $media['data'][$key] = $item;
    }
    echo json_encode($media);
}
if(isset($_POST['fileId'])){
    include("../db_class.php");
    $file = $_POST['fileId'];
    $query = $con->query("delete from familyMedia where id='$file'");
    if($query){
        echo "File Deleted Successfully";
    }
    else {
        echo "Some error happened couldn't delete the file";
    }
}
