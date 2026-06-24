<?php

namespace App\Mail;

use App\Helpers\SettingsHelpers;
use App\Models\Links;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewlinkNotify extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Links $links;

    /**
     * Creates the new link notification email.
     */
    public function __construct(Links $links)
    {
        $this->links = $links;
    }

    /**
     * Returns the mail delivery channels.
     */
    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Builds the new link email message.
     */
    public function build(): static
    {
        return $this->from(SettingsHelpers::getSetting('FROM'), SettingsHelpers::getSetting('SITE_NAME'))
            ->subject(__('mail.subject.new_link'))
            ->view('mail.newlink', ['data' => $this->links]);
    }
}
