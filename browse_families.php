<?php
include_once(__DIR__."/config.php");
include_once(__DIR__."/lib/Mailer.php");
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

		$account_url = $siteUrl.$RELATIVE_PATH."/account.php";
		$login_url = $siteUrl.$RELATIVE_PATH."/login.php";
		$emails  = Family::find($family_id)->users()->responsible()->pluck('email')->toArray();
		$mailer = new Mailer();
		$mailer->setVars(['user_name'=>'Creator/Assistant', 'name'=>$name, 'account_url'=>$account_url, 'login_url'=>$login_url]);
		$mailer->sendMail($emails, "New access request", 'access_request.html', 'access_request.txt');
		$success = "Request Sent Successfully";
	}
}
include_once(__DIR__."/header.php");
?>
<div class="section is-hero has-gradient position-relative overflow-hidden is-subpage">
	<div class="container position-relative">
		<div class="col block-centered text-align-center lg-7 md-12" style="margin-top: 5vh !important;">
			<h1 style="font-size: 40px;font-family: inherit;
    font-weight: 500 !important;
    line-height: 1.2;"><?=trans('hamayel_registered_families')?></h1>

		</div>
	</div>
</div>
<br>
<section class="search-sec">
	<div class="container" id="searchDiv">
		<form>
			<div class="row">
				<div class="col-12 col-md-5 mb-1 p-0">
					<input type="search" name="search" placeholder="<?=trans('searchFamily')?>" id="search" class="home-search" value="<?php echo $_GET['search']; ?>" >
				</div>
				<div class="col-12 col-md-5 mb-1 p-0">

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
<section>
	<div class="container">
		<div class="row">
			<div class="col-md-12" style="display:contents;">
				<?php
				$page = isset($_GET['page']) ? $_GET['page'] : 1;
				$families = Family::with('creator')->valid();
				if(isset($_GET['search']) && $_GET['search'] != ''){
					$search= "%".$_GET['search']."%";
					$families = $families->where('name_ar', 'like', $search)->orWhere('name_en', 'like', $search);
				}
				if(isset($_GET['country']) && $_GET['country'] != ''){
					$families = $families->whereHas('country', function (Illuminate\Database\Eloquent\Builder $q){
						$q->where(['id'=>$_GET['country']]);
					});
				}
				if(! isset($_GET['type']) || (int) $_GET['type'] == 1){
					$families = $families->orderBy("id")->paginate(20, ['*'], 'page', $page);
				} else {
					$families = $families->orderBy("name_".$lang)->paginate(20, ['*'], 'page', $page);
				}
				foreach($families->items() as $family){
					$row3 = $family->country;
					$rowMP = $family->toArray();
					$rowMP['name'] = $family->creator->name;
					?>
					<div class="col-md-3">
						<?php include("include/familycard.php");?>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php
	$page = $page + 0;
	$all = $families->total();
	$pages = $families->lastPage();
	if ($pages > 1) {?>
		<br>
		<ul class="pagination justify-content-center">
			<li class="page-item <?=$page > 1 ? '' : 'disabled'?>">
				<?php if ($page > 1){ ?>
					<a class="page-link" href="browse_families.php?page=<?=$page-1?>">
						<i class="fa fa-caret-left paginate-icon" aria-hidden="true"></i>
					</a>
				<?php } else { ?>
					<a href="#" class="page-link">
						<i class="fa fa-caret-left paginate-icon" aria-hidden="true"></i>
					</a>
				<?php } ?>
			</li>
			<?php foreach(range(1, $pages, 1) as $one_page){ ?>
				<?php if ($page > $page-3 && $page < $pages + 3) {?>
					<li class="page-item <?=$one_page==$page?'active':''?>">
						<a class="page-link navigate <?=$one_page==$page?'active':''?>"
						   href="browse_families.php?page=<?=$one_page?>">
							<?=$one_page?>
						</a>
					</li>
				<?php } ?>
			<?php } ?>
			<li class="page-item <?=$page < $pages ? '' : 'disabled'?>">
				<?php if ($page < $pages){ ?>
					<a class="page-link" href="browse_families.php?page=<?=$page-1?>">
						<i class="fa fa-caret-right paginate-icon" aria-hidden="true"></i>
					</a>
				<?php } else { ?>
					<a href="#" class="page-link">
						<i class="fa fa-caret-right paginate-icon" aria-hidden="true"></i>
					</a>
				<?php } ?>

			</li>
		</ul>
	<?php }?>

</section>
<br>
<div class="modal" id="modal3" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title ml-auto mt-3" style="text-align: center;"><?=trans('request_private')?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="POST">
				<div class="modal-body">
					<input type="hidden" value="" name="familyId" id="familyId">
					<div class="form-group">
						<label><?=trans('name')?>* :</label>
						<input type="text" class="form-control" required name="strangerName" placeholder="<?=trans('enter')?><?=trans('name')?>">
					</div>
					<div class="form-group">
						<label><?=trans('email')?>* :</label>
						<input type="email" class="form-control" required name="strangerEmail" placeholder="<?=trans('enter')?><?=trans('email')?>">
					</div>
				</div>
				<div class="modal-footer" style="margin: auto;">
					<button type="submit" class="btn hbtn btn-hred" name="requestSubmit"><?=trans('submit')?></button>
					<button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="close3"><?=trans('close')?></button>
				</div>
			</form>
		</div>
	</div>
</div>
<?php include_once(__DIR__."/footer.php");?>
