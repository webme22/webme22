<?php
include_once(__DIR__."/config.php");
include_once(__DIR__."/lib/Mailer.php");
include_once(__DIR__."/header.php");
if(isset($_POST['requestSubmit'])){
	$family_id = $_POST['familyId'];
	$name = trim($_POST['strangerName']);
	$email = trim($_POST['strangerEmail']);
	if(! filter_var($email, FILTER_VALIDATE_EMAIL)){
		$error = "Failed to send request, Email is invalid .";
	} else {
		FamilyAccess::create([
				'family_id'=>  $family_id ,
				'name'=>  $name ,
				'email'=> $email  ,
				'accept'=> '2'  ,
				'acceptedBy'=> null  ,
				'expire_date'=>  null ,
				'date' => date('Y-m-d h:i:s a')
		]);
		$emails  = Family::find($family_id)->users()->responsible()->pluck('email')->toArray();
        $account_url = $siteUrl.$RELATIVE_PATH."/account.php";
		$login_url = $siteUrl.$RELATIVE_PATH."/login.php";
		$mailer = new Mailer();
		$mailer->setVars(['user_name'=>'Creator/Assistant', 'name'=>$name, 'account_url'=>$account_url, 'login_url'=>$login_url]);
		$mailer->sendMail($emails, "New access request", 'access_request.html', 'access_request.txt');
		$success = "Request Sent Successfully";
	}
}
?>

<input type="hidden" id="topLang" value="<?=$lang?>">
<div class="section is-hero has-gradient position-relative overflow-hidden is-subpage">
    <div class="container position-relative">
        <div class="col block-centered text-align-center lg-7 md-12" style="margin-top: 5vh !important;">
            <h1 style="font-size: 40px;font-family: inherit;
    font-weight: 500 !important;
    line-height: 1.2;"><?=trans('hamayel_most_popular')?></h1>

        </div>
    </div>
</div>
<br>
<input type="hidden" id="error" value="<?php echo $error; ?>">
<input type="hidden" id="success" value="<?php echo $success; ?>">
<section class="search-sec">
    <div class="container">
        <form action="" novalidate="novalidate" style="width:100%">
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-12 col-md-5 mb-1">
                            <input name="search" type="text" class="home-search search-slt" placeholder="<?=trans('searchFamily')?>" value="<?php
                            echo $_GET['search'];
                            ?>" id="inputSearch">
                        </div>

                        <div class="col-12 col-md-4 mb-1">
                            <select name="country" class="form-control search-slt" id="searchSelect" style="background-color: #1f2b36 !important;color: #fff; border-radius: 6px; border: 1px solid #1e2b36; margin-right: 5px !important;">
                                <option value=''><?=trans('select_country')?></option>
                                <?php

                                $countries = Country::active()->get();
                                foreach($countries as $country){?>
                                    <option value='<?=$country['id']?>'
                                                            <?=$_GET['country'] == $country['id']? 'selected' : ''?>>
                                        <?=db_trans($country,'name')?></option>
                                <?php } ?>

                            </select>
                        </div>
                        <div class="col-12 col-md-2 mb-1">
                            <button class="button-primary w-100 animated w-inline-block" style="height: 53px;" type="submit"><?=trans('search')?></button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col-9 offset-1 col-md-8 offset-md-2">
                <ul class="nav nav-pills nav-fill pl-0 pr-0">
                    <li class="nav-item profile-nav-item p-0 m-0">
                        <a class="nav-link <?= ((int) $_GET['type'] == 1 || ! isset($_GET['type']))? 'active' : '' ?> search_families_filter m-0 p-xs-0" href="#" type="1"><?=trans('sort_by_oldest')?></a>
                    </li>
                    <li class="nav-item profile-nav-item p-0 m-0">
                        <a class="nav-link <?= ((int) $_GET['type'] == 2)? 'active' : '' ?> search_families_filter m-0 p-xs-0" href="#" type="2"><?=trans('sort_by_alphabetical')?></a>
                    </li>
                </ul>
                <hr>
            </div>
        </div>
    </div>
</section>
<br>
<section>
    <div class="container">
        <div class="row">
            <div class="col-md-12" style="display:contents;">
                <?php

                $families = Family::valid()->popular();
                if(isset($_GET['search']) && $_GET['search'] != ''){
                    $search = '%'.trim($_GET['search']).'%';
                    $families = $families->where(function ($query) use ($search){
                            $query->where('name_ar', 'like', $search)->orWhere('name_en', 'like', $search);
                    });
                }

                if(isset($_GET['country']) && $_GET['country'] != ''){
                    $country = $_GET['country'];
					$families = $families->where(['country_id'=>$country]);
                }

                if(! isset($_GET['type']) || (int) $_GET['type'] == 1){
				    $families = $families->orderBy("id")->get();
                } else {
				    $families = $families->orderBy("name_".$lang)->get();
                }
                foreach($families as $rowMP){
                    $row3 = $rowMP->country;
                    ?>
                    <div class="col-md-3">
                        <?php include("include/familycard.php");?>
                    </div>
                <?php }
                if (count($families) == 0){?>
                        <h5 class="w-100 text-center">No Results</h5>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</section>
<br>
<div class="modal" id="modal3" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ml-auto mt-3">Request Access To Private Family</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <input type="hidden" value="" name="familyId" id="familyId">
                    <div class="form-group">
                        <label style="font-size: 15px;">Name</label>
                        <input type="text" class="form-control" required name="strangerName">
                    </div>

                    <div class="form-group">
                        <label style="font-size: 15px;">Email</label>
                        <input type="email" class="form-control" required name="strangerEmail">
                    </div>

            </div>
            <div class="modal-footer" style="margin: auto;">
                <button type="submit" class="btn hbtn btn-hred" name="requestSubmit" style="font-size: 15px;">Send</button>
                <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="close3" style="font-size: 15px;">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>

<?php include"footer.php";?>
<script>
    $(document).ready(function(){
        $('body').on('click', '.familyPron', function(){
            let audio = $(this).attr('audio');
            let audioTag = document.createElement('audio');
            audioTag.setAttribute('src', audio);
            audioTag.play();
        })
        let error = $('#error').val();
        let success = $('#success').val();
        if(error.length > 0){
            Swal.fire({
                title: 'Error!',
                width: 400,
                text: `${error}`,
                icon: 'error',
                confirmButtonText: 'Ok'
            })
        }
        if(success.length > 0){
            Swal.fire({
                title: 'Success',
                width: 400,
                icon: 'success',
                text: `${success}`,
                confirmButtonText: 'Ok'
            })
        }

        $('body').on('click', '#close3', function(){
            $('#modal3').modal('hide');
        })

    })
</script>

