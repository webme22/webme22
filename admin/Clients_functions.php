<?php

// $page = $start = 0;
// define(ITEMS_PER_PAGE, 20);


if (isset($_POST['get_address_by_client_id'])) {

    global $con;

    include("../connection.php");

    $client_id = $_POST['get_address_by_client_id'];

    $query = $con->query("SELECT * FROM `client_addresses` WHERE `client_id`='$client_id'");

    echo "<option value=''>choose </option>";
    while ($row = mysqli_fetch_assoc($query)) {
        $client_address_id = $row['client_address_id'];
        $region = $row['region'];
        $query_2 = $con->query("SELECT * FROM `regions` WHERE `region_id`='$region' LIMIT 1");
        $row_select = mysqli_fetch_array($query_2);
        $region_name_ar = $row_select['region_name_ar'];
        echo "<option value='{$client_address_id}'>{$region_name_ar}</option>";
    }
}
function count_orders($client_id) {

    global $con;

    if ($_SESSION['user_type'] == 1) {
        $query = $con->query("SELECT * FROM `orders` where `client_id`='$client_id' ");
    } else {
        
        $query = $con->query("SELECT * FROM `orders` where `client_id`='$client_id' and  `branch_id`='" . $_SESSION['branch_id'] . "'  ORDER BY `order_id` ASC");
    }


    $client_count = mysqli_num_rows($query);

    return $client_count;
}
function clientPhone($client_id) {
    global $con;
    $query_select = $con->query("SELECT * FROM `clients` WHERE `client_id`='" . $client_id . "' ORDER BY `client_id` LIMIT 1 ");
    $row_select = mysqli_fetch_array($query_select);
    $client_phone = $row_select['client_phone'];
    return $client_phone;
}

function add_client($client_first_name, $client_password, $client_email, $client_phone) {

    global $con;

    $con->query("INSERT INTO `clients` VALUES (null,'$client_first_name',
    '$client_password','$client_email','$client_phone','0','0','" . date("Y-m-d H:i:s") . "')");

    return mysqli_insert_id($con);
}

function add_client_address($temp) {

    global $con;
    for ($i = 0; $i < count($temp['itr']); $i++) {
        if (isset($temp['lat_' . $i]) && $temp['lat_' . $i] != '') {
            $con->query("INSERT INTO `client_addresses` VALUES (null,'" . $temp['lat_' . $i] . "','" . $temp['lang_' . $i] . "','" . $temp['region_' . $i] . "','" . $temp['block_' . $i] . "','" . $temp['road_' . $i] . "','" . $temp['building_' . $i] . "','" . $temp['flat_number_' . $i] . "','" . $temp['phone_' . $i] . "','" . $temp['notes_' . $i] . "','" . $temp['client_id'] . "' ,'" . date('Y-m-d H:i:s') . "')");
        }
    }

    return mysqli_insert_id($con);
}

function clientName($client_id) {
    global $con;
    $query_select = $con->query("SELECT * FROM `clients` WHERE `client_id`='" . $client_id . "' ORDER BY `client_id` LIMIT 1 ");
    $row_select = mysqli_fetch_array($query_select);
    $client_name = $row_select['client_name'];
    return $client_name;
}

function count_client_address($client_id) {

    global $con;

    $query = $con->query("SELECT * FROM `client_addresses` where `client_id`='$client_id' ORDER BY `client_address_id` DESC");


    $client_count = mysqli_num_rows($query);

    return $client_count;
}

function view_client_address($client_id) {

    global $con;

    $query = $con->query("SELECT * FROM `client_addresses` where `client_id`='$client_id' ORDER BY `client_address_id` DESC");

    $x = 1;

    while ($row = mysqli_fetch_assoc($query)) {
        $client_address_id = $row['client_address_id'];
        $client_id = $row['client_id'];
        $client_name = clientName($client_id);
        $region = $row['region'];
        $region_by_id = getRegionId($region);
        $region_name = $region_by_id[0];

        $block = $row['block'];
        $road = $row['road'];
        $building = $row['building'];
        $flat_number = $row['flat_number'];
        $client_phone = $row['client_phone'];
        $date = $row['date'];
        ?>
        <tr class="gradeX">
            <td><?php echo $x; ?></td>
            <td><?php echo $client_name; ?></td>
            <td><?php echo $region_name; ?></td>
            <td><?php echo $block; ?></td>
            <td><?php echo $road; ?></td>
            <td><?php echo $building; ?></td>
            <td><?php echo $flat_number; ?></td>
            <td><?php echo $client_phone; ?></td>



            <td><?php echo $date; ?></td>

            <td>     
                <a href="client_address_edit.php?client_address_Id=<?php echo $client_address_id; ?>" class="on-default"><i class="fa fa-pencil"></i></a>
            </td>
            <td class="actions">
                <a href="<?php echo $client_address_id; ?>" class="on-default remove-row" id="deleteParent"><i class="fa fa-trash-o"></i></a>
            </td>
        </tr>       
        <?php
        $x++;
    }

    return mysqli_insert_id($con);
}

function view_client($start) {

    global $con;
    $clients = array();

    $sql = "SELECT * FROM `clients` ORDER BY `client_id` DESC LIMIT $start, 20";
    // 
    // $sql.= $limit ? "LIMIT {$start},{$limit}" : "";
    $query = $con->query($sql);

    $x = 1;

    while ($row = mysqli_fetch_assoc($query)) {
        array_push($clients, $row);

        $x++;
    }

    return $clients;
}

function view_clients_by_search($search, $start) {

    global $con;
    $clients = array();

    $clientsCount = [];
    if($search == 1){
        
        $clientsCount = orders_num();
        
    } elseif($search == 2){
        
        $clientsCount = comments_num();
    
    } elseif($search == 3){
        
        $clientsCount = favs_num();
        
    } elseif($search == 4){
            
        $clientsCount = addresses_num();
        
    }

    
    arsort($clientsCount);
    $clientsCount = array_slice($clientsCount, $start, 20, true);
   
    foreach($clientsCount as $key=>$value){

            $query = $con->query("SELECT * FROM `clients` WHERE `client_id`='$key'");
            while ($row = mysqli_fetch_assoc($query)) {
                
                array_push($clients, $row);
                
            }

        
    }


    return $clients;
}

function orders_num(){
    global $con;
    $queryOne = $con->query("SELECT `client_id` FROM `clients`");
    $ordersArr = [];

    while($row = mysqli_fetch_assoc($queryOne)){
        
            $clientId = $row['client_id'];
            
            $query = $con->query("SELECT * FROM `orders` WHERE `client_id`='$clientId'");
            $ordersArr[$clientId] = mysqli_num_rows($query);
        
    }

    
    return $ordersArr;
    
}
function favs_num(){
    global $con;
    $queryOne = $con->query("SELECT `client_id` FROM `clients`");
    $favsArr = [];
    while($row = mysqli_fetch_assoc($queryOne)){

        $clientId = $row['client_id'];
        
        $query = $con->query("SELECT * FROM `client_fav` WHERE `client_id`='$clientId' ");
        $favsArr[$clientId] = mysqli_num_rows($query);
        
    }

    
    return $favsArr;
    
}

function addresses_num(){
    global $con;
    $queryOne = $con->query("SELECT `client_id` FROM `clients`");
    $addressesArr = [];
    while($row = mysqli_fetch_assoc($queryOne)){

        $clientId = $row['client_id'];
        
        $query = $con->query("SELECT * FROM `client_addresses` WHERE `client_id`='$clientId' ");
        $addressesArr[$clientId] = mysqli_num_rows($query);
        
    }

    
    return $addressesArr;
    
}

function comments_num(){
    global $con;
    $queryOne = $con->query("SELECT `client_id` FROM `clients`");
    $commentsArr = [];
    while($row = mysqli_fetch_assoc($queryOne)){

        $clientId = $row['client_id'];
        
        $query = $con->query("SELECT * FROM `sub_category_comments` WHERE `client_id`='$clientId' ");
        $commentsArr[$clientId] = mysqli_num_rows($query);
        
    }

    
    return $commentsArr;
}

if (isset($_POST['verify'])) {

    include("../connection.php");

    $verify = $_POST['verify'];

    $query = $con->query("UPDATE `clients` SET `client_verify`=1 WHERE `client_id`='$verify'");

    if ($query) {
        echo get_success("Verified Successfully  ");
    }
}

if (isset($_POST['cancel_verify'])) {

    include("../connection.php");

    $cancel_verify = $_POST['cancel_verify'];

    $query = $con->query("UPDATE `clients` SET `client_verify`=0 WHERE `client_id`='$cancel_verify'");

    if ($query) {
        echo get_success("تم إلغاء التفعيل بنجاح");
    }
}

if (isset($_POST['client'])) {

    include("../connection.php");

    $client = $_POST['client'];
    $query = $con->query("DELETE FROM `clients` WHERE `client_id`=' $client'");
    $queryA = $con->query("DELETE FROM `client_addresses` WHERE `client_id`=' $client'");
    $queryA = $con->query("DELETE FROM `cart` WHERE `client_id`=' $client'");
    $queryA = $con->query("DELETE FROM `client_fav` WHERE `client_id`=' $client'");
    $queryA = $con->query("DELETE FROM `sub_category_comments` WHERE `client_id`=' $client'");
    $queryB = $con->query("DELETE FROM `orders` WHERE `client_id`=' $client'");

    if ($query) {
        $img_path = dirname(__FILE__) . "/../uploads/clients/" . $client . '.jpg';
        $img_path_thumb = dirname(__FILE__) . "/../uploads/clients/thumbs/" . $client . '.jpg';
        if (file_exists($img_path)) {
            unlink($img_path);
        }
        if (file_exists($img_path_thumb)) {

            unlink($img_path_thumb);
        }
        echo get_success("Deleted Successfully  ");
    }
}
if (isset($_POST['del_client_address_id'])) {

    include("../connection.php");

    $client_address_id = $_POST['del_client_address_id'];

    $query = $con->query("DELETE FROM `client_addresses` WHERE `client_address_id`=' $client_address_id '");

    if ($query) {
        echo get_success("Deleted Successfully  ");
    }
}

function client_count() {

    global $con;
    
    $sql = "SELECT * FROM `clients` ORDER BY `client_id` ASC";
    
    $query = $con->query($sql);
    
    $client_count = mysqli_num_rows($query);

    return $client_count;
}

function client_addresses_count() {

    global $con;

    $query = $con->query("SELECT * FROM `client_addresses` ORDER BY `client_address_id` ASC");

    $client_addresses_count = mysqli_num_rows($query);

    return $client_addresses_count;
}

if (isset($_POST['client_id'])) {
    global $con;

    $client_id = $_POST['client_id'];
    $client_address = $con->query("SELECT * FROM `client_addresses` where client_id='$client_id' ORDER BY `client_address_id` ASC");
    $count = mysqli_num_rows($client_address);
    echo $count;
}
if (isset($_POST['search'])) {
    include("../connection.php");

    $client_name = $_POST['search'];

    $sql = "SELECT  * FROM `clients`";
    $sql.= " WHERE `client_verify`='1' ";
    if (isset($client_name) && $client_name != '') {
        $sql.= "AND `client_name` like '%$client_name%'";
    }
    $query = $con->query($sql);
    $x = 1;
    $count = mysqli_num_rows($query);

    if ($count > 0) {

        while ($row = mysqli_fetch_assoc($query)) {

            $data[] = array('value' => $row['client_id'], 'label' => $row['client_name']);
            $x ++;
        }
        echo json_encode($data);
        exit();
    } else {
        echo "null";
        exit();
    }
}





?>
