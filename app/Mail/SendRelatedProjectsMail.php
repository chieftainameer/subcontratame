<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;


class SendRelatedProjectsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $usi;
    public $project;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($usi,$project)
    {
        $this->usi = $usi;
        $this->project = $project;
        Log::info($this->usi->email);

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.send_related_projects')
            ->subject('Ole! tenemos un nuevo proyecto para ti');
    }
}
