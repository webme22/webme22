<?php
include_once(__DIR__."/config.php");
include_once(__DIR__."/functions/global.php");
include_once(__DIR__."/lib/Mailer.php");
if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != ''){
	header("location: profile.php");
	exit();
}
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Validation\RequiredConstraintsViolated;
use Lcobucci\JWT\Validation\Constraint\IdentifiedBy;

$config = Configuration::forSymmetricSigner(
		new Sha256(),
		InMemory::base64Encoded($APP_KEY));
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$token = isset($_POST['token']) ? $_POST['token'] : "";
	$username = isset($_POST['username']) ? $_POST['username'] : "";
	$config->setValidationConstraints(new IdentifiedBy($username));
	$parsed_token = $config->parser()->parse($token);
	$constraints = $config->validationConstraints();
	try {
		$config->validator()->assert($parsed_token, ...$constraints);
		$password = isset($_POST['password']) ? $_POST['password'] : "";
		$password_confirm = isset($_POST['password_confirm']) ? $_POST['password_confirm'] : "";
		$errors = [];
		if(empty($password)){
			$errors['password'] =  trans("password_required");
		}
		if(empty($password_confirm)){
			$errors['password_confirm'] = trans("password_confirm_required");
		}
		if (! empty($password) && ! empty($password_confirm) && $password != $password_confirm){
			$errors['password'] =  trans("password_mismatch");
			$errors['password_confirm'] = trans("password_mismatch");
		}
		if(empty($errors)){
			if(strlen($password) < 6){
				$errors['password'] =  trans("password_min_6");
			}
			else {
				$user = User::where(['user_name'=>$username])->first();
				if($user){
					$user->update(['user_password'=>password_hash($password, PASSWORD_DEFAULT)]);
					$_SESSION['registration_successful'] = trans("reset_success");
					header("Location: login.php");
					exit();
				}
			}
		}
		$valid = true;
	} catch (RequiredConstraintsViolated $e) {
		$valid = false;
	}
}
else {
	$token = isset($_GET['token']) ? $_GET['token'] : "";
	$username = isset($_GET['username']) ? $_GET['username'] : "";
	$config->setValidationConstraints(new IdentifiedBy($username));
	$parsed_token = $config->parser()->parse($token);
	$constraints = $config->validationConstraints();
	try {
		$config->validator()->assert($parsed_token, ...$constraints);
		$valid = true;
	} catch (RequiredConstraintsViolated $e) {
		$valid = false;
	}
}

include_once (__DIR__."/header.php");
?>
<div class="section is-hero has-gradient position-relative overflow-hidden is-subpage">
    <div class="container position-relative">
        <div class="col block-centered text-align-center lg-7 md-12" style="margin-top: 5vh !important;">
            <h1><?=trans('reset_password')?></h1>
        </div>
    </div>
</div>
<div class="section has-bg-accent position-relative pt-0"  style="background-color: #f7f7f7;height: auto;">
    <div class="container pt-4">

		<?php if ($valid) { ?>
            <div class="row justify-content-center">
                <div class="col-12 col-md-8">
                    <form method="post" class="form">
                        <input type="hidden" value="<?=$token?>" name="token">
                        <input type="hidden" value="<?=$username?>" name="username">
                        <div class="form-group">
                            <label ><?=trans('new_password')?></label>
														<div class="input-group">
															<input type="password" class="form-control <?php if(isset($errors['password'])){ echo 'is-invalid'; } ?>" name="password" required>
															<div class="input-group-append">
                                <a type="buttton" class="input-group-text show-password" style="background: #fff; border-left: 0; cursor: pointer;"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                              </div>
                            <?php
                            if(isset($errors['password'])){?>
                                <div class="invalid-feedback display-block">
                                    <?=$errors['password']?>
                                </div>
														<?php
                            }
                            ?>
														</div>
                        </div>
                        <div class="form-group">
                            <label ><?=trans('confirmPassword')?></label>
														<div class="input-group">
															<input type="password" class="form-control <?php if(isset($errors['password_confirm'])){ echo 'is-invalid'; } ?>" name="password_confirm" required>
															<div class="input-group-append">
																	<a type="buttton" class="input-group-text show-password" style="background: #fff; border-left: 0; cursor: pointer;"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
															</div>
															<?php
															if(isset($errors['password_confirm'])){?>
																								<div class="invalid-feedback display-block">
																	<?=$errors['password_confirm']?>
																								</div>
																<?php
															}
															?>
														</div>
                        </div>
                        <div class="form-group text-center">
                            <input type="submit" value="<?=trans('submit')?>" class="button-primary is-small">
                        </div>
                    </form>
                </div>
            </div>
		<?php } else { ?>
            <div class="row">
                <div class="col-12">
                    <label class="alert alert-danger mt-5 mb-5 w-100">
                        Invalid or Expired Link, Please request a new link<br>
                        <a href="home.php">Go Home</a> or
                        <a href="login.php">Login Page</a>
                    </label>
                </div>
            </div>
		<?php } ?>
    </div>
</div>

<?php
include_once (__DIR__."/footer.php");
?>
<script>
	$(".show-password").click(function(){
			const input = $(this).parent().prev()[0];
			if(input.type === "text") input.type = "password";
			else input.type = "text";
	})
</script>