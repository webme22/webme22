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
<div class="section is-hero has-gradient position-relative overflow-hidden is-subpage">
	<div class="container position-relative">
		<div class="col block-centered text-align-center lg-7 md-12" style="margin-top: 5vh !important;">
			<h1 style="font-size: 40px !important;"><?=trans('search_results')?></h1>
		</div>
	</div>
</div>
<br>
<section class="search-sec">
	<div class="container"  id="searchDiv">
		<form>
		<div class="row">
			<div class="col-12 col-md-5 mb-1 p-0">
				<input type="search" name="search" placeholder="<?=trans('searchFamily')?>" id="search" class="home-search" value="<?php echo $_GET['search']; ?>" >
			</div>
			<div class="col-12 col-md-4 mb-1 p-0">
				<select name="country" id="home-country" class="w-100" style="text-align: center !important; padding: auto !important;">
					<option value=''><?=trans('select_country')?></option>
					<?php
					$countries = Country::active()->get();
					foreach($countries as $country){?>
						<option value="<?=$country->id?>"
								<?=($_GET['country']) && $_GET['country'] == $country->id?'selected': ''?>>
							<?=db_trans($country,'name')?>
						</option>
					<?php }?>
				</select>
				&nbsp;&nbsp;&nbsp;</div>
			<div class="col-12 col-md-2 p-0 pl-md-1">
				<button class="button-primary animated w-inline-block" style="height: 53px;" type="submit">
					<div style="-webkit-transform:translate3d(0, 0PX, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, 0PX, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, 0PX, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, 0PX, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:1" class="button-primary-text text-light"><?=trans('search')?></div>
					<div style="opacity:0;display:block;-webkit-transform:translate3d(0, 20PX, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, 20PX, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, 20PX, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, 20PX, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0)" class="button-primary-text for-hover text-light"><?=trans('search')?> </div>
				</button>
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
<section id="searchDiv">
	<?php if(isset($_GET['country']) || isset($_GET['search'])){ ?>
		<div class="container">
			<div class="row">
				<div class="col-md-12" style="display:contents;">
					<?php
					$search_results = User::verified();
					if(isset($_GET['search']) && $_GET['search'] != ''){
						$search = '%'.str_replace(' ', '%',$_GET['search']).'%';
						$search_results = $search_results->where('club_name', 'like', $search)->orWhere('interests', 'like', $search)->orWhere('occupation', 'like', $search)
								->orWhereHas('family', function (Illuminate\Database\Eloquent\Builder $q) use ($search){
									$q->where('name_ar', 'like', $search)->valid();
								})->orWhereHas('family', function (Illuminate\Database\Eloquent\Builder $q) use ($search){
									$q->where('name_en', 'like', $search)->valid();
								});
					}

					if(isset($_GET['country']) && $_GET['country'] != ''){
						$search_results = $search_results->whereHas('country', function (Illuminate\Database\Eloquent\Builder $q){
							$q->where(['id'=>$_GET['country']]);
						});
					}

					if(! isset($_GET['type']) || (int) $_GET['type'] == 1){
						$search_results = $search_results->orderBy("user_id", 'asc')->get()->unique("user_id");
					} else {
						$search_results = $search_results->orderBy("name", 'asc')->get()->unique("user_id");
					}

					if(count($search_results) >= 1){
						$flag = 1;
						foreach($search_results as $search_result){
						    if(! $search_result->country){
						        continue;
							}
							$row3 = $search_result->country->toArray();
							$rowMP = $search_result->family()->with('creator')->first();
							$rowMP = $rowMP?$rowMP->toArray():$rowMP;
							if($rowMP){
								$rowMP['name'] = $search_result->name;
								$rowMP['user_id'] = $search_result->user_id;
							}
							?>
							<div class="col-md-3">
								<?php include("include/familycard.php");?>
							</div>
						<?php } } else {
						?>
						<h3 style='text-align: <?php echo $align; ?> !important;'><?=trans('noResults') . "<span style='color: blue;'>" . $_GET['search'] . "</span>" . " , " . $languages[$lang]['tryAgain']  ; ?></h3>;
						<?php

					} ?>
				</div>
			</div>
		</div>
	<?php } ?>
</section>
<input type="hidden" id="success" value="<?=$success?>">
<input type="hidden" id="error" value="<?=$error?>">
<br><br>
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
						<label>Name</label>
						<input type="text" class="form-control" required name="strangerName" style="font-size: 15px !important;">
					</div>

					<div class="form-group">
						<label>Email</label>
						<input type="email" class="form-control" required name="strangerEmail" style="font-size: 15px !important;">
					</div>

			</div>
			<div class="modal-footer" style="margin: auto;">
				<button type="submit" class="btn hbtn btn-hred" name="requestSubmit" style="font-size: 15px !important;">Send</button>
				<button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="close3" style="font-size: 15px !important;">Close</button>
			</div>
			</form>
		</div>
	</div>
</div>
</div>
<?php include"footer.php";?>
<script type="text/javascript">
    $(document).ready(function() {
        $('body').on('click', '.familyPron', function(){
            let audio = $(this).attr('audio');

            let audioTag = document.createElement('audio');
            audioTag.setAttribute('src', audio);
            audioTag.play();
        })
        let success = $('#success').val();
        if(success.length > 0){
            Swal.fire({
                title: 'Success',
                width: 400,
                icon: 'success',
                text: `${success}`,
                confirmButtonText: 'Ok'
            })
        }
        let error = $('#error').val();
        if(error.length > 0){
            Swal.fire({
                title: 'Error!',
                width: 400,
                text: `${error}`,
                icon: 'error',
                confirmButtonText: 'Ok'
            })
        }
        $('body').on('click', '#close3', function(){
            $('#modal3').modal('hide');
        })
    });
</script>
