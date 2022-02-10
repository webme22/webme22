<?php
include_once("config.php");
include_once("header.php");
include_once(__DIR__."/lib/Mailer.php");
include_once(__DIR__."/lib/Plan.php");
if(isset($_POST['featureSubmit'])){
	include_once(__DIR__.'/lib/recaptchalib.php');

	$type = $_POST['type'];
	$captcha_token = $_POST['g-recaptcha-response'];
	if(isset($_SESSION['user_id'])){
		$user = getUserData($_SESSION['user_id']);
		$name = $user['name'];
		$email = $user['email'];
		$phone = ($_POST['phone'])? trim($_POST['phone']) : $user['phone'];
	} else {
		$name = trim($_POST['name']);
		$email = trim($_POST['email']);
		$phone = trim($_POST['phone']);
	}
	$message = trim($_POST['message']);
	$resp = check_recaptcha ($RECAPTCHA, $captcha_token);
	if (!$resp) {
		$error = trans("wrong_captcha");
	}
	else if (! in_array($type, ['book', 'studio', 'magazine', 'account'])){
		$error = trans("invalidServiceType");
	}
	else if($name == "" || $email == ""){
	    $error = trans('name_email_required');
    }
	else if(! filter_var($email, FILTER_VALIDATE_EMAIL)){
		$error = trans("invalidEmail");
	} else {
		$feature = Feature::create([
				'type' => $type,
				'name' => $name,
				'email' => $email,
				'message' => $message,
				'phone' => $phone,
				'date' => date('Y-m-d h:i:s a'),
		]);
        
        $admin_login = $siteUrl.$RELATIVE_PATH."admin/additional_services.php";
		$mailer = new Mailer();
		$mailer->setVars(['user_name'=>$name, 'phone'=>$phone, 'email'=>$email, 'type'=>$type, 'admin_login'=>$admin_login]);
		$mailer->sendMail(['admin@alhamayel.com'], "New Service Request", 'new_service.html', 'new_service.txt');
		$mailer->sendMail(['marketing@alhamayel.com'], "New Service Request", 'new_service.html', 'new_service.txt');

        $mailer = new Mailer();
		$mailer->setVars(['user_name'=>$name]);
		$mailer->sendMail([$email], "Thank You for inquiry", 'thanks_for_inquiry.html', 'thanks_for_inquiry.txt');

		$success = trans('request_sent');
	}
}
if(isset($_POST['requestSubmit'])){
	$family_id = $_POST['familyId'];
	$name = trim($_POST['strangerName']);
	$email = trim($_POST['strangerEmail']);
	if(! filter_var($email, FILTER_VALIDATE_EMAIL)){
		$error = "Failed to send request, Email is invalid .";
	} else {
		FamilyAccess::create([
				'family_id'=>  $family_id ,
				'name'=>  $name ,
				'email'=> $email  ,
				'accept'=> '2'  ,
				'acceptedBy'=> null  ,
				'expire_date'=>  null ,
				'date' => date('Y-m-d h:i:s a')
		]);
		$emails  = Family::find($family_id)->users()->responsible()->pluck('email')->toArray();
        $account_url = $siteUrl.$RELATIVE_PATH."/account.php";
		$login_url = $siteUrl.$RELATIVE_PATH."/login.php";
		$mailer = new Mailer();
		$mailer->setVars(['user_name'=>'Creator/Assistant', 'name'=>$name, 'account_url'=>$account_url, 'login_url'=>$login_url]);
		$mailer->sendMail($emails, "New access request", 'access_request.html', 'access_request.txt');
		$success = "Request Sent Successfully";
	}
}
$rowSetting = Setting::find(1);
?>
<link rel="stylesheet" href="css/home.css">
<script src="//www.google.com/recaptcha/api.js" async defer></script>

<div class="mrgnTop section is-hero position-relative bg-hero2 overflow-hidden md-sm-xs-no-padding-bottom flexv-justify-center " >
    <div class="container position-relative ">
        <div class="row">
            <div  class="top-home-title col-md-12 col-lg-8 offset-lg-2" >
				<?php if($lang=='en'){?>
                    <h1 class="c-herotext__main" style="font-family:Poppins;font-weight: 100;color:#fff;"><?=trans('alhamayel')?></h1>
				<?php }else{?>
                    <h1 class="c-herotext__main" style="font-family:Poppins;font-weight: 100;width: 85%;margin: 0 auto;margin-top:50px;">
                        <picture>
                            <source srcset="<?=asset('images/logo-ar.webp')?>" type="image/webp">
                            <source srcset="<?=asset('images/logo-ar.png')?>" type="image/png">
                            <img src="<?=asset('images/logo-ar.png')?>" alt=""/>
                        </picture>
                    </h1>
				<?php }?>
                <h1 class="c-herotext__main subTitle <?= ($lang == 'ar')? 'mt-4':'' ?>" style="font-weight:100;color:#fff;"><?=trans('family_platform')?></h1>
                <div class="color-dark text-medium margin-bottom" style="text-align:center;background:none;color:#fff;"><?=trans('long_title')?></div>
                <input type="hidden" id="error" value="<?=isset($error)?$error:''?>">
                <input type="hidden" id="success" value="<?=isset($success)?$success:''?>">
                <form action="search.php">
                    <div class="home-search-in pr-2 pl-2 pb-2 flex-wrap">
                        <div class="col-12 col-md-6 mb-1 p-0">
                            <input type="search" name="search" placeholder="<?=trans('searchFamily')?>" id="search" class="home-search" value="<?=isset($_GET['search'])?$_GET['search']:''?>">
                        </div>
                        <div class="col-12 col-md-3 mb-1 p-0">
                            <select name="country" id="home-country" class="w-100">
                                <option value=''><?=trans('select_country')?></option>
								<?php
								$countries = Country::active()->get();
								foreach($countries as $country){?>
                                    <option value="<?=$country->id?>"
											<?=($_GET['country']) && $_GET['country'] == $country->id?'selected': ''?>>
										<?=db_trans($country,'name')?>
                                    </option>
								<?php }?>
                            </select>
                        </div>
                        <div class="col-12 col-md-3 p-0">
                            <button  class="button-primary animated w-inline-block" style="height: 53px;" type="submit">
                                <div style="-webkit-transform:translate3d(0, 0PX, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, 0PX, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, 0PX, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, 0PX, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:1" class="button-primary-text text-light"><?=trans('search')?></div>
                                <div style="opacity:0;display:block;-webkit-transform:translate3d(0, 20PX, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, 20PX, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, 20PX, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, 20PX, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0)" class="button-primary-text for-hover text-light"><?=trans('search')?> </div>
                            </button>
                        </div>
                        <div class="col-12  text-center">
                            <hr>
                            <a href="browse_families.php" class="btn btn-primary ml-auto animated " style="background-color: rgba(204, 190, 174, 0.5);color:white;border:0;border-radius:0">
								<?=trans('or_browse')?></a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-12 col-lg-12  mb-lg-0"></div>
        </div>
    </div>
    <div class="decoration-rightside img-hero2 d-flex justify-content-center flex-wrap">
        <div class="side-gallery position-relative">
            <div class="side-gallery-container">
                <h3 class="text-left pl-2 side-gallery-title"><?=trans('most_popular')?></h3>
                <h5 class="text-left pl-2 side-gallery-subtitle"><?=trans('scroll_more')?></h5>
            </div>
            <div class="people-list position-absolute">
                <ul style="padding-top: 60px;">
					<?php
					$popular_families = Family::valid()->popular()->orWhere('id', 80)->with(['country', 'creator'])->limit(20)->get();
					foreach($popular_families as $popular_family){
						?>
                        <li class="clearfix">
                            <div class="name position-relative rtl-dir-reverse">
								<?php if(isset($_SESSION['family_id']) && $_SESSION['family_id'] == $popular_family->id){ ?>
                                <a class="text-light" href="profile.php"  >
									<?php } else { ?>
                                    <a class="text-light familyAccess" href="<?=$popular_family->id?>" status="<?=$popular_family->status?>" >
										<?php } ?>
                                        <img src="<?=asset($popular_family->creator->image)?>" width= "50px" height="50px" alt="<?=$popular_family->image?>" style="border-radius:80%;" loading="lazy">
										<?php
										$count_notifications = get_home_notifications($popular_family->id);
										if($count_notifications > 0){?>
                                            <span class="badge user-home-badge">
												<?=$count_notifications?>
                                            </span>
										<?php } else if($popular_family->id == 80){ ?>
                                            <span class="badge user-home-badge">
                                                1
                                            </span>
                                        <?php } ?>
										<?=db_trans($popular_family, 'name')?>
                                    </a>
                            </div>
                        </li>
					<?php }?>
                </ul>
            </div>
            <div class="hidden"></div>
        </div>
        <br>
        <div class="side-gallery position-relative">
            <div class="side-gallery-container">
                <h3 class="text-left pl-2 side-gallery-title" ><?=trans('family_updates')?></h3>
                <h5 class="text-left pl-2 side-gallery-subtitle"><?=trans('scroll_more')?></h5>
            </div>
            <div class="people-list position-absolute" >
                <ul style="padding-top: 70px;">
					<?php
					$now = date('Y-m-d');
					$six_months_ago = date('Y-m-d', strtotime('-6 months'));
					$families_updates = Family::valid()->where(function ($query) use ($six_months_ago, $now){
                    $query->whereBetween('date', [$six_months_ago, $now])
                            ->orWhere('id', 80)
							->orWhereHas('media', function (Illuminate\Database\Eloquent\Builder $q) use ($six_months_ago, $now){
								$q->whereBetween('date', [$six_months_ago, $now]);
							})->orWhereHas('users', function (Illuminate\Database\Eloquent\Builder $q) use ($six_months_ago, $now) {
								$q->whereBetween('date', [$six_months_ago, $now]);
							});})->limit(20)->orderBy('id', 'desc')->get();
					foreach($families_updates as $families_update){
						?>
                        <li class="clearfix">
                            <div class="name position-relative rtl-dir-reverse">
								<?php if(isset($_SESSION['family_id']) && $_SESSION['family_id'] == $families_update->id){ ?>
                                <a class="text-light" href="profile.php"  >
									<?php } else { ?>
                                    <a class="text-light familyAccess" href="<?=$families_update->id?>" status="<?=$families_update['status']?>">
                                        <span id="" style="">
											<?php } ?>
                                            <img src="<?=asset($families_update->creator->image)?>" width= "50px" height="50px" alt="" style="border-radius:50%;" loading="lazy">
											<?php
											$count_notifications = get_home_notifications($families_update->id);
											if($count_notifications > 0){
												?>
                                                <span class="badge user-home-badge"><?=$count_notifications?></span>
											<?php } else if($families_update->id == 80){ ?>
                                                <span class="badge user-home-badge">1</span>
                                            <?php } ?>
                                        </span> <?=db_trans($families_update, 'name') ?></a>
                            </div>
                        </li>
						<?php
					}
					// }}
					?>
                </ul>
            </div>
        </div>
        <br>
    </div>
    <!-- <a href="#" class="text-center mt-4 scroll_down"><i class="fa fa-arrow-circle-down fa-4x text-dark"></i></a> -->
    <span class="text-center mt-4">
        <picture>
            <source srcset="<?=asset('img/icons/arrow-down.webp')?>" type="image/webp">
            <source srcset="<?=asset('img/icons/arrow-down.png')?>" type="image/png">
            <img src="img/icons/arrow-down.png" style="width:15%;">
        </picture>
    </span>
</div>
<div class="modal" id="modal3" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ml-auto mt-3" style="text-align: center;"><?=trans('request_private')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <input type="hidden" value="" name="familyId" id="familyId">
                    <div class="form-group">
                        <label><?=trans('name')?>* :</label>
                        <input type="text" class="form-control" required name="strangerName" placeholder="<?=trans('enter')?><?=trans('name')?>">
                    </div>
                    <div class="form-group">
                        <label><?=trans('email')?>* :</label>
                        <input type="email" class="form-control" required name="strangerEmail" placeholder="<?=trans('enter')?><?=trans('email')?>">
                    </div>
            </div>
            <div class="modal-footer" style="margin: auto;">
                <button type="submit" class="btn hbtn btn-hred" name="requestSubmit"><?=trans('submit')?></button>
                <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="close3"><?=trans('close')?></button>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="modal" id="modal5" tabindex="-1" role="dialog">
    <div class="modal-dialog <?php if(! isset($_SESSION['user_id'])){ ?> modal-lg <?php } ?>" role="document">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header ">
                    <h5 class="modal-title ml-auto mt-3" id="featureTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" value="" name="type" id="type">
					<?php if(! isset($_SESSION['user_id'])){ ?>
                        <div class="form-group">
                            <label>Name *</label>
                            <input type="text" class="form-control" required name="name">
                        </div>
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" class="form-control" required name="email">
                        </div>
					<?php } ?>
                    <div class="form-group">
                        <label>Telephone</label>
                        <input type="tel" class="form-control" required name="phone">
                    </div>
                    <div class="form-group">
                        <label>Message</label>
                        <textarea class="form-control" name="message"></textarea>
                    </div>
                    <div class="form-group">
                        <div class="g-recaptcha" data-sitekey="6LfJc-obAAAAANrRhwAcYLO5gvtjAPGLIKMU1hqa"></div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="submit" class="btn hbtn btn-hred" name="featureSubmit">Submit</button>
                    <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="close5">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="section position-relative ">
    <!-- <div class="c-shadowtext"><?=trans('popular_families')?></div> -->
    <div class="container position-relative">
        <div class="row">
            <div class="col-12 block-centered col-lg-8 col-md-12 text-center position-relative">
                <h2 style="text-align:center;"><?=trans('hamayel_most_popular')?></h2>
                <div style="text-align:center;" class="text-medium low-text-contrast"><?=trans('find_most_popular')?></div>
            </div>
        </div>
    </div>
    <!-- Most Popular Families -->
	<?php
	$most_popular_families = $popular_families->toArray();
	$most_popular_families_count = count($most_popular_families);
	$xl_slides = ceil($most_popular_families_count / 4);
	$lg_slides = ceil($most_popular_families_count / 3);
	$md_slides = ceil($most_popular_families_count / 2);
	$xs_slides = ceil($most_popular_families_count);
	?>
    <div class="container">
        <div id="carouselExampleIndicators-xl" class="carousel slide d-none d-xl-block" data-ride="carousel" >
            <!-- xl-screen carousel indicators -->
            <ol class="carousel-indicators d-none d-xl-flex">
				<?php foreach(range(0, $xl_slides-1) as $slide) {?>
                    <li data-target="#carouselExampleIndicators-xl" data-slide-to="<?=$slide?>" class="<?php if ($slide==0) echo 'active';?>"></li>
				<?php }?>
            </ol>
            <!-- END xl-screen carousel indicators -->
            <!-- xl-screen carousel -->
            <div class="carousel-inner d-none d-xl-block">
				<?php foreach(range(0, $xl_slides-1) as $slide) {?>
                    <div class="carousel-item <?php if ($slide==0) echo 'active';?>">
                        <div class="row " style="margin-bottom: 50px;padding-left: 7%;padding-right: 7%;">
							<?php
							foreach(array_slice($most_popular_families, $slide*4, 4) as $rowMP){
								$row3 = $rowMP['country'];
								$rowMP['name'] = $rowMP['creator']['name'];
								?>
                                <div class="col-xl-3 sm-padding">
									<?php include("include/familycard.php");?>
                                </div>
							<?php } ?>
                        </div>
                    </div>
				<?php }?>
            </div>
            <!-- end xl-screen carousel -->
            <!-- Controls -->
            <a class="carousel-control-prev" href="#" data-target="#carouselExampleIndicators-xl" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#" data-target="#carouselExampleIndicators-xl" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
            <!-- Controls -->
        </div>
        <div id="carouselExampleIndicators-lg" class="carousel slide d-none d-lg-block d-xl-none" data-ride="carousel" >
            <!-- lg-screen carousel indicators -->
            <ol class="carousel-indicators d-none d-lg-flex d-xl-none">
				<?php foreach(range(0, $lg_slides-1) as $slide) {?>
                    <li data-target="#carouselExampleIndicators-lg" data-slide-to="<?=$slide?>" class="<?php if ($slide==0) echo 'active';?>"></li>
				<?php }?>
            </ol>
            <!-- END lg-screen carousel indicators -->
            <!-- lg-screen carousel -->
            <div class="carousel-inner d-none d-lg-block d-xl-none">
				<?php foreach(range(0, $lg_slides-1) as $slide) {?>
                <div class="carousel-item <?php if ($slide==0) echo 'active';?>">
                    <div class="row " style="margin-bottom: 50px;padding-left: 7%;padding-right: 7%;">
						<?php
						foreach(array_slice($most_popular_families, $slide*3, 3) as $rowMP){
						$row3 = $rowMP['country'];
						?>
                        <div class="col-lg-4 sm-padding"">
						<?php include("include/familycard.php");?>
                    </div>
					<?php } ?>
                </div>
            </div>
			<?php }?>
        </div>
        <!-- end lg-screen carousel -->
        <!-- Controls -->
        <a class="carousel-control-prev" href="#" data-target="#carouselExampleIndicators-lg" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#" data-target="#carouselExampleIndicators-lg" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
        <!-- Controls -->
    </div>
    <div id="carouselExampleIndicators-md" class="carousel slide d-none d-md-block d-lg-none" data-ride="carousel" >
        <!-- md-screen carousel indicators -->
        <ol class="carousel-indicators d-none d-md-flex d-lg-none">
			<?php foreach(range(0, $md_slides-1) as $slide) {?>
                <li data-target="#carouselExampleIndicators-md" data-slide-to="<?=$slide?>" class="<?php if ($slide==0) echo 'active';?>"></li>
			<?php }?>
        </ol>
        <!-- END md-screen carousel indicators -->
        <!-- md-screen carousel -->
        <div class="carousel-inner d-none d-md-block d-lg-none">
			<?php foreach(range(0, $md_slides-1) as $slide) {?>
            <div class="carousel-item <?php if ($slide==0) echo 'active';?>">
                <div class="row " style="margin-bottom: 50px;padding-left: 7%;padding-right: 7%;">
					<?php
					foreach(array_slice($most_popular_families, $slide*2, 2) as $rowMP){
					$row3 = $rowMP['country'];
					?>
                    <div class="col-md-6 sm-padding"">
					<?php include("include/familycard.php");?>
                </div>
				<?php } ?>
            </div>
        </div>
		<?php }?>
    </div>
    <!-- end md-screen carousel -->
    <!-- Controls -->
    <a class="carousel-control-prev" href="#" data-target="#carouselExampleIndicators-md" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#" data-target="#carouselExampleIndicators-md" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
    <!-- Controls -->
</div>
<div id="carouselExampleIndicators-xs" class="carousel slide d-block d-md-none" data-ride="carousel" >
    <!-- xs-screen carousel indicators -->
    <ol class="carousel-indicators d-flex d-md-none">
		<?php foreach(range(0, $xs_slides-1) as $slide) {?>
            <li data-target="#carouselExampleIndicators-xs" data-slide-to="<?=$slide?>" class="<?php if ($slide==0) echo 'active';?>"></li>
		<?php }?>
    </ol>
    <!-- END xs-screen carousel indicators -->
    <!-- xs-screen carousel -->
    <div class="carousel-inner d-block d-md-none">
		<?php foreach(range(0, $xs_slides-1) as $slide) {?>
        <div class="carousel-item <?php if ($slide==0) echo 'active';?>">
            <div class="row " style="margin-bottom: 50px;padding-left: 7%;padding-right: 7%;">
				<?php
				foreach(array_slice($most_popular_families, $slide, 1) as $rowMP){
				$row3 = $rowMP['country'];
				?>
                <div class="col-12 sm-padding"">
				<?php include("include/familycard.php");?>
            </div>
			<?php } ?>
        </div>
    </div>
	<?php }?>
</div>
<!-- end xl-screen carousel -->
<!-- Controls -->
<a class="carousel-control-prev" href="#" data-target="#carouselExampleIndicators-xs" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
</a>
<a class="carousel-control-next" href="#" data-target="#carouselExampleIndicators-xs" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
</a>
<!-- Controls -->
</div>
</div>
<br>
<div class="col-12">
    <button onclick="window.location.href='top-family.php';" class="btn btn-primary top-home-btn"><?=trans('view_more')?></button>
</div>
</div>
<?php
include(__DIR__."/include/services.php");
include(__DIR__."/include/features.php");
?>
<?php if(! isset($_SESSION['user_id']) || !isset($_SESSION['family_id']) || $_SESSION['family_id'] == -1 || $_SESSION['family_id'] == 0){ ?>
    <div class="section  has-bg-accent position-relative ">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="c-financial-finder text-align-center">
                        <h3 class="text-center"><?=trans('get_started')?></h3>
                        <div class="text-center text-medium low-text-contrast ">
							<?=trans('choosePlan')?>
                        </div>
                        <div class="plans justify-content-center">
                            <section class="Sponsers">
                            <?php
							$plans = DBPlan::active()->orderBy('price', 'asc')->get();
							foreach($plans as $plan){
								include(__DIR__.'/include/plancard.php');
							}
							?>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } else {
	$family_plan = new Plan($_SESSION['family_id']);
	$next_plans = $family_plan->upgradeable();

	if($next_plans){    ?>
        <div class="section  has-bg-accent position-relative">
            <div class="c-shadowtext" style="bottom:0;top:unset"><small><?=trans('upgrade_plan')?></small></div>
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="c-financial-finder text-align-center">
                            <h3 class="text-center"><?=trans('upgrade_plan')?></h3>
                            <div class="text-center text-medium low-text-contrast mb-5">
								<?=trans('choosePlan')?>
                            </div>
                            <div class="plans flex-wrap justify-content-center">
                                <section class="Sponsers">
                                <?php foreach($next_plans as $plan){
									include(__DIR__.'/include/plancard.php');
								}?>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	<?php } } ?>
<?php include"footer.php";?>
<script type="text/javascript">
    $(document).ready(function() {
        $('body').on('click', '.familyPron', function(){
            let audio = $(this).attr('audio');
            let audioTag = document.createElement('audio');
            audioTag.setAttribute('src', audio);
            audioTag.play();
        })

        $('body').on('click', '.inquire', function(){
            let type = $(this).attr('data-value');
            $('#type').val(type);
            if(type.includes('book')){
                $('#featureTitle').empty().html('Family Book Inquire form');
            } else if(type.includes('studio')){
                $('#featureTitle').empty().html('Alhamayel Studio Inquire form');
            } else if(type.includes('magazine')){
                $('#featureTitle').empty().html('Family Magazine Inquire form');
            } else if(type.includes('account')){
                $('#featureTitle').empty().html('Account Manager Inquire form');
            }
            $('#modal5').modal('show');
        })
        $('body').on('click', '#close5', function(){
            $('#modal5').modal('hide');
        })
        $('.homeImage').click(function(e){
            $(this).height(400);
            $(this).width(700);
            $(this).off();
            e.preventDefault();
        })
        let error = $('#error').val();
        let success = $('#success').val();
        if(error.length > 0){
            Swal.fire({
                title: 'Error!',
                width: 400,
                text: `${error}`,
                icon: 'error',
                confirmButtonText: 'Ok'
            })
        }
        $('body').on('click', '#close3', function(){
            $('#modal3').modal('hide');
        })
    });
</script>
<script src="<?=asset('js/jquery.waypoints.min.js')?>"></script>
<script src="<?=asset('js/jquery.lazyloadvid-min.js')?>"></script>
<script>
    $('.carousel').carousel({
        interval: 6000,
        pause: "false"
    });
</script>
