<?php

namespace App\Providers;

use App\Models\JobStatus;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        LengthAwarePaginator::useBootstrap();

        Queue::after(function (JobProcessed $event) {
            $payload = json_decode($event->job->getRawBody());
            $data = unserialize($payload->data->command);
            $job_status = JobStatus::where([['uuid', '=', $payload->uuid],['queue' ,'=', 'marketing'],[ 'mail_task_id', '=', $data->mailable->mail_task_id],
                ['group_id', '=', $data->mailable->group_id], ['group_email_id', '=', $data->mailable->group_email_id]])->first();
            if(! $job_status){
                JobStatus::create(['queue' => 'marketing', 'mail_task_id' => $data->mailable->mail_task_id,
                    'group_id' => $data->mailable->group_id, 'group_email_id' => $data->mailable->group_email_id, 'status' => true,'uuid'=>$payload->uuid]);
            }
        });
        Queue::failing(function (JobFailed $event) {
            $payload = json_decode($event->job->getRawBody());
            $data = unserialize($payload->data->command);
            JobStatus::create(['queue' => 'marketing', 'mail_task_id' => $data->mailable->mail_task_id,
                'group_id' => $data->mailable->group_id, 'group_email_id' => $data->mailable->group_email_id, 'status' => false,'uuid'=>$payload->uuid]);
        });
    }
}
