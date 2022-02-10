<?php
include_once("config.php");
include_once("functions/translation.php");
include_once(__DIR__."/lib/Plan.php");
middleware('user');
$family_id = $_SESSION['family_id'];
$family_plan = new Plan($family_id);
$remaining_days = $family_plan->remaining_days();
$per_day = 1 / 365;
$price = ceil($remaining_days * $per_day);
include_once("header.php");
?>
<script src="https://credimax.gateway.mastercard.com/checkout/version/57/checkout.js"
	data-error="errorCallback"
	data-cancel="<?=$siteUrl?><?=$RELATIVE_PATH?>mostpopular.php"
	data-complete="<?=$siteUrl?><?=$RELATIVE_PATH?>mostpopular_response.php">
</script>

<div class="section is-hero has-gradient position-relative overflow-hidden is-subpage">
    <div class="container position-relative">
	<div class="col block-centered text-align-center lg-7 md-12" style="margin-top: 5vh !important;">
	    <h1><?=trans('mostpopular_subscribe')?></h1>
	</div>
    </div>
</div>
<div class="section has-bg-accent position-relative"  style="background-color: #f7f7f7;height: auto;padding-top:0px; <?php

if($lang == 'ar'){
    echo "direction: rtl";
} else {
    echo "direction: ltr";
}


?>">
    <div class="container p-2">
	<br>
	<form id="upgrade-form" method="POST">
	    <div class="payment-container">
		<div class="row">
		    <div class="col-12">
			<h3 class="text-left"><?=trans('mostpopular_subscribe')?> ( <?=$price?> <?=trans('usd')?>)</h3>
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
					    <a href="terms.php?lang=<?php echo $lang; ?>" style="color: blue !important;" target="_blank"><?php echo trans('terms_conditions'); ?></a>
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
					    <a href="terms.php?lang=<?php echo $lang; ?>" style="color: blue !important;" target="_blank"><?php echo trans('terms_conditions'); ?></a>
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
	    <input type="hidden" name="payment_type" value="credit">
	    <input type="hidden" name="plan_id" value="<?=isset($_POST['plan_id']) && $_POST['plan_id'] !== ''?$_POST['plan_id']:'-1'?>">
	    <div class="col-md-12 col-12 mt-3">
		<label class="alert alert-danger d-none form-errors w-100"><?=trans('fix_form')?></label>
	    </div>
	</form>
    </div>
</div>
<?php
include_once("footer.php");
?>
<script>
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
    });
    $('body').on('submit', '#upgrade-form',function(){
        pay();
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
        let form = $('#upgrade-form').serializeArray();
        let data = {total: 1};
        for(let i=0; i<form.length; i++){
            data[form[i]['name']] = form[i]['value']
        }
        $.ajax({
            type: 'post',
            url: 'api/mostpopular_gen.php?lang=<?=$lang?>',
            data: data,
            dataType: 'Json',
            cache: false
        }).done(function(res){
            if (res.status){
                if (res.done){
                    location.href="<?=$siteUrl.$RELATIVE_PATH?>login.php"
                }
                else {
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
                        inp.next($('.invalid-feedback')).html(errors[keys[i]]);
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
