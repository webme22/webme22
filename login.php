<?php
include_once(__DIR__."/config.php");
include_once(__DIR__."/lib/Mailer.php");
include_once(__DIR__."/lib/Plan.php");
if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != ''){
    header("location: profile.php");
    exit();
}
if(isset($_POST['completeData'])){
    $userName = trim($_POST['name']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);
    $user = $_POST['userId'];
    $family = $_POST['familyId'];
    $errorRegis = null;
    if(checkUserNameExists($userName, 0)){
        $errorRegis = "Failed to complete info, User Name Already Exists";
    } else if($confirmPassword != $password){
        $errorRegis = "Password and Password Confirmation don't match .";
    } else {
		$update = User::where(['user_id'=>$user])
                    ->where(['family_id'=>$family])
                    ->whereNull('user_name')
                    ->whereNull('user_password')->update([
                'user_name'=>$userName,
                'user_password' => password_hash($password,PASSWORD_DEFAULT)
        ]);
        if(isset($_FILES['photo']['name']) && $_FILES['photo']['name'] != ''){

            $imageName = $_FILES['photo']['name'];
	        $imageTmpName = $_FILES['photo']['tmp_name'];
            if (!file_exists("uploads/users/" . $user)) {
                mkdir("uploads/users/" . $user, 0775, true);
            }
            $target_path = "uploads/users/" . $user . "/" . round(microtime(true)) . ".jpg";
            $imagedatabase = "uploads/users/" . $user . "/" . round(microtime(true)) . ".jpg";
            $update->image = $imagedatabase;
            $update->save();
            move_uploaded_file($imageTmpName, $target_path);

        }
        if($update){
            $family_creator = Family::find($family)->user_id;
            $creator = Family::find($family)->creator;
            $assistant = User::find($user);
            $mailer = new Mailer();
            $mailer->setVars(['user_name'=>$creator['name'], 'assistant_name'=>$assistant['name'], 'assistant_id'=>$assistant['user_id']]);
            $mailer->sendMail([$creator['email']], "Assistant accepted invitation",
                'assistant_accept.html', 'assistant_accept.txt');
            $success = "Registration Completed Successfully, You can login now .";
        }
    }
}
if(isset($_GET['activation'])){
    $userName = $_GET['activation'];
	$users = User::verified()->get();
	foreach($users as $user){
        if(password_verify($user['user_name'], $userName)){
            $id = $user['user_id'];
            $alreadyVerified = true;
            break;
        }
    }
    if(!$alreadyVerified){
		$users = User::notverified()->get();
		foreach($users as $user){
            if(password_verify($user['user_name'], $userName)){
                $id = $user['user_id'];
                $user->update(['verified'=>true]);

            }
        }
    }
}
if(isset($_POST['loginSubmit'])){
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $error = "";
    $user = User::where(['user_name'=>$username])->first();
    if(check_if_family_deleted($user['family_id']) == 0){
        if($user && password_verify($password, $user->user_password)){
            $user_family = $user->family;
            $family_plan = $user_family->plan;
            if ($family_plan->price > 0 && $user_family->payment->payment_type == 'wire'  && ! $user_family->payment->confirmed){
                $error = trans("wireTransferWait");
            }
            else {
				session_regenerate_id(true);
                $_SESSION['user_id'] = $user->user_id;
                $_SESSION['name'] = $user->name;
                $_SESSION['user_name'] = $user->user_name;
                $_SESSION['email'] = $user->email;
                $_SESSION['phone'] = $user->phone;
                $_SESSION['image'] = $user->image;
                $_SESSION['family_id'] = $user->family_id;
                $_SESSION['country_id'] = $user->country_id;
                $_SESSION['cover'] = $user->cover;
                $_SESSION['role'] = $user->role;
                $_SESSION['waiting_to_join'] = $user->waiting_to_join;
                if(! $user->verified){
                    header("Location: complete_registration.php");
                    exit();
                }
                else {
                    header("Location: profile.php");
                    exit();
                }
            }
        } else {
            $error = trans("userNotExist");
        }
    } else {
        $error = trans('family_deleted');
    }
}
include_once(__DIR__."/header.php"); ?>
<div class="section is-hero has-gradient position-relative overflow-hidden is-subpage">
    <div class="container position-relative">
        <div class="col block-centered text-align-center lg-7 md-12" style="margin-top: 5vh !important;">
            <h1><?=trans('loginWelcome')?></h1>
        </div>
    </div>
</div>
<input type="hidden" id="success" value="<?php echo $success; ?>">
<input type="hidden" id="error" value="<?php echo $errorRegis; ?>">
<div class="section has-bg-accent position-relative pt-0"  style="background-color: #f7f7f7;height: auto;">
    <div class="c-shadowtext" style="font-size: 65px;bottom:0; top:unset"><?=trans("login"); ?></div>
    <div class="container margin-bottom-quad" style="padding-top: 40px;direction: <?=$dir?>;">
        <div class="row p-2">
            <div class="col lg-6 alignself-center md-12">
                <div class="c-horizontal-form">
                </div>
                <p style="padding: 10px !important; background-color: #ffdede; color: #2d3841; font-size: 20px; <?php if($lang == 'ar') echo "text-align: right !important;"; if(empty($error)) echo "display: none;";?>"><?php echo $error; ?></p>
                <br /><br />
                <?php if (isset($_SESSION['registration_successful'])) { ?>
                    <label class="alert alert-success w-100"><?= $_SESSION['registration_successful'] ?></label>
                    <?php
                    unset($_SESSION['registration_successful']);
                }
                ?>
                <?php if (isset($alreadyVerified) && $alreadyVerified==true) { ?>
                    <label class="alert alert-warning w-100"><?= trans('already_activated') ?></label>
                    <?php
                }
                ?>
                <h2 style="margin-bottom: 50px; <?php if($lang == 'ar') echo "text-align: right !important; "; ?>"><?=trans('loginToAccount')?></h2>
                <div class="w-form">
                    <form action="login.php?lang=<?php echo $lang; ?>" method="post" class="form">
                        <div class="form-group">
                            <input type="text" class="form-control" maxlength="256" name="username" data-name="Zip code" placeholder="<?=trans('username')?>" id="Username" required="" autocomplete="off"/>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <input type="password" value="" class="form-control" maxlength="256" name="password" data-name="Zip code" placeholder="<?=trans('password')?>" id="password" value="" required="" autocomplete="off"/>
                                <div class="input-group-append">
                                    <a type="buttton" class="input-group-text show-password" style="background: #fff; border-left: 0; cursor: pointer;"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="button-primary animated w-inline-block" style="letter-spacing: 0;"
                                name="loginSubmit"><?=trans('login')?></button>
                    </form>
                    <br />
                    <div class="form text-left">
                        <button id="myBtn" class="m-auto"><?=trans('forgetPassword') ?></button><br><br>
                        <a href="signup.php" class="m-auto" style="background: rgb(239, 239, 239); color: rgb(74, 74, 74) !important;
                        padding: 1%;"><?=trans("dontHaveAccount")?></a>
                    </div>


                    <div class="w-form-done" style="display: none;">
                        <div>Thank you! Your submission has been received!</div>
                    </div>
                    <?php if(!$msg){?>
                        <div class="w-form-fail" style="display: none;">
                            <div><?=trans('fMsg')?></div>
                        </div>
                    <?php }?>
                    <br /><br />
                    <?php if(!empty($sMsg)){?>
                        <div class="w-form-done" style="display: none;">
                            <div><?=$sMsg?></div>
                        </div>
                    <?php }?>

                    <?php if(!empty($fMsg)){?>
                        <div class="w-form-fail" style="display: none;">
                            <div><?=$fMsg?></div>
                        </div>
                    <?php }?>

                    <a data-w-id="8f4d69e5-b8ce-97df-5a1b-3cfdfd580f57" href="#" class="button-primary animated w-inline-block" style="margin: 0 auto; display: none;">
                        <div style="-webkit-transform:translate3d(0, 0PX, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, 0PX, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, 0PX, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, 0PX, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:1" class="button-primary-text "><?=trans('login')?></div>
                        <div style="opacity:0;display:block;-webkit-transform:translate3d(0, 20PX, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, 20PX, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, 20PX, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, 20PX, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0)" class="button-primary-text for-hover"><?=trans('login')?></div>
                    </a>
                </div>
                <br /><br />

            </div>
            <div class="col lg-6 md-12 text-center" style="padding-top: 50px;">
                <picture>
                    <source srcset="<?=asset('images/login_'.$lang.'.webp')?>" type="image/webp">
                    <source srcset="<?=asset('images/login_'.$lang.'.png')?>" type="image/png">
                    <img src="<?=asset('images/login_'.$lang.'.png')?>" style="max-width: 80%;" />
                </picture>

            </div>
        </div>


    </div>
    <!-- The Modal -->
    <div id="myModal" class="modal fade">
        <div class="modal-dialog">
            <!-- Modal content -->
            <div class="modal-content" >
                <div class="modal-header">
                    <h5 class="modal-title mt-3 ml-auto"><?= trans('reset_password_or_username') ?></h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
                <!--<form method="post" id="forgetPassword">-->
                <!--<p style="text-align:center;"> <?=trans('enterEmail')?>-->
                <div class="modal-body">
                    <input type="email" class="form-control" maxlength="256" name="resetEmail" placeholder="<?=trans('email')?>" id="email" required="" autocomplete="off" /><br />
                    <!-- <input type="text" class="form-control" maxlength="256" name="name" placeholder="<?=trans('username')?>" id="name" autocomplete="off" /><br /> -->
                    <!-- <br> -->
                    <div class="modal-footer">
                        <button id="forgetPassword" class="btn hbtn btn-hred" style="letter-spacing: 0;margin: 0 auto;" style="text-transform: none;"><?=trans('send')?></button>
                    </div>
                </div>
                <!--</form>-->
            </div>
        </div>
    </div>
    <div id="modal3" class="modal">
        <!-- Modal content -->
        <div class="modal-content" >
            <div class="modal-body">
                <?php if(isset($_SESSION['loginFailed'])){ ?>
                    <p><?php echo $_SESSION['loginFailed']; ?></p>
                <?php } ?>
            </div>
            <div class="modal-footer">
                <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
                <button id="closeModal3" class="button-primary animated w-inline-block" style="letter-spacing: 0;margin: 0 auto;"><?=trans('close')?></button>
            </div>
        </div>
    </div>
    <div class="modal" id="modal5">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="text-align: center; font-size: 15px !important;"><?= trans('complete_data') ?></h5>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <input type="hidden" value="" name="familyId" id="familyId">
                        <input type="hidden" value="" name="userId" id="userId">
                        <div class="form-group">
                            <label><?= trans('username') ?></label>
                            <input type="text" class="form-control" required name="name" style="font-size: 15px !important;" id="username">
                            <span class="d-none user_name_exists"><?= trans('usernameExists') ?></span>
                        </div>

                        <div class="form-group">
                            <label><?= trans('password') ?></label>
                            <div class="input-group">
                                <input type="password" class="form-control" required name="password" style="font-size: 15px !important;" id="strangerPassword">
                                <div class="input-group-append">
                                    <a type="buttton" class="input-group-text show-password" style="background: #fff; border-left: 0; cursor: pointer;"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?= trans('confirmPassword') ?></label>
                            <div class="input-group">
                                <input type="password" class="form-control" required name="confirmPassword" style="font-size: 15px !important;" id="confirmPassword">
                                <div class="input-group-append">
                                    <a type="buttton" class="input-group-text show-password" style="background: #fff; border-left: 0; cursor: pointer;"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                </div>
                                <span class="d-none passwords_match"><?= trans('passwordMatch') ?></span>
                            </div>
                        </div>
                        <!-- <div class="form-group">
                            <label><?=trans('profile_picture')?></label>
                            <input type="file" class="form-control" name="photo">
                        </div> -->

                </div>
                <div class="modal-footer" style="margin: auto;">
                    <button type="submit" class="btn btn-primary" name="completeData" style="font-size: 15px !important;"><?= trans('submit') ?></button>
                    <!--<button type="button" class="btn btn-secondary" data-dismiss="modal" id="close5" style="font-size: 15px !important;">Close</button>-->
                </div>
                </form>
            </div>
        </div>
    </div>

</div>


</div>
<?php include"footer.php";?>

<script>
    $(document).ready(function(){
        let success = $('#success').val();
        if(success.length > 0){
            // setTimeout(function(){
            //   location.href = `login.php`;  
            // }, 3000)
        }
        let error = $('#error').val();
        if(error.length > 0){
            Swal.fire({
                title: 'Error !',
                icon: 'error',
                text: `${error}`,
                confirmButtonText: 'Ok'
            })
        }
        let url = location.href;
        
        url = new URL(url);
        let user = url.searchParams.get("flag");
        let family = url.searchParams.get("belong");
        let delete_family = url.searchParams.get('status');

        if(user && family && success.length == 0){
            family = family - 20;
            user = user - 10;
            $('#familyId').val(family);
            $('#userId').val(user);
            $.ajax({
                type: 'post',
                url: 'api/global.php',
                data : {
                    check_user: user
                },
                dataType: 'Text',
                cache: false
            }).done(function(res){
                if(res == 1){
                    $('#modal5').modal('show');
                }
            })
        }

        if(delete_family && delete_family.includes('delete')){
            Swal.fire({
                title: 'Success',
                icon: 'success',
                text: `Family deleted successfully`,
                confirmButtonText: 'Ok'
            }).then(function(){
                location.href = location.href.replace(location.search,'');
            })
        }
        $('body').on('click', '#close5', function(){
            $('#modal5').modal('hide');
        })
        $('#username').mouseleave(function(){
            let username = $(this).val();
            let lang ="<?=$lang?>";
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
                        $('#username').addClass('input-error');
                        $('.user_name_exists').removeClass('d-none');
                    } else {
                        $('#username').removeClass('input-error');
                        $('.user_name_exists').addClass('d-none');
                    }
                })
            }
        })
        $("#confirmPassword").mouseleave(function(){
            let password = $('#strangerPassword').val();
            let confirmation = $(this).val();

            if(password !== confirmation){
                $('#confirmPassword').addClass('input-error');
                $('.passwords_match').removeClass('d-none');
            } else {
                $('#confirmPassword').removeClass('input-error');
                $('.passwords_match').addClass('d-none');
            }
        })
        $(".show-password").click(function(){
            const input = $(this).parent().prev()[0];
            if(input.type === "text") input.type = "password";
            else input.type = "text";
        })
        $('body').on('click', '#closeModal3', function(){
            $('#modal3').modal('hide');
            <?php
            unset($_SESSION['loginFailed']);
            ?>
        })
        $('body').on('click', '#forgetPassword', function(){
            let email = $('#email').val();
            // let name = $('#name').val();
            let lang = "<?=$lang?>";
            if(email != ''){
                if(! validateEmail(email)){
                    let message = 'Invalid Email Address';
                    if(lang.includes('ar')){
                        message = 'هذا البريد الالكترونى غير صحيح';
                    }
                    Swal.fire({
                        title: 'Error!',
                        width: 400,
                        text: `${message}`,
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    })
                    return false;
                }
                $.ajax({
                    type: 'post',
                    url : 'api/global.php',
                    data: {
                        lang: lang,
                        // name: name,
                        memberFamily: family,
                        email: email
                    },
                    dataType: 'Text',
                    cache: false
                }).done(function(res){
                    Swal.fire({
                        title: 'Info!',
                        width: 400,
                        text: `${res}`,
                        icon: 'info',
                        confirmButtonText: "<?=trans('ok')?>"
                    })
                    // console.log(res);
                })
            }
            $('#myModal').modal('hide');
            $('#email').val("");
            // $('#name').val("");
        })
    })
    function validateEmail(email, lang){
        let reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        if (reg.test(email) == false)
        {
            return false;
        }
        return true;
    }
</script>
