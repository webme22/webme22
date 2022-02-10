<div class="col-xs-12 col-lg-10">
<div class="content-page">
    <div class="content">
        <div class="container">

            <h3 style="display:block;text-align:center;margin-top: 15px;border-bottom: 1px solid #000;width: 40%;margin-right: auto;padding: 20px 0;margin-left: auto;margin-bottom: 60px;">
                <?php  echo $languages[$lang]["welcome"];     ?>  		</h3>

            <div class="row pricing-plan">
                <div class="col-md-12">
                    <div class="row">


                        <div class="col-md-3 col-lg-3 col-xl-3">
                            <div class="price_card text-center">
                                <div class="pricing-header bg-custom">
                                    <span class="name"> <i class="fa fa-users"></i> <?php  echo $languages[$lang]["families"]; ?>  </span>
                                </div>
                                <ul class="price-features">
                                    
                                    <li><a href="families_view.php?lang=<?php echo $lang; ?>"><span><?php  echo $languages[$lang]["viewFamilies"]; ?>  </span></a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-md-3 col-lg-3 col-xl-3">
                            <div class="price_card text-center">
                                <div class="pricing-header bg-custom">
                                    <span class="name"> <i class="fa fa-users"></i> <?php  echo $languages[$lang]["plans"]; ?> </span>
                                </div>
                                <ul class="price-features">
                                    <li><a href="plan_add.php?lang=<?php echo $lang; ?>"><span><?php  echo $languages[$lang]["addPlan"]; ?>  </span></a></li>
                                    <li><a href="plans_view.php?lang=<?php echo $lang; ?>"><span><?php  echo $languages[$lang]["viewPlans"]; ?></span></a></li>
                                </ul>
                            </div>
                        </div>


                        <div class="col-md-3 col-lg-3 col-xl-3">
                            <div class="price_card text-center">
                                <div class="pricing-header bg-custom">
                                    <span class="name"> <i class="fa fa-users"></i> <?php  echo $languages[$lang]["countries"]; ?>  </span>
                                </div>
                                <ul class="price-features">
                                    <li><a href="country_add.php?lang=<?php echo $lang; ?>"><span> <?php  echo $languages[$lang]["addCountry"]; ?> </span></a></li>
                                    <li><a href="countries_view.php?lang=<?php echo $lang; ?>"><span><?php  echo $languages[$lang]["viewCountries"]; ?> </span></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3 col-xl-3">
                            <div class="price_card text-center">
                                <div class="pricing-header bg-custom">
                                    <span class="name"> <i class="fa fa-users"></i> <?php  echo $languages[$lang]["clients"]; ?> </span>
                                </div>
                                <ul class="price-features">
                                    
                                    <li><a href="clients_view.php?lang=<?php echo $lang; ?>"><span><?php  echo $languages[$lang]["viewClients"]; ?></span></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3 col-xl-3">
                            <div class="price_card text-center">
                                <div class="pricing-header bg-custom">
                                    <span class="name"> <i class="fa fa-users"></i> <?php  echo $languages[$lang]["reviews"]; ?> </span>
                                </div>
                                <ul class="price-features">
                                    <li><a href="review_add.php?lang=<?php echo $lang; ?>"><span><?php  echo $languages[$lang]["addReview"]; ?></span></a></li>
                                    <li><a href="reviews_view.php?lang=<?php echo $lang; ?>"><span><?php  echo $languages[$lang]["viewReviews"]; ?></span></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3 col-xl-3">
                            <div class="price_card text-center">
                                <div class="pricing-header bg-custom">
                                    <span class="name"> <i class="fa fa-users"></i> <?php  echo $languages[$lang]["messages"]; ?> </span>
                                </div>
                                <ul class="price-features">
                                    <li><a href="add_message.php?lang=<?php echo $lang; ?>"><span><?php  echo $languages[$lang]["addMessage"]; ?> </span></a></li>
                                    <li><a href="messages_view.php?lang=<?php echo $lang; ?>&type=1"><span><?php  echo $languages[$lang]["viewMessages"]; ?> </span></a></li>
                                    <li><a href="subscriptions_view.php?lang=<?php echo $lang; ?>"><span><?php  echo $languages[$lang]["subscriptions"]; ?> </span></a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-md-3 col-lg-3 col-xl-3">
                            <div class="price_card text-center">
                                <div class="pricing-header bg-custom">
                                    <span class="name"> <i class="fa fa-users"></i> <?php echo $languages[$lang]["managers"];   ?> </span>
                                </div>
                                <ul class="price-features">
                                    <li><a href="user_add.php?lang=<?php echo $lang; ?>"><span><?php echo $languages[$lang]["addManager"];   ?>   </span></a></li>
                                    <li><a href="users_view.php?lang=<?php echo $lang; ?>"><span><?php echo $languages[$lang]["viewManagers"];   ?>  </span></a></li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-lg-3 col-xl-3">
                            <div class="price_card text-center">
                                <div class="pricing-header bg-custom">
                                    <span class="name"> <i class="fa fa-users"></i> <?php echo $languages[$lang]["questions"];   ?> </span>
                                </div>
                                <ul class="price-features">
                                    <li><a href="category_add.php?lang=<?php echo $lang; ?>"><span><?php echo $languages[$lang]["add_category"];   ?>   </span></a></li>
                                    <li><a href="categories_view.php?lang=<?php echo $lang; ?>"><span><?php echo $languages[$lang]["view_categories"];   ?>  </span></a></li>
                                    <li><a href="question_add.php?lang=<?php echo $lang; ?>"><span><?php echo $languages[$lang]["addQuestion"];   ?>   </span></a></li>
                                    <li><a href="questions_view.php?lang=<?php echo $lang; ?>"><span><?php echo $languages[$lang]["viewQuestions"];   ?>  </span></a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-md-3 col-lg-3 col-xl-3">
                            <div class="price_card text-center">
                                <div class="pricing-header bg-custom">
                                    <span class="name"> <i class="fa fa-users"></i> <?php echo $languages[$lang]["how_it_works"];   ?> </span>
                                </div>
                                <ul class="price-features">
                                    <li><a href="how_it_works_add.php?lang=<?php echo $lang; ?>"><span><?php echo $languages[$lang]["add_item"];   ?>   </span></a></li>
                                    <li><a href="how_it_works_view.php?lang=<?php echo $lang; ?>"><span><?php echo $languages[$lang]["view_items"];   ?>  </span></a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-md-3 col-lg-3 col-xl-3">
                            <div class="price_card text-center">
                                <div class="pricing-header bg-custom">
                                    <span class="name"> <i class="fa fa-users"></i> <?php echo $languages[$lang]["groups"];   ?> </span>
                                </div>
                                <ul class="price-features">
                                    <li><a href="group_add.php?lang=<?php echo $lang; ?>"><span><?php echo $languages[$lang]["group_add"];   ?>   </span></a></li>
                                    <li><a href="groups_view.php?lang=<?php echo $lang; ?>"><span><?php echo $languages[$lang]["groups_view"];   ?>  </span></a></li>
                                </ul>
                            </div>
                        </div>
                        
                        <!--<div class="col-md-3 col-lg-3 col-xl-3">-->
                        <!--    <div class="price_card text-center">-->
                        <!--        <div class="pricing-header bg-custom">-->
                        <!--            <span class="name"> <i class="fa fa-users"></i> العملاء</span>-->
                        <!--        </div>-->
                        <!--        <ul class="price-features">-->
                        <!--            <li><a href="client_view.php"><span>عرض العملاء</span></a></li>-->
                        <!--        </ul>-->
                        <!--    </div>-->
                        <!--</div>-->
                        <!--<div class="col-md-3 col-lg-3 col-xl-3">-->
                        <!--    <div class="price_card text-center">-->
                        <!--        <div class="pricing-header bg-custom">-->
                        <!--            <span class="name"> <i class="fa fa-map"></i> المناطق</span>-->
                        <!--        </div>-->
                        <!--        <ul class="price-features">-->
                        <!--            <li><a href="regions_add.php">أضف منطقة </a></li>-->
                        <!--            <li><a href="regions_view.php">عرض المناطق</a></li>-->
                        <!--        </ul>-->
                        <!--    </div>-->
                        <!--</div>-->

                        <!--<div class="col-md-3 col-lg-3 col-xl-3">-->
                        <!--    <div class="price_card text-center">-->
                        <!--        <div class="pricing-header bg-custom">-->
                        <!--            <span class="name"> <i class="fa fa-info"></i>  الإعدادات </span>-->
                        <!--        </div>-->
                        <!--        <ul class="price-features">-->
                        <!--             <li><a href="about_edit.php">عن التطبيق</a></li>-->
                        <!--            <li><a href="contact_edit.php">اتصل بنا</a></li>-->
                        <!--            <li><a href="setting_edit.php">إعدادات عامة</a></li>-->
                        <!--        </ul>-->
                        <!--    </div>-->
                        <!--</div>-->


                        <!--<div class="col-md-3 col-lg-3 col-xl-3">-->
                        <!--    <div class="price_card text-center">-->
                        <!--        <div class="pricing-header bg-custom">-->
                        <!--            <span class="name"> <i class="fa fa-user"></i> المستخدمين</span>-->
                        <!--        </div>-->
                        <!--        <ul class="price-features">-->
                        <!--            <li><a href="user_add.php"> اضف مستخدم </a></li>-->
                        <!--            <li><a href="users_view.php"> عرض المستخدمين </a></li>-->
                        <!--        </ul>-->
                        <!--    </div>-->
                        <!--</div>-->

                        <!--<div class="col-md-3 col-lg-3 col-xl-3">-->
                        <!--    <div class="price_card text-center">-->
                        <!--        <div class="pricing-header bg-custom">-->
                        <!--            <span class="name"> <i class="fa fa-dollar"></i> الإحصائيات</span>-->
                        <!--        </div>-->
                        <!--        <ul class="price-features">-->
                        <!--            <li><a href="statistics.php">عرض الإحصائيات</a></li>-->
                        <!--        </ul>-->
                        <!--    </div>-->
                        <!--</div>-->

                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php include("include/footer_text.php"); ?>
</div>
</div>
