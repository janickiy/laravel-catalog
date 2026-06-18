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
     * Создает письмо обратной связи с данными формы.
     */
    public function __construct(stdClass $data)
    {
        $this->data = $data;
    }

    /**
     * Возвращает каналы доставки письма.
     */
    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Собирает email-сообщение обратной связи.
     */
    public function build(): static
    {
        return $this->from(SettingsHelpers::getSetting('FROM'), SettingsHelpers::getSetting('SITE_NAME'))
            ->subject('Форма обратной связи')
            ->view('mail.feedback', ['data' => $this->data]);
    }
}
