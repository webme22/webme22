<?php
include_once(__DIR__."/config.php");
include_once(__DIR__."/functions/translation.php");

$family = $_GET['family'] ?? null;
$gallery_item = $_GET['gallery_item'] ?? null;
if(!$family || ! $gallery_item){
	header('HTTP/1.0 404 Not Found');
	die;
}
$family = Family::find($family);
$gallery_item = $family->media()->where(['id'=>$gallery_item])->first();
if(!$family || ! $gallery_item){
	header('HTTP/1.0 404 Not Found');
	die;
}
$FILE = $siteUrl.asset($gallery_item->file);
$IMAGE = trim($siteUrl, '/').asset('images/map-preview.png');
$DESC = trans('siteDesc');
$TITLE = trans('siteTitle');
$OG_DESC = trans('ogDesc');
$URL = $siteUrl.$RELATIVE_PATH."gallery_item.php"."?family=".$family->id."&gallery_item=".$gallery_item->id;
$SEO_TAGS = "
<meta name='description' content='$DESC'>
<meta name='keywords' content='alhamayel, family tree, bahrain'>
<meta property='og:title' content='$TITLE' />
<meta property='og:url' content='$URL' />
<meta property='og:description' content='$OG_DESC' />
<meta property='og:image:alt'  content='Alhamayel.com preview image' />
<meta name='twitter:site' content='@alhamayel' />
<meta name='twitter:title' content='$TITLE' />
<meta name='twitter:description' content='$DESC' />
<meta name='twitter:card' content='summary_large_image' />
<meta name='twitter:image:alt' content='$TITLE' />
";
if ($gallery_item->file_type == 'Video'){
	$IMAGE = $siteUrl.asset('images/video-bg.png');
    $SEO_TAGS .= "<meta property='og:type' content='video.other' />";
    $SEO_TAGS .= "<meta property='og:video' content='$FILE' />";
    $SEO_TAGS .= "<meta property='og:video:url' content='$FILE' />";
    $SEO_TAGS .= "<meta property='og:video:secure_url' content='$FILE' />";
    $SEO_TAGS .= "<meta property='og:video:type' content='video/mp4' />";
    $SEO_TAGS .= "<meta property='og:video:width' content='400' />";
    $SEO_TAGS .= "<meta property='og:video:height' content='300' />";
	$SEO_TAGS .= "<meta property='og:image' itemprop='image' content='$IMAGE' />";
	$SEO_TAGS .= "<meta property='og:image:secure_url' itemprop='image' content='$IMAGE' />";
	$SEO_TAGS .= "<meta name='twitter:image' content='$IMAGE' />";
}
else if ($gallery_item->file_type == 'Image') {
	$SEO_TAGS .= "<meta property='og:type' content='website' />";
	$SEO_TAGS .= "<meta property='og:image' itemprop='image' content='$FILE' />";
	$SEO_TAGS .= "<meta property='og:image:secure_url' itemprop='image' content='$FILE' />";
	$SEO_TAGS .= "<meta name='twitter:image' content='$FILE' />";
}
else if ($gallery_item->file_type == 'Audio') {
	$IMAGE = $siteUrl.asset('images/audio-bg.png');
	$SEO_TAGS .= "<meta property='og:type' content='website' />";
	$SEO_TAGS .= "<meta property='og:audio' content='$FILE' />";
	$SEO_TAGS .= "<meta property='og:audio:url' content='$FILE' />";
	$SEO_TAGS .= "<meta property='og:audio:secure_url' content='$FILE' />";
	$SEO_TAGS .= "<meta property='og:audio:type' content='audio/mpeg' />";
	$SEO_TAGS .= "<meta property='og:image' itemprop='image' content='$IMAGE' />";
	$SEO_TAGS .= "<meta property='og:image:secure_url' itemprop='image' content='$IMAGE' />";
	$SEO_TAGS .= "<meta name='twitter:image' content='$IMAGE' />";
}
else if ($gallery_item->file_type == 'PDF') {
	$IMAGE = $siteUrl.asset('images/pdf-bg.png');
	$SEO_TAGS .= "<meta property='og:type' content='website' />";
	$SEO_TAGS .= "<meta property='og:image' itemprop='image' content='$IMAGE' />";
	$SEO_TAGS .= "<meta property='og:image:secure_url' itemprop='image' content='$IMAGE' />";
	$SEO_TAGS .= "<meta name='twitter:image' content='$IMAGE' />";
}

include_once("header.php");
?>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/lightgallery/1.2.21/css/lightgallery.min.css'>
<link rel='stylesheet' href='css/gallery.css'>

<div class="section is-hero has-gradient position-relative overflow-hidden is-subpage">
    <div class="container position-relative">
        <div class="col block-centered text-align-center lg-7 md-12" style="margin-top: 4vh !important;">
            <h1><?=trans('family_gallery')?></h1>
            <div class="text-center margin-bottom is-heading-color text-light"
                 style="font-size: 1.8em !important; width: 100% !important;margin: auto !important;" >

				<?php echo $lang != 'ar' ? ucfirst($family['name_' . $lang]) . " " . $languages[$lang]['family'] : $languages[$lang]['family'] . " ". ucfirst(db_trans($family, 'name')); ?>

            </div>
        </div>
    </div>
</div>
<div class="section bg-dark text-light position-relative"  style="background-color: #f7f7f7;height: auto;background-repeat: no-repeat;background-size: cover;">
    <div class="container member-gallery-main">
        <?php if ($family->status == 0 || $family->status == 2 && !(isset($_SESSION['family_id']) && $_SESSION['family_id'] == $family->id)) { ?>
        <div class="row my-5">
            <h1 class="w-100 text-center">You cannot view items of this gallery</h1>
            <div class="col-12 text-center mt-3">
				<?php if(isset($_SESSION['family_id']) && $_SESSION['family_id'] == $family->id){ ?>
                <a class="text-light  btn btn-primary" href="profile.php"  >
					<?php } else { ?>
                    <a class="text-light familyAccess btn btn-primary" href="<?=$family->id?>" status="<?=$family->status?>" >
						<?php } ?>
                        Learn More
                    </a>
            </div>
        </div>
        <?php } else {?>
        <div class="row">
            <div class="col-12 text-center">
                <h4 class="text-light"><?=db_trans($gallery_item, 'name')?></h4>
                <p class="text-light"><?=db_trans($gallery_item, 'description')?></p>
            </div>
			<?php if ($gallery_item->file_type == 'Image'){ ?>
                <div class="col-12 col-md-6 offset-md-3 col-xl-4 offset-xl-4">
                    <div id="gallery">
                        <div data-responsive="<?=asset($gallery_item->file)?>" data-src="<?=asset($gallery_item->file)?>"
                             data-sub-html="<h4><?=db_trans($gallery_item, 'name')?></h4><p><?=db_trans($gallery_item, 'description')?>.</p>" data-pinterest-text="Pin it" data-tweet-text="share on twitter" class="opne-img position-relative w-100">
                            <div class="text-center">
                                <a href="javascript:;">
                                    <img class="custom-img-responsive d-inline-block" src="<?=asset($gallery_item->file)?>" style="max-height: 400px">
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
			<?php } else if ($gallery_item->file_type == 'Video'){ ?>
            <div class="col-12 col-md-6 offset-md-3 col-xl-4 offset-xl-4">
                <div class="nak-video-gallery nlg1 h-auto" id="video-gallery">
                    <div data-responsive="<?=asset($gallery_item->file)?>"
                         data-sub-html="<h4><?=db_trans($gallery_item, 'name')?></h4><p><?=db_trans($gallery_item, 'description')?>.</p>"
                         data-pinterest-text="Pin it" data-tweet-text="share on twitter" class="video-img " data-html="#video-<?=$gallery_item->id?>">
                        <div style="display:none;" id="video-<?=$gallery_item->id?>">
                            <video class="lg-video-object lg-html5" controls preload="none">
                                <source src="<?=asset($gallery_item->file)?>" type="video/mp4">
                                Your browser does not support HTML5 video.
                            </video>
                        </div>
                        <div class="nak-video-gallery-poster custom-video-container">
                            <video preload="metadata" width="100%" height="100%">
                                <source src="<?=asset($gallery_item->file)?>" type="video/mp4" >
                            </video>
                        </div>
                    </div>
                </div>
            </div>
			<?php } else if ($gallery_item->file_type == 'Audio'){ ?>
                <div class="col-12 col-md-6 offset-md-3 col-xl-4 offset-xl-4">
                    <div class="audio-row p-1">
                        <audio controls class="branding audio-card">
                            <source src="<?=asset($gallery_item->file)?>" type="audio/ogg">
                            <source src="<?=asset($gallery_item->file)?>" type="audio/mpeg">
                            Your browser does not support the audio tag.
                        </audio>
                    </div>
                </div>
			<?php } else if ($gallery_item->file_type == 'PDF'){ ?>
                <div class="col-12 col-md-6 offset-md-3 col-xl-4 offset-xl-4">
                    <div class="document-card position-relative text-center">
                        <a href="<?=asset($gallery_item->file)?>" target="_blank">
                            <img data-pdf-thumbnail-file="<?=asset($gallery_item->file)?>" class="pdf-thumbnail h-100" src="images/pdf.png">
                        </a>
                    </div>
                </div>
			<?php } ?>
            <div class="col-12 text-center mt-3">
				<?php if(isset($_SESSION['family_id']) && $_SESSION['family_id'] == $family->id){ ?>
                <a class="text-light  btn btn-primary" href="profile.php"  >
					<?php } else { ?>
                    <a class="text-light familyAccess btn btn-primary" href="<?=$family->id?>" status="<?=$family->status?>" >
						<?php } ?>
                        Go To Full Family Profile
                    </a>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
<?php include_once("footer.php"); ?>
<script src='//cdnjs.cloudflare.com/ajax/libs/lightgallery/1.2.21/js/lightgallery-all.min.js'></script>
<script src='//npmcdn.com/isotope-layout@3.0/dist/isotope.pkgd.min.js'></script>
<script src='//npmcdn.com/imagesloaded@4.1/imagesloaded.pkgd.js'></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.943/pdf.js"></script>
<script src="js/pdfThumbnails.js"></script>
<script>
	<?php if ($gallery_item->file_type == 'Image'){ ?>
    $(document).ready(function() {
        var $gallery = $('#gallery');
        var $boxes = $('.opne-img');
        $boxes.hide();
        $gallery.imagesLoaded( {background: true}, function() {
            $boxes.fadeIn();
            $gallery.isotope({
                // options
                sortBy : 'original-order',
                layoutMode: 'fitRows',
                itemSelector: '.opne-img',
                stagger: 30,
            });
        });
        $("#gallery").lightGallery({
            caption:true,
            captionLink:true
        });

    });
	<?php } else if ($gallery_item->file_type == 'Video'){ ?>
    $(document).ready(function() {
        var $gallery = $('#video-gallery');
        var $boxes = $('.opne-img');
        $boxes.hide();

        $gallery.imagesLoaded( {background: true}, function() {
            $boxes.fadeIn();

            $gallery.isotope({
                // options
                sortBy : 'original-order',
                layoutMode: 'fitRows',
                itemSelector: '.opne-img',
                stagger: 30,
            });
        });

        $("#video-gallery").lightGallery({

        });
    });
	<?php } else if ($gallery_item->file_type == 'PDf'){ ?>
    $(document).ready(function () {
        createPDFThumbnails();
    });
	<?php } ?>

</script>
