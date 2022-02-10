<?php
include_once(__DIR__."/config.php");
middleware('guest');
$visitor_country = ip_info("Visitor", "Country");
// Form Submit
if(isset($_POST['formSubmit'])) {
	$errors = [];
	$required_fields = ['username', 'password', 'cpass', 'country', 'key', 'phone', 'nationality', 'email', 'confirmEmail'];
	$errors = validate($required_fields);
	$userName = trim($_POST["username"]);
	$password = $_POST["password"];
	$confirmPassword = $_POST["cpass"];
	$country = trim($_POST["country"]);
	$key = trim($_POST["key"]);
	$phone = trim($_POST["phone"]);
	$nationality = trim($_POST["nationality"]);
	$email = trim($_POST["email"]);
	$confirmEmail = trim($_POST["confirmEmail"]);
	$role = "creator";
	if(checkUserNameExists($userName, 0)){
		$errors['username'] = trans("usernameExists");
	}
	if($password != $confirmPassword){
		$errors['password'] = trans("passwordMatch");
	}
	if($email != $confirmEmail){
		$errors['email'] = trans("emailMatch");
	}
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$errors['email'] = trans("invalidEmail");
	}
	if(checkEmailExists($email, 0)){
		$errors['email'] = trans("emailExists");
	}
	if(empty($errors)) {
		$profile_image = "images/default-user.png";
		$user_id = add_user($userName, $password, null, $country, $phone, $email, $profile_image, $role, null, null,
				null, null, $nationality, null, null, null, null, null, null, null);
		sendActivationEmail($user_id);
		header("Location: complete_registration.php", true, 301);
		exit();
	} else {
		$_SESSION['submitStatus'] = trans("failed");
		$_SESSION['errors'] = [];
		$_SESSION['errors'] = $errors;
	}
}
// End Form Submit

include_once("header.php");
if (isset($_SESSION['waiting_for_payment']) && $_SESSION['waiting_for_payment']){
	$user_id = $_SESSION['waiting_for_payment'];
//	$user = $con->query("SELECT * from users WHERE user_id={$user_id}");
	$user = User::find($user_id);
//    $user_id = add_user($userName, $password, null, $country, $phone, $email, $profile_image, $role, null, null,
//        null, null, $nationality, null, null, null, null, null, null, null);
	$old_username = $user['user_name'];
	//die($old_username);
	$old_email = $user['email'];
	$old_country = $user['country_id'];
	$old_nationality = $user['nationality'];

//	$query = $con->query("select countryKey from countries where `id`='$old_country'");
	$row = Country::find($old_country);
	$old_key = $row['countryKey'];
	$old_phone = $user['phone'];
//	$con->query("DELETE from users WHERE user_id={$user_id}");
	User::where(['user_id'=>$user_id])->delete();
	unset($_SESSION['waiting_for_payment']);
}
?>
<script src="https://credimax.gateway.mastercard.com/checkout/version/57/checkout.js"
        data-error="errorCallback"
        data-cancel="<?=$siteUrl?><?=$RELATIVE_PATH?>signup.php"
        data-complete="<?=$siteUrl?><?=$RELATIVE_PATH?>response.php">//cancelCallback
</script>
<div class="section is-hero has-gradient position-relative overflow-hidden is-subpage">
    <div class="container position-relative">
        <div class="row">
            <div class="col-12 block-centered text-align-center col-lg-7 col-md-12" style="margin-top: 5vh !important;">
                <h1 class="signup-header"><?=trans('signToAlhamayel')?></h1>
            </div>
        </div>
    </div>
</div>
<div class="section has-bg-accent">
    <div class="c-shadowtext" style="font-size: 65px;"><?=trans('joinUs')?></div>
    <form id="join-form" action="signup.php" method="post" enctype="multipart/form-data">
        <div class="container">
            <label class="alert alert-danger w-100 <?=errExist()?'':'d-none'?>"><?=trans('form_errors')?></label>
            <h2 style="margin-bottom: 20px; font-size: 20px; <?php if($lang == 'ar') echo "text-align: right !important; "; ?>"><?=trans('joinUsNow')?></h2>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="c-financial-finder text-align-center">
                        <h3 class="text-left">1- <?=trans('choosePlan')?></h3>
                        <div class="plans flex-wrap justify-content-center">
							<?php
							$highlight = DBPlan::highlight()->first();
							$plans = DBPlan::active()->orderBy('price', 'asc')->get();
							$active = isset($_POST['plan_id'])?DBPlan::find($_POST['plan_id']):$highlight;
							foreach($plans as $plan){
								include(__DIR__.'/include/selectable_plan_card.php');
							}
							?>
                        </div>
                    </div>
                </div>
                <div class="col-12 text-center text-large <?=$active->popular ? '' : 'd-none' ?>" id="mostpopular">
                    <div class="c-horizontal-form">
                        <br>
                        <input  name="mostpopular" type="checkbox" style="width:20px; height: 20px;">
                        <strong> <?=trans('join_most_pop')?></strong> ( +1 <?=trans('usd')?> )
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="container">
            <h3 class="text-left">2- <?=trans('registered_info')?></h3>
            <hr>
            <div class="row">
                <div class="form-group col-md-6 col-12 order-md-1 order-1">
                    <label><span><?=trans('username')?></span> <span style="color: red;">*</span></label>
                    <input type="text" class="form-control <?=inputErr('username')?'is-invalid':''?>"
                           maxlength="256" name="username"  placeholder="<?=trans('username')?>" id="username"
                           value="<?=isset($_POST['username'])?$_POST['username']:(isset($old_username)? $old_username:'')?>" required autocomplete="off">
                    <div class="invalid-feedback"><?=inputErr('username')?:''?></div>
                </div>
                <div class="form-group col-md-6 col-12 order-md-3 order-2">
                    <label><span><?=trans('password')?></span> <span style="color: red;">*</span></label>
                    <div class="input-group">
                        <input type="password" class="form-control  <?=inputErr('password')?'is-invalid':''?>"
                                maxlength="256" name="password"  placeholder="<?=trans('password')?>" id="password"  autocomplete="off" required />
                        <div class="input-group-append">
                            <a type="buttton" class="input-group-text show-password" style="background: #fff; border-left: 0; cursor: pointer;"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                        </div>
                        <div class="invalid-feedback"><?=inputErr('password')?:''?></div>
                    </div>
                </div>
                <div class="form-group col-md-6 col-12 order-md-5 order-3">
                    <label><span ><?=trans('confirmPassword')?></span> <span style="color: red;">*</span></label>
                    <div class="input-group">
                        <input type="password" value="" required class="form-control <?=inputErr('password')?'is-invalid':''?>" maxlength="256" name="cpass"
                                placeholder="<?=trans('confirmPassword')?>"  id="cpass"  autocomplete="off" />
                        <div class="input-group-append">
                            <a type="buttton" class="input-group-text show-password" style="background: #fff; border-left: 0; cursor: pointer;"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                        </div>
                        <div class="invalid-feedback"><?=inputErr('password')?:''?></div>
                    </div>
                </div>
                <div class="form-group col-md-6 col-12 order-md-7 order-4">
                    <label><span> <?=trans('email')?></span> <span style="color: red;">*</span></label>
                    <input type="email" class="form-control email-confirm <?=inputErr('email')?'is-invalid':''?>" maxlength="256" name="email"  placeholder="<?=trans('email')?>" id="email"
                           required=""  autocomplete="off" value="<?=isset($_POST['email'])?$_POST['email']:(isset($old_email)? $old_email:'')?>"  />
                    <div class="invalid-feedback"><?=inputErr('email')?:''?></div>
                </div>
                <div class="form-group col-md-6 col-12 order-md-2 order-5">
                    <label><span><?=trans('confirmEmail')?></span> <span style="color: red;">*</span></label>
                    <input type="email" class="form-control email-confirm <?=inputErr('email')?'is-invalid':''?>" maxlength="256" name="confirmEmail"  placeholder="<?=trans('confirmEmail')?>"
                           id="emailConfirmation" required value="<?=isset($_POST['confirmEmail'])?$_POST['confirmEmail']:(isset($old_email)? $old_email:'')?>"/>
                    <div class="invalid-feedback"><?=inputErr('email')?:''?></div>
                </div>
                <div class="form-group col-md-6 col-12 order-md-4 order-6">
                    <label><span><?=trans('chooseCountry')?></span> <span style="color: red;">*</span></label>
                    <select   id="country" name="country" class="form-control <?=inputErr('country')?'is-invalid':''?>" required>
                        <option value=""><?=trans('chooseCountry')?></option>
						<?php
						$countries = Country::active()->get();
						foreach($countries as $country){
							$selected  = (isset($_POST['country'])?($_POST['country'] == $country["id"] ?'selected':''):($visitor_country == $country['name_en'] ? 'selected' : (isset($old_country) && $old_country == $country['id']? 'selected':'')));
							echo "<option value='{$country["id"]}' " . $selected . ">".db_trans($country, 'name')."</option>";
						}
						?>
                    </select>
                    <div class="invalid-feedback"><?=inputErr('country')?:''?></div>
                </div>
                <div class="form-group col-md-6 col-12 order-md-9 order-8">
                    <label><span><?=trans('chooseNationality')?></span> <span style="color: red;">*</span></label>
                    <select id="nationality" name="nationality" class="form-control <?=inputErr('nationality')?'is-invalid':''?>" required>
                        <option value=""><?=trans('chooseNationality')?></option>
						<?php
						$nationalities = Nationality::all();
						foreach($nationalities as $nationality){
							$selected = (isset($_POST['nationality'])?($_POST['nationality'] == $nationality["id"] ?'selected':''):(strpos($nationality['name'],$visitor_country) !==  false ? 'selected' : (isset($old_nationality) && $old_nationality == $nationality['id'] ? 'selected': '')));
							echo "<option value='{$nationality["id"]}' " . $selected . ">{$nationality['name']}</option>";
						} ?>
                    </select>
                    <div class="invalid-feedback"><?=inputErr('nationality')?:''?></div>
                </div>
                <div class="form-group col-md-6 col-12 order-md-6 order-7">
                    <label><span><?=trans('phone')?></span> <span style="color: red;">*</span></label>
                    <div class="c-horizontal-form phone-section-joinus flex-wrap" style="width:100%;">
                        <input   type="number" class="form-control <?=inputErr('key')?'is-invalid':''?>" id="key" maxlength="256" name="key"  placeholder="<?=trans('key')?>"
                                 autocomplete="off" value="<?=isset($_POST['key'])?$_POST['key']:isset($old_key)? $old_key:""?>" style="max-width: 100px; " required/>
                        <input   required type="text" class="form-control <?=inputErr('phone')?'is-invalid':''?>" maxlength="10" name="phone"
                                 placeholder="<?=trans('phone')?>" id="phone" title="Only Numbers" style="width: calc(100% - 102px);"
                                 autocomplete="off" value="<?=isset($_POST['phone'])?$_POST['phone']:isset($old_phone)? $old_phone:''?>" /><!-- pattern="[1-9]{1}[0-9]{9}" -->
                        <div class="invalid-feedback"><?=inputErr('key')?:''?><br><?=inputErr('phone')?:''?></div>
                    </div>
                </div>

            </div>
        </div>
        <div class="container">

            <div class="payment-container">
				<?php
				$row = $active;
				?>
                <div class="row">
                    <div class="col-12">
                        <h3 class="text-left">3- <?=trans('payment')?></h3>
                    </div>
                    <div class="col-12">
                        <div class="col-12">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link pay-nav-link active pl-2 pr-2" id="credit-tab" data-toggle="tab" href="#" data-target="#credit" role="tab" aria-controls="credit" aria-selected="true"><?=trans('pay_with_card')?></a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link pay-nav-link pl-2 pr-2" id="wire-tab" data-toggle="tab" href="#" data-target="#wire" role="tab" aria-controls="wire" aria-selected="false"><?=trans('pay_with_wire')?></a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active bg-light p-3" id="credit" role="tabpanel" aria-labelledby="credit-tab">
                                <h4 class="w-100 mb-2"><?=trans('card_payment')?></h4>
                                <div class="col-md-12 col-12">
                                    <div class="c-horizontal-form">
                                        <input type="checkbox" style=" width: 15px !important; height: 15px !important;" name="confirmCorrect"  placeholder="" id="confirmCorrect" required />
                                        <span style="font-size: 20px !important;"><?=trans('certify')?></span>
                                    </div>
                                </div>
                                <div class="col-md-12 col-12">
                                    <div class="c-horizontal-form">
                                        <input type="checkbox" style=" width: 15px !important; height: 15px !important;" name="acceptTerms"  placeholder="" required id="terms">
                                        <span style="font-size: 20px !important;"><?=trans('acceptTerms');?>
                                            <a href="terms.php" style="color: blue !important;" target="_blank"><?php echo trans('terms_conditions'); ?></a>
                                        </span>
                                    </div>
                                </div>
                                <div class="p-3 text-left">
                                    <button class="button-primary animated d-inline-block"><?=trans('submit')?></button>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="wire" role="tabpanel" aria-labelledby="wire-tab">
                                <?php include "include/bank_transfer.php" ?>
                                <div class="col-md-12 col-12">
                                    <div class="c-horizontal-form">
                                        <input type="checkbox" style=" width: 15px !important; height: 15px !important;" name="confirmCorrect1"  placeholder=""  id="confirmCorrect1"/>
                                        <span style="font-size: 20px !important;"><?=trans('certify')?></span>
                                    </div>
                                </div>
                                <div class="col-md-12 col-12">
                                    <div class="c-horizontal-form">
                                        <input type="checkbox" style=" width: 15px !important; height: 15px !important;" name="acceptTerms1"  placeholder=""  id="terms1"> <span style="font-size: 20px !important;">
											<?=trans('acceptTerms');?>
                                            <a href="terms.php" style="color: blue !important;" target="_blank"><?php echo trans('terms_conditions'); ?></a>
                                        </span>
                                    </div>
                                </div>
                                <div class="w-100 text-left mt-1">
                                    <button class="button-primary animated d-inline-block"
                                            name="formSubmit"><?=trans('submit')?></button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-md-12 col-12 mt-3">
                <label class="alert alert-danger d-none form-errors w-100"><?=trans('fix_form')?></label>
            </div>
            <div class="col-md-12 col-12 d-none submit-form">
                <div class="c-horizontal-form">
                    <input type="checkbox" style=" width: 15px !important; height: 15px !important;" name="confirmCorrect2"  placeholder="" id="confirmCorrect2" />
                    <span style="font-size: 20px !important;"><?=trans('certify')?></span>
                </div>
            </div>
            <div class="col-md-12 col-12 d-none submit-form">
                <div class="c-horizontal-form">
                    <input type="checkbox" style=" width: 15px !important; height: 15px !important;" name="acceptTerms2"  placeholder=""  id="terms2">
                    <span style="font-size: 20px !important;"><?=trans('acceptTerms');?>
                        <a href="terms.php" style="color: blue !important;" target="_blank"><?php echo trans('terms_conditions'); ?></a>
                    </span>
                </div>
            </div>
            <div class="w-100 text-left d-none mt-1 submit-form">
                <button class="button-primary animated d-inline-block"
                        name="formSubmit"><?=trans('submit')?></button>
            </div>
        </div>
        <input type="hidden" name="payment_type" value="credit">
        <input type="hidden" name="plan_id" value="<?=isset($_POST['plan_id']) && $_POST['plan_id'] !== ''?$_POST['plan_id']:'1'?>">

    </form>
</div>
<?php
include_once("footer.php");
?>
<script>
    let form_errors = [];
    $('#username').focusout(function(){
        let username_input = $(this);
        let username = username_input.val();
        let lang = "<?=$lang?>";

        if(username.length > 0){
            $.ajax({
                type: 'post',
                url: 'api/global.php',
                data: {
                    username: username,
                    lang: lang,
                    x: 1
                },
                dataType: 'Text',
                cache: false
            }).done(function(res){
                if(res.length > 0){
                    username_input.addClass('is-invalid');
                    username_input.next($('.invalid-feedback')).html(res);
                    form_errors.push('username');
                } else {
                    username_input.removeClass('is-invalid');
                    username_input.next($('.invalid-feedback')).html("");
                    form_errors = form_errors.filter(function(el){return el != 'username'})
                }
            })
        }
        else {
            username_input.removeClass('is-invalid');
            username_input.next($('.invalid-feedback')).html("");
            form_errors = form_errors.filter(function(el){return el != 'username'})
        }

    }),

    $('input[type=password]').focusout(function(){
        let password = $('input[name=password]').val();
        let cpass = $('input[name=cpass]').val();
        if(cpass.length > 0 && password.length > 0){
            if(cpass !== password){
                $('input[type=password]').each(function () {
                    $(this).addClass('is-invalid');
                    $(this).nextAll('.invalid-feedback').html("Passwords Don't Match");
                    form_errors.push('cpass');
                });
            } else {
                $('input[type=password]').each(function () {
                    $(this).removeClass('is-invalid');
                    $(this).nextAll('.invalid-feedback').html("");
                    form_errors = form_errors.filter(function(el){return el != 'cpass'});
                });
            }
        }
        else {
            $('input[type=password]').each(function () {
                $(this).removeClass('is-invalid');
                $(this).nextAll('.invalid-feedback').html("");
                form_errors = form_errors.filter(function(el){return el != 'cpass'});
            });
        }

    })

    $('.email-confirm').focusout(function(){
        let emailConfirmation = $('#emailConfirmation').val();
        let email = $('#email').val();
        let lang = "<?=$lang?>";
        if(emailConfirmation.length > 0 && email.length > 0){
            if(emailConfirmation !== email){
                $('.email-confirm').each(function () {
                    $(this).addClass('is-invalid');
                    $(this).next($('.invalid-feedback')).html("Emails Don't Match");
                });
                form_errors.push('email');
            } else {
                $('.email-confirm').each(function () {
                    $(this).removeClass('is-invalid');
                    $(this).next($('.invalid-feedback')).html("");
                });
                form_errors = form_errors.filter(function(el){return el != 'email'});
            }
        }
        else {
            $('.email-confirm').each(function () {
                $(this).removeClass('is-invalid');
                $(this).next($('.invalid-feedback')).html("");
            });
            form_errors = form_errors.filter(function(el){return el != 'email'});
        }

    });

    $(".show-password").click(function(){
        const input = $(this).parent().prev()[0];
        if(input.type === "text") input.type = "password";
        else input.type = "text";
    })

    $('body').on('submit', '#join-form',function(){
        let payment_type = $("input[name=payment_type]").val();
        let errors_label = $('.form-errors').first();
        if (form_errors.length > 0 ){
            errors_label.removeClass('d-none');
            event.preventDefault();
            return false;
        }
        else {
            errors_label.addClass('d-none');
        }
        pay();
    });
    $('#country').change(function(){
        let country = $(this).val();
        // alert(country);
        $.ajax({
            type: 'POST',
            url: 'api/global.php',
            data: {
                country: country,
                ajax: 'key'
            },
            dataType: 'Text',
            cache: false
        }).done(function(res){
            $('#key').val(res);
        })
    })
    $('#credit-tab').on('shown.bs.tab', function (){
        $("input[name=payment_type]").val('credit');
        $('#terms').prop('required', true);
        $('#confirmCorrect').prop('required', true);
        $('#terms1').prop('required', false);
        $('#confirmCorrect1').prop('required', false);
    });
    $('#wire-tab').on('shown.bs.tab', function () {
        $("input[name=payment_type]").val('wire');
        $('#terms').prop('required', false);
        $('#confirmCorrect').prop('required', false);
        $('#terms1').prop('required', true);
        $('#confirmCorrect1').prop('required', true);
    })
    $(document).ready(function (){
        let url = location.href;
        url = new URL(url);
        let plan = url.searchParams.get("plan");
        if (plan){
            if ($('.joinus-plan-'+plan).length){
                $('.joinus-plan-'+plan).click();
            }
        }
        $('#country').change();
    });
</script>
<script>
    function errorCallback(error) {
        //alert(JSON.stringify(error));
        console.log(JSON.stringify(error));
    }
    function cancelCallback() {
    }
    function pay(){
        event.preventDefault();
        let errors_label = $('.form-errors').first();
        if (form_errors.length > 0 ){
            errors_label.removeClass('d-none');
            return false;
        }
        else {
            errors_label.addClass('d-none');
        }
        let form = $('#join-form').serializeArray();
        let data = {total: 1};
        for(let i=0; i<form.length; i++){
            data[form[i]['name']] = form[i]['value']
        }
        $.ajax({
            type: 'post',
            url: 'api/gen.php',
            data: data,
            dataType: 'Json',
            cache: false
        }).done(function(res){
            if (res.status){
                if (res.done){
                    location.href="<?=$siteUrl.$RELATIVE_PATH?>login.php"
                } else {
                    Checkout.configure({
                        merchant   : 'E15701950',
                        order      : {
                            amount     : '0.1' ,

                            //function () { //Dynamic calculation of amount
                            //            return 80 + 20
                            //        },
                            currency   : 'USD',//BHD
                            description: 'Plan Subscription',
                            id: res.data.ref
                        },
                        session: {
                            id:res.data.session_id
                        },
                        interaction: {
                            merchant      : {
                                name   : 'Alhamayel Family Tree',
                                address: {
                                    line1: 'Bahrain'
                                },
                                email  : 'support@alhamayel.com',
                                phone  : '0097317300000',
                                logo   : 'https://localhost/images/logo.png'
                            },
                            operation: 'AUTHORIZE',
                            locale        : 'en_US',//ar_SAen_US
                            theme         : 'default',
                            displayControl: {
                                billingAddress  : 'HIDE',//OPTIONAL  READ_ONLY  MANDATORY
                                customerEmail   : 'HIDE',
                                orderSummary    : 'HIDE',
                                shipping        : 'HIDE'
                            }
                        }
                    });
                    requestAnimationFrame(function() {
                        requestAnimationFrame(function () {
                            Checkout.showLightbox();
                        });
                    });

                    console.log(res);
                }
            }
            else {
                if(res.error == 1){
                    Swal.fire({
                        title: '<?=trans("error")?>!',
                        width: 400,
                        text: res.message,
                        icon: 'error',
                        confirmButtonText: "<?=trans('ok')?>"
                    });
                }
                else if (res.error == 0){
                    let errors = res.errors;
                    let keys = Object.keys(errors);
                    let count = keys.length;
                    for(let i =0; i < count; i++){
                        let inp = $("input[name="+keys[i]+"]");
                        inp = (inp.length ?inp:$("select[name="+keys[i]+"]"));
                        inp.addClass('is-invalid');
                        inp.nextAll('.invalid-feedback').html(errors[keys[i]]);
                    }

                }
            }
        }).fail(function(jqXHR, textStatus, errorThrown){ //replaces .error
            Swal.fire({
                title: '<?=trans("error")?>!',
                width: 400,
                text: "<?=trans('payment_gateway_error')?>",
                icon: 'error',
                confirmButtonText: "<?=trans('ok')?>"
            });
            // console.log("error");
            // console.dir(arguments);
        });
    }

</script>
<script>
    $('body').on('change', 'input[name=mostpopular]', function () {
        if($(this).prop('checked')) {
            let plan_price = parseInt($('.joinus-plan.active-plan').find('.plan-price').attr('data-value'));
            $('.total-price-with-mostpopular').html(1+plan_price);
            $('.most-popular-pricing').removeClass('d-none');
        }else {
            $('.most-popular-pricing').addClass('d-none');
        }
    });
    $('body').on('click', '.joinus-plan', function (){
        $('.joinus-plan').removeClass('active-plan');
        $(this).addClass('active-plan');
        if($(this).hasClass('has-mostpopular')){
            $('#mostpopular').removeClass('d-none');
        }
        else {
            $('#mostpopular').addClass('d-none');
            $('input[name=mostpopular]').prop('checked', false);
        }
        let plan_name = $(this).find('.plan-title_1').html();
        let plan_id = $(this).find('.plan-title_1').attr('data-value');
        let plan_price = $(this).find('.plan-price').attr('data-value');
        let payment_container = $('.payment-container');
        $('input[name=plan_id]').val(plan_id);
        $('input[name=plan_price]').val(plan_price);
        payment_container.find('.payment-plan-name').html(plan_name);
        payment_container.find('.payment-plan-price').html(plan_price);
        if (parseInt(plan_price) == 0){
            payment_container.addClass('d-none');
            $('.submit-form').removeClass('d-none');
            $('#terms').prop('required', false);
            $('#confirmCorrect').prop('required', false);
            $('#terms1').prop('required', false);
            $('#confirmCorrect1').prop('required', false);
            $('#terms2').prop('required', true);
            $('#confirmCorrect2').prop('required', true);

        }
        else {
            $('#terms2').prop('required', false);
            $('#confirmCorrect2').prop('required', false);
            payment_container.removeClass('d-none');
            $('.submit-form').addClass('d-none');
            $('.pay-nav-link').first().click();
        }
    });
</script>
<?php
unset($_SESSION['submitStatus']);
unset($_SESSION['message']);
unset($_SESSION['errors']);
?>
