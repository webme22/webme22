<div id="cssmenu" class="collapse" aria-expanded="true" style="height: 0">
    <ul>
        <li id="item1" class="active">
            <a href="index.php?lang=<?php echo $lang;  ?>">
                <img src="assets/images/home.svg" alt="Home icon" />
                <span><?php echo $languages[$lang]["home"];  ?></span>
            </a>
        </li>

        <li id="item33" class="has-sub">
            <a href="analytics.php?lang=<?php echo $lang; ?>">
                <img src="assets/images/analytics.svg" alt="Analytics icon" />
                <span> <?php  echo $languages[$lang]["analytics"]; ?></span>
            </a>
        </li>

        <li id="item31" class="has-sub">
            <a href="families_view.php?lang=<?php echo $lang; ?>">
                <img src="assets/images/family.svg" alt="Families icon" />
                <span> <?php  echo $languages[$lang]["families"]; ?></span>
            </a>
        </li>

        <li id="item3" class="has-sub">
            <input type="checkbox" name="list-item" id="plans" hidden>
            <label for="plans">
                <img src="assets/images/plans.svg" alt="Plans icon" />
                <span> <?php  echo $languages[$lang]["plans"]; ?> </span>
                <span class="expand-item"></span>
            </label>
            <ul class="has-sub">
                <li>
                    <a href="plan_add.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/form.svg" alt="Form icon" />
                        <span><?php  echo $languages[$lang]["addPlan"];  ?></span>
                    </a>
                </li>
                <li>
                    <a href="plans_view.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/table.svg" alt="Table icon" />
                        <span><?php echo $languages[$lang]["viewPlans"];    ?></span>
                    </a>
                </li>
                <li>
                    <a href="payments_view.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/plans.svg" alt="Plans icon" />
                        <span><?php echo $languages[$lang]["payments"];    ?></span>
                    </a>
                </li>
            </ul>
            </li>

        <li id="item5" class="has-sub">
            <input type="checkbox" name="list-item" id="contries" hidden>
            <label for="contries">
                <img src="assets/images/countries.svg" alt="Countries icon" />
                <span> <?php  echo $languages[$lang]["countries"]; ?> </span>
                <span class="expand-item"></span>
            </label>
            <ul class="has-sub">
                <li>
                    <a href="country_add.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/form.svg" alt="Form icon" />
                        <span><?php  echo $languages[$lang]["addCountry"];  ?></span>
                    </a>
                </li>
                <li>
                    <a href="countries_view.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/table.svg" alt="Table icon" />
                        <span><?php echo $languages[$lang]["viewCountries"];    ?></span>
                    </a>
                </li>
            </ul>
        </li>

        <li id="item51" class="has-sub">
            <input type="checkbox" name="list-item" id="reviews" hidden>
            <label for="reviews">
                <img src="assets/images/reviews.svg" alt="Reviews icon" />
                <span> <?php  echo $languages[$lang]["reviews"]; ?> </span>
                <span class="expand-item"></span>
            </label>
            <ul class="has-sub">
                <li>
                    <a href="review_add.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/form.svg" alt="Form icon" />
                        <span><?php  echo $languages[$lang]["addReview"];  ?></span>
                    </a>
                </li>
                <li>
                    <a href="reviews_view.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/table.svg" alt="Table icon" />
                        <span><?php echo $languages[$lang]["viewReviews"];    ?></span>
                    </a>
                </li>
            </ul>
        </li>

        <li id="item55" class="has-sub">
            <input type="checkbox" name="list-item" id="about" hidden>
            <label for="about">
                <img src="assets/images/about.svg" alt="About icon" />
                <span> <?php  echo $languages[$lang]["about"]; ?> </span>
                <span class="expand-item"></span>
            </label>
            <ul class="has-sub">
                <li>
                    <a href="about_add.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/form.svg" alt="Form icon" />
                        <span><?php  echo $languages[$lang]["addAbout"];  ?></span>
                    </a>
                </li>
                <li>
                    <a href="about_view.php?lang=<?php echo $lang; ?>&flag=1">
                        <img src="assets/images/table.svg" alt="Table icon" />
                        <span><?php echo $languages[$lang]["viewAbout"];    ?></span>
                    </a>
                </li>
            </ul>
        </li>

        <li id="item50" class="has-sub">
            <input type="checkbox" name="list-item" id="services" hidden>
            <label for="services">
                <img src="assets/images/services.svg" alt="Services icon" />
                <span> <?php  echo $languages[$lang]["services"]; ?> </span>
                <span class="expand-item"></span>
            </label>
            <ul class="has-sub">
                <li>
                    <a href="service_edit.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/form.svg" alt="Form icon" />
                        <span><?php  echo $languages[$lang]["editService"];  ?></span>
                    </a>
                </li>
                <li>
                    <a href="service_view.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/table.svg" alt="Table icon" />
                        <span><?php echo $languages[$lang]["viewService"];    ?></span>
                    </a>
                </li>
            </ul>
        </li>

        <li id="item53" class="has-sub">
            <input type="checkbox" name="list-item" id="questions" hidden>
            <label for="questions">
                <img src="assets/images/questions.svg" alt="Questions icon" />
                <span> <?php  echo $languages[$lang]["questions"]; ?> </span>
                <span class="expand-item"></span>
            </label>
            <ul class="has-sub">
                <li>
                    <a href="category_add.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/form.svg" alt="Form icon" />
                        <span><?php  echo $languages[$lang]["add_category"];  ?></span>
                    </a>
                </li>
                <li>
                    <a href="categories_view.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/table.svg" alt="Table icon" />
                        <span><?php echo $languages[$lang]["view_categories"];    ?></span>
                    </a>
                </li>
                <li>
                    <a href="question_add.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/form.svg" alt="Form icon" />
                        <span><?php  echo $languages[$lang]["addQuestion"];  ?></span>
                    </a>
                </li>
                <li>
                    <a href="questions_view.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/table.svg" alt="Table icon" />
                        <span><?php echo $languages[$lang]["viewQuestions"];    ?></span>
                    </a>
                </li>
            </ul>
        </li>

        <li id="item54" class="has-sub">
            <input type="checkbox" name="list-item" id="how" hidden>
            <label for="how">
                <img src="assets/images/how-it-works.svg" alt="How it works icon" />
                <span> <?php  echo $languages[$lang]["how_it_works"]; ?> </span>
                <span class="expand-item"></span>
            </label>
            <ul class="has-sub">
                <li>
                    <a href="how_it_works_add.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/form.svg" alt="Form icon" />
                        <span><?php  echo $languages[$lang]["add_item"];  ?></span>
                    </a>
                </li>
                <li>
                    <a href="how_it_works_view.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/table.svg" alt="Table icon" />
                        <span><?php echo $languages[$lang]["view_items"];    ?></span>
                    </a>
                </li>
            </ul>
        </li>



        <!--<li id="item2" class="has-sub">-->
        <!--    <a href="#"><span> المنتجات</span></a>-->
        <!--    <ul class="has-sub">-->
        <!--        <li><a href="products_add.php"><span>أضف منتج</span></a></li>-->
        <!--        <li><a href="products_view.php"><span>عرض المنتجات</span></a></li>-->
        <!--    </ul>-->
        <!--</li>-->
        <!--<li id="item20" class="has-sub">-->
        <!--    <a href="#"><span> العروض</span></a>-->
        <!--    <ul class="has-sub">-->
        <!--        <li><a href="offer_add.php"><span>أضف عرض</span></a></li>-->
        <!--        <li><a href="offer_view.php"><span>عرض العروض</span></a></li>-->
        <!--    </ul>-->
        <!--</li>-->
        <!--<li id="item202" class="has-sub">-->
        <!--    <a href="#"><span> أحدث المنتجات</span></a>-->
        <!--    <ul class="has-sub">-->
        <!--        <li><a href="latest_add.php"><span>أحدث المنتجات </span></a></li>-->
        <!--        <li><a href="latest_view.php"><span>عرض الكل </span></a></li>-->
        <!--    </ul>-->
        <!--</li>-->
        <!--<li id="item5" class="has-sub">-->
        <!--    <a href="#"><span> السلايدر</span></a>-->
        <!--    <ul class="has-sub">-->
        <!--        <li><a href="slider_add.php"><span>أضف سلايدر </span></a></li>-->
        <!--        <li><a href="slider_view.php"><span>عرض السلايدرز</span></a></li>-->
        <!--    </ul>-->
        <!--</li>-->


        <!--<li id="item6" class="has-sub">-->
        <!--    <a href="#"><span>الطلبات</span></a>-->
        <!--    <ul class="has-sub">-->
        <!--        <li><a href="order_view.php"><span>عرض الطلبات الحالية</span></a></li>-->
        <!--        <li><a href="last_orders.php"><span>عرض الطلبات السابقة</span></a></li>-->
        <!--    </ul>-->
        <!--</li>-->



        <li id="item7" class="has-sub">
            <a href="clients_view.php?lang=<?php echo $lang; ?>">
                <img src="assets/images/clients.svg" alt="Clients icon" />
                <span><?php echo $languages[$lang]["clients"];   ?></span>
            </a>
        </li>

        <li id="item103" class="has-sub">
            <input type="checkbox" name="list-item" id="msgs" hidden>
            <label for="msgs">
                <img src="assets/images/messages.svg" alt="Messages icon" />
                <span><?php echo $languages[$lang]["messages"];    ?></span>
                <span class="expand-item"></span>
            </label>
            <ul class="has-sub">
                <li>
                    <a href="add_message.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/form.svg" alt="Form icon" />
                        <span><?php  echo $languages[$lang]["newMessage"];  ?></span>
                    </a>
                </li>
                <li>
                    <a href="messages_view.php?lang=<?php echo $lang; ?>&type=1">
                        <img src="assets/images/table.svg" alt="Table icon" />
                        <span><?php echo $languages[$lang]["viewMessages"];    ?></span>
                    </a>
                </li>
                <li>
                    <a href="subscriptions_view.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/table.svg" alt="Table icon" />
                        <span><?php echo $languages[$lang]["subscriptions"];    ?></span>
                    </a>
                </li>
            </ul>

        </li>

        <li id="item105" class="has-sub">
            <input type="checkbox" name="list-item" id="marketing" hidden>
            <label for="marketing">
                <img src="assets/images/marketing.svg" alt="Marketing icon" />
                <span><?php echo $languages[$lang]["marketing"];    ?></span>
                <span class="expand-item"></span>
            </label>
            <ul class="has-sub">
                <li>
                    <a href="group_add.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/form.svg" alt="Form icon" />
                        <span><?php  echo $languages[$lang]["group_add"];  ?></span>
                    </a>
                </li>
                <li>
                    <a href="groups_view.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/table.svg" alt="Table icon" />
                        <span><?php echo $languages[$lang]["groups_view"];    ?></span>
                    </a>
                </li>
                <li>
                    <a href="<?=$siteUrl.$RELATIVE_PATH?>cpanel">
                        <img src="assets/images/table.svg" alt="Table icon" />
                        <span><?php echo $languages[$lang]["mail_task"];    ?></span>
                    </a>
                </li>
            </ul>

        </li>

        <li id="item71" class="has-sub">
            <a href="setting.php?lang=<?php echo $lang; ?>">
                <img src="assets/images/settings.svg" alt="Settings icon" />
                <span><?php echo $languages[$lang]["setting"];   ?></span>
            </a>
        </li>

        <li id="item72" class="has-sub">
            <a href="contact.php?lang=<?php echo $lang; ?>">
            <img src="assets/images/contact.svg" alt="Contact icon" />
            <span><?php echo $languages[$lang]["contact"];   ?></span>
            </a>
        </li>

        <li id="item75" class="has-sub">
            <a href="terms.php?lang=<?php echo $lang; ?>">
                <img src="assets/images/terms-and-conditions.svg" alt="Terms and Conditions icon" />
                <span><?php echo $languages[$lang]["terms"];   ?></span>
            </a>
        </li>

        <li id="item77" class="has-sub">
            <a href="additional_services.php?lang=<?php echo $lang; ?>&type=1">
                <img src="assets/images/services.svg" alt="Additional services icon" />
                <span><?php echo $languages[$lang]["additionalServices"];   ?></span>
            </a>
        </li>

        <!--<li id="item73" class="has-sub">-->
        <!--    <a href="#"><span><?php echo $languages[$lang]["footer"];   ?></span></a>-->
        <!--     <ul class="has-sub">-->
        <!--        <li><a href="subscription_view.php?lang=<?php echo $lang; ?>"><span><?php  echo $languages[$lang]["subscriptions"];  ?></span></a></li>-->
        <!--        <li><a href="footer.php?lang=<?php echo $lang; ?>"><span><?php echo $languages[$lang]["footer"];    ?></span></a></li>-->
        <!--    </ul>-->
        <!--</li>-->


        <!--<li id="item8" class="has-sub">-->
        <!--    <a href="#"><span>المناطق</span></a>-->
        <!--    <ul class="has-sub">-->
        <!--        <li><a href="regions_add.php"><span>أضف منطقة </span></a></li>-->
        <!--        <li><a href="regions_view.php"><span>عرض المناطق</span></a></li>-->
        <!--    </ul>-->
        <!--</li>	-->

        <!--<li id="item10" class="has-sub">-->
        <!--    <a href="complaints_view.php"><span>الشكاوى والإقتراحات</span></a>-->
        <!--</li>-->


        <!--<li id="item9" class="has-sub">-->
        <!--    <a href="about_edit.php?id=1"><span>عن التطبيق</span></a>-->
        <!--</li>-->

        <!--<li id="item99" class="has-sub">-->
        <!--    <a href="contact_edit.php"><span>أتصل بنا </span></a>-->
        <!--</li>-->


        <!--<li id="item110" class="has-sub"><a href="product_comments_view.php"><span>التعليقات</span></a></li>-->
        <!--    <li id="item51" class="has-sub">-->
        <!--        <a href="#"><span>التقارير</span></a>-->
        <!--        <ul class="has-sub">-->
        <!--            <li><a href="edit_products_report.php"><span>تقرير المنتجات المعدلة </span></a></li>-->

        <!--            <li><a href="financial_report.php"><span>تقرير مالي بالتاريخ</span></a></li>-->
        <!--            <li><a href="select_financial_report.php"><span>اختر نوع التقرير</span></a></li>-->
        <!--            <li><a href="payment.php"><span> تقرير الدفع المالي </span></a></li>-->
        <!--            <li><a href="average_cost_report.php"><span>تقرير متوسط الشيك  </span></a></li>-->

        <!--            <li><a href="clients_most_order_report.php" target="_blank"><span> العملاء الأكثر طلبا   </span></a></li>-->
        <!--            <li><a href="sub_cats_high_rate_report.php" target="_blank"><span>الأصناف الأعلي تقييما   </span></a></li>-->
        <!--            <li><a href="subcats_most_paid_report.php" target="_blank"><span>   الأصناف الأكثر مبيعا   </span></a></li>-->
        <!--            <li><a href="client_report.php"><span>  تقرير طلبات العميل  </span></a></li>-->

        <!--        </ul>-->
        <!--    </li>        -->
        <!--<li id="item102" class="has-sub"><a href="setting_edit.php"><span>إعدادات عامة</span></a></li>-->

        <!--<li id="item11" class="has-sub"><a href="statistics.php"><span>الإحصائيات</span></a></li>-->

        <li id="item12" class="has-sub">
            <input type="checkbox" name="list-item" id="clients" hidden>
            <label for="clients">
                <img src="assets/images/clients.svg" alt="Clients icon" />
                <span><?php echo $languages[$lang]["managers"];   ?></span>
                <span class="expand-item"></span>
            </label>
            <ul class="has-sub">
                <li>
                    <a href="user_add.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/form.svg" alt="Form icon" />
                        <span> <?php echo $languages[$lang]["addManager"];   ?></span>
                    </a>
                </li>
                <li>
                    <a href="users_view.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/table.svg" alt="Table icon" />
                        <span><?php echo $languages[$lang]["viewManagers"];   ?></span>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="logout.php">
                <img src="assets/images/logout.svg" alt="Logout icon" />
                <span><?php echo $languages[$lang]["logout"];      ?></span>
            </a>
        </li>
        <!-- class="changeLanguage" -->
    </ul>
</div>
<div class="col-lg-2 hidden-xs visible-lg navbar-default lg-sidebar">
    <ul>
        <li id="item1" class="active">
        <a href="index.php?lang=<?php echo $lang;  ?>">
            <img src="assets/images/home.svg" alt="Home icon" />
            <span><?php echo $languages[$lang]["home"];  ?></span>
        </a>
        </li>

        <li id="item33" class="has-sub">
        <a href="analytics.php?lang=<?php echo $lang; ?>">
            <img src="assets/images/analytics.svg" alt="Analytics icon" />
            <span> <?php  echo $languages[$lang]["analytics"]; ?></span>
        </a>
        </li>

        <li id="item31" class="has-sub">
        <a href="families_view.php?lang=<?php echo $lang; ?>">
            <img src="assets/images/family.svg" alt="Families icon" />
            <span> <?php  echo $languages[$lang]["families"]; ?></span>
        </a>
        </li>

        <li id="item3" class="has-sub">
        <input type="checkbox" name="list-item" id="plans-lg" hidden>
        <label for="plans-lg">
            <img src="assets/images/plans.svg" alt="Plans icon" />
            <span> <?php  echo $languages[$lang]["plans"]; ?> </span>
            <span class="expand-item"></span>
        </label>
        <ul class="has-sub">
            <li>
            <a href="plan_add.php?lang=<?php echo $lang; ?>">
                <img src="assets/images/form.svg" alt="Form icon" />
                <span><?php  echo $languages[$lang]["addPlan"];  ?></span>
            </a>
            </li>
            <li>
            <a href="plans_view.php?lang=<?php echo $lang; ?>">
                <img src="assets/images/table.svg" alt="Table icon" />
                <span><?php echo $languages[$lang]["viewPlans"];    ?></span>
            </a>
            </li>
            <li>
            <a href="payments_view.php?lang=<?php echo $lang; ?>">
                <img src="assets/images/plans.svg" alt="Plans icon" />
                <span><?php echo $languages[$lang]["payments"];    ?></span>
            </a>
            </li>
        </ul>
        </li>

        <li id="item5" class="has-sub">
        <input type="checkbox" name="list-item" id="contries-lg" hidden>
        <label for="contries-lg">
            <img src="assets/images/countries.svg" alt="Countries icon" />
            <span> <?php  echo $languages[$lang]["countries"]; ?> </span>
            <span class="expand-item"></span>
        </label>
        <ul class="has-sub">
            <li>
            <a href="country_add.php?lang=<?php echo $lang; ?>">
                <img src="assets/images/form.svg" alt="Form icon" />
                <span><?php  echo $languages[$lang]["addCountry"];  ?></span>
            </a>
            </li>
            <li>
            <a href="countries_view.php?lang=<?php echo $lang; ?>">
                <img src="assets/images/table.svg" alt="Table icon" />
                <span><?php echo $languages[$lang]["viewCountries"];    ?></span>
            </a>
            </li>
        </ul>
        </li>

        <li id="item51" class="has-sub">
        <input type="checkbox" name="list-item" id="reviews-lg" hidden>
        <label for="reviews-lg">
            <img src="assets/images/reviews.svg" alt="Reviews icon" />
            <span> <?php  echo $languages[$lang]["reviews"]; ?> </span>
            <span class="expand-item"></span>
        </label>
        <ul class="has-sub">
            <li>
            <a href="review_add.php?lang=<?php echo $lang; ?>">
                <img src="assets/images/form.svg" alt="Form icon" />
                <span><?php  echo $languages[$lang]["addReview"];  ?></span>
            </a>
            </li>
            <li>
            <a href="reviews_view.php?lang=<?php echo $lang; ?>">
                <img src="assets/images/table.svg" alt="Table icon" />
                <span><?php echo $languages[$lang]["viewReviews"];    ?></span>
            </a>
            </li>
        </ul>
        </li>

        <li id="item55" class="has-sub">
            <input type="checkbox" name="list-item" id="about-lg" hidden>
            <label for="about-lg">
                <img src="assets/images/about.svg" alt="About icon" />
                <span> <?php  echo $languages[$lang]["about"]; ?> </span>
                <span class="expand-item"></span>
            </label>
            <ul class="has-sub">
                <li>
                    <a href="about_add.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/form.svg" alt="Form icon" />
                        <span><?php  echo $languages[$lang]["addAbout"];  ?></span>
                    </a>
                </li>
                <li>
                    <a href="about_view.php?lang=<?php echo $lang; ?>&flag=1">
                        <img src="assets/images/table.svg" alt="Table icon" />
                        <span><?php echo $languages[$lang]["viewAbout"];    ?></span>
                    </a>
                </li>
            </ul>
        </li>

        <li id="item50" class="has-sub">
            <input type="checkbox" name="list-item" id="services-lg" hidden>
            <label for="services-lg">
                <img src="assets/images/services.svg" alt="Services icon" />
                <span> <?php  echo $languages[$lang]["services"]; ?> </span>
                <span class="expand-item"></span>
            </label>
            <ul class="has-sub">
                <li>
                    <a href="service_edit.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/form.svg" alt="Form icon" />
                        <span><?php  echo $languages[$lang]["editService"];  ?></span>
                    </a>
                </li>
                <li>
                    <a href="service_view.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/table.svg" alt="Table icon" />
                        <span><?php echo $languages[$lang]["viewService"];    ?></span>
                    </a>
                </li>
            </ul>
        </li>

        <li id="item53" class="has-sub">
            <input type="checkbox" name="list-item" id="questions-lg" hidden>
            <label for="questions-lg">
                <img src="assets/images/questions.svg" alt="Questions icon" />
                <span> <?php  echo $languages[$lang]["questions"]; ?> </span>
                <span class="expand-item"></span>
            </label>
            <ul class="has-sub">
                <li>
                    <a href="category_add.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/form.svg" alt="Form icon" />
                        <span><?php  echo $languages[$lang]["add_category"];  ?></span>
                    </a>
                </li>
                <li>
                    <a href="categories_view.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/table.svg" alt="Table icon" />
                        <span><?php echo $languages[$lang]["view_categories"];    ?></span>
                    </a>
                </li>
                <li>
                    <a href="question_add.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/form.svg" alt="Form icon" />
                        <span><?php  echo $languages[$lang]["addQuestion"];  ?></span>
                    </a>
                </li>
                <li>
                    <a href="questions_view.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/table.svg" alt="Table icon" />
                        <span><?php echo $languages[$lang]["viewQuestions"];    ?></span>
                    </a>
                </li>
            </ul>
        </li>

        <li id="item54" class="has-sub">
            <input type="checkbox" name="list-item" id="how-lg" hidden>
            <label for="how-lg">
                <img src="assets/images/how-it-works.svg" alt="How it works icon" />
                <span> <?php  echo $languages[$lang]["how_it_works"]; ?> </span>
                <span class="expand-item"></span>
            </label>
            <ul class="has-sub">
                <li>
                    <a href="how_it_works_add.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/form.svg" alt="Form icon" />
                        <span><?php  echo $languages[$lang]["add_item"];  ?></span>
                    </a>
                </li>
                <li>
                    <a href="how_it_works_view.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/table.svg" alt="Table icon" />
                        <span><?php echo $languages[$lang]["view_items"];    ?></span>
                    </a>
                </li>
            </ul>
        </li>



        <!--<li id="item2" class="has-sub">-->
        <!--    <a href="#"><span> المنتجات</span></a>-->
        <!--    <ul class="has-sub">-->
        <!--        <li><a href="products_add.php"><span>أضف منتج</span></a></li>-->
        <!--        <li><a href="products_view.php"><span>عرض المنتجات</span></a></li>-->
        <!--    </ul>-->
        <!--</li>-->
        <!--<li id="item20" class="has-sub">-->
        <!--    <a href="#"><span> العروض</span></a>-->
        <!--    <ul class="has-sub">-->
        <!--        <li><a href="offer_add.php"><span>أضف عرض</span></a></li>-->
        <!--        <li><a href="offer_view.php"><span>عرض العروض</span></a></li>-->
        <!--    </ul>-->
        <!--</li>-->
        <!--<li id="item202" class="has-sub">-->
        <!--    <a href="#"><span> أحدث المنتجات</span></a>-->
        <!--    <ul class="has-sub">-->
        <!--        <li><a href="latest_add.php"><span>أحدث المنتجات </span></a></li>-->
        <!--        <li><a href="latest_view.php"><span>عرض الكل </span></a></li>-->
        <!--    </ul>-->
        <!--</li>-->
        <!--<li id="item5" class="has-sub">-->
        <!--    <a href="#"><span> السلايدر</span></a>-->
        <!--    <ul class="has-sub">-->
        <!--        <li><a href="slider_add.php"><span>أضف سلايدر </span></a></li>-->
        <!--        <li><a href="slider_view.php"><span>عرض السلايدرز</span></a></li>-->
        <!--    </ul>-->
        <!--</li>-->


        <!--<li id="item6" class="has-sub">-->
        <!--    <a href="#"><span>الطلبات</span></a>-->
        <!--    <ul class="has-sub">-->
        <!--        <li><a href="order_view.php"><span>عرض الطلبات الحالية</span></a></li>-->
        <!--        <li><a href="last_orders.php"><span>عرض الطلبات السابقة</span></a></li>-->
        <!--    </ul>-->
        <!--</li>-->



        <li id="item7" class="has-sub">
        <a href="clients_view.php?lang=<?php echo $lang; ?>">
            <img src="assets/images/clients.svg" alt="Clients icon" />
            <span><?php echo $languages[$lang]["clients"];   ?></span>
        </a>
        </li>

        <li id="item103" class="has-sub">
            <input type="checkbox" name="list-item" id="msgs-lg" hidden>
            <label for="msgs-lg">
                <img src="assets/images/messages.svg" alt="Messages icon" />
                <span><?php echo $languages[$lang]["messages"];    ?></span>
                <span class="expand-item"></span>
            </label>
            <ul class="has-sub">
                <li>
                    <a href="add_message.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/form.svg" alt="Form icon" />
                        <span><?php  echo $languages[$lang]["newMessage"];  ?></span>
                    </a>
                </li>
                <li>
                    <a href="messages_view.php?lang=<?php echo $lang; ?>&type=1">
                        <img src="assets/images/table.svg" alt="Table icon" />
                        <span><?php echo $languages[$lang]["viewMessages"];    ?></span>
                    </a>
                </li>
                <li>
                    <a href="subscriptions_view.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/table.svg" alt="Table icon" />
                        <span><?php echo $languages[$lang]["subscriptions"];    ?></span>
                    </a>
                </li>
            </ul>

        </li>

        <li id="item105" class="has-sub">
            <input type="checkbox" name="list-item" id="marketing-lg" hidden>
            <label for="marketing-lg">
                <img src="assets/images/marketing.svg" alt="Marketing icon" />
                <span><?php echo $languages[$lang]["marketing"];    ?></span>
                <span class="expand-item"></span>
            </label>
            <ul class="has-sub">
                <li>
                    <a href="group_add.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/form.svg" alt="Form icon" />
                        <span><?php  echo $languages[$lang]["group_add"];  ?></span>
                    </a>
                </li>
                <li>
                    <a href="groups_view.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/table.svg" alt="Table icon" />
                        <span><?php echo $languages[$lang]["groups_view"];    ?></span>
                    </a>
                </li>
                <li>
                    <a href="<?=$siteUrl.$RELATIVE_PATH?>cpanel">
                        <img src="assets/images/table.svg" alt="Table icon" />
                        <span><?php echo $languages[$lang]["mail_task"];    ?></span>
                    </a>
                </li>
            </ul>

        </li>

        <li id="item71" class="has-sub">
            <a href="setting.php?lang=<?php echo $lang; ?>">
                <img src="assets/images/settings.svg" alt="Settings icon" />
                <span><?php echo $languages[$lang]["setting"];   ?></span>
            </a>
        </li>

        <li id="item72" class="has-sub">
            <a href="contact.php?lang=<?php echo $lang; ?>">
            <img src="assets/images/contact.svg" alt="Contact icon" />
            <span><?php echo $languages[$lang]["contact"];   ?></span>
            </a>
        </li>

        <li id="item75" class="has-sub">
            <a href="terms.php?lang=<?php echo $lang; ?>">
                <img src="assets/images/terms-and-conditions.svg" alt="Terms and Conditions icon" />
                <span><?php echo $languages[$lang]["terms"];   ?></span>
            </a>
        </li>

        <li id="item77" class="has-sub">
            <a href="additional_services.php?lang=<?php echo $lang; ?>&type=1">
                <img src="assets/images/services.svg" alt="Additional services icon" />
                <span><?php echo $languages[$lang]["additionalServices"];   ?></span>
            </a>
        </li>

        <!--<li id="item73" class="has-sub">-->
        <!--    <a href="#"><span><?php echo $languages[$lang]["footer"];   ?></span></a>-->
        <!--     <ul class="has-sub">-->
        <!--        <li><a href="subscription_view.php?lang=<?php echo $lang; ?>"><span><?php  echo $languages[$lang]["subscriptions"];  ?></span></a></li>-->
        <!--        <li><a href="footer.php?lang=<?php echo $lang; ?>"><span><?php echo $languages[$lang]["footer"];    ?></span></a></li>-->
        <!--    </ul>-->
        <!--</li>-->


        <!--<li id="item8" class="has-sub">-->
        <!--    <a href="#"><span>المناطق</span></a>-->
        <!--    <ul class="has-sub">-->
        <!--        <li><a href="regions_add.php"><span>أضف منطقة </span></a></li>-->
        <!--        <li><a href="regions_view.php"><span>عرض المناطق</span></a></li>-->
        <!--    </ul>-->
        <!--</li>	-->

        <!--<li id="item10" class="has-sub">-->
        <!--    <a href="complaints_view.php"><span>الشكاوى والإقتراحات</span></a>-->
        <!--</li>-->


        <!--<li id="item9" class="has-sub">-->
        <!--    <a href="about_edit.php?id=1"><span>عن التطبيق</span></a>-->
        <!--</li>-->

        <!--<li id="item99" class="has-sub">-->
        <!--    <a href="contact_edit.php"><span>أتصل بنا </span></a>-->
        <!--</li>-->


        <!--<li id="item110" class="has-sub"><a href="product_comments_view.php"><span>التعليقات</span></a></li>-->
        <!--    <li id="item51" class="has-sub">-->
        <!--        <a href="#"><span>التقارير</span></a>-->
        <!--        <ul class="has-sub">-->
        <!--            <li><a href="edit_products_report.php"><span>تقرير المنتجات المعدلة </span></a></li>-->

        <!--            <li><a href="financial_report.php"><span>تقرير مالي بالتاريخ</span></a></li>-->
        <!--            <li><a href="select_financial_report.php"><span>اختر نوع التقرير</span></a></li>-->
        <!--            <li><a href="payment.php"><span> تقرير الدفع المالي </span></a></li>-->
        <!--            <li><a href="average_cost_report.php"><span>تقرير متوسط الشيك  </span></a></li>-->

        <!--            <li><a href="clients_most_order_report.php" target="_blank"><span> العملاء الأكثر طلبا   </span></a></li>-->
        <!--            <li><a href="sub_cats_high_rate_report.php" target="_blank"><span>الأصناف الأعلي تقييما   </span></a></li>-->
        <!--            <li><a href="subcats_most_paid_report.php" target="_blank"><span>   الأصناف الأكثر مبيعا   </span></a></li>-->
        <!--            <li><a href="client_report.php"><span>  تقرير طلبات العميل  </span></a></li>-->

        <!--        </ul>-->
        <!--    </li>        -->
        <!--<li id="item102" class="has-sub"><a href="setting_edit.php"><span>إعدادات عامة</span></a></li>-->

        <!--<li id="item11" class="has-sub"><a href="statistics.php"><span>الإحصائيات</span></a></li>-->

        <li id="item12" class="has-sub">
            <input type="checkbox" name="list-item" id="clients-lg" hidden>
            <label for="clients-lg">
                <img src="assets/images/clients.svg" alt="Clients icon" />
                <span><?php echo $languages[$lang]["managers"];   ?></span>
                <span class="expand-item"></span>
            </label>
            <ul class="has-sub">
                <li>
                    <a href="user_add.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/form.svg" alt="Form icon" />
                        <span> <?php echo $languages[$lang]["addManager"];   ?></span>
                    </a>
                </li>
                <li>
                    <a href="users_view.php?lang=<?php echo $lang; ?>">
                        <img src="assets/images/table.svg" alt="Table icon" />
                        <span><?php echo $languages[$lang]["viewManagers"];   ?></span>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="logout.php">
            <img src="assets/images/logout.svg" alt="Logout icon" />
            <span><?php echo $languages[$lang]["logout"];      ?></span>
            </a>
        </li>
        <!-- class="changeLanguage" -->
    </ul>
</div>
