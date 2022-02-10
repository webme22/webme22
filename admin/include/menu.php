<ul>
    <li class="text-muted menu-title">القائمة الجانبية</li>

    <li class="">
        <a href="index.php" class="waves-effect"><i class="fa fa-home"></i> <span> الرئيسية </span> </a>
    </li>

    <li class="has_sub">
        <a href="parent_category_add.php" class="waves-effect">
            <i class="fa fa-map-pin"></i>
            <span class="label label-primary pull-right"><?php echo regions_count(); ?></span>
            <span>  المناطق </span>
        </a>
        <ul class="list-unstyled">
            <li><a href="regions_add.php"> <i class="fa fa-plus"></i> أضف منطقة جديدة </a></li>
            <li><a href="regions_view.php"> <i class="fa fa-eye"></i> عرض المناطق  </a></li>
        </ul>
        <a href="parent_category_add.php" class="waves-effect">
            <i class="fa fa-file"></i>
            <span class="label label-primary pull-right"><?php echo parent_cat_count(); ?></span>
            <span> الأصناف الرئيسية </span>
        </a>
        <ul class="list-unstyled">
            <li><a href="parent_category_add.php"> <i class="fa fa-plus"></i> أضف صنف جديد </a></li>
            <li><a href="parent_category_view.php"> <i class="fa fa-eye"></i> عرض الأصناف  </a></li>
        </ul>
        <a href="sub_category_add.php" class="waves-effect">
            <i class="fa fa-file-text"></i>
            <span class="label label-primary pull-right"><?php echo sub_cat_count(); ?></span>
            <span> الأصناف الفرعية </span>
        </a>
        <ul class="list-unstyled">
            <li><a href="sub_category_add.php"> <i class="fa fa-plus"></i> أضف صنف جديد </a></li>
            <li><a href="sub_category_view.php"> <i class="fa fa-eye"></i> عرض الأصناف </a></li>
        </ul>

        <a href="sub_category_customize_additions.php" class="waves-effect">
            <i class="fa fa-th-large"></i>
            <span class="label label-primary pull-right"><?php // echo sub_cat_count();                                 ?></span>
            <span> تخصيص الإضافات </span>
        </a>
        <ul class="list-unstyled">
            <li><a href="sub_category_customize_additions.php"> <i class="fa fa-plus"></i> تخصيص الإضافات </a></li>
            <li><a href="sub_category_customize_view.php"> <i class="fa fa-eye"></i> عرض تخصيص الإضافات </a></li>
        </ul>
        <a href="menue_view.php" class="waves-effect">
            <i class="fa fa-th-large"></i>
            <span class="label label-primary pull-right"><?php echo menu_count(); ?></span>
            <span> قائمة الطعام </span>
        </a>
        <ul class="list-unstyled">
            <li><a href="menue_add.php"> <i class="fa fa-plus"></i> إضافة قائمة الطعام </a></li>
            <li><a href="menue_view.php"> <i class="fa fa-eye"></i> قائمة الطعام </a></li>
        </ul>

        <a href="order_add.php" class="waves-effect">
            <i class="ti-user"></i>
            <span class="label label-primary pull-right"><?php echo 'جديد ' . new_order_count(); ?></span>
            <span> الطلبات </span>
        </a>
        <ul class="list-unstyled">

            <li><a href="order_add.php"> <i class="fa fa-plus"></i> أضف طلب جديد </a></li>
            <li><a href="order_view.php"> <i class="fa fa-eye"></i> عرض الطلبات </a></li>
            <li><a href="last_orders.php"> <i class="fa fa-lock"></i> الطلبات السابقة </a></li>
        </ul>
        <a href="client_add.php" class="waves-effect">
            <i class="fa fa-users"></i>
            <span class="label label-primary pull-right"><?php echo client_count(); ?></span>
            <span> العملاء </span>
        </a>
        <ul class="list-unstyled">
            <li><a href="client_add.php"> <i class="fa fa-plus"></i> أضف عميل جديد </a></li>
            <li><a href="client_view.php"> <i class="fa fa-eye"></i> عرض  العملاء </a></li>
        </ul>
        <a href="client_address.php" class="waves-effect">
            <i class="fa fa-users"></i>
            <span class="label label-primary pull-right"><?php echo client_addresses_count(); ?></span>
            <span> عناوين العملاء </span>
        </a>
        <ul class="list-unstyled">
            <li><a href="client_address_add.php"> <i class="fa fa-plus"></i> أضف عنوان جديد </a></li>
            <li><a href="client_address_view.php"> <i class="fa fa-eye"></i> عرض عناوين العملاء </a></li>
        </ul>
        <a href="user_add.php" class="waves-effect">
            <i class="fa fa-user"></i>
            <span class="label label-primary pull-right"><?php echo user_count(); ?></span>
            <span> الإدارة </span>
        </a>
        <ul class="list-unstyled">
            <li><a href="user_add.php"> <i class="fa fa-plus"></i> أضف مدير جديد </a></li>
            <li><a href="users_view.php"> <i class="fa fa-eye"></i> عرض المديرين </a></li>
        </ul>	
        <a href="staff_view.php" class="waves-effect">
            <i class="fa fa-file"></i>
            <span class="label label-primary pull-right"><?php echo staff_count(); ?></span>
            <span> فريق العمل </span>
        </a>
        <ul class="list-unstyled">
            <li><a href="staff_add.php"> <i class="fa fa-plus"></i> إضافة فريق العمل  </a></li>
            <li><a href="staff_view.php"> <i class="fa fa-eye"></i> عرض فريق العمل  </a></li>
        </ul>

        <a href="about_edit.php" class="waves-effect">
            <i class="fa fa-envelope"></i>
            <span class="label label-primary pull-right"><?php // echo suggest_count();                                 ?></span>
            <span> عن المطعم</span>
        </a>
        <ul class="list-unstyled">
            <li><a href="about_edit.php?id=1"> <i class="fa fa-eye"></i> عن المطعم </a></li>
        </ul>
        <a href="contact_edit.php" class="waves-effect">
            <i class="fa fa-home"></i>
            <span class="label label-primary pull-right"><?php // echo suggest_count();                                 ?></span>
            <span>  اتصل بنا </span>
        </a>	
        <ul class="list-unstyled">
            <li><a href="contact_edit.php"> <i class="fa fa-eye"></i> اتصل بنا </a></li>
        </ul>
        <a href="setting_add.php" class="waves-effect">
            <i class="fa fa-home"></i>
            <span class="label label-primary pull-right"><?php // echo suggest_count();                                 ?></span>
            <span> إعدادات الموقع </span>
        </a>	
        <ul class="list-unstyled">
            <li><a href="setting_edit.php"> <i class="fa fa-eye"></i> إعدادات الموقع </a></li>
        </ul>

        <a href="logout.php"><i class="fa fa-lock"></i><span> تسجيل خروج </span></a>
        <ul class="list-unstyled">
            <li><a href="logout.php"> <i class="fa fa-lock"></i> تسجيل خروج </a></li>
        </ul>
    </li>

</ul>
<div class="clearfix"></div>