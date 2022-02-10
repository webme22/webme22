
<div class="topbar">
    <!-- LOGO -->
    <div class="topbar-left">
        <div class="<?= $lang == "ar" ? 'pull-right' : 'pull-left' ?> hidden-lg">
            <!-- class="button-menu-mobile open-left" -->
            <button class="button-menu-mobile" type="button" data-toggle="collapse" data-target="#cssmenu" aria-expanded="false" aria-controls="collapseExample">
                <i class="ion-navicon"></i>
            </button>
        </div>
        <div class="text-center <?= $lang == "ar" ? 'pull-right' : 'pull-left' ?>">
            <a href="index.php?lang=<?php echo $lang; ?>" class="logo"><?php echo $languages[$lang]["siteTitle"];   ?></a>
        </div>
    </div>

    <!-- Button mobile view to collapse sidebar menu -->
    <div class="navbar navbar-default" role="navigation">
        <div class="container">
            <div class="">


                <!-- Refresh Notification For (Orders Count) -->
                <!--<script>var auto_refresh = setInterval( function () { $('#refreshNotifications').load('refresh_count_orders.php').fadeIn("slow"); }, 10000);</script>-->							
                <ul class="nav navbar-nav navbar-right pull-right">
                    <div id="ordersViewed"></div>

                    <li class="dropdown hidden-xs ordersView" id="refreshNotifications">
                        <a href="#" data-target="#" class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="true">
                            <i class="icon-bell"></i> <?php // if (count_orders_notification() != 0) {      ?><span class="badge badge-xs badge-danger"><?php // echo count_orders_notification();      ?></span> <?php // }      ?></a>
                        <ul class="dropdown-menu dropdown-menu-lg">

                            <div id="">
                                <?php // if (count_orders_notification() != 0) { ?>
                                <li class="notifi-title"><span class="label label-default pull-right"><?php // echo count_orders_notification();      ?> جديد</span>طلبات</li>
                                <?php // } else { ?><li class="notifi-title">لا يوجد طلبات جديدة</li><?php // } ?>
                            </div>

                            <?php // latest_orders_notification(); ?>
                            <!--<li><a href="#" class="list-group-item text-right"><small class="font-600">عرض الطلبات</small></a></li>-->
                        </ul>
                    </li>

                    <?php
                    $query = $con->query("SELECT * FROM `users` WHERE `user_id`='" . $_SESSION['user_id'] . "' ORDER BY `user_id` DESC");
                    $x = 1;

                    $row = mysqli_fetch_assoc($query);

                    $user_name = $row['user_name'];
                    $user_image = $row['image'];
                    
                    ?>

                    <li class="hidden-xs"><a href="#" id="btn-fullscreen" class="waves-effect waves-light"><i class="icon-size-fullscreen"></i></a></li>
                    <li class="dropdown">
                        <a href="" class="dropdown-toggle profile" data-toggle="dropdown" aria-expanded="true"><img src="<?=asset($user_image)?>" alt="user-img" class="img-circle"> </a>
                        <ul class="dropdown-menu">
                            <li><a href="user_edit.php?userID=<?php echo $_SESSION["user_id"]; ?>"><i class="ti-user"></i> <?php echo $languages[$lang]["profile"];   ?>  </a></li>
                            <li>
                                <a href="<?php echo $redirected_url . $lang_param . (($lang == 'en')? 'ar' : 'en'); ?>">
                                    <img src="assets/images/language.svg" alt="Language icon" />
                                    <?php echo $languages[$lang]["lang"];?>
                                </a>
                            </li>
                            <li><a href="logout.php"><i class="ti-power-off"></i> <?php echo $languages[$lang]["logout"];   ?>  </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
