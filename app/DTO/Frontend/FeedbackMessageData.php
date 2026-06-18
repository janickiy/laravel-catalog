<?php

namespace App\DTO\Frontend;

use App\DTO\DataTransferObject;
use stdClass;

final readonly class FeedbackMessageData implements DataTransferObject
{
    public function __construct(
        private string $name,
        private string $email,
        private string $message,
        private string $ip,
    ) {}

    /**
     * Создает DTO сообщения обратной связи из валидированного массива формы.
     */
    public static function fromArray(array $data, string $ip): self
    {
        return new self(
            (string) $data['name'],
            (string) $data['email'],
            (string) $data['message'],
            $ip,
        );
    }

    /**
     * Преобразует сообщение в объект payload для события отправки письма.
     */
    public function toEventPayload(): stdClass
    {
        $data = new stdClass;
        $data->name = $this->name;
        $data->email = $this->email;
        $data->message = $this->message;

        return $data;
    }

    /**
     * Преобразует DTO в массив атрибутов сообщения обратной связи.
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'message' => $this->message,
            'ip' => $this->ip,
        ];
    }
}
