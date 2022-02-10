
<?php
$clients = $con->query("SELECT * FROM `clients` where `client_verify`='1'");
$clients_num = mysqli_num_rows($clients);

$orders = $con->query("SELECT * FROM `orders`");
$orders_num = mysqli_num_rows($orders);

$regions = $con->query("SELECT * FROM `regions`");
$regions_num = mysqli_num_rows($regions);

$product = $con->query("SELECT * FROM `products`");
$product_num = mysqli_num_rows($product);

$orders_accepted = $con->query("SELECT * FROM `orders` where `order_status`=1");
$orders_accepted_num = mysqli_num_rows($orders_accepted);

$orders_cancelled = $con->query("SELECT * FROM `orders` where `order_status`=2");
$orders_cancelled_num = mysqli_num_rows($orders_cancelled);


$orders_cash = $con->query("SELECT * FROM `orders` where `payment`='cash'");
$orders_cash_num = mysqli_num_rows($orders_cash);


$orders_debit = $con->query("SELECT * FROM `orders` where `payment`='debit'");
$orders_debit_num = mysqli_num_rows($orders_debit);


$orders_credit = $con->query("SELECT * FROM `orders` where `payment`='credit'");
$orders_credit_num = mysqli_num_rows($orders_credit);


$parent_categories = $con->query("SELECT * FROM `parent_categories`");
$parent_categories_num = mysqli_num_rows($parent_categories);

$sub_categories = $con->query("SELECT * FROM `sub_categories`");
$sub_categories_num = mysqli_num_rows($sub_categories);

$product = $con->query("SELECT * FROM `products`");
$product_num = mysqli_num_rows($product);


$product_comments = $con->query("SELECT * FROM `product_comments`");
$comments_num = mysqli_num_rows($product_comments);


$users = $con->query("SELECT * FROM `users` ");
$users_count = mysqli_num_rows($users);
?>
<div class="row">
    <div class="col-md-6 col-lg-3">
        <div class="widget-bg-color-icon card-box fadeInDown animated">
            <div class="bg-icon bg-icon-info pull-left">
                <i class="md md-attach-money text-info"></i>
            </div>
            <div class="text-right">
                <h3 class="text-dark"><b class="counter"><?php echo $clients_num; ?></b></h3>
                <p class="text-muted">عدد العملاء </p>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="widget-bg-color-icon card-box">
            <div class="bg-icon bg-icon-pink pull-left">
                <i class="md md-add-shopping-cart text-pink"></i>
            </div>
            <div class="text-right">
                <h3 class="text-dark"><b class="counter"><?php echo $orders_num; ?></b></h3>
                <p class="text-muted">إجمالي عدد الطلبات  </p>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="widget-bg-color-icon card-box">
            <div class="bg-icon bg-icon-pink pull-left">
                <i class="md md-add-shopping-cart text-pink"></i>
            </div>
            <div class="text-right">
                <h3 class="text-dark"><b class="counter"><?php echo $orders_accepted_num; ?></b></h3>
                <p class="text-muted">إجمالي عدد الطلبات الموافق عليها</p>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="widget-bg-color-icon card-box">
            <div class="bg-icon bg-icon-pink pull-left">
                <i class="md md-add-shopping-cart text-pink"></i>
            </div>
            <div class="text-right">
                <h3 class="text-dark"><b class="counter"><?php echo $orders_cancelled_num; ?></b></h3>
                <p class="text-muted">إجمالي عدد الطلبات  تم رفضها</p>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
                    <div class="clearfix"></div>

    <div class="col-md-6 col-lg-3">
        <div class="widget-bg-color-icon card-box">
            <div class="bg-icon bg-icon-pink pull-left">
                <i class="md md-add-shopping-cart text-pink"></i>
            </div>
            <div class="text-right">
                <h3 class="text-dark"><b class="counter"><?php echo $orders_cash_num; ?></b></h3>
                <p class="text-muted">إجمالي عدد الطلبات  الكاش </p>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="widget-bg-color-icon card-box">
            <div class="bg-icon bg-icon-pink pull-left">
                <i class="md md-add-shopping-cart text-pink"></i>
            </div>
            <div class="text-right">
                <h3 class="text-dark"><b class="counter"><?php echo $orders_debit_num; ?></b></h3>
                <p class="text-muted">إجمالي عدد الطلبات  الديبت </p>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="widget-bg-color-icon card-box">
            <div class="bg-icon bg-icon-pink pull-left">
                <i class="md md-add-shopping-cart text-pink"></i>
            </div>
            <div class="text-right">
                <h3 class="text-dark"><b class="counter"><?php echo $orders_credit_num; ?></b></h3>
                <p class="text-muted">إجمالي عدد الطلبات الكريديت </p>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="widget-bg-color-icon card-box">
            <div class="bg-icon bg-icon-success pull-left">
                <i class="md md-remove-red-eye text-success"></i>
            </div>
            <div class="text-right">
                <h3 class="text-dark"><b class="counter"><?php echo $regions_num; ?></b></h3>
                <p class="text-muted">عدد  المناطق</p>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="widget-bg-color-icon card-box">
            <div class="bg-icon bg-icon-success pull-left">
                <i class="md md-remove-red-eye text-success"></i>
            </div>
            <div class="text-right">
                <h3 class="text-dark"><b class="counter"><?php echo $sub_categories_num; ?></b></h3>
                <p class="text-muted">عدد الأقسام الفرعية  </p>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="widget-bg-color-icon card-box">
            <div class="bg-icon bg-icon-success pull-left">
                <i class="md md-remove-red-eye text-success"></i>
            </div>
            <div class="text-right">
                <h3 class="text-dark"><b class="counter"><?php echo $parent_categories_num; ?></b></h3>
                <p class="text-muted">عدد الأقسام الرئيسية  </p>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="widget-bg-color-icon card-box">
            <div class="bg-icon bg-icon-success pull-left">
                <i class="md md-remove-red-eye text-success"></i>
            </div>
            <div class="text-right">
                <h3 class="text-dark"><b class="counter"><?php echo $product_num; ?></b></h3>
                <p class="text-muted">عدد  المنتجات </p>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="widget-bg-color-icon card-box">
            <div class="bg-icon bg-icon-success pull-left">
                <i class="md md-remove-red-eye text-success"></i>
            </div>
            <div class="text-right">
                <h3 class="text-dark"><b class="counter"><?php echo $comments_num; ?></b></h3>
                <p class="text-muted">عدد  التعليقات </p>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-3">
        <div class="widget-bg-color-icon card-box">
            <div class="bg-icon bg-icon-success pull-left">
                <i class="md md-remove-red-eye text-success"></i>
            </div>
            <div class="text-right">
                <h3 class="text-dark"><b class="counter"><?php echo $users_count; ?></b></h3>
                <p class="text-muted">عدد  المستخدمين </p>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>




