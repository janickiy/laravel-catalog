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
     * Создает письмо уведомления о новой ссылке.
     */
    public function __construct(Links $links)
    {
        $this->links = $links;
    }

    /**
     * Возвращает каналы доставки письма.
     */
    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Собирает email-сообщение о новой ссылке.
     */
    public function build(): static
    {
        return $this->from(SettingsHelpers::getSetting('FROM'), SettingsHelpers::getSetting('SITE_NAME'))
            ->subject(__('mail.subject.new_link'))
            ->view('mail.newlink', ['data' => $this->links]);
    }
}
