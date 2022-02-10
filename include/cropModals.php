<div class="modal fade crop-modal" id="profile-pic-chooser" data-value="profile_image_copper">
    <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content" >
            <div class="modal-header">
                <h4 class="modal-title ml-auto mt-3"><?=trans('crop_image')?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div style="width: 100%; height: 400px">
                    <img class="profile-pic-chooser-image" src="<?=$default_profile?:''?>" style="max-width: 100%;display: block;max-height:100%">
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="submit" class="btn hbtn btn-hred" data-dismiss="modal" ><?=trans('done_cropping')?></button>
                <!--                <button type="button" class="btn hbtn btn-hmuted profile-pic-chooser-reset-crop" data-dismiss="modal">Don't Crop</button>-->
            </div>
        </div>
    </div>
</div>
<div class="modal fade crop-modal" id="club-pic-chooser" data-value="club_image_copper">
    <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content" >
            <div class="modal-header">
                <h4 class="modal-title ml-auto mt-3"><?=trans('crop_image')?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div style="width: 100%; height: 400px">
                    <img class="profile-pic-chooser-image"  src="<?=$default_club?:''?>" style="  max-width: 100%;display: block;max-height:100%">
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="submit" class="btn hbtn btn-hred" data-dismiss="modal" ><?=trans('done_cropping')?></button>
                <!--                <button type="button" class="btn hbtn btn-hmuted profile-pic-chooser-reset-crop" data-dismiss="modal">Don't Crop</button>-->
            </div>
        </div>
    </div>
</div>
