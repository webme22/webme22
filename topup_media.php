<?php
include_once(__DIR__.'/config.php');
middleware('user');
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
include_once(__DIR__."/header.php");
?>
<script src="https://credimax.gateway.mastercard.com/checkout/version/57/checkout.js"
        data-error="errorCallback"
        data-cancel="<?=$siteUrl?><?=$RELATIVE_PATH?>topup_media.php"
        data-complete="<?=$siteUrl?><?=$RELATIVE_PATH?>topup_media_response.php">//cancelCallback
</script>
<div class="section is-hero has-gradient position-relative overflow-hidden is-subpage">
    <div class="container position-relative">
        <div class="col block-centered text-align-center lg-7 md-12" style="margin-top: 4vh !important;">
            <h1>TopUp Media</h1>
        </div>
    </div>
</div>
<div class="section no-padding-bottom position-relative">
    <form id="topup-media" method="POST">
        <div class="c-shadowtext-pop">   Top Up  </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="plans">
                        <div class="plan col-md-12">
                            <div class="head_popup">
                                <h3 class="plan-title_1">Top Up Media</h3></div>
                            <div class=" main_npl">
                                <p class="plan-price-pop"> $175 <span class="plan-unit-pop">valid until the current subscription expiry date</span></p>
                                <ul class="plan-features">
                                    <li class=" plan-feature pop-fea">2.5 GB<span class="plan-feature-name">&nbsp;  Media Storage</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="w-richtext">
                        <p style="font-size: 16px; color: #556575;"> </p>
                    </div>
                </div>
                <div class="col-md-12 order-first md-text-align-center"> </div>
            </div>
        </div>
        <div class="container">

            <div class="payment-container">
                <div class="row">
                    <div class="col-12">
                        <h3 class="text-left"><?=trans('payment')?></h3>
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
                        <a href="terms.php?lang=<?php echo $lang; ?>" style="color: blue !important;" target="_blank"><?php echo trans('terms_conditions'); ?></a>
                    </span>
                </div>
            </div>
            <div class="w-100 text-left d-none mt-1 submit-form">
                <button class="button-primary animated d-inline-block"
                        name="formSubmit"><?=trans('submit')?></button>
            </div>
        </div>
        <input type="hidden" name="payment_type" value="credit">
    </form>
</div>
<?php include"footer.php";?>
<script>
    $(document).ready(function(){

        var   message = $('#submitMessage').val();
        if(message !== undefined && message.length > 0){
            Swal.fire({
                title: 'Success',
                width: 400,
                icon: 'success',
                text: `${message}`,
                confirmButtonText: 'Ok'
            })
        }

        let error = $('#submitError').val();
        if(error !== undefined && error.length > 0){
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
<script>
    let form_errors = [];
    $('body').on('submit', '#topup-media',function(){
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
        let form = $('#topup-media').serializeArray();
        let data = {total: 1};
        for(let i=0; i<form.length; i++){
            data[form[i]['name']] = form[i]['value']
        }
        $.ajax({
            type: 'post',
            url: 'api/topup_media_gen.php',
            data: data,
            dataType: 'Json',
            cache: false
        }).done(function(res){
            if (res.status){
                if (res.done){
                    location.href="<?=$siteUrl.$RELATIVE_PATH?>profile.php"
                }
                else {
                    Checkout.configure({
                        merchant   : 'E15701950',
                        order      : {
                            amount     : '0.1' ,
                            currency   : 'USD',//BHD
                            description: 'Top up media',
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
        });
    }

</script>
<?php
unset($_SESSION['submitStatus']);
unset($_SESSION['message']);
unset($_SESSION['errors']);
?>
