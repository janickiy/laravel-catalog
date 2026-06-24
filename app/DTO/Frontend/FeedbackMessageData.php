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
     * Creates a feedback message DTO from the validated form array.
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
     * Converts the message to a payload object for the mail sending event.
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
     * Converts the DTO to a feedback message attribute array.
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
