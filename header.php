<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?=trans("siteTitle")?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta id="viewport" name="viewport" content="user-scalable=yes, initial-scale=1.0, maximum-scale=5.0, minimum-scale=1.0, width=device-width" />
        <meta name="google-signin-client_id" content="158532838399-dud76rvpuofmm4a5v1a2mejgam5uvv1j.apps.googleusercontent.com">
        <!-- SEO -->
		<?php if (isset($SEO_TAGS)) {
			echo $SEO_TAGS;
			?>
		<?php } else { ?>
            <meta name="description" content="<?=trans('siteDesc')?>">
            <meta name="keywords" content="alhamayel, family tree, bahrain">
            <meta property="og:title" content="<?=trans("siteTitle")?>" />
            <meta property="og:url" content="<?=$siteUrl?>" />
            <meta property="og:type" content="website" />
            <meta property="og:description" content="<?=trans('ogDesc')?>" />
            <meta property="og:image" itemprop="image" content="<?=trim($siteUrl, '/').asset('images/map-preview.png')?>" />
            <meta property="og:image:alt"  content="Alhamayel.com preview image" />
            <meta name="twitter:site" content="@alhamayel" />
            <meta name="twitter:title" content="<?=trans("siteTitle")?>" />
            <meta name="twitter:description" content="<?=trans('siteDesc')?>" />
            <meta name="twitter:card" content="summary_large_image" />
            <meta name="twitter:image" content="<?=trim($siteUrl, '/').asset('images/map-preview.png')?>" />
            <meta name="twitter:image:alt" content="<?=$languages[$lang]["siteTitle"]?>" />
		<?php } ?>
        <!-- End SEO -->
        <link rel="shortcut icon" href="<?=asset('images/favicon.ico')?>" type="image/x-icon">
        <link rel="icon" href="<?=asset('images/favicon.ico')?>" type="image/x-icon">
        <link href="css_<?php echo $lang; ?>/components.css" rel="stylesheet" type="text/css">
        <link href="css/quaid.css" rel="stylesheet" type="text/css">
        <link href="css_<?php echo $lang; ?>/quaid.css" rel="stylesheet" type="text/css">
        <link href="css/normalize.css" rel="stylesheet" type="text/css">
        <link href="css/fm.selectator.jquery.css" rel="stylesheet" type="text/css">
        <link href='https://fonts.googleapis.com/css?family=Poppins&display=swap' rel='stylesheet'>
        <link href="css/lightbox.min.css" rel="stylesheet" type="text/css">
        <link href="css/plans.css" rel="stylesheet" type="text/css">
        <link
                href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600&display=swap"
                rel="stylesheet"
        /> <script src='//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                
            });
            
        </script>
        <!-- [if lt IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js" type="text/javascript"></script><![endif] -->
        <link href="<?=asset('images/favicon.ico')?>" rel="shortcut icon" type="image/x-icon">
        <link href="<?=asset('images/favicon.ico')?>" rel="apple-touch-icon">
        <link href="css_<?php echo $lang; ?>/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="//cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css" >
        <link href="css/style.css" rel="stylesheet" type="text/css">
        <link href="css_<?php echo $lang; ?>/style.css" rel="stylesheet" type="text/css">
        <link href="css/stackedCards.css" rel="stylesheet" type="text/css">
        <link href="css/hmodals.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <style>
            [data-title]{
            position: relative !important;
            overflow: visible !important;
            line-height: 16px !important;
            display: inline-block !important;
            }
            [data-title]:hover:after{
            display: inline-block !important;
            position: absolute !important;
            width: max-content;
            z-index: 999 !important;
            padding: 8px !important;
            left: 0px !important;
            top: -40px !important;
            border-radius: 4px !important;
            background-color: #3F3F3F !important;
            text-shadow: 0px 1px 0px #000 !important;
            color: #FFF !important;
            speak: none !important;
            font-style: normal !important;
            font-weight: normal !important;
            font-variant: normal !important;
            text-transform: none !important;
            font-size: 14px !important;
            -webkit-user-select: none !important;
            -moz-user-select: none !important;
            -ms-user-select: none !important;
            -o-user-select: none !important;
            user-select: none !important;
            -webkit-font-smoothing: antialiased !important;
            -moz-osx-font-smoothing: grayscale !important;
            content: attr(data-title);
            transition: all 0.2s ease !important;
            -moz-transition: all 0.2s ease !important;
            -o-transition: all 0.2s ease !important;
            -webkit-transition: all 0.2s ease !important;
            }
            #myVideo {
            right: 0;
            bottom: 0;
            min-width: 100%;
            min-height: 100%;
            }
            #headerMessage {
            text-align: center;color: #fff;margin-top: 15px; font-size: 16px; font-family: Metropolis, sans-serif;
            }
            a.brand.w-inline-block > picture img {
            min-width: 30%;
            width: 40px !important;
            /*display: inline-block !important;*/
            vertical-align: middle !important;
            /*max-width: 100% !important;*/
            /*margin: 0 auto;*/
            }
        </style>
        <link rel="stylesheet" href="<?=asset('css/cropper.min.css')?>"/>
    </head>
    <body style="height: auto; overflow-y:scroll; <?php if ($lang == 'ar') echo "text-align: right !important"; ?>">
    <?php include_once("analyticstracking.php") ?>
    <?php include('analytics.php'); ?>
        <header class="navigation-section position-absolute" style="<?php
            if ($lang == 'ar') {
                echo "direction: rtl";
            } else {
                echo "direction: ltr";
            }
            ?>">
            <div class="navigation-overlay"></div>
            <div class="topbar container" >
                <div class="topbar-menus ">
                    <li>
                        <a href="<?php echo $redirected_url . $lang_param . (($lang == 'en')? 'ar' : 'en'); ?>"><?php echo $languages[$lang]["lang"]; ?></a>
                    </li>
                </div>
            </div>
            <div class="navigation-and-offcanvas" style="">
                <div class="col no-margin-bottom lg-5 md-basis-uato">
                    <nav class="navigation-menu">
                        <a href="home.php" class="nav-link custom-nav-link"><?php echo $languages[$lang]["home"]; ?></a>
                        <a href="terms.php" class="nav-link custom-nav-link " style=""><?php echo $languages[$lang]["terms"]; ?></a>
                        <?php if (isset($_SESSION['user_id'])) { ?>
                        <a href="faq.php" class="nav-link custom-nav-link" style=""><?php echo $languages[$lang]["faq_short"]; ?></a>
                        <?php } ?>
                        <ul class="navbar-nav custom-navbar-nav <?=!isset($_SESSION['user_id'])?'mr-auto':''?> d-none">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?=trans('resources')?>
                                </a>
                                <style>
                                    .custom-navbar-nav {
                                    width:120px;
                                    }
                                    .custom-drop-down {
                                    background-color: #ccbeae;
                                    }
                                    .custom-drop-down .dropdown-item {
                                    color:white;
                                    }
                                    .custom-drop-down .dropdown-item:hover {
                                    background: transparent;
                                    color:black
                                    }
                                </style>
                                <div class="dropdown-menu custom-drop-down" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="terms.php"><?php echo $languages[$lang]["terms"]; ?></a>
                                    <a class="dropdown-item" href="faq.php"><?php echo $languages[$lang]["faq"]; ?></a>
                                    <a class="dropdown-item" href="services.php"><?php echo $languages[$lang]["services"]; ?></a>
                                </div>
                            </li>
                        </ul>
                        <?php if (!isset($_SESSION['user_id'])) { ?>
                        <a href="services.php" class="nav-link custom-nav-link"><?php echo $languages[$lang]["services"]; ?></a>
                        <?php } else { ?>
                        
                        <?php }
                            ?>
                    </nav>
                </div>
                <div class="col col-lg-auto md-basis-auto md-order-first no-margin-bottom-lg text-center">
                    <a href="home.php" class="brand w-inline-block">
                        <picture>
                            <source srcset="<?=asset('images/al-logo2.webp')?>" type="image/webp">
                            <source srcset="<?=asset('images/al-logo2.png')?>" type="image/png">
                            <img src="<?=asset('images/al-logo2.png')?>" alt="home">
                        </picture>
                    </a>
                </div>
                <div class="col no-margin-bottom lg-5">
                    <nav class="navigation-menu justify-end">
                        <?php if (!isset($_SESSION['user_id'])) { ?>
                        <a href="login.php" class="nav-link custom-nav-link"><?php echo $languages[$lang]["login"]; ?></a>
                        <a href="faq.php" class="nav-link custom-nav-link" style=""><?php echo $languages[$lang]["faq_short"]; ?></a>
                        <a href="about.php" class="nav-link custom-nav-link"><?php echo $languages[$lang]["about"]; ?></a>
                        <?php } else { ?>
                        <a href="logout.php" class="nav-link custom-nav-link"><?php echo $languages[$lang]["logout"]; ?></a>
                        <a href="profile.php" class="nav-link custom-nav-link"><?php echo $languages[$lang]["familyTree"]; ?></a>
                        <?php
                            }
                            
                            $flag = "joinUs";
                            $href = "signup.php";
                            $style="";
                            if (isset($_SESSION['user_id'])) {
                                $flag = "myAccount";
                                $href = "account.php";
                                $style="position: relative";
                            }
                            ?>
                        <div class="position-relative">
                            <a data-w-id="e02ef0fd-d341-7eaf-5ed3-b53528c9af97" href="<?php echo $href; ?>" class="button-primary animated is-small alignself-center w-inline-block text-light" style="<?= $style ?>">
                                <div class="button-primary-text"><?php echo $languages[$lang][$flag]; ?></div>
                                <div class="button-primary-text for-hover"><?php echo $languages[$lang][$flag]; ?></div>
                            </a>
                            <?php if(isset($_SESSION['user_id']) && familyNotifications($_SESSION['family_id']) > 0) { ?>
                            <span class="badge text-light" style="position: absolute; border-radius: 50%; background-color: green;top:0;right:0"><?= familyNotifications($_SESSION['family_id']); ?></span>
                            <?php } ?>
                        </div>
                    </nav>
                </div>
                <a data-w-id="83a36909-9554-440b-ec90-d232c2c0c85f" href="#" class="c-nav__close-button w-inline-block">
                    <div class="iconfont is-offcanvas-close-button"><em class="iconfont__no-italize"></em></div>
                </a>
            </div>
            <div class="mobile-navigation-bar" style="right: 10px;">
                <a data-w-id="83a36909-9554-440b-ec90-d232c2c0c868" href="#" class="burger-button w-inline-block bkColor">
                    <div class="iconfont is-burger"><em class="iconfont__no-italize"></em></div>
                </a>
            </div>
            <p id="headerMessage" style="letter-spacing: 9px;">GETTING TO KNOW EACH OTHER BETTER</p>
        </header>
