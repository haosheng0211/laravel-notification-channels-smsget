<?php

namespace NotificationChannels\SmsGet;

class SmsGetMessage
{
    public function __construct(
        public string $content,
    ) {
    }

    public static function create(string $content): static
    {
        return new static($content);
    }

    public function content(string $content): static
    {
        $this->content = $content;

        return $this;
    }
}
