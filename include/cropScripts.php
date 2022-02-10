<script>
    function readURL(input, extras) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            let preview = $(input).next($('.preview'));
            let img = preview.find($('img')).first();
            reader.onload = function(e) {
                img.attr('src', e.target.result);
                for (i=0;i<extras.length;i++){
                    $(extras[i]).attr('src', e.target.result)
                }
                preview.removeClass('d-none');
            };

            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }
    $('input.has-preview').change(function () {
        let preview_div = $(this).next($('.crop-preview')).first();
        let data_target = preview_div.attr('data-target');
        readURL(this, [data_target+' .profile-pic-chooser-image']);
        preview_div.attr('onclick', "$('"+data_target+"').modal('show')");
        $(data_target).modal('show');

    });
    var profile_image_copper = {
        cropper : null,
        autoCropArea : 0.8,
        cropping_data : {},
        input: 'image_cropping',
        preview: '.profile-preview-div'
    };
    var club_image_copper = {
        cropper : null,
        autoCropArea : 0.8,
        cropping_data : {},
        input: 'club_cropping',
        preview: '.club-preview-div'
    };

    $('.crop-modal').on('shown.bs.modal', function () {
        let current_modal = $(this);
        let current_image = current_modal.find($('.profile-pic-chooser-image'));
        let cropper_name = current_modal.attr('data-value');
        requestAnimationFrame(function() {
            requestAnimationFrame(function () {
                if (window[cropper_name]['cropper']){
                    window[cropper_name]['cropper'].destroy();
                }
                requestAnimationFrame(function() {
                    requestAnimationFrame(function () {
                        window[cropper_name]['cropper'] = new Cropper(current_image[0],
                            {preview: window[cropper_name]['preview'], autoCropArea:window[cropper_name]['autoCropArea'],data: window[cropper_name]['cropping_data'],viewMode: 1, aspectRatio: 1 / 1,
                                crop(event) {
                                    window[cropper_name]['cropping_data'] = event.detail;
                                    $('input:hidden[name='+window[cropper_name]['input']+']').val(JSON.stringify(event.detail))
                                }
                            });
                    });
                });
            });
        });
    });
</script>
