<?php
include_once(__DIR__.'/config.php');
if(isset($_POST['submit'])){
	include_once(__DIR__.'/lib/recaptchalib.php');
	$client = trim($_POST['client']);
	$email = trim($_POST['email']);
	$mobile = trim($_POST['mobile']);
	$message = trim($_POST['message']);
	$captcha_token = $_POST['g-recaptcha-response'];
	$resp = check_recaptcha ($RECAPTCHA, $captcha_token);
	if (!$resp) {
		$error = trans("wrong_captcha");
	}
	else if(! filter_var($email, FILTER_VALIDATE_EMAIL)){
		$error = trans("invalidEmail");
	} else {
		Message::create([
				'type'=> '1',
				'client_name'=>$client,
				'client_email'=>$email,
				'content'=>$message,
				'viewed'=> '0',
				'reply'=> null,
				'date' => date('Y-m-d')
		]);
		$success = trans("sent");
	}
}
include_once(__DIR__."/header.php");
?>
<script src="//www.google.com/recaptcha/api.js" async defer></script>

<div class="section is-hero has-gradient position-relative overflow-hidden is-subpage">
	<div class="container position-relative">
		<div class="col block-centered text-align-center lg-7 md-12" style="margin-top: 4vh !important;">
			<h1><?=trans('about_us')?></h1>
			<div class="text-center padding-<?php echo $align; ?> padding-<?php echo $align2; ?> margin-bottom is-heading-color text-light"
                 style="font-size: 1.8em !important; width: 100% !important;margin: auto !important;" >

				<?= trans('about_site') ?>

			</div>
		</div>
	</div>
</div>

<div class="section has-bg-accent position-relative"  style="background-color: #f7f7f7;height: auto;background-repeat: no-repeat;background-size: cover;">
	<div class="container">
		<div class="row">
			<div class="col-12 col-lg-5 alignself-center col-md-12 address_section" style="margin-top: -100px;">
				<?php
				$contact = Contact::find(1);
				?>
				<strong style="font-size: 1.8em;"><?=trans('address')?></strong><br />
				<span style="font-size: 1.5em; color: #000;"><?php
				if($lang == 'en'){
				 echo str_replace(' , ', "<br>", $contact['address']);
				} else {
					echo "بلوك : ٣١٦ <br> مبنى : ١٠٤ <br> مركز المنامة <br> صندوق بريد ١٥٣";
				}
				  ?></span>
				<br><br>
				<strong style="font-size: 1.8em;"> <?= ucwords(trans('alhamayel_team')) ?> </strong><br />
				<span style="font-size: 1.5em; color: #000;"><?php echo $contact['sales_email']; ?></span></br>
				<span style="font-size: 1.5em; color: #000; unicode-bidi: embed; direction: ltr;"><?php 
				if($lang == 'en'){
				echo $contact['sales_num'];
				} else {
					echo "+۹۷۳ ۱۷۸۲۰۷۰۲";
				}
				 ?></span><br />
			</div>
			<input type="hidden" id="submitMessage" value="<?php echo $success; ?>">
			<input type="hidden" id="submitError" value="<?php echo $error; ?>">
			<div class="col-12 col-lg-6 col-md-12" style="padding-top: 50px;text-align: left;">
				<div class="col lg-6 md-12" style="padding-top: 50px;text-align: left;">
					<div class="w-form" >
						<h3 style="text-align: <?= $align ?>"><?= ucwords(trans('contact_form')) ?></h3>
						<form method="POST" action="about.php">
							<input type="text" class="form-input-text no-margin-bottom-lg margin-right md-no-margin-lr w-input remove_borders" maxlength="256" name="client" data-name="Zip code" placeholder="<?=$languages[$lang]['name']?>" id="Username" required="" autocomplete="off" />
							<input type="email" class="form-input-text no-margin-bottom-lg margin-right md-no-margin-lr w-input remove_borders" maxlength="256" name="email"  placeholder="<?=$languages[$lang]['email']?>" id="Username" required="" autocomplete="off" />
							<input type="text" class="form-input-text no-margin-bottom-lg margin-right md-no-margin-lr w-input remove_borders" maxlength="256" name="mobile"  placeholder="<?=$languages[$lang]['mobile']?>" id="Username" required="" autocomplete="off" />
							<textarea rows="4" class="form-input-text no-margin-bottom-lg margin-right md-no-margin-lr w-input remove_borders" placeholder="<?=$languages[$lang]['message']?>" name="message"></textarea>
                            <div class="g-recaptcha" data-sitekey="6LfJc-obAAAAANrRhwAcYLO5gvtjAPGLIKMU1hqa"></div>

                            <br /><br />
							<button class="button-primary animated w-inline-block" style="font-weight: 600; background-color: #666453;" type="submit" name="submit">
								<?=$languages[$lang]['send']?>
							</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include"footer.php";?>
<script>
    $(document).ready(function(){
        let message = $('#submitMessage').val();
        if(message.length > 0){
            Swal.fire({
                title: 'Success',
                width: 400,
                icon: 'success',
                text: `${message}`,
                confirmButtonText: 'Ok'
            })
        }
        let error = $('#submitError').val();
        if(error.length > 0){
            Swal.fire({
                title: 'Error!',
                width: 400,
                text: `${error}`,
                icon: 'error',
                confirmButtonText: 'Ok'
            })
        }
    })
</script>
