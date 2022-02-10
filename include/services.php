<div class="section has-bg-accent position-relative">
	<div class="container position-relative mb-5">
		<div class="col-12 block-centered col-lg-8 col-md-12 text-center position-relative">
			<h3 class="text-center"><?=trans('hamayel_services')?></h3>
			<div class="text-center text-medium low-text-contrast"><?=trans('hamayel_services_subtitle')?></div>
		</div>
	</div>
	<div class="container">
		<div class="row" >
			<?php
			$rowService = Service::find(1);
			?>
			<div class="col-md-6 col-sm-12 col-xs-12">
				<div class="service-box" >
					<video autoplay muted loop id="myVideo"  style="width:100%;" class="lazyLoadVid">
						<source src="<?=$rowService->Tmedia?>" type="video/mp4">
						Your browser does not support HTML5 video.
					</video>
					<div class="video-text">
						<h2><?=trans('family_tree')?></h2>
					</div>
					<div class="service-content">
						<h3><?=trans('family_tree')?></h3>
						<p class="MediaDesc mb-2  text-left"><?php
							if($lang == 'en'){
								echo $rowService->Tdesc;
							} else {
								echo "تسهل المنصة على المستخدمين إمكانية إنشاء شجرة العائلة بإضافة البيانات الأساسية للأفراد مثل الأسماء والتواريخ وحتى الصور، مع ذكر نبذة مختصرة تحت كل فرد كمؤهله الأكاديمي وهواياته وفريقه الرياضي، بجانب حساباته على وسائل التواصل الاجتماعي";
							}
							?></p>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-sm-12 col-xs-12">
				<div class="service-box">
					<video autoplay muted loop id="myVideo" style="width:100%;" class="lazyLoadVid">
						<source src="<?=$rowService->Gmedia?>" type="video/mp4">
						Your browser does not support HTML5 video.
					</video>
					<div class="video-text">
						<h2><?=trans('family_gallery')?></h2>
					</div>
					<div class="service-content">
						<h3><?=trans('family_gallery')?></h3>
						<p class="MediaDesc mb-2  text-left"><?php if($lang == 'en'){
								echo $rowService->Gdesc;
							} else {
								echo "يشكّل ألبوم العائلة فرصة لمشاركة كل فرد مع عائلته أو مع الآخرين أهم اللحظات المميزة والسعيدة، وذلك من خلال إنشاء ألبوم ومعرض يتم فيه تحميل الصور والمقاطع المرئية أو الصوتية القديمة والحديثة، وتكوين قاعدة بيانات مرئية ومحتوى إلكتروني شامل محفوظ في مكان آمن واحد يمكن الرجوع إليه في أي وقت بسهولة. والذي يعكس روح المحبة وفخر الانتماء";
							}
							?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
