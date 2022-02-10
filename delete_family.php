<?php

include('config.php');
include_once(__DIR__."/lib/Mailer.php");

$families = Family::where(['deleted'=> 1])->get(['id', 'deleted_at', 'user_id', 'name_en']);
$now = date('Y-m-d');

foreach($families as $family){
    $creator = [];
    $creator = getUserData($family->user_id);
    $futureDate = date('Y-m-d', strtotime('+1 year', strtotime($family->deleted_at)));
    $diff = round(($futureDate - $now) / (60 * 60 * 24));
    if($diff == 7 || $diff == 1){
        $mailer = new Mailer();
        $mailer->setVars(['user_name'=> $creator['name'], 'family_name' => $family->name_en]);
        $mailer->sendMail([$creator['email']], "deleting family permanently", 'delete_family.html', 'delete_family.txt');
    } else if($diff == 0){
        User::where(['family_id'=>$family->id])->delete();
		FamilyAccess::where(['family_id'=>$family->id])->delete();
		FamilyHistory::where(['family_id'=>$family->id])->delete();
		FamilyInvitation::where(['family_id'=>$family->id])->delete();
		FamilyMedia::where(['family_id'=>$family->id])->delete();
		FamilyJoinRequest::where(['family_id'=>$family->id])->delete();
		SiteMail::where(['family_id'=>$family->id])->delete();
        $family->delete();
    }

}