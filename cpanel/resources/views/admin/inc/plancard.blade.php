<div class="Sponser Sponser--{{$plan->styling}} position-relative">
    <div class="Sponser-Inner-Container">
    <h3 class="Sponser__header">{{$plan->name_en}}</h3>
    <p class="plan-price"><span class="d-block rtl-dir-reverse">
            @if($plan->price == 0)
                Free Trial
            @else
                {{ $plan->price . " USD"}}
            @endif
            </span></p>
    <br>
    <ul class="Sponser__advantages">
        <li class="border-bottom padding-bottom"><span class="bold"> {{ $plan->members}}</span> Family Members on Tree</li>
        <li class="border-bottom padding-bottom"><span class="bold"> {{ $plan->media}} GB</span> Family Media Uploads</li>
         @if($plan->price == 0)
        <li class="bold">For 3 months Only</li>
         @elseif ($plan->popular)
        <li class="bold"><a href="{{str_replace('cpanel', '', url('/'))}}/faq.php?tab=profile&q=MPF" target="_blank" class="text-dark">Join Most Popular Families?</a></li>
         @else
        <li class="bold"></li>
         @endif
    </ul>
    <br>
    </div>
    <div class="w-100 confirm-div text-center">
        <a class="Sponser__confirm d-inline-block" href="{{str_replace('cpanel', '', url('/'))}}/{{isset($_SESSION['family_id']) ? 'upgrade_plan.php' : 'signup.php'}}?plan={{$plan->id}}">
            <span>Confirm</span></a>
    </div>
</div>

