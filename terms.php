<?php
include_once(__DIR__."/config.php");
include_once(__DIR__."/header.php");

$terms = Terms::find(1);
$title = $terms->first_title;
$header = $terms->first_header;
$terms_text = $terms->terms;
if($lang == 'ar'){
    $title = $terms->first_title_ar;
    $header = $terms->first_header_ar;
    $terms_text = $terms->terms_ar;
}
?>
<div class="section is-hero has-gradient position-relative overflow-hidden is-subpage">
    <div class="container position-relative">
        <div class="col block-centered text-align-center lg-7 md-12">
            <h1>
				<?=trans('terms_conditions')?>
            </h1>
            <div class="text-center padding-left padding- margin-bottom is-heading-color text-light" style="font-size: 1.8em !important; width: 100% !important;margin: auto !important;">
                <?= $title ?>
            </div>
        </div>
    </div>
</div>
<div class="section position-relative pt-1 mt-5" >
    <div class="p-2" style="width: 80% !important; margin: auto !important;background-repeat: no-repeat;background-size: cover;">
        <?= $terms_text ?>
    </div>
</div>
<?php include "footer.php"; ?>

