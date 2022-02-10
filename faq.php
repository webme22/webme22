<?php
include_once(__DIR__.'/config.php');
include_once(__DIR__.'/header.php');
$how_it_works = HowItWorks::all();
$cats = QuestionCategory::all();
$tab = isset($_GET['tab']) ? $_GET['tab'] : null;
?>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<link rel="stylesheet" href="css/faq.css">
<style>
    h2 {
        font-weight: 800;
        font-size: 2.5rem;
        color: #091f2f;
        text-transform: uppercase;
    }
    .panel-default .panel-body {
        font-size: 1.2rem;
    }
    .white {
        color: #fff !important;
    }

    .black {
        color: #616161 !important;
    }
    ul.side li {
        background-color: #fff;
        box-shadow: 0 1px 5px rgba(85, 85, 85, 0.15);
        border: 1px solid #f6f6f6;
        font-size: 1.4em;
        font-weight: bold;
        cursor: pointer;
        padding: 1em;
    }


    ul.side li a {
        color: #616161 !important;
    }

    ul.side li:hover, ul.side li.active {
        background-color: #f6f6f6;
    }
    .nav-tabs {
        border-bottom: none !important;
    }

    @media screen and (max-width: 768px){
        .how_it_works {
            width: 100% !important;
        }
        .#faq .card .card-header .btn-header-link{
            width: 98%;
        }
    }
</style>
<div class="section is-hero has-gradient position-relative overflow-hidden is-subpage">
    <div class="container position-relative">
        <div class="col block-centered text-align-center lg-7 md-12">
            <h1 style=" margin-top: 4vh !important;
                color: #fff;
                line-height: 1.15;
                font-weight: 500;
                display: block !important; "><?=trans('faq')?></h1>
        </div>
    </div>
</div>

<div class="section has-bg-accent position-relative" >
    <div class="container">
        <div class="row">
            <div class="col-9 offset-1 col-md-8 offset-md-2">
                <ul class="nav nav-pills nav-fill pl-0 pr-0">
                    <li class="nav-item profile-nav-item p-0 m-0 how_it_works">
                        <a class="nav-link <?=$tab == 'home' || $tab == null ? 'active' : ''?> profile-nav-link m-0 p-xs-0" href="#" data-toggle="tab" data-target="#home"><?=trans('how_it_works')?></a>
                    </li>
                    <li class="nav-item profile-nav-item p-0 m-0">
                        <a class="nav-link <?=$tab == 'profile' ? 'active' : ''?> profile-nav-link m-0 p-xs-0" href="#" data-toggle="tab" data-target="#profile"><?=trans('faq')?></a>
                    </li>
                </ul>
                <hr>
            </div>
        </div>
        <div class="tab-content">
            <div class="tab-pane <?=$tab == 'home' || $tab == null ? 'active' : ''?>" id="home" role="tabpanel" aria-labelledby="home-tab">
                <h2 class="w-100 text-center"><?=trans('how_it_works')?></h2>
                <hr>
                <!-- <ul class="hwi-list">
                
                    <li>
                        <h3 class="mb-0">
                    <a class="btn btn-link hwi-button w-100 text-left" target="_blank" href=""></a>
                        </h3>
                    </li>
                
                </ul> -->
                <div class="container">
                    <div class="accordion" id="faq">
                    <?php foreach($how_it_works as $key=>$item) {?>
                        <div class="card">
                            <div class="card-header" id="faqhead-<?= $key ?>">
                                <a href="#" class="btn btn-header-link <?= $lang=='ar'? 'text-'.$align_2 : 'text-left' ?> hwi-button collapsed" data-toggle="collapse" data-target="#faq-<?= $key ?>"
                                aria-expanded="true" aria-controls="faq-<?= $key ?>"><?=db_trans($item, 'title')?></a>
                            </div>

                            <div id="faq-<?= $key ?>" class="collapse" aria-labelledby="faqhead-<?= $key ?>" data-parent="#faq">
                                <div class="card-body">
                                    <iframe src="<?=asset($item['file'])?>" width="100%" height="700px">
                                    </iframe>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                        <!-- <div class="card">
                            <div class="card-header" id="faqhead2">
                                <a href="#" class="btn btn-header-link hwi-button collapsed" data-toggle="collapse" data-target="#faq2"
                                aria-expanded="true" aria-controls="faq2">S.S.S</a>
                            </div>

                            <div id="faq2" class="collapse" aria-labelledby="faqhead2" data-parent="#faq">
                                <div class="card-body">
                                    <iframe src="images/al-hamayel.pdf" width="100%" height="700px">
                                </iframe>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="faqhead3">
                                <a href="#" class="btn btn-header-link hwi-button collapsed" data-toggle="collapse" data-target="#faq3"
                                aria-expanded="true" aria-controls="faq3">S.S.S</a>
                            </div>

                            <div id="faq3" class="collapse" aria-labelledby="faqhead3" data-parent="#faq">
                                <div class="card-body">
                                <iframe src="images/al-hamayel.pdf" width="100%" height="700px">
                                </iframe>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
            <div class="tab-pane <?=$tab == 'profile' ? 'active' : ''?>" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <h2 class="w-100 text-center"><?=trans('faq')?></h2>
                <!-- <hr>
                <ul class="nav nav-pills nav-fill pl-0 pr-0">
                    <?php foreach($cats as $key => $cat) { ?>
                        <li class="nav-item profile-nav-item p-0 m-0">
                            <a class="nav-link <?=$key == 0 ? 'active' : ''?> qa-nav-link m-0 p-xs-0" href="#" data-toggle="tab" data-target="#faq_<?=$cat['id']?>">
                                <?=db_trans($cat, 'category')?>
                            </a>
                        </li>
                    <?php } ?>
                </ul> -->
                <hr>
                <div class="tab-content">
                    <?php foreach($cats as $key => $cat) { ?>
                        <div class="tab-pane <?=$key == 1 ? 'active' : ''?>" id="faq_<?=$cat['id']?>" role="tabpanel" aria-labelledby="faq_<?=$cat['id']?>-tab">
                            <div class="accordion my-accordion" id="accordion-<?=$cat['id']?>">
                                <?php foreach($cat->questions as $key => $item) { ?>
                                    <div class="card">
                                        <div class="card-header" id="heading-<?=$item['id']?>">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapse-heading-<?=$item['id']?>" aria-expanded="false" aria-controls="collapse-heading-<?=$item['id']?>">
                                                    <?=db_trans($item, 'question')?>
                                                </button>
                                            </h2>
                                        </div>

                                        <div id="collapse-heading-<?=$item['id']?>" class="collapse " aria-labelledby="heading-<?=$item['id']?>" data-parent="#accordion-<?=$cat['id']?>">
                                            <div class="card-body">
                                                <?=db_trans($item, 'answer')?>
                                                <?php if ($item['image'] && $item['image'] != "") { ?>
                                                    <div class="text-center">
                                                        <img src="<?=asset($item['image'])?>" height="250">
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include('footer.php');
?>
<script src="//cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.943/pdf.js"></script>
<script src="js/pdfThumbnails.js"></script>
<script>
    $(document).ready(function(){
        $('body').on('click', '.link', function(){
            $('.nav-item').removeClass('active');
            $(this).parent().addClass('active');
        })

        // $('#faq').on('shown.bs.collapse', function () {
        //     $('.collapse.show').find('iframe')[0].contentWindow.location.reload();
        // })

        let url = location.href;
        url = new URL(url);
        if(url.searchParams.get('q') == 'MPF'){
            $('#collapse-heading-3').addClass('show');
            $('html, body').animate({
                scrollTop: $("#collapse-heading-3").offset().top
            }, 2000);
        } else {
            $('#collapse-heading-3').removeClass('show');
        }
    })
</script>
 
 