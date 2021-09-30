<?php

namespace App\Mail;

use stdClass;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use App\Helpers\{SettingsHelpers};

class FeedbackMailer extends Mailable implements ShouldQueue
{
    use Queueable;

    private $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(stdClass $data) {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }


    /**
     * Build the message.
     *
     * @return \App\Events\FeedbackMailer
     */
    public function build() {
        return $this->from(SettingsHelpers::getSetting('FROM'), SettingsHelpers::getSetting('SITE_NAME'))
            ->subject('Форма обратной связи')
            ->view('mail.feedback', ['data' => $this->data]);
    }


}
