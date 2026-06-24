<?php

namespace App\Mail;

use App\Helpers\SettingsHelpers;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use stdClass;

class FeedbackMailer extends Mailable implements ShouldQueue
{
    use Queueable;

    private stdClass $data;

    /**
     * Creates the feedback email with form data.
     */
    public function __construct(stdClass $data)
    {
        $this->data = $data;
    }

    /**
     * Returns the mail delivery channels.
     */
    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Builds the feedback email message.
     */
    public function build(): static
    {
        return $this->from(SettingsHelpers::getSetting('FROM'), SettingsHelpers::getSetting('SITE_NAME'))
            ->subject(__('mail.subject.feedback'))
            ->view('mail.feedback', ['data' => $this->data]);
    }
}
