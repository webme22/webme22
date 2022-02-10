<?php
defined('_DEFVAR') or exit('Restricted Access');
?>
<div class="card top-listing" style="border: 1px solid white; box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.3); border-radius: 2vh;">
    <picture>
        <source srcset="<?=asset("images/map-min.webp")?>" type="image/webp">
        <source srcset="<?=asset("images/map-min.png")?>" type="image/png">
        <img class="card-img-top" src="<?=asset("images/map-min.png")?>" alt="<?php echo $row['name']; ?>" width="200" height="140" style="border-top-right-radius: 2vh; border-top-left-radius: 2vh; background-color: #96948d; position: relative;">
    </picture>
    <h6 class="slider-user-block-onimage-title"><?=db_trans($row3, 'name')?></h6>
    <div class="card-body text-center position-relative">
        <img class="avatar rounded-circle" src="<?=asset($rowMP['creator']['image'])?>" style="margin-top: -6em; box-shadow: 0px 0px 0px 6px #E0E0E0; position: relative;height: 80px;width:80px">
        <img style="position: absolute !important; top: -10px;  border-radius: 100%; left: calc( 50% + 18px );;" width="40" height="40" src="<?=asset($row3['image'])?>">
        <h5 class="card-subtitle mb-1 mt-1 "><?php
            if (strlen(db_trans($rowMP, 'name')) > 20){
                echo substr(db_trans($rowMP, 'name'), 0, 15) . '...';
            } else {
                echo db_trans($rowMP, 'name');
            }
            ?></h5>
        <h6 class="card-title mb-1" style="overflow:hidden; white-space: nowrap;"><?php
            if (strlen($rowMP['name']) > 15){
                echo substr($rowMP['name'], 0, 15) . '...';
            } else {
                echo $rowMP['name'];
            }
            ?></h6>
        <picture>
            <source srcset="<?=asset('images/audio-recording.webp')?>" type="image/webp">
            <source srcset="<?=asset('images/audio-recording.png')?>" type="image/png">
            <img src="<?=asset('images/audio-recording.png')?>" height="40" width="60"
                 style="margin: auto;cursor: pointer;" title="Click To Listen To Family Pronunciation" audio="<?=asset($rowMP['pronunciation'])?>" class="familyPron">
        </picture>
        <p class="card-text h6 text-dark mt-1 mb-3" style="overflow:hidden; white-space: nowrap;">
            <?php
            if (strlen(db_trans($rowMP, 'desc')) > 20){
                echo substr(db_trans($rowMP, 'desc'), 0, 20) . '...';
            } else {
                echo db_trans($rowMP, 'desc')?:'...';
            }
            ?>
        <p>
            <?php if(isset($_SESSION['family_id']) && $_SESSION['family_id'] == $rowMP['id']){ ?>
                <a href="profile.php?lang=<?= $lang ?>&view_member=<?=$rowMP['user_id']?>" class="h6 special-top-listing text-light" style="background-color: red; padding: .2em 1em; border-radius: 5vh;"><?=trans('view')?></a>
            <?php } else { ?>
                <a href="<?php echo $rowMP['id']; ?>" status="<?php echo $rowMP['status']; ?>" data-value="<?=$rowMP['user_id']?>" class="h6 special-top-listing familyAccess text-light" style="background-color: red; padding: .2em 1em; border-radius: 5vh;" flag="<?= $flag ?>"><?=trans('view')?></a>
            <?php } ?>
    </div>
</div>
