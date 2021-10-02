<?php

namespace App\Mail;

use App\Helpers\SettingsHelpers;
use App\Models\Links;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailable;

class NewlinkNotify extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Links $links;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Links $links)
    {
        $this->links = $links;
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
     * @return $this
     */
    public function build()
    {
        return $this->from(SettingsHelpers::getSetting('FROM'), SettingsHelpers::getSetting('SITE_NAME'))
            ->subject('Добавлена новая ссылка')
            ->view('mail.newlink', ['data' => $this->links]);
    }
}
