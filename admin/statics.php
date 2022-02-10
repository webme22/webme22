
<?php
$clients = $con->query("SELECT * FROM `clients` ");
$clients_num = mysqli_num_rows($clients);

$orders = $con->query("SELECT * FROM `orders`");
$orders_num = mysqli_num_rows($orders);

$regions = $con->query("SELECT * FROM `regions`");
$regions_num = mysqli_num_rows($regions);

$staff = $con->query("SELECT * FROM `staff`");
// $staff_num = mysqli_num_rows($staff);

$orders_accepted = $con->query("SELECT * FROM `orders` where `order_status`=1");
$orders_accepted_num = mysqli_num_rows($orders_accepted);

$orders_cancelled = $con->query("SELECT * FROM `orders` where `order_status`=2");
$orders_cancelled_num = mysqli_num_rows($orders_cancelled);

$complaints = $con->query("SELECT * FROM `complaints`");
$complaints_num = mysqli_num_rows($complaints);

$parent_cat = $con->query("SELECT * FROM `parent_categories`");
$parent_cat_num = mysqli_num_rows($parent_cat);

$sub_cat = $con->query("SELECT * FROM `sub_category` ORDER BY `id` ASC");
$sub_cat_count = mysqli_num_rows($sub_cat);

$sub_category_comments = $con->query("SELECT * FROM `sub_category_comments` ");
$sub_category_comments_count = mysqli_num_rows($sub_category_comments);

//revenue
$date = date("Y-m-d");
$revenue = $con->query("SELECT COUNT(*) FROM orders WHERE `order_status`='1' and `date` >= DATE_SUB(CURRENT_DATE(), INTERVAL 1 DAY)
");
$revenue_by_day = mysqli_num_rows($revenue);


$revenue_week = $con->query("SELECT * FROM orders WHERE `order_status`='1' and `date` > DATE_SUB(NOW(), INTERVAL 1 WEEK)");
$revenue_by_week = mysqli_num_rows($revenue_week);

$revenue_month = $con->query("SELECT * FROM orders WHERE `order_status`='1' and `date` > DATE_SUB(NOW(), INTERVAL 1 MONTH)");
$revenue_by_month = mysqli_num_rows($revenue_month);

$revenue_year = $con->query("SELECT * FROM orders WHERE `order_status`='1' and `date` > DATE_SUB(NOW(), INTERVAL 1 YEAR)");
$revenue_by_year = mysqli_num_rows($revenue_year);
?>
<div class="row">
    <div class="col-md-6 col-lg-3">
        <div class="widget-bg-color-icon card-box fadeInDown animated">
            <div class="bg-icon bg-icon-info pull-left">
                <i class="md md-attach-money text-info"></i>
            </div>
            <div class="text-right">
                <h3 class="text-dark"><b class="counter"><?php echo $clients_num; ?></b></h3>
                <p class="text-muted">عدد العملاء</p>
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
                <p class="text-muted">عدد الطلبات</p>
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
                <p class="text-muted">عدد الطلبات الموافق عليها</p>
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
                <p class="text-muted">عدد الطلبات المرفوضه</p>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="col-md-6 col-lg-3">
        <div class="widget-bg-color-icon card-box">
            <div class="bg-icon bg-icon-success pull-left">
                <i class="md md-remove-red-eye text-success"></i>
            </div>
            <div class="text-right">
                <h3 class="text-dark"><b class="counter"><?php echo $parent_cat_num; ?></b></h3>
                <p class="text-muted">عدد الاقسام الرئيسيه</p>
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
                <h3 class="text-dark"><b class="counter"><?php echo $sub_cat_count; ?></b></h3>
                <p class="text-muted">عدد الاقسام الفرعيه</p>
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
                <h3 class="text-dark"><b class="counter"><?php echo $sub_category_comments_count; ?></b></h3>
                <p class="text-muted">عدد التعليقات</p>
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
                <p class="text-muted">عدد المناطق</p>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <!-- <div class="col-md-6 col-lg-3">
        <div class="widget-bg-color-icon card-box">
            <div class="bg-icon bg-icon-success pull-left">
                <i class="md md-remove-red-eye text-success"></i>
            </div>
            <div class="text-right">
                <h3 class="text-dark"><b class="counter"><?php echo $staff_num; ?></b></h3>
                <p class="text-muted">Number of Employees</p>
            </div>
            <div class="clearfix"></div>
        </div>
    </div> -->
</div>

<div class="row">

    <div class="col-lg-4">
        <div class="card-box">
            <h4 class="text-dark header-title m-t-0 m-b-30">Total sales sales</h4>

            <div class="widget-chart text-center">
                <input class="knob" data-width="150" data-height="150" data-linecap=round data-fgColor="#fb6d9d" value="80" data-skin="tron" data-angleOffset="180" data-readOnly=true data-thickness=".15"/>
                <h5 class="text-muted m-t-20">Total sales during the day</h5>
                <h2 class="font-600"><?php echo $revenue_by_day; ?></h2>
                <ul class="list-inline m-t-15">
                    <li>
                        <h5 class="text-muted m-t-20">Total sales during the week</h5>
                        <h4 class="m-b-0"><?php echo $revenue_by_week; ?></h4>
                    </li>
                    <li>
                        <h5 class="text-muted m-t-20">Total sales during the month</h5>
                        <h4 class="m-b-0"><?php echo $revenue_by_month; ?></h4>
                    </li>
                    <li>
                        <h5 class="text-muted m-t-20">Total sales during the year</h5>
                        <h4 class="m-b-0"><?php echo $revenue_by_year; ?></h4>
                    </li>
                </ul>
            </div>
        </div>

    </div>

    <!-- col -->

    <div class="col-lg-8">
        <div class="card-box">
            <a href="order_view.php" class="pull-right btn btn-default btn-sm waves-effect waves-light">عرض كل الطلبات</a>
            <h4 class="text-dark header-title m-t-0">View recent requests</h4>
<!--            <p class="text-muted m-b-30 font-13">
                Use the button classes on an element.
            </p>-->

            <div class="table-responsive">
                <table class="table table-actions-bar m-b-0">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Client Name </th>
                            <th>Client Phone </th>
                            <th>Order Status </th>
                            <th style="min-width: 80px;">Order Date </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query_select = $con->query("SELECT * FROM `orders`  ORDER BY `order_id` DESC limit 5");
                        $x = 1;
                        while ($row = mysqli_fetch_assoc($query_select)) {
                            $order_id = $row['order_id'];
                            $client_id = $row['client_id'];
                            $order_status = $row['order_status'];
                            $client_address_id = $row['client_address_id'];
                            $date = $row['date'];
                            ?>
                            <tr class="gradeX">
                                <td><?php echo $x; ?></td>
                                <td><?php echo get_client_name_by_id($client_id); ?></td>
                                <td class="customFont"><?php echo get_client_phone_by_id($client_id); ?></td>
                                <td style="text-align:center;" class="mousta">
                                    <?php
                                    if ($order_status == 0) {
                                        echo '<div class="verifyMeTwo"><a>Not Approved</a>';
                                    } elseif ($order_status == 1) {
                                        echo '<div class="cancelVerifyMeTwo"><a>Approved</a>';
                                    } elseif ($order_status == 2) {
                                        echo '<div class="cancelVerifyMeTwo"><a>refused </a>';
                                    }
                                    ?>
                                </td>
                                <td class="customFont"><?php echo $date; ?></td>
                            </tr>		
                            <?php
                            $x++;
                        }
                        ?>



                    </tbody>
                </table>
            </div>

        </div>
    </div>
    <!-- end col -->




</div>
<!-- end row -->



