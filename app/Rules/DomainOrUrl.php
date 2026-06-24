<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class DomainOrUrl implements ValidationRule
{
    public function __construct(private readonly string $message) {}

    /**
     * Checks that the value is a URL or a domain without a scheme.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) && ! is_numeric($value)) {
            $fail($this->message);

            return;
        }

        $url = trim((string) $value);

        if ($url === '' || preg_match('/\s/u', $url)) {
            $fail($this->message);

            return;
        }

        $scheme = parse_url($url, PHP_URL_SCHEME);

        if (is_string($scheme) && ! in_array(mb_strtolower($scheme, 'UTF-8'), ['http', 'https'], true)) {
            $fail($this->message);

            return;
        }

        if (! is_string($scheme)) {
            $url = 'http://'.$url;
        }

        $host = parse_url($url, PHP_URL_HOST);

        if (! is_string($host) || ! str_contains($host, '.') || filter_var($url, FILTER_VALIDATE_URL) === false) {
            $fail($this->message);
        }
    }
}
