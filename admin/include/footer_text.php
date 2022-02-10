<?php 

if($lang == 'ar'){
    $dir = "text-right";
} else {
    $dir = "text-left";
}

?>
<footer class="footer text-left">
    <?php echo $languages[$lang]["siteTitle"];   ?>    - <?php echo date("Y"); ?>
</footer>