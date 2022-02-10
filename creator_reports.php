<?php

    include('config.php');
    include_once(__DIR__."/lib/Mailer.php");
    
    $creators = User::creator()
                    ->where('family_id', '!=', 0);
    $countries = Country::whereIn('id', $creators->pluck('country_id'))->get(['id', 'country_code']);
    
    foreach($countries as $country){
    
        $info = \DateTimeZone::listIdentifiers(\DateTimeZone::PER_COUNTRY, $country['country_code']);
        date_default_timezone_set($info[0]);
        if(date('H:i') == '23:50'){
            $creators = User::creator()
                    ->where('family_id', '!=', 0)->where(['country_id'=>$country['id']])->get(['email', 'name','family_id']);
            foreach($creators as $creator){
                $report = [];
                $report = creator_daily_report($creator['family_id']);
                if(array_sum($report) > 0){
                    $mailer = new Mailer();
                    $mailer->setVars(['user_name'=> $creator['name'], 'report' => $report]);
                    $mailer->sendMail([$creator['email']], "Today's Report", 'report_creator.html', 'report_creator.txt');
                }
            }
        }
    
    }