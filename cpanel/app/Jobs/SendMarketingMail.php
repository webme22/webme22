<?php

namespace App\Jobs;

use App\Mail\MarketingMail;
use App\Models\GroupEmail;
use App\Models\JobStatus;
use App\Models\SusccessfulJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

class SendMarketingMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public MarketingMail $mailable;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mailable)
    {
        $this->onQueue('marketing');
        $this->mailable = $mailable;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mail_to = GroupEmail::find($this->mailable->group_email_id);
        Mail::to($mail_to->email)->send($this->mailable);

    }
}
