<?php
include_once(__DIR__."/config.php");
include_once(__DIR__."/header.php");
?>
<style type="text/css">
    <!--
    input[type=text], input[type=email] {
        margin-bottom: 15px;
    }
    -->
</style>
<?php
if(isset($_POST['submit'])){
    $client = trim($_POST['client']);
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $message = trim($_POST['message']);
    if(! filter_var($email, FILTER_VALIDATE_EMAIL)){
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
?>
<div class="section is-hero has-gradient position-relative overflow-hidden is-subpage">
    <div class="container position-relative">
        <div class="col block-centered text-align-center lg-7 md-12">
            <h1>Contact Us</h1>
            <div class="padding-left padding-right margin-bottom is-heading-color" style="font-size: 16px !important;">Innovation and simplicity makes us happy: our goal is to remove any technical or financial barriers that can prevent business owners from making a website.</div>
        </div>
    </div>
</div>
<div class="section has-bg-accent position-relative"  style="background-color: #f7f7f7;height: auto;background-repeat: no-repeat;background-size: cover;">
    <div class="c-shadowtext"><?=trans('contact')?></div>
    <br>
    <div class="container margin-bottom-quad" style="direction: <?=$dir?>;margin-top:-100px;">
        <div class="col lg-5 alignself-center md-12">
	    <?php
	    $contact = Contact::find(1);
	    ?>
            <strong><?=trans('address')?></strong><br />
            <!--<?=trans('addrCont')?>-->
	    <?=trans('address')?>
            <br /><br />
            <strong><?=trans('sales')?></strong><br />
	    <?php echo $contact['sales_email']; ?><br />
	    <?php echo $contact['sales_num']; ?><br />
            <br />
            <strong><?=trans('support')?></strong><br />
	    <?php echo $contact['support_email']; ?>
        </div>
        <input type="hidden" id="submitMessage" value="<?=$success?>">
        <p style="color: red; font-size: 20px;"><?=$error?></p>
        <div class="col lg-1 no-margin-bottom"></div>
        <div class="col lg-6 md-12" style="padding-top: 50px;text-align: left;">
            <div class="col lg-6 md-12" style="padding-top: 50px;text-align: left;">
                <div class="w-form" >
                    <form method="POST" action="contactus.php">
                        <input type="text" class="form-input-text no-margin-bottom-lg margin-right md-no-margin-lr w-input" maxlength="256" name="client" data-name="Zip code" placeholder="<?=trans('name')?>" id="Username" required="" autocomplete="off" />
                        <input type="email" class="form-input-text no-margin-bottom-lg margin-right md-no-margin-lr w-input" maxlength="256" name="email"  placeholder="<?=trans('email')?>" id="Username" required="" autocomplete="off" />
                        <input type="text" class="form-input-text no-margin-bottom-lg margin-right md-no-margin-lr w-input" maxlength="256" name="mobile"  placeholder="<?=trans('mobile')?>" id="Username" required="" autocomplete="off" />
                        <textarea rows="4" class="form-input-text no-margin-bottom-lg margin-right md-no-margin-lr w-input" placeholder="<?=trans('message')?>" name="message"></textarea>
                        <br /><br />
                        <button class="button-primary animated w-inline-block" style="font-weight: 600; text-align: center; letter-spacing: 0.15em;" type="submit" name="submit">
			    <?=trans('send')?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include"footer.php";?>
