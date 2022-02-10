<?php
include_once(__DIR__."/config.php");
middleware('user_not_complete');
$user_id=current_user();
$user = User::find($user_id);

if ($user['verified'] != 1){
    $step = 0;
}
else if ($user['family_id'] == -1 && $user['role'] == 'creator'){
    $step = 1;
    if(isset($_POST['formSubmit'])){
        $errors = [];
        $required_fields = ['name', 'familyNameAr', 'familyNameEn', 'fdesc', 'fDescEn', 'fstatus', 'gender', 'DOB'];
        $errors = validate($required_fields);
        $name = trim($_POST["name"]);
        $fArName = trim($_POST["familyNameAr"]);
        $fEnName = trim($_POST["familyNameEn"]);
        $fArDesc = trim($_POST["fdesc"]);
        $fEnDesc = trim($_POST["fDescEn"]);
        $fStatus = trim($_POST["fstatus"]);
        $kunya = ($_POST["kunya"] != '')? trim($_POST["kunya"]) : null;;
        $occupation = ($_POST["occupation"] != '')? trim($_POST["occupation"]) : null;
        $gender = $_POST['gender'];
        $DOB = $_POST['DOB'];
        $facebook = ($_POST["facebook"] != '')? trim($_POST["facebook"]) : null;
        $twitter = ($_POST["twitter"] != '')? trim($_POST["twitter"]) : null;
        $instagram = ($_POST["instagram"] != '')? trim($_POST["instagram"]) : null;
        $snapchat = ($_POST["snapchat"] != '')? trim($_POST["snapchat"]) : null;
        $club_name = ($_POST["club_name"] != '')? trim($_POST["club_name"]) : null;
        $interests = ($_POST["interests"] != '')? trim($_POST["interests"]) : null;
        $about = ($_POST["about"] != '')? trim($_POST["about"]) : null;
        if($_POST["mostpopular"] != 1){
            $mostPopular = 0;
        } else {
            $mostPopular = 1;
        }
        if(checkFamilyExists($fArName, 0)){
            $errors['familyNameAr'] = trans("familyExists");
        }
        if(empty($errors)){

            if(! file_exists("uploads/users/" . $user_id)){
                mkdir("uploads/users/" . $user_id, 0775, true);
            }
            $imagedatabase = "images/default-user.png";
            if(isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])){
                $imageName = $_FILES['image']['name'];
                $imageTmpName = $_FILES['image']['tmp_name'];
                $target_path = "uploads/users/" . $user_id . "/" . round(microtime(true)) . ".jpg";
                $imagedatabase =  "uploads/users/" . $user_id . "/" . round(microtime(true)) . ".jpg";
                move_uploaded_file($imageTmpName, $target_path);
                // crop image
                if (isset($_POST['image_cropping'])){
                    $cropping_details = json_decode($_POST['image_cropping'], true);
                    $imagick = new \Imagick(realpath($target_path));
                    $imagick->cropImage($cropping_details['width'], $cropping_details['height'], $cropping_details['x'], $cropping_details['y']);
                    $imagick->writeImage(realpath($target_path));
                }


            }
            $logodatabase = "";
            if(isset($_FILES['logo']['name']) && !empty($_FILES['logo']['name'])){
                $logoName = $_FILES['logo']['name'];
                $logoTmpName = $_FILES['logo']['tmp_name'];
                $logo_path = "uploads/users/" . $user_id . "/logo_" . round(microtime(true)) . ".jpg";
                $logodatabase = "uploads/users/" . $user_id . "/logo_" . round(microtime(true)) . ".jpg";
                move_uploaded_file($logoTmpName, $logo_path);

                // crop image
                if (isset($_POST['club_cropping'])){
                    $cropping_details = json_decode($_POST['club_cropping'], true);
                    $imagick = new \Imagick(realpath($logo_path));
                    $imagick->cropImage($cropping_details['width'], $cropping_details['height'], $cropping_details['x'], $cropping_details['y']);
                    $imagick->writeImage(realpath($logo_path));
                }
            }

            $family = Family::where(['user_id'=>$user_id])->first();
            $family_id = $family['id'];
            $data= ["family_id"=>$family_id,"name"=>$name,"club_logo"=>$logodatabase,"image"=>$imagedatabase,
                    "kunya"=>$kunya,"occupation"=>$occupation,"gender"=>$gender,"date_of_birth"=>$DOB,
                    "facebook"=>$facebook,"twitter"=>$twitter,"instagram"=>$instagram,"snapchat"=>$snapchat,
                    "club_name"=>$club_name,"interests"=>$interests,"about"=>$about];
            $fArName = str_replace('عائله', '', $fArName);
            $fArName = str_replace('عائلة', '', $fArName);
            $fEnName = str_replace('family', '', $fEnName);
            $fEnName = str_replace('Family', '', $fEnName);
            $family_data=["name_ar"=>$fArName,"name_en"=>$fEnName,"desc_ar"=>$fArDesc,"desc_en"=>$fEnDesc,
                    "status"=>$fStatus,"mostpopular"=>$mostPopular];
            User::find($user_id)->update($data);
            Family::find($family_id)->update($family_data);
            $_SESSION['family_id'] = $family_id;
            $_SESSION['submitStatus'] = trans("success");
            $_SESSION['message'] = trans("verify");
            $_POST = [];
            $step = 2;
            echo '<meta http-equiv="refresh" content="5;url=profile.php">';
        } else {
            $_SESSION['submitStatus'] = trans("failed");
            $_SESSION['errors'] = [];
            $_SESSION['errors'] = $errors;
        }
    }
}
include_once("header.php");
?>
<link rel="stylesheet" href="<?=asset('css/steps.css')?>">
<div class="section is-hero has-gradient position-relative overflow-hidden is-subpage">
    <div class="container position-relative">
        <div class="row">
            <div class="col-12 block-centered text-align-center col-lg-7 col-md-12" style="margin-top: 5vh !important;">
                <h1 class="signup-header"><?=trans('almost_there')?></h1>
            </div>
        </div>
    </div>
</div>
<div class="section pt-1">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-left p-0 mb-2">
                <div class="card px-0 pt-4 pb-0 mt-3 mb-3 bg-transparent">
                    <h2 id="heading"><?=tranS('you_almost_there')?></h2>
                    <p><?=trans('not_long')?></p>
                    <div class="steps-container">
                        <ul id="progressbar">
                            <li class="active" id="account"><strong><?=trans('activate_email')?></strong></li>
                            <li class="<?=$step>0?'active':''?>" id="payment"><strong><?=trans('your_info')?></strong></li>
                            <li class="<?=$step>1?'active':''?>" id="confirm"><strong><?=trans('finish')?></strong></li>
                        </ul>
                        <br>
                    </div>
                        <?php if($step == 0){ ?>
                            <div class="text-left bg-light p-5">
                                <h5><?=trans('activation_set')?> <a href="mailto:<?=$user['email']?>" target="_blank"><?=$user['email']?></a>.<br>
                                    <?=trans('check_email')?></h5>
                            </div>
                        <?php }else if ($step == 2) {?>
                    <div class="section pt-3"  style="height: auto;background-image: url();background-repeat: no-repeat;background-size: cover;">
                        <label class="alert w-100 alert-success"><?=trans('access_Family')?><br>
                            <?=trans('will_redirect')?><br>
                        <?=trans('redirect_now')?> <a href="profile.php?lang=<?=$lang?>"><?=trans('family_tree')?></a></label>
                    </div>
                    <?php }else if ($step == 1) {?>
                        <div class="section has-bg-accent pt-3"  style="background-color: #f7f7f7;height: auto;background-image: url();background-repeat: no-repeat;background-size: cover;">

                        <form id="join-form" action="complete_registration.php?lang=<?php echo $lang;?>" method="post" enctype="multipart/form-data" class="form">
                                <div class="container">
                                    <label class="alert alert-danger w-100 <?=errExist()?'':'d-none'?>"><?=trans('form_errors')?></label>
                                    <label class="alert alert-success w-100 <?=isset($_SESSION['submitStatus']) && ! errExist()?'':'d-none'?>"><?=trans('register_success')?></label>
                                </div>
                                <div class="container margin-bottom-quad" style="vertical-align: top;top: 0;margin-bottom: 0;">
                                    <div class="row">
                                        <div class="col-lg-12 alignself-center col-md-12">
                                            <div class="w-form" >
                                                <h3 class="text-left"><?=trans('registered_family_info')?></h3>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>
                                                            <span style="text-align: left;color: #808080; font-size: 17px;"><?=trans('familyNameEn')?></span> <span style="color: red;">*</span>
                                                        </label>
                                                        <input   type="text" required class="form-control <?=inputErr('familyNameEn')?'is-invalid':''?>" maxlength="256" name="familyNameEn"  placeholder="<?=trans('familyNameEn')?>"
                                                                 id="fnameEn"  autocomplete="off" value="<?=isset($_POST['familyNameEn'])?$_POST['familyNameEn']:""?>" style="margin-bottom: 0px !important;">
                                                        <div class="invalid-feedback"><?=inputErr('familyNameEn')?:''?></div>
                                                    </div>
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>
                                                            <span style="text-align: left;color: #808080; font-size: 17px;"><?=trans('familyDescEn')?></span> <span style="color: red;">*</span>
                                                        </label>
                                                        <textarea  class=" form-control <?=inputErr('fDescEn')?'is-invalid':''?>" style="height: 48px;"  name="fDescEn"  required placeholder="<?php echo trans("familyDescEn"); ?>"
                                                                   id="fDescEn"    autocomplete="off"><?=isset($_POST['fDescEn'])?$_POST['fDescEn']:""?></textarea>
                                                        <div class="invalid-feedback"><?=inputErr('fDescEn')?:''?></div>
                                                    </div>
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>
                                                        <span style="text-align: left;color: #808080; font-size: 17px;"><?=trans('familyNameAr')?></span> <span style="color: red;">*</span>
                                                        </label>
                                                        <input   type="text" required class="form-control <?=inputErr('familyNameAr')?'is-invalid':''?>" maxlength="256" name="familyNameAr"  placeholder="<?=trans('familyNameAr')?>"
                                                                 id="fnameAr"  autocomplete="off" value="<?=isset($_POST['familyNameAr'])?$_POST['familyNameAr']:""?>" style="height: 48px;">
                                                        <div class="invalid-feedback"><?=inputErr('fnameAr')?:''?></div>
                                                    </div>
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>
                                                        <span style="text-align: left;color: #808080; font-size: 17px;"><?=trans('familyDescAr')?></span> <span style="color: red;">*</span>
                                                        </label>
                                                        <textarea  class=" form-control <?=inputErr('fdesc')?'is-invalid':''?>" style="    height: 48px;"  name="fdesc" required  placeholder="<?php echo trans("familyDescAr"); ?>"
                                                                   id="fdesc"   autocomplete="off"><?=isset($_POST['fdesc'])?$_POST['fdesc']:""?></textarea>
                                                        <div class="invalid-feedback"><?=inputErr('fdesc')?:''?></div>
                                                    </div>
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>
                                                        <span style="text-align: left;color: #808080; font-size: 17px;"><?=trans('fStatus')?></span> <span style="color: red;">*</span>
                                                        </label>
                                                        <select id="fstatus"  required name="fstatus" class="form-control <?=inputErr('fstatus')?'is-invalid':''?>" >
                                                            <option value=""><?=trans('fStatus')?></option>
                                                            <option value="0" <?=isset($_POST['fstatus'])?($_POST['fstatus'] == "0" ?'selected':''):""?>><?=trans('private')?></option>
                                                            <option value="1" <?=isset($_POST['fstatus'])?($_POST['fstatus'] == "1" ?'selected':''):""?>><?=trans('public')?></option>
                                                        </select>
                                                        <div class="invalid-feedback"><?=inputErr('fstatus')?:''?></div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <h3 class="text-left"><?=trans('registered_info')?></h3>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>
                                                            <span style="text-align: left;color: #808080; font-size: 17px;"><?=trans('name')?></span> <span style="color: red;">*</span>
                                                        </label>
                                                        <input type="text" class="form-control <?=inputErr('name')?'is-invalid':''?>" maxlength="256" name="name"  placeholder="<?=trans('name')?>" id="name" required   autocomplete="off" value="<?=isset($_POST['name'])?$_POST['name']:""?>" />
                                                        <div class="invalid-feedback"><?=inputErr('name')?:''?></div>
                                                    </div>
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>
                                                            <span style="text-align: left;color: #808080; font-size: 17px;"><?=trans('DOB')?></span> <span style="color: red;">*</span>
                                                        </label>
                                                        <input type="date" class="form-control <?=inputErr('DOB')?'is-invalid':''?>" name="DOB"  id="DOB" required max="<?php echo date('Y-m-d'); ?>" value="<?=isset($_POST['DOB'])?$_POST['DOB']:""?>"/>
                                                        <div class="invalid-feedback"><?=inputErr('DOB')?:''?></div>
                                                    </div>
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>
                                                            <span style="text-align: left;color: #808080; font-size: 17px;"><?=trans('gender')?></span> <span style="color: red;">*</span>
                                                        </label>
                                                        <select name="gender" class="form-control <?=inputErr('gender')?'is-invalid':''?>" required>
                                                            <option value=""><?=trans('gender')?></option>
                                                            <option value="Female" <?=isset($_POST['gender'])?($_POST['gender'] == 'Female' ?'selected':''):""?>><?=trans('female')?></option>
                                                            <option value="Male" <?=isset($_POST['gender'])?($_POST['gender'] == 'Male' ?'selected':''):""?>><?=trans('male')?></option>
                                                        </select>
                                                        <div class="invalid-feedback"><?=inputErr('gender')?:''?></div>
                                                    </div>
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>
                                                            <span style="text-align: left;color: #808080; font-size: 17px;"><?=trans('kunya')?></span>
                                                        </label>
                                                        <input type="text" class="form-control" maxlength="256" name="kunya"  placeholder="<?=trans('kunya')?>"   autocomplete="off" value="<?=isset($_POST['name'])?$_POST['name']:($updateMod?$uInfo['name']:"")?>" />
                                                    </div>

                                                    <div class="form-group col-md-6 col-12">
                                                        <span style="text-align: left;color: #808080; font-size: 17px;"><?=trans('occupation')?></span>
                                                        <input type="text" class="form-control" maxlength="256" name="occupation"  placeholder="<?=trans('occupation')?>"
                                                               autocomplete="off" value="<?=isset($_POST['occupation'])?$_POST['occupation']:''?>"/>
                                                    </div>
                                                    <div class="form-group col-md-6 col-12">
                                                        <span style="text-align: left;color: #808080; font-size: 17px;"><?=trans('facebook')?></span>
                                                        <input type="text" class="facebook form-control" maxlength="256" name="facebook"  placeholder="<?=trans('facebook')?>" value="<?=isset($_POST['facebook'])?$_POST['facebook']:''?>">
                                                        <div class="invalid-feedback"><?=inputErr('facebook')?:''?></div>
                                                    </div>
                                                    <div class="form-group col-md-6 col-12">
                                                        <span style="text-align: left;color: #808080; font-size: 17px;"><?=trans('twitter')?></span>
                                                        <input type="text" class="twitter form-control" maxlength="256" name="twitter"  placeholder="<?=trans('twitter')?>" value="<?=isset($_POST['twitter'])?$_POST['twitter']:''?>">
                                                        <div class="invalid-feedback"><?=inputErr('twitter')?:''?></div>

                                                    </div>
                                                    <div class="form-group col-md-6 col-12">
                                                        <span style="text-align: left;color: #808080; font-size: 17px;"><?=trans('instagram')?></span>
                                                        <input type="text" class="instagram form-control" maxlength="256" name="instagram"  placeholder="<?=trans('instagram')?>" value="<?=isset($_POST['instagram'])?$_POST['instagram']:''?>">
                                                        <div class="invalid-feedback"><?=inputErr('instagram')?:''?></div>
                                                    </div>
                                                    <div class="form-group col-md-6 col-12">
                                                        <span style="text-align: left;color: #808080; font-size: 17px;"><?=trans('snapchat')?></span>
                                                        <input type="text" class="snapchat form-control" maxlength="256" name="snapchat"  placeholder="<?=trans('snapchat')?>" value="<?=isset($_POST['snapchat'])?$_POST['snapchat']:''?>">
                                                        <div class="invalid-feedback"><?=inputErr('snapchat')?:''?></div>
                                                    </div>
                                                    <div class="form-group col-md-6 col-12">
                                                        <span class="text-left" style="color: #808080; font-size: 17px;"><?php echo trans("uploadPic"); ?></span>
                                                        <div class="row ml-0 mr-0">
                                                            <input type="hidden" name="image_cropping">
                                                            <input type="file" accept="image/jpeg" class="form-control has-preview col-9" maxlength="256" name="image"  id="img"  autocomplete="off" />
                                                            <div class="crop-preview preview col-3 p-0 ml-auto" data-target="#profile-pic-chooser">
                                                                <div class="profile-preview-div preview-div">
                                                                    <a href="#"  data-toggle="modal">
                                                                        <picture>
                                                                            <source srcset="<?=asset('images/default-user.webp')?>" type="image/webp">
                                                                            <source srcset="<?=asset('images/default-user.png')?>" type="image/png">
                                                                            <img class="profile-pic-image " height="100" src="<?=asset('images/default-user.png')?>">
                                                                        </picture>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6 col-12">
                                                        <span class="text-left" style="color: #808080; font-size: 17px;"><?php echo trans("uploadLogo"); ?></span>
                                                        <div class="row ml-0 mr-0">
                                                            <input type="hidden" name="club_cropping">
                                                            <input type="file" accept="image/jpeg" class="form-control has-preview col-9" maxlength="256" name="logo"  id="logo" autocomplete="off" />
                                                            <div class="crop-preview preview col-3 p-0 ml-auto" data-target="#club-pic-chooser">
                                                                <div class="club-preview-div preview-div">
                                                                    <a href="#"  data-toggle="modal">
                                                                        <picture>
                                                                            <source srcset="<?=asset('images/default-club.webp')?>" type="image/webp">
                                                                            <source srcset="<?=asset('images/default-club.png')?>" type="image/png">
                                                                            <img class="profile-pic-image " height="100" src="<?=asset('images/default-club.png')?>">
                                                                        </picture>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6 col-12">
                                                        <span style="text-align: left;color: #808080; font-size: 17px;"><?=trans('clubName')?></span>
                                                        <input type="text" class="form-control" maxlength="256" name="club_name" placeholder="<?php echo trans("clubName"); ?>" value="<?=isset($_POST['club_name'])?$_POST['club_name']:''?>"/>
                                                    </div>
                                                    <div class="form-group col-md-6 col-12">
                                                        <span style="text-align: left;color: #808080; font-size: 17px;"><?=trans('interests')?></span>
                                                        <textarea  class="form-control"  name="interests"  placeholder="<?php echo trans("interests"); ?>"><?=isset($_POST['interests'])?$_POST['interests']:''?></textarea>
                                                    </div>
                                                    <div class="form-group col-md-6 col-12">
                                                        <span style="text-align: left;color: #808080; font-size: 17px;"><?=trans('about_member')?></span>
                                                        <textarea  class="form-control"  name="about"  placeholder="<?php echo trans("about_member"); ?>"><?=isset($_POST['about'])?$_POST['about']:''?></textarea>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="container">
                                    <div class="col-md-12 col-12" style="margin-top:15px;">
                                        <label class="alert alert-danger d-none form-errors w-100"><?=trans('fix_form')?></label>
                                        <button type="submit" class="button-primary animated w-inline-block" style="letter-spacing: 0;" id="formSubmit" name="formSubmit"><?=trans('submit')?></button>
                                    </div>
                                </div>
                                <input type="hidden" name="payment_type" value="credit">
                            </form>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
include_once("footer.php");
?>
<?php if($step == 1) {?>
    <script src="<?=asset('js/bundle.js')?>"></script>
    <script>
        $(document).ready(function () {
            $('body').on('click', '.joinus-plan', function (){
                $('.joinus-plan').removeClass('active-plan');
                $(this).addClass('active-plan');
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
                }
                else {
                    payment_container.removeClass('d-none');
                }
            });
            var today = new Date();

            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0');
            var yyyy = today.getFullYear();

            today = yyyy + '-' + mm + '-' + dd;


            $('#DOB').prop("max", `${today}`);
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
            })

            $('input[type=password]').focusout(function(){
                let password = $('input[name=password]').val();
                let cpass = $('input[name=cpass]').val();
                if(cpass.length > 0 && password.length > 0){
                    if(cpass !== password){
                        $('input[type=password]').each(function () {
                            $(this).addClass('is-invalid');
                            $(this).next($('.invalid-feedback')).html("Passwords don't match");
                            form_errors.push('cpass');
                        });
                    } else {
                        $('input[type=password]').each(function () {
                            $(this).removeClass('is-invalid');
                            $(this).next($('.invalid-feedback')).html("");
                            form_errors = form_errors.filter(function(el){return el != 'cpass'});
                        });
                    }
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
                            $(this).next($('.invalid-feedback')).html("Emails don't match");
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
            });

            $('#fdesc').focusout(function() {
                let desc = $(this).val();
                //let lang = "<?//=$lang?>//";
                if (desc!= "" && !isArabic(desc)) {
                    $(this).addClass('is-invalid');
                    $(this).next($('invalid-feedback')).html("<?=trans('language_mismatch')?>");
                    form_errors.push('desc');
                    return false;
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).next($('invalid-feedback')).html("");
                    form_errors = form_errors.filter(function (el) {
                        return el != 'desc'
                    });


                }
            });
            $('#fDescEn').focusout(function() {
                let desc = $(this).val();
                //let lang = "<?//=$lang?>//";
                if (desc!= "" && !isEnglish(desc)) {
                    $(this).addClass('is-invalid');
                    $(this).next($('invalid-feedback')).html("<?=trans('language_mismatch')?>");
                    form_errors.push('fDescEn');
                    return false;
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).next($('invalid-feedback')).html("");
                    form_errors = form_errors.filter(function (el) {
                        return el != 'fDescEn'
                    });


                }
            });

            $('#fnameAr').focusout(function(){
                let name = $(this).val();
                let lang = "<?=$lang?>";
                if (name != "" && ! isArabic(name)){
                    $(this).addClass('is-invalid');
                    $(this).next($('invalid-feedback')).html("<?=trans('language_mismatch')?>");
                    form_errors.push('name');
                    return false;
                }
                else {
                    $(this).removeClass('is-invalid');
                    $(this).next($('invalid-feedback')).html("");
                    form_errors = form_errors.filter(function(el){return el != 'name'});
                }
                if(name.length > 0){
                    $.ajax({
                        type: 'post',
                        url: 'api/global.php',
                        data: {
                            nameAr: name,
                            lang: lang
                        },
                        dataType: 'Text',
                        cache: false
                    }).done(function(res){
                        if(res.length > 0){
                            $('#fnameAr').addClass('is-invalid');
                            $('#fnameAr').next($('invalid-feedback')).html(res);
                            form_errors.push('name');
                        } else {
                            $('#fnameAr').removeClass('is-invalid');
                            $('#fnameAr').next($('invalid-feedback')).html("");
                            form_errors = form_errors.filter(function(el){return el != 'name'});
                        }
                    })
                }
            });

            $('#fnameEn').focusout(function(){
                let name = $(this).val();
                let lang = "<?=$lang?>";
                if (name != "" && ! isEnglish(name)){
                    $(this).addClass('is-invalid');
                    $(this).next($('invalid-feedback')).html("<?=trans('language_mismatch')?>");
                    form_errors.push('namen');
                    return false;
                }
                else {
                    $(this).removeClass('is-invalid');
                    $(this).next($('invalid-feedback')).html("");
                    form_errors = form_errors.filter(function(el){return el != 'namen'});
                }
                if(name.length > 0){
                    $.ajax({
                        type: 'post',
                        url: 'api/global.php',
                        data: {
                            nameEn: name,
                            lang: lang
                        },
                        dataType: 'Text',
                        cache: false
                    }).done(function(res){
                        if(res.length > 0){
                            $('#fnameEn').addClass('is-invalid');
                            $('#fnameEn').next($('.invalid-feedback')).html(res);
                            form_errors.push('namen');
                        } else {
                            $('#fnameEn').removeClass('is-invalid');
                            $('#fnameEn').next($('.invalid-feedback')).html("");
                            form_errors = form_errors.filter(function(el){return el != 'namen'});
                        }
                    })
                }
            })

            $('.facebook').focusout(function(){
                let fb = $(this).val();
                if(fb.length > 0 && ! fb.includes("facebook.com")){
                    $(this).addClass('is-invalid');
                    $(this).next($('.invalid-feedback')).html("<?=trans('invalid_facebook')?>.");
                    form_errors.push('facebook');
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).next($('.invalid-feedback')).html("");
                    form_errors = form_errors.filter(function(el){return el != 'facebook'});
                }
            });

            $('.twitter').focusout(function(){
                let twitter = $(this).val();
                if(twitter.length > 0 && ! twitter.includes("twitter.com")){
                    $(this).addClass('is-invalid');
                    $(this).next($('.invalid-feedback')).html("<?=trans('invalid_twitter')?>.");
                    form_errors.push('twitter');
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).next($('.invalid-feedback')).html("");
                    form_errors = form_errors.filter(function(el){return el != 'twitter'});
                }
            })

            $('.instagram').focusout(function(){
                let instagram = $(this).val();
                if(instagram.length > 0 && ! instagram.includes("instagram.com")){
                    $(this).addClass('is-invalid');
                    $(this).next($('.invalid-feedback')).html("<?=trans('invalid_instagram')?>.");
                    form_errors.push('instagram');
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).next($('.invalid-feedback')).html("");
                    form_errors = form_errors.filter(function(el){return el != 'instagram'});

                }
            });
            $('.snapchat').focusout(function(){
                let snapchat = $(this).val();
                if(snapchat.length > 0 && ! snapchat.includes("snapchat.com")){
                    $(this).addClass('is-invalid');
                    $(this).next($('.invalid-feedback')).html("<?=trans('invalid_snapchat')?>.");
                    form_errors.push('snapchat');
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).next($('.invalid-feedback')).html("");
                    form_errors = form_errors.filter(function(el){return el != 'snapchat'});
                }
            });
            $('body').on('submit', '#join-form',function(){
                let errors_label = $('#formSubmit').prev($('.form-errors'));
                if (form_errors.length > 0 ){
                    errors_label.removeClass('d-none');
                    event.preventDefault();
                }
                else {
                    errors_label.addClass('d-none');
                }
            });
        });
        $(document).on('keyup change', 'input[name=familyNameEn],textarea[name=fDescEn]', function () {
            let what_changed = $(this);
            var to_change;
            if (what_changed.attr('name') == 'familyNameEn'){
                to_change = $("input[name=familyNameAr]");
            }
            else {
                to_change = $("textarea[name=fdesc]");
            }
            translate(what_changed.val(), { to: "ar" })
                .then(res => {
                    // I do not eat six days
                    to_change.val(res.text);
                })
                .catch(err => {
                    console.error(err);
                });
        })
    </script>
<?php } ?>
<?php
unset($_SESSION['submitStatus']);
unset($_SESSION['message']);
unset($_SESSION['errors']);
?>
