<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MarketingMail2 extends Mailable
{
    use Queueable, SerializesModels;
    public string $lang;
    public string $group_id;
    public string $group_email_id;
    public string $mail_task_id;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mail_task_id, $group_id, $group_email_id, $lang)
    {
        $this->lang = $lang;
        $this->mail_task_id = $mail_task_id;
        $this->group_id = $group_id;
        $this->group_email_id = $group_email_id;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if(strpos($this->lang, 'en') !== false) {
            $this->subject("Alhamayel Platform: Your History, Your Present into Your Future in One Place");
            return $this->view('mail.marketing2.en');
        }
        else if (strpos($this->lang, 'ar') !== false){
            $this->subject("منصة الحمايل : تاريخك , حاضرك ومستقبلك فى مكان واحد");
            return $this->view('mail.marketing2.ar');
        }
    }
}
