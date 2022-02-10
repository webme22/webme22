<?php

namespace App\Http\Controllers;

use App\Jobs\SendMarketingMail;
use App\Jobs\SendMarketingMail2;
use App\Mail\MarketingMail;
use App\Mail\MarketingMail2;
use App\Models\Group;
use App\Models\MailTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class DashboardController extends Controller
{
    public function index(Request $request){
        $groups = Group::all();
        $tasks = MailTask::paginate(40);
        return view('admin.index', compact('groups', 'tasks'));
    }
    public function add_to_queue(Request $request){
        $task = MailTask::create(['group_id'=>$request->group_id]);
        $group = Group::find($request->group_id);
        foreach($group->emails as $group_email) {
            if($request->email_type == 'mail_en' || $request->email_type == 'mail_ar'){
                $message = (new MarketingMail($task->id, $group->id, $group_email->id, $request->email_type))->onQueue('marketing');
                SendMarketingMail::dispatch($message);
            } else if($request->email_type == 'mail2_en' || $request->email_type == 'mail2_ar') {
                $message = (new MarketingMail2($task->id, $group->id, $group_email->id, $request->email_type))->onQueue('marketing');
                SendMarketingMail2::dispatch($message);
            }
        }
        return back();
    }
}
