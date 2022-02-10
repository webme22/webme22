<?php

    include("languages.php");
    
    
    function view_additional_services($type) {

        global $con;
        
        if($type == 1){
            $genre = 'book';
        } elseif($type == 2){
            $genre = 'studio';
        } elseif($type == 3){
            $genre = 'magazine';
        } elseif($type == 4){
            $genre = 'account';
        }

        $query = $con->query("SELECT * FROM `features` where type='$genre' ORDER BY `id` DESC");
    
        $x = 1;
    
        while ($row = mysqli_fetch_assoc($query)) {
            $messages_id = $row['id'];
            
            $date = $row['date'];
            $name = $row['name'];
            $phone = $row['phone'];
            $message = $row['message'];
            $email = $row['email'];
            
            
            ?>
            <tr class="gradeX <?php echo $messages_id; ?>">
                <td><?php echo $x; ?></td>
                <td><?php
                    echo $name;
                    ?>
                </td>
                <td><?php
                    echo $email;
                    ?>
                </td>
                <td><?php echo $phone; ?></td>
                <td><?= $message ?></td>
                <td><?php echo $date; ?></td>
    
            </tr>		
            <?php
            $x++;
        }
    
        return mysqli_insert_id($con);
    }
    
    function view_subscriptions(){
        global $con;
        include("languages.php");

        $query = $con->query("SELECT * FROM `subscriptions` ORDER BY `id` DESC");
    
        $x = 1;
    
        while ($row = mysqli_fetch_assoc($query)) {
            $messages_id = $row['id'];
            
            $date = $row['date'];
            $email = $row['email'];
            $name = $row['name'];
            
            
            ?>
            <tr class="gradeX <?php echo $messages_id; ?>">
                <td><?php echo $x; ?></td>
                <td><?php
                    echo $name;
                    ?>
                </td>
                <td><?php
                    echo $email;
                    ?>
                </td>
                <td><?php echo $date; ?></td>
                <td>
					<a href="<?php echo $messages_id; ?>"  class="on-default remove-row" id="deleteParent"><i class="fa fa-trash-o"></i></a>
                </td>
            </tr>		
            <?php
            $x++;
        }
    
        return mysqli_insert_id($con);
    }
    
    function view_messages($lang, $type) {

        global $con;
        include("languages.php");

        $query = $con->query("SELECT * FROM `messages` where type='$type' ORDER BY `id` DESC");
    
        $x = 1;
    
        while ($row = mysqli_fetch_assoc($query)) {
            $messages_id = $row['id'];
            
            $date = $row['date'];
            $content = $row['content'];
            $viewed = $row['viewed'];
            $reply = $row['reply'];
            $client = $row['client_name'];
            $email = $row['client_email'];
            
            
            ?>
            <tr class="gradeX <?php echo $messages_id; ?>">
                <td><?php echo $x; ?></td>
                <?php if($type == 1){ ?>
                <td><?php
                    echo $client;
                    ?>
                </td>
                <?php } ?>
                <td><?php
                    echo $content;
                    ?>
                </td>
                <?php if($type == 1){ ?>
                <td><?php
                    if ($viewed == 0) {
                        echo "<span style='color:red'>{$languages[$lang]['notRead']} </span>";
                    } elseif ($viewed == 1) {
                        echo $languages[$lang]['read'];
                    } 
                    ?>
                </td>
                <td><?php
                
                    if(! empty($reply)){
                        
                        echo $reply;
                        
                    } else {
                        
                        echo "<span style='color:red'>{$languages[$lang]['NotAnswered']} </span>";
                                
                    }
                
                
                ?></td>
                <?php } ?>
                <td><?php echo $date; ?></td>

                <td class="actions">
                    <?php if($type == 1){ ?>
                    
                    <a href="message_details.php?messages_id=<?php echo $messages_id; ?>&lang=<?php echo $lang; ?>" class="on-default"><i class="fa fa-eye"></i></a>
                    
                    <a href="javascript:;" data-id="<?php echo $messages_id; ?>" data-client="<?php echo $email; ?>" class="sendmsg" id="sendParent"><i class="fa fa-send"></i></a>

                    <?php } ?>
                    <a href="<?php echo $messages_id; ?>" data-id="<?php echo $messages_id; ?>" class="on-default remove-row" id="deleteParent"><i class="fa fa-trash-o"></i></a>
                </td>
    
            </tr>		
            <?php
            $x++;
        }
    
        return mysqli_insert_id($con);
    }
    
    if(isset($_POST['message_id'])){
        include("../connection.php");
        include("languages.php");
        
        $message = $_POST['message_id'];
        $lang = $_POST['lang'];
        // var_dump($_POST); die();
        $delete = $con->query("delete from `messages` where `id`='$message'");
        
        if($delete){
            echo get_success($languages[$lang]["deleteMessage"]);
        }
        
        
    }

    if(isset($_POST['subscription_id'])){
        include("../connection.php");
        
        $subscription_id = $_POST['subscription_id'];
        $lang = $_POST['lang'];
        
        $delete = $con->query("delete from `subscriptions` where `id`='$subscription_id'");
        
        if($delete){
            echo get_success($languages[$lang]["deleteMessage"]);
        }
        
        
    }
    
    if(isset($_POST['email']) && isset($_POST['message']) && isset($_POST['content'])){
        
        include("../connection.php");
        include_once(__DIR__."/../../lib/Mailer.php");

        $message = $_POST['message'];
        $email = $_POST['email'];
        $content = $_POST['content'];
        $lang = $_POST['lang'];
        
        $update = $con->query("update `messages` set `reply`='$content' where `id`='$message'");
        $message_row = $con->query("SELECT * FROM `messages`  where `id`='$message'");
        $message_row = mysqli_fetch_assoc($message_row);

//        $subject = "Al Hamayel - Reply To Your Message";
//        $sendEmail = mail($email, $subject, $content, $headers);

        $mailer = new Mailer();
        $mailer->setVars(['user_name'=>$message_row['client_name'], 'content'=>$content]);
        $sendEmail = $mailer->sendMail([$email], "Reply To Your Message", 'message_reply.html', 'message_reply.txt');

        if($sendEmail){
            echo get_success($languages[$lang]["sendMessage"]);
        }
        
        
    }
    
