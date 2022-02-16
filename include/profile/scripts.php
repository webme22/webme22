<script src='//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src="//ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js" type="text/javascript"></script>
<script src='//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
<script src="//cdn.rawgit.com/prashantchaudhary/ddslick/master/jquery.ddslick.min.js"  type="text/javascript"></script>
<script src="//cdn.rawgit.com/mattdiamond/Recorderjs/08e7abd9/dist/recorder.js"></script>
<script src="js/fm.selectator.jquery.js"></script>
<script src="js/likely.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js" ></script>
<script src="js/web-audio-recorder/lib-minified/WebAudioRecorder.min.js"></script>
<script src="<?=asset('js/bundle.js')?>"></script>
<script>
    $.fn.attachDragger = function(){
        var attachment = false, lastPosition, position, difference;
        $( $(this).selector ).on("mousedown mouseup mousemove",function(e){
            if( e.type == "mousedown" ) attachment = true, lastPosition = [e.clientX, e.clientY];
            if( e.type == "mouseup" ) attachment = false;
            if( e.type == "mousemove" && attachment == true ){
                position = [e.clientX, e.clientY];
                difference = [ (position[0]-lastPosition[0]), (position[1]-lastPosition[1]) ];
                $(this).scrollLeft( $(this).scrollLeft() - difference[0] );
                $(this).scrollTop( $(this).scrollTop() - difference[1] );
                lastPosition = [e.clientX, e.clientY];
            }
        });
        $(window).on("mouseup", function(){
            attachment = false;
        });
    }
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
    function validateEmail(email){
        let reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        if (reg.test(email) == false)
        {
            return false;
        }
        return true;
    }
    var zoom_val = 1;
    var step = 0.1;
    function setZoom(zoom,el) {
        let lang = "<?=$lang?>";
        transformOrigin = [(lang == 'en' ? 0 : 1),0];
        var p = ["webkit", "moz", "ms", "o"],
            s = "scale(" + zoom + ")",
            oString = (transformOrigin[0] * 100) + "% " + (transformOrigin[1] * 100) + "%";
        for (var i = 0; i < p.length; i++) {
            el.style[p[i] + "Transform"] = s;
            el.style[p[i] + "TransformOrigin"] = oString;
        }
        el.style["transform"] = s;
        el.style["transformOrigin"] = oString;

    }
    function showVal(zoomScale){
        setZoom(zoomScale,document.getElementById('treeDiv'));
        setZoom(zoomScale,document.getElementById('treeDiv-shadow'));
    }
    function zoomIn(el) {
        console.log(el);
        zoom_val = zoom_val + step;
        zoom_val = (zoom_val > 1 ? 1 : zoom_val);
        showVal(zoom_val);
        if(zoom_val === 1) el.style.pointerEvents = "none";
        else el.style.pointerEvents = "initial";
        document.querySelector(".zoom-out-btn").style.pointerEvents = "initial";
        reinitTree();
        return false;
    }
    function zoomOut(el) {
        zoom_val = zoom_val - step;
        zoom_val = (zoom_val < 0.1 ? 0.1 : zoom_val);
        showVal(zoom_val);
        if(zoom_val === 0.1) el.style.pointerEvents = "none";
        else el.style.pointerEvents = "initial";
        document.querySelector(".zoom-in-btn").style.pointerEvents = "initial";
        reinitTree();
        return false;
    }
    function toggleTreeFullScreen(){
        $('.family-tree-container').toggleClass('fullscreen');
        $('section.main-section').toggleClass('d-none');
        $('html, body').animate({
            scrollTop: $(".family-tree-container").offset().top
        }, 2000);
        return false;
    }
    function reinitTree(){
        $('#treeDiv-shadow').width($('#treeDiv').width());
        let tree = $(".treeParent:not(.shadow)");
        let tree_shadow = $(".treeParent.shadow");
        let treeDiv = $("#treeDiv");
        let lang = "<?=$lang?>";
        if(treeDiv && tree){
            if (treeDiv[0].getBoundingClientRect().width < tree[0].getBoundingClientRect().width){
                treeDiv.css((lang== 'en' ? 'left' : 'right'), (tree[0].getBoundingClientRect().width - treeDiv[0].getBoundingClientRect().width)/2)
            }
            else {
                tree_shadow.scroll(function(){
                    $(".treeParent:not(.shadow)")
                        .scrollLeft(tree_shadow.scrollLeft());
                });
                tree.scroll(function(){
                    tree_shadow
                        .scrollLeft(tree.scrollLeft());
                });
                if (lang == 'en'){
                    tree_shadow.scrollLeft((treeDiv[0].getBoundingClientRect().width/2) - tree[0].getBoundingClientRect().width/2);
                }
                else {
                    tree_shadow.scrollLeft(-1 * ((treeDiv[0].getBoundingClientRect().width/2) - (tree[0].getBoundingClientRect().width/2)));
                }
            }
            tree.height(treeDiv[0].getBoundingClientRect().height + 150);
        }

    }
    function flyingSearchState(){
        if ($('#searchTree').val()){
            $('#flyingSearch').removeClass('d-none');
        }
        else {
            $('#flyingSearch').addClass('d-none');
        }
    }
    function flyingSearchStats(){
        let stats = '-/-';
        if ($('a.cell.wanted').length) {
            let results_count = $('a.cell.wanted').length;
            let current_index = $('a.cell.wanted.current-search').attr('search-index');
            if (results_count && current_index){
                stats = ''+current_index+'/'+results_count;
            }
        }
        $('#flyingSearch').find('.stats').html(stats);
    }
    function searchTreeNext(){
        if ($('a.cell.wanted').length) {
            let results_count = $('a.cell.wanted').length;
            let current_index = parseInt($('a.cell.wanted.current-search').attr('search-index'));
            scrollToSearchResult((current_index%results_count)+1)
        }
    }
    function searchTreePrev(){
        if ($('a.cell.wanted').length) {
            let results_count = $('a.cell.wanted').length;
            let current_index = parseInt($('a.cell.wanted.current-search').attr('search-index'));
            let next_index = (current_index-1 > 0 ? current_index-1 : results_count);
            scrollToSearchResult(next_index)
        }
    }
    function searchTreeEnd(){
        $('#searchTreeShadow').val('');
        $('#searchTree').val('');
        flyingSearchState();
        $('a.cell').removeClass('wanted');
        $('a.cell').removeClass('current-search');
        $('a.cell').removeAttr('search-index');
        $('a.cell:first')[0].scrollIntoView();
        $('.family-tree-container')[0].scrollIntoView();
        reinitTree();
    }
    function searchTreeClean(){
        $('a.cell').removeClass('wanted');
        $('a.cell').removeClass('current-search');
        $('a.cell').removeAttr('search-index');
    }
    function scrollToSearchResult(index){
        if ($('a.cell.wanted').length){
            let elem = $('a.cell.wanted[search-index='+index+']');
            elem[0].scrollIntoView();
            $('a.cell.wanted').removeClass('current-search');
            elem.addClass('current-search');

            $(".treeParent:not(.shadow)").scrollLeft($(".treeParent:not(.shadow)").scrollLeft()-(($(".treeParent:not(.shadow)").width()/2)-elem.offset().left));
        }
        flyingSearchStats();
    }
    function searchTree(){
        searchTreeClean();
        let pattern = $('#searchTree').val();
        if(pattern.length > 0){
            let count = 0;
            let search_index = 1;
            $('#treeDiv ul li').each(function() {
                let element = $(this).children(":first");
                if(element.text().toLowerCase().includes(pattern.toLowerCase())){
                    element.addClass('wanted');
                    element.attr('search-index', search_index);
                    search_index += 1;
                } else {
                    count++;
                }
            });
            scrollToSearchResult(1);
        } else {
            searchTreeEnd()
        }
        flyingSearchState();
    }

    function init_audio(path) {
//         let myAudio = document.getElementById("family_pronunciation");
// 	myAudio.load(); // Added for IOS devices By Sanu Khan 
//         myAudio.play();
        // fetch(myAudio.children[0].src)
// 	    console.log(path,'path')
	    
	  
        fetch(path)
            .then(r => r.blob())
            .then(function(blobData){
//                 let sources = myAudio.children;
//                 const audioArrayBuffer = blobData.arrayBuffer();
//                 const url = URL.createObjectURL(blobData);
// 		 for (let i=0; i < sources.length; i++){
//                     sources[i].src = audioObjectURL;
//                     sources[i].type = 'audio/mpeg';
		   
//                 }
//         	myAudio.addEventListener('ended', loopAudio, false);
		
		//Added By Sanu - TEST Audio for IOS/ Chrome
		
                const audioBlob = new Blob([blobData], {type: 'audio/mpeg'});
                const audioObjectURL = window.URL.createObjectURL(audioBlob);
		const myAudioTag = new Audio();
		myAudioTag.autoplay = true;
		myAudioTag.muted = false;
		const source = document.createElement('source');
		  source.src = audioObjectURL;
                  source.type = 'audio/mpeg';
		myAudioTag.appendChild(source).load().play().pause();
		
            })
            .catch(console.error)
	
    }

    function loopAudio(path) {
        let myAudio = document.getElementById("family_pronunciation");
        myAudio.src = path;
        // console.log(myAudio.children[0].src);
        console.log(myAudio);
        myAudio.play();
    }
    $(document).ready(function () {
        $('body').on('click', '#speaker', function () {
            let path = $(this).data('uri');
//             let path = $('#family_pronunciation').children().attr('src');
            if (! path && path == '') {
                Swal.fire({
                    title: "",
                    width: 400,
                    text: "<?=trans('family_has_no_record')?>",
                    icon: 'error',
                    confirmButtonText: "<?=trans('ok')?>"
                })
            } else {
                init_audio(path);
            }

            // document.getElementById('family_pronunciation').play();
            // return false;
            // let family = $(this).attr('family');
            // $.ajax({
            //     type: 'post',
            //     url: 'api/global.php',
            //     data: {
            //         req: 'pronunciation',
            //         familyId: family
            //     },
            //     dataType: 'Text',
            //     cache: false
            // }).done(function (res) {
            //     if (res == '') {
            //         Swal.fire({
            //             title: "",
            //             width: 400,
            //             text: "",
            //             icon: 'error',
            //             confirmButtonText: ""
            //         })
            //     } else {
            //         // let audioElement = document.createElement('audio');
            //         // audioElement.setAttribute('src', `${res}`);
            //         // audioElement.play();
            //         // $('#family_pronunciation').attr('src', res);
            //         // $('#family_pronunciation')[0].play();
            //         // loopAudio(res);
            //         init_audio(res);
            //     }
            // })
        });

        $.ajax({
            url: "api/global.php",
            type: 'GET',
            data: {
                check_both_type_invitation: 1
            },
            dataType: 'Text',
            success: function(res) {
                if(res == 1){
                    Swal.fire({
                        title: "<?= $languages[$lang]["welcome"] ?>",
                        width: 600,
                        icon: 'info',
                        text: "<?= $languages[$lang]["welcoming_invited_user"] ?>",
                        confirmButtonText: "<?=trans('ok')?>"
                    }).then(function(){
                        $('html, body').animate({
                            scrollTop: $(".family_alpha").offset().top - 50
                        }, 2000);
                    })
                }
            }
        });

        $('body').on('click', '#join_family', function(){
            $('#modal30').modal('show');
        });
        $('body').on('click', '#close30', function(){
            $('#modal30').modal('hide');
        });

        $('.is_that_you').on('change', function(){
            if($(this).is(':checked')){
                $.ajax({
                    url: "api/global.php",
                    type: 'GET',
                    data: {
                        get_user_data: 1
                    },
                    dataType: 'Json',
                    success: function(res) {
                        $('#first_name').val(res.name);
                        $('#memberRole').val('assistant');
                        $('#country_of_residence').val(res.country_id);
                        $('.related_country_key').val(res.key);
                        $('#nodePhone').val(res.phone);
                        $('#relatedMemberEmailConfirmation').attr('required', true);
                        $('#relatedMemberEmail').attr('required', true);
                        $('.star').removeClass('d-none');
                        $('#relatedMemberEmail').val(res.email);
                        $('#relatedMemberEmailConfirmation').val(res.email);
                    }
                });
            } else {
                $('#first_name').val('');
                $('#memberRole').val('');
                $('#country_of_residence').val('');
                $('.related_country_key').val('');
                $('#nodePhone').val('');
                $('#relatedMemberEmailConfirmation').attr('required', false);
                $('#relatedMemberEmail').attr('required', false);
                $('.star').addClass('d-none');
                $('#relatedMemberEmail').val('');
                $('#relatedMemberEmailConfirmation').val('');
            }
        })

        const slider = document.querySelector('.treeParent:not(.shadow)');
        let isDown = false;
        let startX;
        let scrollLeft;
        let currentURL = location.href;
        currentURL = new URL(currentURL);

        if(slider){
            slider.addEventListener('mousedown', (e) => {
                isDown = true;
                slider.classList.add('active');
                startX = e.pageX - slider.offsetLeft;
                scrollLeft = slider.scrollLeft;
            });
            slider.addEventListener('mouseleave', () => {
                isDown = false;
                slider.classList.remove('active');
            });
            slider.addEventListener('mouseup', () => {
                isDown = false;
                slider.classList.remove('active');
            });
            slider.addEventListener('mousemove', (e) => {
                if(!isDown) return;
                e.preventDefault();
                const x = e.pageX - slider.offsetLeft;
                const walk = (x - startX) * 3; //scroll-fast
                slider.scrollLeft = scrollLeft - walk;
            });
            reinitTree();
        }
        requestAnimationFrame(function() {
            requestAnimationFrame(function () {
                let view_member = currentURL.searchParams.get("view_member");
                if (view_member && view_member != '') {
                    let elem = $("li a.cell[href=" + view_member + "]");
                    elem.addClass("wanted");
                    elem[0].scrollIntoView();
                    $(".treeParent:not(.shadow)").scrollLeft($(".treeParent:not(.shadow)").scrollLeft()-(($(".treeParent:not(.shadow)").width()/2)-elem.offset().left));

                }
            });
        });

        $('#searchTree').on('keyup', function () {$('#searchTreeShadow').val($(this).val());});
        $('#searchTreeShadow').on('keyup', function () {$('#searchTree').val($(this).val());});
        $('body').on('click', '#searchTreeSubmit', function(){searchTree();});
        $('body').on('click', '#searchTreeSubmit', function(){searchTree();});
        $('body').on('submit', '.tree-search-form', function(){searchTree();event.preventDefault();});
        $('#flyingSearch').find('.next').click(function () {searchTreeNext()});
        $('#flyingSearch').find('.prev').click(function () {searchTreePrev()});
        $('#flyingSearch').find('.close-search').click(function () {searchTreeEnd()});
        $('.modal').on('hidden.bs.modal', function () {
            if ($('.modal.show').length){
                $('body').addClass('modal-open');
            }
        });

        $('#modal7').on('shown.bs.modal', function () {
            $('tr.spacer').show();
            $("tr:hidden").each(function () {
                let pre = $(this).prev($("tr.spacer"));
                if (pre.length){
                    pre.hide();
                }
            })
        });

        let invitation = currentURL.searchParams.get("type");
        let inv_family = currentURL.searchParams.get("flag");
        let successMessage = $('#successMessage').val();
        if(invitation && inv_family && successMessage == ''){
            $.ajax({
                type: 'post',
                url: 'api/global.php',
                data: {
                    check_invitation: 1,
                    family: inv_family,
                    invitation: invitation
                },
                dataType: 'Json',
                cache: false
            }).done(function(res){
                $('#modal55').modal('show');
            });
        }
        $('#inviteSubmit').click(function() {
            let role = $('input[type=radio][name=type]:checked').val();
            let name = $('.strangerName').val();
            let email = $('.strangerEmail').val();
            let family = <?php echo $familyId; ?>;

            let lang = $('#profileLang').val();
            if(email != '' && ! validateEmail(email)){
                let message = 'Invalid Email Address';
                if(lang.includes('ar')){
                    message = '<?=trans("invalid_email")?>';
                }
                Swal.fire({
                    title: '<?=trans("error")?>!',
                    width: 400,
                    text: `${message}`,
                    icon: 'error',
                    confirmButtonText: "<?=trans('ok')?>"
                });
                return false;
            }
            if(name != '' && email != '' && role != ''){
                $.ajax({
                    type: 'post',
                    url: 'api/global.php',
                    data: {
                        strangerName: name,
                        strangerEmail: email,
                        family: family,
                        role: role
                    },
                    dataType: 'Json',
                    cache: false
                }).done(function(res){
                    if(res.success == 0){
                        Swal.fire({
                            title: "<?=trans('error')?>!",
                            width: 400,
                            text: `${res.message}`,
                            icon: 'error',
                            confirmButtonText: "<?=trans('ok')?>"
                        })
                    } else if(res.success == 1) {
                        Swal.fire({
                            title: "<?=trans('succ')?>",
                            width: 400,
                            icon: 'success',
                            text: "<?=trans('invitation_sent')?>",
                            confirmButtonText: "<?=trans('ok')?>"
                        });
                        $('.strangerName').val('');
                        $('.strangerEmail').val('');
                        $('#mailMessage').removeClass('d-none');
                        $('#mailMessage textarea').val($('<div>'+res.message+'</div>').text().trim());
                        $("#invite-send-whatsapp").attr('href', 'https://wa.me/?text='+encodeURIComponent(res.plain_message));
                    }
                });
            } else {
                Swal.fire({
                    title: "<?=trans('error')?>!",
                    width: 400,
                    text: "<?=trans('fill_all')?>. ",
                    icon: 'error',
                    confirmButtonText: "<?=trans('ok')?>"
                })
            }
        });
        $('body').on('click', '.copy-btn', function(){
            let text = document.getElementById('copiedMessage');
            text.select();
            document.execCommand('copy');
        });
        $('body').on('change', '#using', function(){
            let using = $(this).val();
            if(using.includes('email')){
                $('.forEmail').removeClass('d-none').find('input').attr('required', true);
                $('.forWhatsapp').addClass('d-none').find('input').attr('required', false);
            } else if(using.includes('whatsapp')){
                $('.forEmail').addClass('d-none').find('input').attr('required', false);
                $('.forWhatsapp').removeClass('d-none').find('input').attr('required', true);
            } else {
                $('.forEmail').addClass('d-none').find('input').attr('required', false);
                $('.forWhatsapp').addClass('d-none').find('input').attr('required', false);
            }
        });
        $('body').on('click', '#toggleTree', function(){
            let text = $(this).text();
            if(text.includes("<?=trans('show')?>")){
                $(this).find('span').text("<?=trans('hide')?>");
                $('.treeParent').show(2000);
            } else if(text.includes("<?=trans('hide')?>")){
                $(this).find('span').text("<?=trans('show')?>");
                $('.treeParent').hide(2000);
            }
        });
		<?php
		if (isset($_GET['family'])) {
			$familyId = $_GET['family'];
		} elseif (isset($_GET['flag'])) {
			$familyId = $_GET['flag'];
		} elseif (isset($_GET['f']) && $_GET['f']) {
			$familyId = $_GET['f'];
		} else {
			$familyId = $_SESSION['family_id'];
		}
		?>
        $('body').on('click', '.familyMedia .row .one', function(){
            $('.familyMedia .row .one').removeClass('active');
            $(this).addClass('active');
            let type = $('.type.active').text();
            let id = $(this).attr('fileId');
            let date = $(this).attr('date');
            if(type.includes('Image') || type.includes('Audio')){
                let img = $(this).find('img').attr('src');
                let name = $(this).find('img').attr('alt');
                $('#firstMedia').find('img').attr('src', img);
                $('#firstMedia').find('img').attr('alt', name);
                $('#firstMedia span').empty().html(`Uploaded at ${date}`);
                $('#firstMedia figcaption').find('span').html(name);
                $('#firstMedia figcaption').find('.deleteFile').attr('fileId', id);
                if(type.includes('Audio')){
                    let audio = $(this).find('img').attr('audio');
                    $('#firstMedia').find('img').attr('audio', audio);
                } else {
                    $('.viewImage').attr('href', img);
                    $('.viewImage').attr('data-title', name);
                }
            } else if(type.includes('Video')){
                let video = $(this).find('video').find('source').attr('src');
                let name = $(this).attr('name');
                $('#firstMedia').find('video').find('source').attr('src', video);
                $('#firstMedia').find('video')[0].load();
                $('#firstMedia').find('video').attr('name', name);
                $('#firstMedia span').empty().html(`Uploaded at ${date}`);
                $('#firstMedia figcaption').find('span').html(name);
                $('#firstMedia figcaption').find('.deleteFile').attr('fileId', id);
            }
        });
        let audioTag = document.createElement('audio');
        $('body').on('click', '#firstMedia img', function(){
            let type = $('.type.active').text();
            audioTag.setAttribute('src', ' ');
            if(type.includes('Audio')){
                let audio = $(this).attr('audio');
                audioTag.setAttribute('src', audio);
                audioTag.play();
            }
        });
        $('#wifeHus').change(function(){
            let status = $(this).val();
            if(status === '0'){
                $('#NodeHusband').show().attr('required', true);
                $('#nodeFather').show().attr('required', true);
            } else {
                $('#NodeHusband').empty().attr('required', false).hide();
                $('#nodeFather').empty().attr('required', false).hide();
            }
        });
        $('#husband').change(function(){
            let status = $(this).val();
            if(status === '0'){
                $('#invitedUserHusband').show().attr('required', true);
                $('#joinParentSelect').show().attr('required', true);
            } else {
                $('#invitedUserHusband').empty().hide().attr('required', false);
                $('#joinParentSelect').empty().hide().attr('required', false);
            }
        });
        $('#joinParentSelect').change(function () {
            let father = $(this).val();
            if(father == ''){
                $('#joinMother').attr('required', false).empty().hide();
            } else {
                $.ajax({
                    type: 'post',
                    url: 'api/global.php',
                    data: {
                        userFather: father
                    },
                    dataType: 'Text',
                    cache: false,
                    async: false
                }).done(function (res) {
                    if (res) {
                        $('#joinMother').empty().show().attr('required', true).append(res);
                    } else {
                        $('#joinMother').attr('required', false).empty().hide();
                    }
                })
            }
        });
        $('#nodeFather').change(function () {
            let father = $(this).val();
            if(father == ''){
                $('#NodeMother').attr('required', false).empty().hide();
            } else {
                $.ajax({
                    type: 'post',
                    url: 'api/global.php',
                    data: {
                        userFather: father
                    },
                    dataType: 'Text',
                    cache: false,
                    async: false
                }).done(function (res) {
                    // console.log(res)
                    if (res) {
                        $('#NodeMother').empty().attr('required', true).show().append(res);
                    } else {
                        $('#NodeMother').attr('required', false).empty().hide();
                    }
                })
            }
        });
        $('#username').mouseleave(function () {
            let username = $(this).val();
            let lang = $('#profileLang').val();
            if (username.length > 0) {
                $.ajax({
                    type: 'post',
                    url: 'api/global.php',
                    data: {
                        username: username,
                        lang: lang,
                        x: 1
                    },
                    dataType: 'Text',
                    cache: false
                }).done(function (res) {
                    if (res.length > 0) {
                        Swal.fire({
                            title: "<?=trans('warning')?>",
                            width: 400,
                            text: `${res}`,
                            icon: 'warning',
                            confirmButtonText: "<?=trans('ok')?>"
                        })
                    }
                })
            }
        });
        $('#cpass').mouseleave(function () {
            let cpass = $(this).val();
            let password = $('#password').val();
            if (cpass.length > 0 && password.length > 0) {
                if (cpass !== password) {
                    Swal.fire({
                        title: "<?=trans('error')?>!",
                        width: 400,
                        text: "<?=trans('passwordMatch')?> .",
                        icon: 'error',
                        confirmButtonText: "<?=trans('ok')?>"
                    })
                }
            }
        });
        $(".show-password").click(function(){
            const input = $(this).parent().prev()[0];
            if(input.type === "text") input.type = "password";
            else input.type = "text";
        })
        $('body').on('click', '#EditUser', function () {
            let user = $(this).attr('user');
            let lang = $('#profileLang').val();
            location.href = `account.php?lang=${lang}&user=${user}`;
        });
        $('#joinConfirmEmail').mouseleave(function () {
            let confirmation = $(this).val();
            let email = $('#joinMemberEmail').val();
            if (confirmation.length > 0 && email.length > 0) {
                if (confirmation !== email) {
                    $(this).addClass('input-error');
                    $('.email_confirmation_error').removeClass('d-none');
                } else {
                    $(this).removeClass('input-error');
                    $('.email_confirmation_error').addClass('d-none');
                }
            }
        });
        $('#invConfirmEmail').mouseleave(function () {
            let confirmation = $(this).val();
            let email = $('#invjoinMemberEmail').val();
            if (confirmation.length > 0 && email.length > 0) {
                if (confirmation !== email) {
                    Swal.fire({
                        title: "<?=trans('error')?>!",
                        width: 400,
                        text: "<?=trans('email_mismatch')?> .",
                        icon: 'error',
                        confirmButtonText: "<?=trans('ok')?>"
                    })
                } else {
                }
            }
        });
        $('#relatedMemberEmailConfirmation').mouseleave(function () {
            let confirmation = $(this).val();
            let email = $('#relatedMemberEmail').val();
            if (confirmation.length > 0 && email.length > 0) {
                if (confirmation !== email) {
                    $(this).addClass('input-error');
                    $('.confirm_email_error').removeClass('d-none');
                } else {
                    $(this).removeClass('input-error');
                    $('.confirm_email_error').addClass('d-none');
                }
            }
        });
        $('.facebook').mouseleave(function () {
            let fb = $(this).val();
            if (fb.length > 0 && !fb.includes("facebook.com")) {
                $(this).addClass('input-error');
                $('.fb_error').removeClass('d-none');
            } else {
                $(this).removeClass('input-error');
                $('.fb_error').addClass('d-none');
            }
        });
        $('.twitter').mouseleave(function () {
            let twitter = $(this).val();
            if (twitter.length > 0 && !twitter.includes("twitter.com")) {
                $(this).addClass('input-error');
                $('.twitter_error').removeClass('d-none');
            } else {
                $(this).removeClass('input-error');
                $('.twitter_error').addClass('d-none');
            }
        });
        $('.instagram').mouseleave(function () {
            let instagram = $(this).val();
            if (instagram.length > 0 && !instagram.includes("instagram.com")) {
                $(this).addClass('input-error');
                $('.instagram_error').removeClass('d-none');
            } else {
                $(this).removeClass('input-error');
                $('.instagram_error').addClass('d-none');
            }
        });
        $('.snapchat').mouseleave(function () {
            let snapchat = $(this).val();
            if (snapchat.length > 0 && !snapchat.includes("snapchat.com")) {
                $(this).addClass('input-error');
                $('.snapchat_error').removeClass('d-none');
            } else {
                $(this).removeClass('input-error');
                $('.snapchat_error').addClass('d-none');
            }
        });
        $('body').on('click', '#addNode', function (e) {
            $('#modal7').modal('hide');
            $('#modal55').modal('show');
        });
        $('body').on('click', '#close55', function (e) {

            $('#addNodeForm')[0].reset();
            $('#memberName, #memberEmail, #memberConfirmation, #memberPhone, #profileDiv, #country_residence, #addNodeSubmit').hide();
            $('#joinConfirmEmail').removeClass('input-error');
            $('.email_confirmation_error').addClass('d-none');
            $('#modal55').modal('hide');
        });
        $('body').on('click', '#familyAccess', function (e) {

            let loggedFamily = $(this).attr('family');
            let userFamily = $(this).attr('userFamily');
            let status = $(this).attr('status');
            let lang = $('#profileLang').val();
            $.ajax({
                type: 'POST',
                url: 'api/global.php',
                data: {
                    family_to_access: userFamily
                },
                dataType: 'Text',
                cache: false
            }).done(function(res){
                if(res != 0 && loggedFamily != userFamily){
                    location.href = `profile.php?lang=${lang}&family=${userFamily}`;
                } else if(loggedFamily == userFamily){
                    location.href = `profile.php?lang=${lang}`;
                } else if(res == 0){
                    $('#accessedFamilyId').val(userFamily);
                    $('#modal7').modal('hide');
                    $('#modal31').modal('show');
                }
            })
        });
        $('body').on('click', '#close31', function(){
            $('#modal31').modal('hide');
        });
        var modal8_state = false;
        var modal8_el = null;
        $('body').on('click', '.cell', function (e) {
            modal8_el = $(this);
            if ($("#modal7").hasClass('show')){
                $('#modal7').modal('hide');
                modal8_state = true;
                $('#modal7').on('hidden.bs.modal', function (){
                    if (modal8_state) {
                        modal8_el.click();
                    }
                });
                return false;
            }
            modal8_state = false;
            let user = $(this).attr('href');
            let lang = "<?=trans('lang')?>";
            $.ajax({
                type: 'POST',
                url: 'api/global.php',
                data: {
                    user: user,
                    lang: lang
                },
                dataType: "Json",
                cache: false
            }).done(function (res) {
                $('#profileImage').attr('src', `${res.image}`);
                $('#userId').html(res.member_id);
                if(res.role != 'admin' && res.role != 'creator' && res.parent_id != 'alpha'){
                    $('#toggleMember').show();
                    $('#toggleMember').attr('user', `${res.user_id}`);
                    $('#toggleMember').attr('role', `${res.role}`);
                    $('#toggleMember').attr('parent', `${res.parent_id}`);
                } else {
                    $('#toggleMember').hide();
                }
                if(res.display == 0){
                    $('#spanText').text("<?=trans('show')?>");
                    $('#toggleMember').text("<?=trans('show')?>");
                    $('#hideThisMember').text("<?=trans('show')?>");
                } else {
                    $('#spanText').text("<?=trans('hide')?>");
                    $('#toggleMember').text("<?=trans('hide')?>");
                    $('#hideThisMember').text("<?=trans('hide')?>");
                }
                $('#addMember').attr('user', `${res.user_id}`);
                $('#addMember').attr('gender', `${res.gender}`);
                $('#addMember').attr('outerHusband', `${res.outer_husband}`);
                $('#addMember').attr('parent', `${res.parent_id}`);
                if(res.parent_id === 'alpha'){
                    $('#addMember').attr('alpha', '1');
                } else {
                    $('#addMember').attr('alpha', '0');
                }
                $('#modal7 .user-name').html(res.name);
                $('#modal7 .modal-profile-country').attr('src', res.country_row.image);
                if (res.twitter && res.twitter != "") {
                    $('#modal7 .user-twitter').show();
                    $('#modal7 .user-twitter').attr('href', res.twitter);
                }
                else {
                    $('#modal7 .user-twitter').attr('href', '#');
                    $('#modal7 .user-twitter').hide();
                }
                if (res.facebook && res.facebook != "") {
                    $('#modal7 .user-facebook').show();
                    $('#modal7 .user-facebook').attr('href', res.facebook);
                }
                else {
                    $('#modal7 .user-facebook').attr('href', '#');
                    $('#modal7 .user-facebook').hide();
                }
                if (res.snapchat && res.snapchat != "") {
                    $('#modal7 .user-snapchat').show();
                    $('#modal7 .user-snapchat').attr('href', res.snapchat);
                }
                else {
                    $('#modal7 .user-snapchat').attr('href', '#');
                    $('#modal7 .user-snapchat').hide();
                }
                if (res.instagram && res.instagram != "") {
                    $('#modal7 .user-instagram').show();
                    $('#modal7 .user-instagram').attr('href', res.instagram);
                }
                else {
                    $('#modal7 .user-instagram').attr('href', '#');
                    $('#modal7 .user-instagram').hide();
                }
                if (res.club_logo && res.club_logo != "") {
                    $('#modal7 .user-club-anchor').show();
                    $('#modal7 .user-club-anchor').attr('href', res.club_logo);
                    $('#modal7 .user-club').attr('src', res.club_logo);
                }
                else {
                    $('#modal7 .user-club').attr('src', '#');
                    $('#modal7 .user-club-anchor').attr('href', '#');
                    $('#modal7 .user-club-anchor').hide();
                }
                if(res.family_id === 0){
                    $('#userFamily').html(`
                            <span>
                            ${res.family_name}
                            </span
                        `);
                } else {
                    $('#userFamily').html(`
                            <span style="cursor: pointer; text-decoration: underline; color: blue;" id="familyAccess" family="<?php echo $_SESSION['family_id']; ?>" userFamily="${res.family_id}" status="${res.FamilyStatus}">
                            ${res.family_name}
                            </span>
                        `);
                }
                if(res.role == 'user'){
                    $('#member_role').text('Member');
                } else {
                    $('#member_role').text('Assistant');
                }

                $('#memberGender').html(res.memberGender);
                $('#userEmail').html(res.email);
                $('#userPhone').html(res.phone);
                $('#userJob').html(res.occupation);
                $('#userCountry').html(res.country);
                $('#EditUser').attr('user', res.user_id);
                $('#userNationality').html(res.nationality_name);
                $('#DOB').html(res.date_of_birth);
                $('#DOD').html(res.date_of_death);
                $('#userKunya').html(res.kunya);
                $('#UserBio').empty();
                if (res.about != '' && res.about != null) {
                    let str = res.about;
                    $("#user-bio-see .bio-container").html(str);
                    if(str.length > 50) str = str.substring(0,50)+'...<a href="#" data-toggle="modal" data-target="#user-bio-see" class="user-bio-see"><?=trans("view_more")?></a>';
                    $('#bioTab').show();
                    $('#UserBio').html(str);
                }
                $('#userMother').empty();
                if (res.mother) {
                    $('#motherTab').show();
                    $('#userMother').empty().append(`

                            <a href="${res.mother_id}" class="cell" style="color: blue !important; text-decoration: underline !important;">${res.mother} </a>
                    `);
                }
                $('#userFather').empty();
                if (res.parent_id != 'alpha' && res.parent_id != 'alpha_2' && res.parent_id != 0) {
                    $('#fatherTab').show();
                    $('#userFather').empty().append(`
                            <a href="${res.parent_id}" class="cell" style="color: blue !important; text-decoration: underline !important;">${res.parent_name} </a>
                    `);
                }
                if (res.gender == 'Male') {
                    $('#wifesTab').show();
                    $('#userWifes').html('');
                    if(res.wife_name){
                        res.wife_name.forEach(function (wife) {
                            $('#userWifes').append(`
                                <a href="${wife.id}" class="cell" style="color: blue !important; text-decoration: underline !important;">${wife.name}</a> &nbsp;
                            `);
                        })
                    }
                } else {
                    $('#wifesTab').hide();
                }
                if (res.siblings) {
                    $('#siblingsTab').show();
                    $('#userSiblings').html('');
                    res.siblings.forEach(function (row) {
                        $('#userSiblings').append(`
                            <a href="${row.user_id}" class="cell" style="color: blue !important; text-decoration: underline !important;">${row.name} </a> &nbsp;
                        `);
                    })
                } else {
                    $('#userSiblings').html('');
                }
                if (res.children) {
                    $('#childrenTab').show();
                    $('#userChildren').html('');
                    res.children.forEach(function (child) {
                        $('#userChildren').append(`
                            <a href="${child.user_id}" class="cell" style="color: blue !important; text-decoration: underline !important;">${child.name} </a> &nbsp;
                        `);
                    })
                } else {
                    $('#userChildren').html('');
                }
                if (res.gender != 'Male') {
                    $('#husbandTab').show();
                    $('#userHusband').html('');
                    if(res.husband_name){
                        if(res.husband_id){
                            $('#userHusband').append(`
                                <a href="${res.husband_id}" class="cell" style="color: blue !important; text-decoration: underline !important;">${res.husband_name}</a>
                            `);
                        } else {
                            $('#userHusband').append(`
                                <a style="color: blue !important; text-decoration: underline !important;">${res.husband_name}</a>
                            `);
                        }
                    }
                } else {
                    $('#husbandTab').hide();
                }
                $('#clubTab').show();
                $('#userClub').html(res.club_name)
                $('#interestsTab').show();
                $('#userInterests').html(res.interests)
                $('#deleteUser').attr('user', `${res.user_id}`);
                requestAnimationFrame(function() {
                    requestAnimationFrame(function () {
                        $('#modal7').ready(function () {
                            $('#modal7').modal('show');
                        })
                    });
                });
            });
            e.preventDefault();
        });
        $('body').on('click', '#close51', function () {
            $('#modal51').modal('hide');
        });
        $('body').on('click', '#close7', function () {
            $('#modal7').modal('hide');
        });
        if (successMessage.length > 0) {
            $('#modal6').modal('show');
        }
        $('body').on('click', '#startRecord', function () {
            $('#modal17').modal('show');
            $('#encodingRecord').hide();
        });
        $('body').on('click', '#close17', function () {
            $('#record').show();
            $('#modal17').modal('hide');
        });
        let mediaRecorder = {};
        let recordCount;
        $('body').on('click', '#record', function () {
            navigator.mediaDevices.getUserMedia({audio: true})
                .then(function (mediaStreamObj) {
                    let start = document.getElementById('record');
                    let stop = document.getElementById('stop');
                    var audioContext = window.AudioContext ? new window.AudioContext : new window.webkitAudioContext;
                    if (!audioContext) {
                        swal({
                            title: "Sorry!!",
                            text: "Your browers doesn't support Web Audio API, Please upgrade to the latest version or use 'Google Chrome' browser.",
                            type: "warning",
                            confirmButtonText: "Close",
                            closeOnConfirm: true
                        })
                    }
                    let mediaStreamSource = audioContext.createMediaStreamSource(mediaStreamObj);
                    mediaRecorder = new WebAudioRecorder(mediaStreamSource, {
                        encoding: "mp3",
                        encodeAfterRecord: true,
                        workerDir: "<?=asset('js/web-audio-recorder/lib-minified/')?>/",
                    });
                    mediaRecorder.setOptions({
                        encodeAfterRecord: true,
                        ogg: {
                            quality: 0.5
                        },
                        mp3: {
                            bitRate: 160
                        },
                        timeLimit: 10
                    });
                    mediaRecorder.startRecording();
                    $('#close17').hide();
                    $('#stop').show();
                    $('#recordStatus').html(mediaRecorder.state);
                    $('#encodingRecord').hide();
                    // $('#record').prop('disabled', true);
                    $('#record').hide();
                    let count = parseInt($('#counter').html());
                    recordCount = setInterval(function () {
                        if (count > 0) {
                            count--;
                            $('#counter').html(count);
                        }
                    }, 1000);
                    stop.addEventListener('click', (ev) => {
                        mediaRecorder.finishRecording();
                        $('#encodingRecord').show();
                    });
                    mediaRecorder.onComplete = (rec, blob) => {
                        clearInterval(recordCount);
                        $('#counter').html(10);
                        $('#encodingRecord').hide();
                        // $('#record').prop('disabled', false);
                        $('#record').show();
                        $('#stop').hide();
                        $('#close17').show();
                        $('#recordStatus').html('');
                        $('#recordStatus').hide();
                        $('#modal17').modal('hide');
                        var reader = new FileReader();
                        reader.readAsDataURL(blob);
                        let blobUrl;
                        reader.onloadend = function() {
                            blobUrl = reader.result;
                            let data = new FormData();
                            data.append('file', blob);
                            let family = $('#loggedFamily').val();
                            data.append('family', family);
                            console.log(data);
                            $.ajax({
                                url: "api/global.php",
                                type: 'POST',
                                data: data,
                                contentType: false,
                                processData: false,
                                dataType: 'Json'
                            }).done(function (res) {
                                $('#familyAudio').show();
                                $('#familyAudio').attr('src', `${res.record}`).trigger("play");
                                Swal.fire({
                                    icon: 'success',
                                    text: `${res.message}`,
                                    confirmButtonText: 'Ok'
                                }).then(function(){
                                    location.reload();
                                })
                            }).fail(function(err) {
                                console.log("Error: ", err);
                            })
                            mediaStreamObj.getTracks().forEach(track => track.stop())
                        }
                    }
                })
                .catch(function (err) {
                    console.log(err.name, err.message);
                });
        })
        $('#modal17').on('hide.bs.modal', function() {
            clearInterval(recordCount);
            if(mediaRecorder.isRecording && mediaRecorder.isRecording())
                mediaRecorder.cancelRecording();
            $('#counter').html(10);
            $('#record').show();
            $('#stop').hide();
            $('#close17').show();
            $('#recordStatus').html('');
            $('#recordStatus').hide();
        })
        $('.file').on('mouseover', function () {
            $(this).find('.deleteParent').show();
        });
        $('.file').on('mouseleave', function () {
            $('.deleteParent').hide();
        });
        $('.gender').change(function () {
            let currentUrl = window.location.href;
            // alert(currentUrl);
            let url = new URL(currentUrl);
            let type = url.searchParams.get("type");
            let gender = $(this).val();
            if (gender == 'Female' && type != 'H') {
                $('#joinStatus').attr('required', true).show();
                $('#joinParentSelect').attr('required', false).hide();
                $('#joinMother').attr('required', false).hide();
            } else {
                $('#joinStatus').attr('required', false).hide();
                $('#wifeFamily').hide();
                $('#joinParentSelect').attr('required', true).show();
                $('#husband').attr('required', false).hide();
                $('#joinMother').attr('required', false).hide();
            }
        });
        $('body').on('change', '#wifeFamily', function(){
            let family = $(this).val();
            if(family === '0'){
                $('.memberFamily').removeClass('d-none').find('input').attr('required', true);
            } else {
                $('.memberFamily').addClass('d-none').find('input').attr('required', false);
            }
        });
        $('body').on('change', '#family', function(){
            let family = $(this).val();
            // alert(family)
            if(family === '0'){
                $('.member_family').removeClass('d-none').find('input').attr('required', true);
            } else {
                $('.member_family').addClass('d-none').find('input').attr('required', false);
            }
        });
        $('#joinStatus').change(function () {
            let status = $(this).val();
            if (status == 0) {
                $("#joinParentSelect").attr('required', true).show();
                $('#husband').attr('required', false).hide();
                $('#wifeFamily').hide();
            } else if (status == 1) {
                $("#joinParentSelect").attr('required', false).hide();
                $('#joinMother').attr('required', false).hide();
                $('#husband').attr('required', true).show();
                $('#wifeFamily').show();
            }
        });
        $('.selection').select2();
        $('#userRole').change(function () {
            let role = $(this).val();
            if (role == 'assistant') {
                $('#memberName, #memberEmail, #memberConfirmation, #memberPhone, #country_residence,#addNodeSubmit').show();
            } else if(role == 'member' || role == 'both') {
                $('#memberName, #memberEmail, #memberConfirmation, #memberPhone, #profileDiv, #country_residence,#addNodeSubmit').hide();
                setTimeout(function(){
                    Swal.fire({
                        width: 400,
                        text: '<?=trans("add_members_on_tree")?>.',
                        icon: 'info',
                        confirmButtonText: 'Ok'
                    })
                }, 10)
                $('#modal55').modal('hide');
                $('.treeParent').show(2000);
                $('html, body').animate({
                    scrollTop: $(".family_alpha").offset().top - 50
                }, 2000);
                // $('.treeParent')[0].scrollIntoView();
            } else {
                $('#memberName, #memberEmail, #memberConfirmation, #memberPhone, #profileDiv, #country_residence,#addNodeSubmit').hide();
            }
        });
        $('body').on('click', '#addMember', function(){
            $('#modal7').modal('hide');
            let user = $(this).attr('user');
            $('#relatedMember').val(user);
            $('#relatedMemberGender').val($(this).attr('gender'));
            let gender = $(this).attr('gender');
            let outerHusband = $(this).attr('outerhusband');
            let alpha = $(this).attr('alpha');
            let parent = $(this).attr('parent');
            $('#checkAlpha').val(alpha);
            if(gender === 'Male' && outerHusband != 1 && alpha == 0){
                $('#relationType').empty().append(`
                        <option value=''><?=trans('choose_member_type')?></option>
                        <option value='wife'><?=trans('wife')?></option>
                        <option value='sister'><?=trans('sister')?></option>
                        <option value='brother'><?=trans('brother')?></option>
                    `);
                $.ajax({
                    type: 'post',
                    url: 'api/global.php',
                    data: {
                        check_wife_exist: user
                    },
                    dataType: 'Text',
                    cache: false
                }).done(function(res){
                    if(res > 0){
                        $('#relationType').append(`

                                <option value='son'><?=trans('son')?></option>
                                <option value='daughter'><?=trans('daughter')?></option>

                            `);
                    }
                })
            }
            else if(gender === 'Woman' && parent === '0'){
                $('#relationType').empty().append(`
                        <option value=''><?=trans('choose_member_type')?></option>
                        <option value='son'><?=trans('son')?></option>
                        <option value='daughter'><?=trans('daughter')?></option>
                    `);
            }
            else if(gender === 'Woman' && parent !== '0'){
                $('#relationType').empty().append(`
                        <option value=''><?=trans('choose_member_type')?></option>
                        <option value='husband'><?=trans('husband')?></option>
                        <option value='sister'><?=trans('sister')?></option>
                        <option value='brother'><?=trans('brother')?></option>
                        <option value='son'><?=trans('son')?></option>
                        <option value='daughter'><?=trans('daughter')?></option>
                    `);
            }
            else if(gender === 'Male' && outerHusband == 1){
                $('#relationType').empty().append(`
                        <option value=''><?=trans('choose_member_type')?>/option>
                        <option value='son'><?=trans('son')?></option>
                        <option value='daughter'><?=trans('daughter')?></option>
                    `);
            }
            else if(gender === 'Girl' && alpha == 1){
                $('#relationType').empty().append(`
                        <option value=''><?=trans('choose_member_type')?></option>
                        <option value='father'><?=trans('immediate_father')?></option>
                        <option value='husband'><?=trans('husband')?></option>
                        <option value='sister'><?=trans('sister')?></option>
                        <option value='brother'><?=trans('brother')?></option>
                    `);
            }
            else if(gender === 'Girl'){
                $('#relationType').empty().append(`
                        <option value=''><?=trans('choose_member_type')?></option>
                        <option value='husband'><?=trans('husband')?></option>
                        <option value='sister'><?=trans('sister')?></option>
                        <option value='brother'><?=trans('brother')?></option>
                    `);
            }
            else if(gender === 'Male' && outerHusband != 1 && alpha == 1){
                $('#relationType').empty().append(`
                        <option value=''><?=trans('choose_member_type')?></option>
                        <option value='father'><?=trans('immediate_father')?></option>
                        <option value='wife'><?=trans('wife')?></option>
                        <option value='sister'><?=trans('sister')?></option>
                        <option value='brother'><?=trans('brother')?></option>
                    `);
                $.ajax({
                    type: 'post',
                    url: 'api/global.php',
                    data: {
                        check_wife_exist: user
                    },
                    dataType: 'Text',
                    cache: false
                }).done(function(res){
                    if(res > 0){
                        $('#relationType').append(`
                                <option value='son'><?=trans('son')?></option>
                                <option value='daughter'><?=trans('daughter')?></option>
                            `);
                    }
                })
            }
            $('#modal555').modal('show');
        });
        $('#relationType').change(function(){
            let relation = $(this).val();
            let gender = $('#relatedMemberGender').val();
            let alpha = $('#checkAlpha').val();
            let user = $('#relatedMember').val();
            if(relation === 'wife'){
                $('#NodeStatus').hide().attr('required', false);
                $('#familyDiv').removeClass('d-none');
                $('#family').show();
                if(alpha == 1){
                    let father = $('#relatedMember').val();
                    $.ajax({
                        type: 'POST',
                        url: 'api/global.php',
                        data: {
                            fatherChildren: father
                        },
                        dataType: 'Text',
                        cache: false
                    }).done(function(res){
                        if(res){
                            $('#childrenDiv').show();
                            $('#children').attr('required', true).append(res);
                        } else {
                            $('#childrenDiv').hide();
                            $('#children').empty().attr('required', false);
                        }
                    })
                }
            } else if(relation == 'daughter' || relation == 'sister'){
                $('#familyDiv').addClass('d-none');
                $('#family').hide();
                $('#NodeStatus').show().attr('required', true);
            } else if(relation == 'husband') {
                $('#familyDiv').removeClass('d-none');
                $('#family').show();
                $('#NodeStatus').hide().attr('required', false);
            } else {
                $('#NodeStatus').hide().attr('required', false);
                $('#familyDiv').addClass('d-none');
                $('#family').hide();
                $('#childrenDiv').hide();
                $('#children').empty().attr('required', false);
            }
            if(relation == 'father'){
                $('#childrenDiv').hide();
                $('#children').empty().attr('required', false);
                $.ajax({
                    type: 'post',
                    url: 'api/global.php',
                    data: {
                        check_for_wife: user
                    },
                    dataType: 'Text',
                    cache: false
                }).done(function(data){
                    if(data != 0){
                        Swal.fire({
                            title: "<?=trans('warning')?> !",
                            width: 400,
                            text: "<?=trans('add_wife_first')?> .",
                            icon: 'warning',
                            confirmButtonText: "<?=trans('ok')?>"
                        })
                    }
                })
            }
            if(relation == 'husband' && gender === 'Woman'){
                Swal.fire({
                    width: 400,
                    text: "<?=trans('add_husband_hide')?>. ",
                    icon: 'info',
                    confirmButtonText: "<?=trans('ok')?>"
                })
            }
            if(gender == 'Male' && (relation == 'daughter' || relation == 'son')){
                let father = $('#relatedMember').val();
                $.ajax({
                    type: 'post',
                    url: 'api/global.php',
                    data: {
                        userFather: father
                    },
                    dataType: 'Text',
                    cache: false,
                    async: false
                }).done(function (res) {
                    if (res) {
                        $('#memberMomDiv').removeClass('d-none');
                        $('#memberMom').empty().attr('required', true).show().append(res);
                    } else {
                        $('#memberMomDiv').addClass('d-none');
                        $('#memberMom').attr('required', false).empty().hide();
                    }
                })
            } else {
                $('#memberMomDiv').addClass('d-none');
                $('#memberMom').empty().attr('required', false).hide();
            }
            if((relation == 'son' || relation == 'daughter') && gender == 'Woman'){
                $.ajax({
                    type: 'post',
                    url: 'api/global.php',
                    data: {
                        get_wife_possible_husbands: user
                    },
                    dataType: 'Json',
                    cache: false
                }).done(function(res){
                    // console.log(res)
                    if(res.length > 0){
                        $('#choose_father').removeClass('d-none').find('select').empty().attr('required', true).append(`
                                    <option value="">Choose Father</option>
                            `);
                        res.forEach(function(row){
                            $('#choose_father').find('select').append(`
                                    <option value="${row.user_id}">${row.name}</option>
                                `);
                        })
                    } else {
                        $('#choose_father').addClass('d-none').find('select').empty().attr('required', false);
                    }
                })
            } else {
                $('#choose_father').addClass('d-none').find('select').empty().attr('required', false);
            }
        });
        $('#NodeStatus').change(function(){
            let status = $(this).val();
            if(status == 1){
                $('#NodeHusband').show().attr('required', true);
            } else {
                $('#NodeHusband').hide().attr('required', false);
            }
        });
        $('#memberRole').change(function(){
            let role = $(this).val();
            if(role === 'assistant'){
                $('#relatedMemberEmailConfirmation').attr('required', true);
                $('#relatedMemberEmail').attr('required', true);
                $('.star').removeClass('d-none');
            } else {
                $('#relatedMemberEmailConfirmation').attr('required', false);
                $('#relatedMemberEmail').attr('required', false);
                $('.star').addClass('d-none');
            }
        });
        $('body').on('click', '#close555', function(){
            $('#addRelatedMemberForm')[0].reset();
            $('#NodeHusband').hide().attr('required', false);
            $('#memberMomDiv').addClass('d-none');
            $('#memberMom').empty().attr('required', false).hide();
            $('#familyDiv').addClass('d-none');
            $('#family').attr('required', false).hide();
            $('#NodeStatus').hide().attr('required', false);
            $('#childrenDiv').hide();
            $('#children').empty().attr('required', false);
            $('.member_family').addClass('d-none').find('input').attr('required', false);
            $('#choose_father').addClass('d-none').find('select').empty().attr('required', false);
            $('#modal555').modal('hide');
        })
        $('.country_id').change(function () {
            let country = $(this).val();
            $.ajax({
                type: 'POST',
                url: 'api/global.php',
                data: {
                    country: country,
                    ajax: 'key'
                },
                dataType: 'Text',
                cache: false
            }).done(function (res) {
                $('.key').val(res);
            })
        });
        $('body').on('click', '.deleteFile', function () {
            let id = $(this).attr('fileId');
            $('#deleteFile').val(id);
            $('#modal3').modal('show');

        })
        $('body').on('click', '#deleteSubmit', function(){
            $('#modal3').modal('hide');
            let id = $('#deleteFile').val();
            $.ajax({
                type:'post',
                url:'api/global.php',
                data: {
                    fileId: id
                },
                dataType: 'Text',
                cache: false
            }).done(function(res){
                Swal.fire({
                    icon: 'success',
                    text: `${res}`,
                    confirmButtonText: "<?=trans('ok')?>"
                }).then(function () {
                    location.reload();
                });
            })
        });
        let error = $('#error').val();
        if (error.length > 0) {
            Swal.fire({
                title: 'Error!',
                width: 400,
                text: `${error}`,
                icon: 'error',
                confirmButtonText: "<?=trans('ok')?>"
            })
        }
        let deleteMedia = $('#deleteMedia');
        if (deleteMedia.length > 0) {
            deleteMedia = deleteMedia.val();
            if (deleteMedia){
                Swal.fire({
                    title: "<?=trans('succ')?>",
                    width: 400,
                    text: `${deleteMedia}`,
                    icon: 'success',
                    confirmButtonText: "<?=trans('ok')?>"
                })
            }
        }
        $('body').on('click', '.info', function(e){
            let type = $(this).attr('data');
            let family = $(this).attr('family');
            let role = $('#loggedUserRole').val();
            $('.info').removeClass('active');
            $(this).addClass('active');
            $.ajax({
                type: 'post',
                url: 'api/global.php',
                data: {
                    usersType: type ,
                    userFamily: family
                },
                dataType: 'Json',
                cache: false
            }).done(function(res){
                $('#usersList').parent().DataTable().clear().destroy();
                $('#usersList').empty();
                let i = 1;
                role = res.role;
                res.users.forEach(function(row){
                    let color= "color: brown !important";
                    let toggleStatus = "fa fa-toggle-on";
                    let toggleTitle = "<?=trans('hide_tree')?>";
                    let toggleId = "toggleOff";
                    let style = "";
                    let hidden = "";
                    let toggleDisplay = '';
                    if(row.display == '0'){
                        color = "color: #202020 !important";
                        toggleStatus = "fa fa-toggle-off";
                        toggleTitle = "<?=trans('show_tree')?>";
                        toggleId = "toggleOn";
                    } else if(row.display == '2'){
                        color = "color: gray !important";
                        toggleStatus = "fa fa-toggle-off";
                        toggleTitle = "<?=trans('show_tree')?>";
                        toggleId = "toggleOn";
                        toggleDisplay = "display: none !important;"
                    }
                    if(role != 'admin' && role != 'creator'){
                        hidden = 'display: none !important;';
                    }
                    if(row.member == '0'){
                        style = "display: none !important;";
                    }
                    $('#usersList').append(`
                            <tr>
                                <td>
                                    <a href='${row.user_id}' class='cell' style='${color}'>
                                        ${i}- ${row.name}
                                    </a>
                                </td>
                                <td style="text-align: center !important;">
                                  <a style="font-size: 100%; color: black !important; margin: auto 1vw !important; cursor: pointer; ${style}" data-title="<?=trans('show_tree')?>" user="${row.user_id}" class="viewTree"><i class="fa fa-eye"></i></a>
                                  <a style="font-size: 100%; color: black !important; cursor: pointer; margin: auto 1vw !important; ${style} ${hidden}" data-title="<?=trans('delete_tree')?>" user="${row.user_id}" class="deleteTree" parent="${row.parent_id}"><i class="fa fa-trash"></i></a>
                                  <a style="font-size: 100%; color: black !important; cursor: pointer; margin: auto 1vw !important; ${style} ${toggleDisplay}" data-title="${toggleTitle}" user="${row.user_id}" id="${toggleId}"><i class="${toggleStatus}"></i></a>
                                </td>
                            </tr>
                        `);
                    i++;
                });
				<?php if ($lang == 'ar') { ?>
                $('#usersList').parent().DataTable({responsive: true,
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Arabic.json"
                    }});
				<?php } else {?>
                $('#usersList').parent().DataTable({responsive: true});
				<?php } ?>
            });
            e.preventDefault();
        });
        $('body').on('click', '.viewTree', function(){
            let user = $(this).attr('user');
            let name = $(this).parent().siblings().find(".cell").text();
            $.ajax({
                type: 'POST',
                url: 'api/global.php',
                data: {
                    treeUser: user,
                    tree: 1
                },
                dataType: 'Text',
                cache: false
            }).done(function(res){
                $('#firstMember > span').empty().html(name);
                $('#treeBody > ul').empty().append(`
                        ${res}
                    `);
                let arr = [];
                $('#treeBody ul').each(function(){
                    let nodesLength = $(this).children().length;
                    arr.push(nodesLength);
                })
                let width = Math.max( ...arr ) * 300;
                $('#modal88').modal('show');
                $('#modal88').on('shown.bs.modal', function () {
                    requestAnimationFrame(function() {
                        requestAnimationFrame(function () {
                            let container = $('#modal88 #treeBody:first');
                            let inline = $('#modal88 #treeBody ul:first');
                            let lang = "<?=$lang?>";
                            console.log("Centring", inline, container, lang);
                            if(inline[0]){
                                if (lang == 'en'){
                                    container.scrollLeft((inline[0].getBoundingClientRect().width/2) - (container[0].getBoundingClientRect().width/2));
                                    // tree_shadow.scrollLeft((treeDiv[0].getBoundingClientRect().width/2) - tree_shadow[0].getBoundingClientRect().width/2);
                                }
                                else {
                                    container.scrollLeft(-1* ((inline[0].getBoundingClientRect().width/2) - (container[0].getBoundingClientRect().width/2)));
                                    // tree_shadow.scrollLeft(-1 * ((treeDiv[0].getBoundingClientRect().width/2) - (tree_shadow[0].getBoundingClientRect().width/2)));
                                }
                            }

                        });
                    });
                });
            });
        });
        $('#toggleMember').click(function(){
            let role = $(this).attr('role');
            let alpha = $(this).attr('parent');
            if(role != 'creator' && role != 'admin' && alpha != 'alpha'){
                let user = $(this).attr('user');
                $('#hiddenMember').val(user);
                $('#modal7').modal('hide');
                $('#modal10').modal('show');
            }
        });
        $('#hideThisMember').click(function(){
            let user = $('#hiddenMember').val();
            let text = $('#toggleMember').text();
            let display = 0;
            if(text.includes("<?=trans('show')?>")){
                display = 1;
            }
            $('#modal10').modal('hide');
            $.ajax({
                type: 'post',
                url: 'api/global.php',
                data: {
                    hiddenMember: user,
                    display: display
                },
                dataType: 'Text',
                cache: false
            }).done(function(res){
                Swal.fire({
                    icon: 'success',
                    text: `${res}`,
                    confirmButtonText: "<?=trans('ok')?>"
                }).then(function () {
                    location.reload();
                });
            })

        });
        $('#close10').click(function(){
            $('#modal10').modal('hide');
        });
        $('body').on('click', '#close88', function(){
            $('#modal88').modal('hide');
        });
        $('body').on('click', '#toggleOn', function(){
            $('#modal8').modal('hide');
            $('#changeStatusOn').val($(this).attr('user'));
            $('#modal11').modal('show');
        });
        $('body').on('click', '#close11', function(){
            $('#modal11').modal('hide');
        });
        $('body').on('click', '#showUser', function(){
            let user = $('#changeStatusOn').val();
            $('#modal11').modal('hide');
            $.ajax({
                type: 'POST',
                url: 'api/global.php',
                data: {
                    showUser: user
                },
                dataType: 'Text',
                cache: false
            }).done(function(res){
                Swal.fire({
                    icon: 'success',
                    text: `${res}`,
                    confirmButtonText: "<?=trans('ok')?>"
                }).then(function () {
                    location.reload();
                });
            })
        });
        $('body').on('click', '#toggleOff', function(){
            $('#modal8').modal('hide');
            $('#changeStatusOff').val($(this).attr('user'));
            $('#modal111').modal('show');
        });
        $('body').on('click', '#close111', function(){
            $('#modal111').modal('hide');
        });
        $('body').on('click', '#hideUser', function(){
            let user = $('#changeStatusOff').val();
            $('#modal111').modal('hide');
            $.ajax({
                type: 'POST',
                url: 'api/global.php',
                data: {
                    hideUser: user
                },
                dataType: 'Json',
                cache: false
            }).done(function(res){
                if(res.success == 0){
                    Swal.fire({
                        title: "<?=trans('error')?> !",
                        icon: 'error',
                        text: `${res.message}`,
                        confirmButtonText: "<?=trans('ok')?>"
                    })
                } else if(res.success == 1){
                    Swal.fire({
                        title: "<?=trans('succ')?>",
                        icon: 'success',
                        text: `${res.message}`,
                        confirmButtonText: "<?=trans('ok')?>"
                    }).then(function () {
                        location.reload();
                    });
                }

            })
        });
        $('body').on('click', '.deleteTree', function(){
            let role = $('#loggedUserRole').val();
            let user = $(this).attr('user');

            $.ajax({
                type: 'GET',
                url: 'api/global.php',
                data: {
                    get_user_data: 1
                },
                dataType: 'Json'
            }).done(function(res){
                role = res.role;
                if(role.includes('admin') || role.includes('creator')){
                    $('#modal8').modal('hide');
                    let alpha = $(this).attr('parent');
                    if(alpha === 'alpha'){
                        Swal.fire({
                            title: "<?=trans('are_you_sure')?>",
                            width: 400,
                            text: "<?=trans('delete_everything')?> .",
                            icon: 'info',
                            confirmButtonText: "<?=trans('ok')?>"
                        })
                    }
                    $('#deleteNode').val(user);

                    $('#modal51').modal('show');
                }
            })
        });
        $('body').on('click', '#deleteNodeSubmit', function(){
            let user = $('#deleteNode').val();
            let lang = $('#profileLang').val();
            let role = $('#loggedUserRole').val();
            $('#modal51').modal('hide');
            $.ajax({
                type: 'POST',
                url: 'api/global.php',
                data: {
                    deleteTree: user,
                    lang: lang,
                    logged_permissions: role
                },
                dataType: 'Json',
                cache: false
            }).done(function(res){
                if(res.success == 0){
                    Swal.fire({
                        width: 400,
                        text: `${res.message}`,
                        icon: 'error',
                        confirmButtonText: "<?=trans('ok')?>"
                    })
                } else if(res.success == 1){
                    Swal.fire({
                        width: 400,
                        icon: 'success',
                        text: `${res.message}`,
                        confirmButtonText: "<?=trans('ok')?>"
                    })
                }
                setTimeout(function(){
                    location.reload();
                }, 3000)

            })
        });
        $('#modal8').on('show.bs.modal', function () {
            requestAnimationFrame(function() {
                requestAnimationFrame(function () {
                    if ( ! $.fn.DataTable.isDataTable( '#modal8 table' ) ) {
						<?php if ($lang == 'ar') { ?>
                        $('#usersList').parent().DataTable({responsive: true,
                            "language": {
                                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Arabic.json"
                            }});
						<?php } else {?>
                        $('#usersList').parent().DataTable({responsive: true,});
						<?php } ?>
                    }
                });
            });
        });
        $('body').on('click', '#familyMembers', function(){
            $('#modal8').modal('show');
        });
        $('body').on('click', '#close8', function(){
            $('#modal8').modal('hide');
        });
        $('body').on('click', '#inviteUser', function () {
            $('#modal4').modal('show');
        });
        $('body').on('click', '#addFile', function () {
            // alert('family');
            $('#modal1').modal('show');
        });
        $('body').on('click', '#editBtn', function () {
            let family = $(this).attr('family');
            // alert(family);
            $.ajax({
                type: 'POST',
                url: 'api/global.php',
                data: {
                    family: family,
                    ajax: 1
                },
                dataType: 'Json',
                cache: false
            }).done(function (res) {
                $('#fnameEn').val(res.name_en);
                $('#desc_en').val(res.desc_en);
                $('#fnameAr').val(res.name_ar);
                $('#desc_ar').val(res.desc_ar);
                $('#fstatus').val(res.status);
                // $('#mostpopular').val(res.mostpopular);
                $('#edit_family_id').val(res.id);
                $('#modal2').find($('div.invalid-feedback')).remove();
                $('#modal2').find($('.form-control')).removeClass('is-invalid');
                $('#modal2').modal('show');
            })
        });
        $('#close1').click(function () {
            $('#modal1').modal('hide');
        });
        $('#close2').click(function () {
            $('#modal2').modal('hide');
        });
        $('#close3').click(function () {
            $('#modal3').modal('hide');
        });
        $('#close4').click(function () {
            $('#mailMessage').addClass('d-none').find('p').html('');
            $('#modal4').modal('hide');
        });
        $('#close5').click(function () {
            $('.memberFamily').hide().find('input').attr('required', false);
            $('#modal5').modal('hide');
        });
        $('body').on('click', '#close6', function () {
            $('#modal6').modal('hide');
			<?php
			unset($_SESSION['submitStatus']);
			unset($_SESSION['message']);
			unset($_SESSION['errors']);
			?>
            let new_user = $('#newly_added_member').val();
            // $('html, body').animate({
            //     scrollTop: ($(`#${new_user}`).offset().top)
            // },500);
            $(`#${new_user}`)[0].scrollIntoView();
        })
    })

    $(document).on('keyup change', 'input[id=fileTitle], input[id=fileDesc], input[id=editFileTitle], input[id=editFileDesc], input[id=fnameEn], textarea[id=desc_en]', function () {
        let what_changed = $(this);
        let to_change;
        if (what_changed.attr('id') == 'fileTitle')
            to_change = $("input[id=fileArTitle]");
        else if(what_changed.attr('id') == 'fileDesc')
            to_change = $("input[id=fileArDesc]");
        else if(what_changed.attr('id') == 'editFileTitle')
            to_change = $("input[id=editFileArTitle]");
        else if(what_changed.attr('id') == 'editFileDesc')
            to_change = $("input[id=editFileArDesc]");
        else if(what_changed.attr('id') == 'fnameEn')
            to_change = $("input[id=fnameAr]");
        else
            to_change = $("textarea[id=desc_ar]");
        
        translate(what_changed.val(), { to: "ar" })
            .then(res => {
                // I do not eat six days
                to_change.val(res.text);
            })
            .catch(err => {
                console.error(err);
            });
    })
</script>
<?php include("footer.php"); ?>
<script src="//cdn.jsdelivr.net/npm/vue@2.6.11/dist/vue.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/axios/0.21.0/axios.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.943/pdf.js"></script>
<script src="//cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
<script src="js/pdfThumbnails.js"></script>

<!--<script src='//cdn.jsdelivr.net/npm/lightgallery@2.0.0-beta.3/lightgallery.umd.min.js'></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.2.0-beta.0/lightgallery.umd.min.js"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.2.0-beta.0/plugins/fullscreen/lg-fullscreen.umd.min.js"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.2.0-beta.0/plugins/video/lg-video.umd.min.js"></script>-->
<!--<script src="//cdnjs.cloudflare.com/ajax/libs/lightgallery/2.2.0-beta.0/plugins/thumbnail/lg-thumbnail.umd.min.js"></script>-->

<!--<script src="https://cdn.jsdelivr.net/npm/lightgallery@2.1.5/lightgallery.umd.min.js"></script>-->
<!--<script src="https://cdn.jsdelivr.net/npm/lightgallery@2.1.5/plugins/thumbnail/lg-thumbnail.umd.js"></script>-->
<script src='//cdnjs.cloudflare.com/ajax/libs/lightgallery/1.2.21/js/lightgallery-all.min.js'></script>

<script src='//npmcdn.com/isotope-layout@3.0/dist/isotope.pkgd.min.js'></script>
<script src='//npmcdn.com/imagesloaded@4.1/imagesloaded.pkgd.js'></script>
<!--<script src="//cdn.jsdelivr.net/npm/bigpicture@2.5.3/dist/BigPicture.min.js"></script>-->
<script src="//vjs.zencdn.net/4.12/video.js"></script>
<script>
    function rafAsync() {
        return new Promise(resolve => {
            requestAnimationFrame(resolve); //faster than set time out
        });
    }
    async function checkElement(selector) {
        let querySelector = null;
        while (querySelector === null) {
            await rafAsync();
            querySelector = document.querySelector(selector);
        }
        return querySelector;
    }
    var vm = new Vue({
        el: "#vue-app",
        delimiters: ['[[', ']]'],
        data: {
            lang: "<?=$lang?>",
            waiting: true,
            firstLoad: true,
            video_waiting: true,
            active_service: 'Gallery',
            image_gallery: null,
            video_gallery: null,
            editing_media: {
                "name_en" : null,
                "name_ar" : null,
                "file_type" : null,
                "description_en" : null,
                "description_ar" : null,
            },
            media: {
                Gallery: {
                    Image: {all:0, pages:1, page: 1, data: []},
                    Video: {all: 0, pages:1, page: 1, data: []},
                    PDF: {all: 0, pages:1, page: 1, data: []},
                    Audio: {all: 0, pages:1,page: 1, data: []},
                },
                Document: {
                    Image: {all:0, pages:1,page: 1, data: []},
                    Video: {all:0, pages:1,page: 1, data: []},
                    PDF: {all:0, pages:1,page: 1, data: []},
                    Audio: {all:0, pages:1,page: 1, data: []},
                },
                Museum: {
                    Image: {all:0, pages:1,page: 1, data: []},
                    Video: {all:0, pages:1,page: 1, data: []},
                    PDF: {all:0, pages:1,page: 1, data: []},
                    Audio: {all:0, pages:1,page: 1, data: []},
                }

            }
        },
        created: function (){
            // console.log("Created");
        },
        mounted: function (){
            // console.log("Mounted", this.media, Object.keys(this.media));
            this.loadPage('Gallery', 'Image',  1);
        },
        methods: {
            sharingContent(file){
                const url = "<?=$siteUrl.$RELATIVE_PATH?>gallery_item.php" +"?family="+file.family_id+"&gallery_item="+file.id;
                const encoded_url = encodeURIComponent("<?=$siteUrl.$RELATIVE_PATH?>gallery_item.php" +"?family="+file.family_id+"&gallery_item="+file.id);
                const data = "data-url='"+url+"'";
                return `<div class='likely' `+data+`>
                <div class='facebook' `+data+`>Share</div>
                <div class='twitter' `+data+`>Tweet</div>
                <div class='linkedin' `+data+`>Link</div>
                <div class='telegram' `+data+`>Send</div>
                <div class='whatsapp' `+data+`>Send</div>
                <div class='likely__widget'>
                    <a class='likely__button' style='color:black' href='mailto:?subject=ALhamayel Media Share&body=Please see this interesting media item on alhamayel:\"`+encoded_url+`\"'>
                        <img class='likely__icon' alt='' src='<?=asset("images/email.png")?>'>
                        Mail
                    </a>
                </div>
                <div class='likely__widget'>
                    <a class='likely__button answer-example-share-button' href='javascript:;' `+data+`>
                        <i class='fa fa-plus'></i> More (mobile)</a>
                </div>
            </div><div class='text-right'><button class='btn btn-danger btn-sm'>close</button></div>`;
            },
            editFile(file){
                event.preventDefault();
                event.stopPropagation();
                this.editing_media = file;
                $('#edit-media-modal').modal('show')
            },
            shareFile(file){
                event.preventDefault();
                event.stopPropagation();
                const elem = $('a[data-value="'+file.id+'"]')
                elem.popover( {trigger: 'focus', sanitize:false, html:true, container: 'body'}).popover("toggle");
                $('a[data-value="'+file.id+'"]').on("shown.bs.popover", function () {
                    $(document).ready(function () {
                        likely.initiate();
                        elem.popover( {trigger:'focus', sanitize:false, html:true, container: 'body'}).popover('update');
                    });
                })
            },
            deleteFile(file_id, file_type){
                event.preventDefault();
                event.stopPropagation();
                let vue = this;
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let bodyFormData = new FormData();
                        bodyFormData.append('fileId', file_id);

                        axios.post('functions/profile.php', bodyFormData).then(function (response) {
                            Swal.fire({
                                icon: 'success',
                                text: `${response.data}`,
                                confirmButtonText: 'Ok'
                            }).then(function () {
                                vue.loadPage('Gallery', file_type, 1);
                            });
                        }).catch(function (error) {
                            console.log(error);
                        });
                    }})
            },
            setActive(service_name){
                this.active_service = service_name;
                let vue = this;
                requestAnimationFrame(function() {
                    requestAnimationFrame(function () {
                        $('#'+service_name+'-images-switch').click();
                        vue.changeActiveTab('#'+service_name+'-images', service_name, 'Image', 1);
                    });
                });

            },
            changeActiveTab(target, service, file_type, page){
                let vue = this;
                // $(target+'-switch').on('shown.bs.tab', function (e) {
                requestAnimationFrame(function() {
                    requestAnimationFrame(function () {
                        vue.loadPage(service, file_type, page, target);
                    });
                });
            },
            initImageLightGallery(){
                $(document).ready(function() {
                    var $gallery = $('#gallery');
                    var $boxes = $('.opne-img');
                    $boxes.hide();
                    $gallery.imagesLoaded( {background: true}, function() {
                        $boxes.fadeIn();
                    });
                    $("#gallery").lightGallery({
                        caption:true,
                        captionLink:true
                    });

                });
            },
            initVideoLightGallery(){
                const vue = this;
                $(document).ready(function() {
                    window.videos = $('.video-thumbnail').length;
                    $('.video-thumbnail').each(function(){
                        const img = $(this);
                        const video_id = img.attr('data-value');
                        checkElement('#video-preview-'+video_id) //use whichever selector you want
                            .then((element) => {
                                const video = document.getElementById('video-preview-'+video_id);
                                video.addEventListener('loadeddata', function() {
                                    window.videos -= 1;
                                    var canvas = document.createElement('canvas');
                                    canvas.height = video.videoHeight;
                                    canvas.width = video.videoWidth;
                                    var ctx = canvas.getContext('2d');
                                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                                    requestAnimationFrame(function() {
                                        requestAnimationFrame(function () {
                                            img[0].src=canvas.toDataURL();
                                        });
                                    });
                                    if (window.videos == 0){
                                        requestAnimationFrame(function() {
                                            requestAnimationFrame(function () {
                                                setTimeout(function () {
                                                    vue.video_waiting = false;
                                                }, 200)
                                            });
                                        });
                                    }
                                }, false);
                            });

                    });
                    requestAnimationFrame(function() {
                        requestAnimationFrame(function () {
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
                            $("#video-gallery").lightGallery({videojs: true,});

                        });
                    });
                });
            },
            videoDate(video){
                return JSON.stringify({
                    "source": [
                        {"src": video.file, "type":"video/mp4"}
                    ],
                    "attributes": {"preload": false, "controls": true}
                })
            },
            loadPage(service, file_type, page, target='#Gallery-images'){
                // console.log(service, file_type)
                this.waiting = true;
                this.video_waiting = true;
                let vue = this;
                axios.get('functions/profile.php', {
                    params: {
                        page: page,
                        pagination_service: service,
                        pagination_type: file_type,
                        logged_family_id: <?php echo $familyId; ?>
                    }
                }).then(function (response) {
                    // console.log(response, file_type, service)
                    vue.media[service][file_type].data = [];
                    requestAnimationFrame(function() {
                        requestAnimationFrame(function () {
                            vue.media[service][file_type] = response.data;
                            vue.waiting = false;
                            if (file_type === "Image"){
                                vue.initImageLightGallery();
                            }
                            else if (file_type === "Video"){
                                vue.initVideoLightGallery();
                            }
                            else if (file_type === "PDF") {
                                $(document).ready(function(){
                                    if ($('.pdf-thumbnail').length) {
                                        requestAnimationFrame(function() {
                                            requestAnimationFrame(function () {
                                                createPDFThumbnails();
                                            });
                                        });

                                    }
                                })
                            }
                        });
                    });


                })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .then(function () {
                        if (!vue.firstLoad){
                            document.getElementById("gallery-section").scrollIntoView();
                        }
                        else {
                            vue.firstLoad = false;
                        }


                    });
            }
        }
    });
    $('#copiedMessage').on('focusin', function() {
        $(this).select();
    });
	<?php if(isset($fail) && !empty($fail)){
	$messages = "";
	foreach($fail as $key => $value){
		$messages .= $value;
	}
	?>
    let messages="<?=$messages?>";
    $(document).ready(function () {
        Swal.fire({
            title: 'Error',
            width: 400,
            icon: 'error',
            text: messages,
            confirmButtonText: '<?=trans("ok")?>'
        })
    });
	<?php
	unset($fail);
	}?>
    $(document).on('click','.answer-example-share-button', function() {
        const url = $(this).attr('data-url');
        console.log(url)
        if (navigator.share) {
            navigator.share({
                title: 'Alhamayel Platform Family Media',
                text: 'Take a look at this media from alhamayel family tree!',
                url: url,
            })
                .then(() => console.log('Successful share'))
                .catch((error) => console.log('Error sharing', error));
        } else {
            console.log('Share not supported on this browser, do it the old way.');
        }
    });
</script>
<?php if(isset($_SESSION['upgrade_successful']) && !empty($_SESSION['upgrade_successful'])) {
	$message = $_SESSION['upgrade_successful'];
	unset($_SESSION['upgrade_successful']);
	?>
    <script>
        Swal.fire({
            title: '<?=trans("success")?>!',
            width: 400,
            text: "<?=$message?>",
            icon: 'success',
            confirmButtonText: "<?=trans('ok')?>"
        })
    </script>
<?php } else if (isset($_SESSION['upgrade_fail']) && !empty($_SESSION['upgrade_fail'])) {
	$message = $_SESSION['upgrade_fail'];
	unset($_SESSION['upgrade_fail']);
	?>
    <script>
        Swal.fire({
            title: '<?=trans("error")?>!',
            width: 400,
            text: "<?=$message?>",
            icon: 'error',
            confirmButtonText: "<?=trans('ok')?>"
        });
    </script>
<?php } ?>
<script src="//cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

