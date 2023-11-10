<?php

namespace App\Webhooks;

use Illuminate\Support\Facades\Http;

class Webhook{
    protected string $url;
    public string $message;

    public function __construct()
    {
        $this->url = 'https://webhook.site/b693eb60-b369-4f53-b55f-54faeb7fad87';
    }

    public function format(string $message): string
    {
        $message = '{
            "message":'.$message.'
        }';

        return json_encode($message);
    }

    public function send(string $message): void
    {
        Http::post($this->url, $this->format($message));
    }
}