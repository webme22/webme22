<?php
if (isset($_POST['subscriptionSubmit'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    
    $subscription = new Subscription;
    if($subscription->where('email', $email)->exists()){
        $error = trans("email_subscribed");
    } else {
        $subscription = Subscription::create([
            'name' => $name,
            'email' => $email,
            'date' => date('Y-m-d')
        ]);
        if ($subscription) {
            $success = "subscribed successfully";
        }
    }
}
?>
<footer class="section section-footer-dark padding-bottom-16">
    <div class="container">
        <div class="row">
            <div class="col-6 offset-3 col-sm-4 offset-sm-4 col-md-4 col-lg-2 offset-md-1 offset-lg-0 mb-3 mb-md-0">
                <picture>
                    <source srcset="<?=asset('images/al-logo2.webp')?>" type="image/webp">
                    <source srcset="<?=asset('images/al-logo2.png')?>" type="image/png">
                    <img src="<?=asset('images/al-logo2.png')?>" alt="">
                </picture>
            </div>
            <div class="col-6 offset-3 col-sm-4 offset-sm-4  col-lg-2 offset-md-1 col-md-4 mb-3 mb-md-0 mt-md-3 mt-lg-0">
                <h4 class="on-dark"><?=trans('company')?></h4>
                <a href="about.php" class="footer-nav-link on-dark"><?=trans('about')?></a>
                <a href="services.php" class="footer-nav-link on-dark"><?=trans('services_section')?></a>
                <a href="terms.php" class="footer-nav-link on-dark"><?=trans('terms_conditions')?></a>
                <a href="faq.php" class="footer-nav-link on-dark"><?=trans('faq')?></a>
            </div>
            <input type="hidden" id="subscribeSuccess" value="<?php if (isset($success)) {echo $success;} ?>">
            <input type="hidden" id="subscribeError" value="<?php if (isset($error)) {echo $error;} ?>">
            <div class="col-12 col-lg-6 offset-lg-1 col-md-12 mb-2">
                <div style="text-align: center !important; margin-bottom: 2% !important;">
                    <button id="subscribe" style="min-width: 240px !important;height: 40px !important; margin: auto !important;" class="button-primary is-small"><?=trans('news_subscribe')?></button>
                </div>
                <div class="margin-bottom-double" style="text-align: center !important;" style="font-size: 24px !important;"><?=trans('newsletter_join')?></div>
		<?php
		$rowSetting = isset($rowSetting) ?$rowSetting: Setting::find(1);
		?>
                <div class="flexh-space-between" style=" width: 80% !important; margin: auto !important;">
                    <a href="<?php echo $rowSetting->instagram; ?>" class="c-social on-dark w-inline-block" target="_blank">
                        <div class="fa-brand"></div>
                    </a>
                    <a href="<?php echo $rowSetting->facebook; ?>" class="c-social on-dark w-inline-block" target="_blank">
                        <div class="fa-brand"></div>
                    </a>
                    <a href="<?php echo $rowSetting->youtube; ?>" class="c-social on-dark w-inline-block" target="_blank">
                        <div class="fa-brand"></div>
                    </a>
                    <a href="<?php echo $rowSetting->linkedin; ?>" class="c-social on-dark w-inline-block" target="_blank">
                        <div class="fa-brand"></div>
                    </a>
                    <a href="<?php echo $rowSetting->twitter; ?>" class="c-social on-dark w-inline-block" target="_blank">
                        <div class="fa-brand"></div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modal95" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title ml-auto mt-3"><?=trans('news_subscribe')?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST">

                        <div class="form-group">
                            <label style="color: black !important; font-size: 15px !important;"><?=trans('name')?>* :</label>
                            <input type="text" class="form-control" required name="name" placeholder="<?=trans('enter')?><?=trans('name')?>">
                        </div>

                        <div class="form-group">
                            <label style="color: black !important; font-size: 15px !important;"><?=trans('email')?>* :</label>
                            <input type="email" class="form-control" required name="email" id="subscribeEmail" placeholder="<?=trans('enter')?><?=trans('email')?>">
                        </div>

                </div>
                <div class="modal-footer" style="margin: auto;">
                    <button type="submit" class="btn hbtn btn-hred" name="subscriptionSubmit"><?=trans('submit')?></button>
                    <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="close95"><?=trans('close')?></button>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>
</footer>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.slim.min.js" ></script>
<script src="js/quaid.js" type="text/javascript"></script>
<script src="js/lightbox-plus-jquery.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.hislide.min.js" ></script>
<script type="text/javascript" src="js/jquery.hislide.js" ></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="//stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

<script>
    function hiCarousel(){
        $('.slide').hiSlide();
    }
    function isArabic(inputtxt) {
        inputtxt = inputtxt.replace(/\n/g, " ");
        var letters = /^[\u0621-\u064A\u0660-\u0669 ]+$/;
        if (inputtxt.match(letters)) {
            return true;
        } else {
            return false;
        }
    }
    function isEnglish(inputtxt) {
        inputtxt = inputtxt.replace(/\n/g, " ");
        var letters = /^[a-zA-Z0-9 ]+$/;
        if (inputtxt.match(letters)) {
            return true;
        } else {
            return false;
        }
    }
    $('body').on('submit', 'form', function () {
        let needs_text_validation = $('.validate-text');
        if (needs_text_validation.length){
            let prev = false;
            let error = "<div class='invalid-feedback'>The Language of the text you entered doesn't match the required for this input</div>";
            $(this).find('.validate-text.validate-ar').each(function () {
                if (! isArabic($(this).val())){
                    $(this).addClass('is-invalid');
                    $(this).next($('div.invalid-feedback')).remove();
                    // $(this).insertAfter($(error));
                    $(error).insertAfter($(this));
                    prev = true;
                }
                else{
                    $(this).removeClass('is-invalid');
                    $(this).next($('div.invalid-feedback')).remove();
                }
            });
            $(this).find('.validate-text.validate-en').each(function () {
                if (! isEnglish($(this).val())){
                    $(this).addClass('is-invalid');
                    $(this).next($('div.invalid-feedback')).remove();
                    $(error).insertAfter($(this));
                    prev = true;
                }
                else{
                    $(this).removeClass('is-invalid');
                    $(this).next($('div.invalid-feedback')).remove();
                }
            });
            if (prev){
                event.preventDefault();
            }
        }
    })
</script>
<script src="js/slick.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/1.7.22/fabric.min.js"></script>
<script src="<?=asset('js/cropper.min.js')?>"></script>
<script type="text/javascript">
    function slickCarousel() {
        $('.slider').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            arrows: true,
            dots: false,
            centerMode: true,
            variableWidth: true,
            infinite: true,
            focusOnSelect: true,
            cssEase: 'linear',
            touchMove: true,
            prevArrow:'<button class="slick-prev"> < </button>',
            nextArrow:'<button class="slick-next"> > </button>',
        });
    }
    function destroyCarousel() {
        if ($('.slider').hasClass('slick-initialized')) {
            $('.slider').slick('destroy');
        }
    }
</script>
<script>
    $(document).ready(function () {
        let subscribeSuccess = $('#subscribeSuccess').val();
        if (subscribeSuccess.length > 0) {
            Swal.fire({
                title: 'Success',
                width: 400,
                icon: 'success',
                text: `${subscribeSuccess}`,
                confirmButtonText: 'Ok'
            })
        }

        let subscribeError = $('#subscribeError').val();
        if (subscribeError.length > 0) {
            Swal.fire({
                title: 'error',
                width: 400,
                icon: 'error',
                text: `${subscribeError}`,
                confirmButtonText: 'Ok'
            })
        }

        // $(window).unload( function(){
        //     $.ajax({
        //         type: 'POST',
        //         url: 'api/global.php',
        //         async: false,
        //         data: {
        //             test: 1
        //         },
        //         dataType: 'Text',
        //         cache: false
        //     });
        // });
        analyticsData = new FormData();
        analyticsData.append('user_leave_event', '1');
        document.addEventListener('visibilitychange', function logData() {
            if (document.visibilityState === 'hidden') {
                navigator.sendBeacon('api/global.php', analyticsData);
            }
        });
        $('body').on('click', '.search_families_filter', function(e){
			let type = $(this).attr('type');
			let url = location.href;
			url = new URL(url);
			url.searchParams.set('type', type);
			location.href = url;

			e.preventDefault();
		})
        
        $('body').on('click', '#subscribe', function () {
            $('#modal95').modal('show');
        });
        $('body').on('click', '#close95', function () {
            $('#subscribeEmail').val('');
            $('#modal95').modal('hide');
        })
        $('.modal').on('shown.bs.modal', function () {
            let j = parseInt($('.modal.show').first().css('z-index'));
            $('.modal.show').each(function () {
                let current = parseInt($(this).css('z-index'));
                j = ( current < j ? current : j) ;
            });
            let i = j -1;
            $('.modal-backdrop.show').each(function () {
                $(this).css('z-index',i);
                i += 10;
            });
        });
        $('.modal').on('hidden.bs.modal', function () {
            if ($('.modal.show').length){
                $('body').addClass('modal-open');
            }
        });

        $('body').on('click', '.familyAccess', function(e){
            let family = $(this).attr('href');
            let user = $(this).attr('data-value');
            let flag = $(this).attr('flag');

            $.ajax({
                type: 'POST',
                url: 'api/global.php',
                data: {
                    family_to_access: family
                },
                dataType: 'Text',
                cache: false
            }).done(function(status){
                if(status == 0){
                    $('#familyId').val(family);
                    $('#modal3').modal('show');
                } else {
                    let url = (user && user != "" && flag && flag == 1 ? `profile.php?family=${family}&view_member=${user}` : `profile.php?family=${family}`);
                    location.href = url;
                }
            })
            
            e.preventDefault();
        });

        $('body').on('click', '.request_view_gallery, .request_view_tree', function(e){
            let family = $(this).attr('href');
            let status = $(this).attr('status');
            let type = $(this).attr('type');

            $('#requested_type').val(type);
            $('#requested_family').val(family);  
            $('#request_view_modal').modal('show');
            e.preventDefault();
        });
        $('body').on('click', '#request_view_modal_close', function () {
            $('#requested_type').val('');
            $('#requested_family').val(''); 
            $('#request_view_modal').modal('hide');
        })
        
    });
    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the button that opens the modal
    var btn = document.getElementById("myBtn");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal
    if (btn){
        btn.onclick = function () {
            $(modal).modal('show');
        }
    }
    // When the user clicks on <span> (x), close the modal
    if (span){
        span.onclick = function () {
            modal.style.display = "none";
        }
    }
    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>
