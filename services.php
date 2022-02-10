<?php
include_once(__DIR__."/config.php");
include_once(__DIR__."/lib/Mailer.php");
include_once(__DIR__."/header.php");
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
	else if($name == "" || $email == ""){
		$error = trans('name_email_required');
	}
	else if (! in_array($type, ['book', 'studio', 'magazine', 'account'])){
		$error = trans("invalidServiceType");
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
$rowSetting = Setting::find(1);
?>
<link rel="stylesheet" href="css/service.css">
<script src="//www.google.com/recaptcha/api.js" async defer></script>

<input type="hidden" id="error" value="<?=isset($error)?$error:''?>">
<input type="hidden" id="success" value="<?=isset($success)?$success:''?>">
<div class="section is-hero has-gradient position-relative overflow-hidden is-subpage">
    <div class="container position-relative">
        <div class="col block-centered text-align-center lg-7 md-12" style="margin-top: 5vh !important;">
            <h1><?=trans('services')?></h1>

        </div>
    </div>
</div>
<?php
include(__DIR__."/include/services.php");
include(__DIR__."/include/features.php");
?>
<div class="modal" id="modal5" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-3 ml-auto" style="text-align: center;" id="featureTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
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
                <div class="modal-footer" style="margin: auto;">
                    <button type="submit" class="btn hbtn btn-hred" name="featureSubmit">Submit</button>
                    <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="close5">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include"footer.php";?>

<script>

    $(document).ready(function(){
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

        //   if(success.length > 0){
        //       alert(success);
        //   }

    })

</script>
